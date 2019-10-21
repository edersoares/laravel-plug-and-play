# Laravel Plug and Play

Add in [Laravel](https://laravel.com/) application the ability to plug and play packages that are build with [package discover](https://laravel.com/docs/master/packages#package-discovery) without necessarily install a new dependency in `composer.json`.

This package uses the great plugin [Composer Merge Plugin](https://github.com/wikimedia/composer-merge-plugin) that allows merge multiple `composer.json` files at [Composer](https://getcomposer.org/) runtime.

## Installation

[Laravel Plug and Play](https://github.com/edersoares/laravel-plug-and-play/) requires [Composer](https://getcomposer.org/) 1.0.0 or newer.

```bash
composer require edersoares/laravel-plug-and-play

php artisan package:install

composer dump
```

## Usage

The `composer.json` file needs to be updated to something like:

```json
{
    "require": {
        "edersoares/laravel-plug-and-play": "dev-master"
    },
    "extra": {
        "merge-plugin": {
            "include": [
                "packages/*/*/composer.json"
            ]
        }
    }
}
```

This package is a [Composer Merge Plugin](https://github.com/wikimedia/composer-merge-plugin) extension and its functionalities can be used usually. See more in [plugin configuration](https://github.com/wikimedia/composer-merge-plugin#plugin-configuration).
