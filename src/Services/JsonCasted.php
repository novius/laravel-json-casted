<?php

namespace Novius\LaravelJsonCasted\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Novius\LaravelJsonCasted\Traits\HasAttributes;

class JsonCasted
{
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
                $workingClass = new class($value, $cast)
                {
                    use HasAttributes;

                    public function __construct($value, $cast)
                    {
                        $this->setDateFormat('Y-m-d H:i:s');
                        $this->casts = $this->ensureCastsAreStringValues(['attribute' => $cast]);
                        $this->setAttribute('attribute', $value);
                    }
                };

                Arr::set($json, $key, $workingClass->getAttribute('attribute'));
            }
        }

        return $json;
    }
}
