<?php

namespace Novius\LaravelJsonCasted\Services;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Novius\LaravelJsonCasted\Traits\HasAttributes;

class JsonCasted implements Castable
{
    protected static array $casts = [];

    public static function cast(array $json, array $casts): array
    {
        foreach ($casts as $key => $cast) {
            $json = static::castKey($json, $key, $cast);
        }

        return $json;
    }

    protected static function castKey(array $json, $key, $cast): array
    {
        if (Str::contains($key, '.*.')) {
            [$repeater, $key] = explode('.*.', $key, 2);

            $values = Arr::get($json, $repeater);
            if (is_array($values)) {
                $repeaterValues = [];
                foreach ($values as $value) {
                    $repeaterValues[] = static::castKey($value, $key, $cast);
                }
                Arr::set($json, $repeater, $repeaterValues);
            }
        } else {
            $value = Arr::get($json, $key);
            if ($value !== null) {
                /** @phpstan-ignore class.missingExtends */
                $workingClass = new class($value, $cast)
                {
                    use HasAttributes;

                    public function __construct($value, $cast)
                    {
                        $this->setDateFormat('Y-m-d H:i:s');
                        $this->casts = ['attribute' => $cast];
                        $this->setAttribute('attribute', $value);
                    }
                };

                Arr::set($json, $key, $workingClass->getAttribute('attribute'));
            }
        }

        return $json;
    }

    public static function castUsing(array $arguments)
    {
        return new class(static::$casts) implements CastsAttributes
        {
            public function __construct(public array $casts) {}

            public function get(Model $model, string $key, mixed $value, array $attributes): ?Fluent
            {
                if (is_null($value)) {
                    return null;
                }

                $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

                return is_array($value) ? new Fluent(JsonCasted::cast($value, $this->casts)) : $value;
            }

            public function set($model, string $key, mixed $value, array $attributes)
            {
                return json_encode($value, JSON_THROW_ON_ERROR);
            }
        };
    }
}
