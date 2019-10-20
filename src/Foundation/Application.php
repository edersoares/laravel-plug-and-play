<?php

namespace EderSoares\Laravel\PlugAndPlay\Foundation;

use Illuminate\Foundation\Application as LaravelApplication;

class Application extends LaravelApplication
{
    use PlugAndPlayPackages;
}
