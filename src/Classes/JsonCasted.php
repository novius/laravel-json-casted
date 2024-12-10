<?php

namespace Novius\LaravelJsonCasted\Classes;

use ArrayAccess;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Novius\LaravelJsonCasted\Casts\JsonCastable;
use Novius\LaravelJsonCasted\Traits\HasAttributes;

class JsonCasted implements Arrayable, ArrayAccess, Castable, JsonSerializable
{
    use HasAttributes;

    public function __construct(array $attributes = [], array $casts = [])
    {
        $this->casts = $this->ensureCastsAreStringValues($casts);

        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    public function toArray(): array
    {
        return $this->attributesToArray();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function castUsing(array $arguments): string
    {
        return JsonCastable::class;
    }

    public function offsetExists(mixed $offset): bool
    {
        return ! is_null($this->getAttribute($offset));
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->getAttribute($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setAttribute($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }

    public function __get(string $key): mixed
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    public function __isset(string $key): bool
    {
        return $this->offsetExists($key);
    }

    public function __unset(string $key): void
    {
        $this->offsetUnset($key);
    }
}
