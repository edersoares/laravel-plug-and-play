<?php

namespace EderSoares\Laravel\PlugAndPlay\Foundation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\PackageManifest;

trait PlugAndPlayPackages
{
    /**
     * Register the basic bindings into the container. Replace the
     * PackageManifest binding.
     *
     * @see Application::registerBaseBindings()
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        parent::registerBaseBindings();

        $this->instance(PackageManifest::class, new CustomPackageManifest(
            new Filesystem(),
            $this->basePath(),
            $this->getCachedPackagesPath()
        ));
    }
}
