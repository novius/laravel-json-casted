<?php

namespace Novius\LaravelJsonCasted\Traits;

use Illuminate\Database\Eloquent\Concerns\HasAttributes as HasAttributesBase;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HidesAttributes;

trait HasAttributes
{
    use HasAttributesBase;
    use HasRelationships;
    use HasTimestamps;
    use HidesAttributes;

    public bool $exists = true;

    public bool $wasRecentlyCreated = false;

    protected function usesTimestamps(): bool
    {
        return false;
    }

    protected function getIncrementing(): bool
    {
        return false;
    }

    protected static function preventsAccessingMissingAttributes(): bool
    {
        return false;
    }
}
