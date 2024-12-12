<?php

namespace Novius\LaravelJsonCasted\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JsonException;
use Novius\LaravelJsonCasted\Services\JsonCasted;

class JsonWithCasts implements CastsAttributes
{
    public function __construct(protected mixed $getCastsMethod = []) {}

    /**
     * @throws JsonException
     */
    public function get($model, string $key, mixed $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        if (is_array($value) && method_exists($model, $this->getCastsMethod)) {
            $casts = $model->{$this->getCastsMethod}();

            $value = JsonCasted::cast($value, $casts);

            return $value;
        }

        return $value;
    }

    /**
     * @throws JsonException
     */
    public function set($model, string $key, mixed $value, array $attributes)
    {
        return json_encode($value, JSON_THROW_ON_ERROR);
    }
}
