<?php

namespace Novius\LaravelJsonCasted\Tests;

use Illuminate\Support\Carbon;
use Novius\LaravelJsonCasted\Services\JsonCasted;
use Novius\LaravelJsonCasted\Tests\Classes\TestEnum;

class JsonCastedTest extends TestCase
{
    /* --- JsonCasted Tests --- */

    /** @test */
    public function cast_date(): void
    {
        $array = ['date' => '2021-01-01'];
        $return = JsonCasted::cast($array, ['date' => 'date']);

        $this->assertInstanceOf(Carbon::class, $return['date']);
    }

    /** @test */
    public function cast_datetime(): void
    {
        $array = ['date' => '2021-01-01 00:00:00'];
        $return = JsonCasted::cast($array, ['date' => 'datetime']);

        $this->assertInstanceOf(Carbon::class, $return['date']);
    }

    /** @test */
    public function cast_bool(): void
    {
        $array = ['bool' => 0];
        $return = JsonCasted::cast($array, ['bool' => 'bool']);

        $this->assertIsBool($return['bool']);
    }

    /** @test */
    public function cast_enum(): void
    {
        $array = ['enum' => 'foo'];
        $return = JsonCasted::cast($array, ['enum' => TestEnum::class]);

        $this->assertInstanceOf(TestEnum::class, $return['enum']);
    }
}
