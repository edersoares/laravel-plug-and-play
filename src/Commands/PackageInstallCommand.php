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
    protected $signature = 'package:install';

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

        return $result;
    }
}
