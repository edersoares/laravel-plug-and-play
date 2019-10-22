<?php

namespace EderSoares\Laravel\PlugAndPlay\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Input\InputArgument;

class PackageInstallCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:install {--no-composer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install plug and play packages';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Application';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../stubs/application.stub';
    }

    /**
     * Modify Application class in bootstrap/app.php.
     *
     * @throws FileNotFoundException
     *
     * @return void
     */
    protected function modifyBootstrapAppFile()
    {
        $filename = base_path('bootstrap/app.php');

        $file = str_replace(
            'Illuminate\Foundation\Application',
            $this->qualifyClass($this->argument('name')),
            $this->files->get($filename)
        );

        $this->files->put($filename, $file);
    }

    /**
     * Create packages directory if not exists.
     *
     * @return void
     */
    protected function createPackagesDirectory()
    {
        $packages = base_path('packages');

        if ($this->files->exists($packages)) {
            return;
        }

        $this->files->makeDirectory(base_path('packages'));
    }

    /**
     * Create packages/.gitignore file if not exists.
     *
     * @return void
     */
    protected function createGitIgnoreFile()
    {
        $filename = base_path('packages/.gitignore');

        if ($this->files->exists($filename)) {
            return;
        }

        $content = implode("\n", ['*', '!.gitignore']);

        $this->files->put($filename, $content);
    }

    /**
     * Add merge-plugin config in composer.json.
     *
     * @throws FileNotFoundException
     *
     * @return void
     */
    protected function modifyComposerJson()
    {
        $composerFilename = base_path('composer.json');

        $composer = json_decode(
            $this->files->get($composerFilename),
            true
        );

        $composer['extra']['merge-plugin'] = [
            'include' => [
                'packages/*/*/composer.json',
            ],
        ];

        $this->files->put(
            $composerFilename,
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     *
     * @return null|bool
     */
    public function handle()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, '', 'Extensions\\Application');

        $result = parent::handle();

        $this->modifyBootstrapAppFile();
        $this->createPackagesDirectory();
        $this->createGitIgnoreFile();

        if (empty($this->option('no-composer'))) {
            $this->modifyComposerJson();
        }

        return $result;
    }
}
