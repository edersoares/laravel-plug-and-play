<?php

namespace EderSoares\Laravel\PlugAndPlay\Foundation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;

trait PlugAndPlayPackages
{
    /**
     * @inheritDoc
     */
    protected function registerBaseBindings()
    {
        parent::registerBaseBindings();

        $this->instance(PackageManifest::class, new CustomPackageManifest(
            new Filesystem,
            $this->basePath(),
            $this->getCachedPackagesPath()
        ));
    }
}
