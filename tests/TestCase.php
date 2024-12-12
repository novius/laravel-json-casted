<?php

namespace Novius\LaravelJsonCasted\Tests;

use Novius\LaravelJsonCasted\LaravelJsonCastedServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelJsonCastedServiceProvider::class,
        ];
    }
}
