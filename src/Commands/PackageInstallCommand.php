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
        $updateComposerJson = empty($this->option('no-composer'));

        $result = parent::handle();

        $filename = base_path('bootstrap/app.php');
        $gitignoreFilename = base_path('packages/.gitignore');

        $file = str_replace(
            'Illuminate\Foundation\Application',
            $this->qualifyClass($this->argument('name')),
            $this->files->get($filename)
        );

        $gitignore = implode("\n", ['*', '!.gitignore']);

        $this->files->makeDirectory(base_path('packages'));
        $this->files->put($filename, $file);
        $this->files->put($gitignoreFilename, $gitignore);

        if ($updateComposerJson) {
            $this->modifyComposerJson();
        }

        return $result;
    }
}
