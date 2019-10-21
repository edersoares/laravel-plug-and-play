# Laravel Plug and Play

Add to the [Laravel](https://laravel.com/) application the ability to plug and play packages that are build with [package discover](https://laravel.com/docs/master/packages#package-discovery) without necessarily installing a new dependency on `composer.json`.

This package uses the great plugin [Composer Merge Plugin](https://github.com/wikimedia/composer-merge-plugin) which allows you to merge multiple `composer.json` files into the [Composer](https://getcomposer.org/) runtime.

## Installation

[Laravel Plug and Play](https://github.com/edersoares/laravel-plug-and-play/) requires [Composer](https://getcomposer.org/) 1.0.0 or newer.

```bash
composer require edersoares/laravel-plug-and-play

php artisan package:install
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

This package is a [Composer Merge Plugin](https://github.com/wikimedia/composer-merge-plugin) extension and its features can be used usually. See more in [plugin configuration](https://github.com/wikimedia/composer-merge-plugin#plugin-configuration).

### Adding a package

Move or clone the package to `packages/<vendor>/<name>` folder and runs:

```bash
composer update --lock 
```

Whenever package dependencies are updated, this command must be executed.

> This will instruct Composer to recalculate the file hash for the top-level composer.json thus triggering composer-merge-plugin to look for the sub-level configuration files and update your dependencies.
>
> https://github.com/wikimedia/composer-merge-plugin#updating-sub-levels-composerjson-files

### Creating a package

To create a package execute:

```bash 
php artisan package:create <vendor>/<name>
```

This will create a package in `packages` folder:

``` 
packages
\_ <vendor>
  \_ <name>
    \_ src
      \_ ServiceProvider.php
    \_ composer.json
``` 

See the official documentation about [package discover](https://laravel.com/docs/master/packages#package-discovery).

### Removing a package

Just only remove the package folder and runs:

```bash
composer update --lock 
```

## License

[Laravel Plug and Play](https://github.com/edersoares/laravel-plug-and-play/) is licensed under the MIT license. See the [license](https://github.com/edersoares/laravel-plug-and-play/blob/master/license.md) file for more details.
