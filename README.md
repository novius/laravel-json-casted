# Laravel Json Casted

[![Novius CI](https://github.com/novius/laravel-json-casted/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/novius/laravel-json-casted/actions/workflows/main.yml)
[![Packagist Release](https://img.shields.io/packagist/v/novius/laravel-json-casted.svg?maxAge=1800&style=flat-square)](https://packagist.org/packages/novius/laravel-json-casted)
[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](http://www.gnu.org/licenses/agpl-3.0)


## Introduction

A package to cast json fields, each sub-keys is castable

## Requirements

* PHP >= 8.2
* Laravel 10.0

## Installation

You can install the package via composer:

```bash
composer require novius/laravel-json-casted
```

## Usage

### Define casts by a method 

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Novius\LaravelJsonCasted\Classes\JsonCasted;

class Post extends Model {

    protected $casts = [
        'extras' => JsonCasted::class.':getExtrasCasts',
    ];
    
    public function getExtrasCasts(): array
    {
        return [
            'date' => 'date:Y-m-d',
        ];
    }
}
```

### Define casts by a class

```php
namespace App\Casts;

use Novius\LaravelJsonCasted\Classes\JsonCasted;

class Extras extends JsonCasted {

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
}
```

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Casts\Extras;

class Post extends Model {

    protected $casts = [
        'extras' => Extras::class,
    ];
}
```

### Use casted field

```php

    $model = Post::first();
    // $model->extras->date is a now Carbon class 
    $model->extras->date->lt(now());
```

## CS Fixer

Lint your code with Laravel Pint using:

```bash
composer run cs-fix
```

## Licence

This package is under [GNU Affero General Public License v3](http://www.gnu.org/licenses/agpl-3.0.html) or (at your option) any later version.
