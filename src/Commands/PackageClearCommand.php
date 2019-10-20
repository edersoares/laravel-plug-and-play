<?php

namespace EderSoares\Laravel\PlugAndPlay\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PackageClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the packages cache file';

    /**
     * Execute the console command.
     *
     * @param Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $filesystem->delete(
            $this->getLaravel()->getCachedPackagesPath()
        );

        $this->info('Packages cache cleared!');
    }
}
