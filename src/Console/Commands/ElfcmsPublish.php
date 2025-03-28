<?php

namespace Elfcms\Elfcms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ElfcmsPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:publish {module=elfcms} {--tag=} {--noforce}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishing of ELF CMS Module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $module = ucfirst($this->argument('module'));
        $provider = 'Elfcms\\' . $module . '\Providers\ElfcmsModuleProvider';
        $exitCode = Artisan::call('vendor:publish', [
            '--provider' => $provider, '--force' => !$this->option('noforce'), '--tag' => $this->option('tag') ?? null
        ]);

        if ($exitCode == 0) {
            $this->info('Module ELF CMS ' . $module . ' was published successfully!');
        } else {
            $this->error('Publishing of module ELF CMS ' . $module . ' completed with error ' . $exitCode);
        }
    }
}
