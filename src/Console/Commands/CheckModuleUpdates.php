<?php

namespace Elfcms\Elfcms\Console\Commands;

use Illuminate\Console\Command;
use Elfcms\Elfcms\Services\ModuleUpdater;

class CheckModuleUpdates extends Command
{
    protected $signature = 'elfcms:check-updates';
    protected $description = 'Checking for new versions of ELF CMS modules via GitHub API';

    public function handle(ModuleUpdater $updater): int
    {
        $this->info('Checking for module updates...');

        try {
            $updater->checkAll();
            $this->info('Done!');
        } catch (\Throwable $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
