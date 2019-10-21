<?php

namespace EderSoares\Laravel\PlugAndPlay\Foundation;

use Illuminate\Foundation\PackageManifest;

class CustomPackageManifest extends PackageManifest
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $packages = [];
        $customPackages = [];

        if ($this->files->exists($path = $this->vendorPath . '/composer/installed.json')) {
            $packages = json_decode($this->files->get($path), true);
        }

        $ignoreAll = in_array('*', $ignore = $this->packagesToIgnore());

        $composerJson = $this->basePath . '/composer.json';

        if ($this->files->exists($composerJson)) {
            $json = json_decode($this->files->get($composerJson), true);

            $include = $json['extra']['merge-plugin']['include'] ?? [];
            $require = $json['extra']['merge-plugin']['require'] ?? [];

            $customPackages = collect($include)->merge($require)->map(function ($package) {
                return glob($package);
            })->flatten()->filter(function ($package) {
                return $this->files->exists($package);
            })->map(function ($path) {
                return json_decode($this->files->get($path), true);
            })->mapWithKeys(function ($package) {
                return [$package['name'] => $package['extra']['laravel'] ?? []];
            })->filter()->all();
        }

        $write = collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['laravel'] ?? []];
        })->merge($customPackages)->each(function ($configuration) use (&$ignore) {
            $ignore = array_merge($ignore, $configuration['dont-discover'] ?? []);
        })->reject(function ($configuration, $package) use ($ignore, $ignoreAll) {
            return $ignoreAll || in_array($package, $ignore);
        })->filter()->all();

        $this->write($write);
    }
}
