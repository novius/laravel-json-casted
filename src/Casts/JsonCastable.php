<?php

namespace Novius\LaravelJsonCasted\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JsonException;
use Novius\LaravelJsonCasted\Classes\JsonCasted;

class JsonCastable implements CastsAttributes
{
    public function __construct(protected mixed $casts = []) {}

    /**
     * @throws JsonException
     */
    public function get($model, string $key, mixed $value, array $attributes): ?JsonCasted
    {
        if (is_null($value)) {
            return null;
        }

        $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        if (is_array($this->casts)) {
            return new JsonCasted($value, $this->casts);
        }

        if (method_exists($model, $this->casts)) {
            $cast = $model->{$this->casts}();

            return new JsonCasted($value, $cast);
        }

        if (is_subclass_of($this->casts, JsonCasted::class)) {
            return new $this->casts($value);
        }

        return new JsonCasted($value);
    }

    /**
     * @throws JsonException
     */
    public function set($model, string $key, mixed $value, array $attributes)
    {
        return json_encode($value, JSON_THROW_ON_ERROR);
    }
}
