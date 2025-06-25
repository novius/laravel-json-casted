<?php

namespace Novius\LaravelJsonCasted\Hooks;

use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Novius\LaravelJsonCasted\Casts\JsonWithCasts;

class ModelHasJsonWithCastsHook implements ModelHookInterface
{
    public function run(ModelsCommand $command, Model $model): void
    {
        $casts = $model->getCasts();
        foreach ($casts as $attribute => $cast) {
            if (Str::startsWith($cast, JsonWithCasts::class)) {
                $command->setProperty($attribute, '\\'.Fluent::class, true, true, null);
            }
        }
    }
}
