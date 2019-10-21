<?php

namespace EderSoares\Laravel\PlugAndPlay\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PackageCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:create {name} {namespace?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a plug and play package';

    /**
     * Execute the console command.
     *
     * @param Filesystem $filesystem
     *
     * @throws FileNotFoundException
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $name = $this->argument('name');
        $namespace = $this->argument('namespace');

        if (empty($namespace)) {
            $namespace = collect(explode('/', $name))->map(function ($name) {
                return Str::studly($name);
            })->join('\\\\');
        }

        $replaces = [
            '{{ $composerNamespace }}' => $namespace,
            '{{ $namespace }}' => str_replace('\\\\', '\\', $namespace),
            '{{ $projectName }}' => $name,
        ];

        $from = array_keys($replaces);
        $to = array_values($replaces);

        $composer = $filesystem->get(__DIR__ . '/../../stubs/composer.stub');
        $provider = $filesystem->get(__DIR__ . '/../../stubs/service-provider.stub');

        $composer = str_replace($from, $to, $composer);
        $provider = str_replace($from, $to, $provider);

        $path = base_path("packages/{$name}");
        $srcPath = base_path("packages/{$name}/src");

        $filesystem->makeDirectory($path, 0755, true);
        $filesystem->makeDirectory($srcPath, 0755, true);

        $filesystem->put("$path/composer.json", $composer);
        $filesystem->put("$path/src/ServiceProvider.php", $provider);

        $this->line("Project created in: <info>{$path}</info>");
    }
}
