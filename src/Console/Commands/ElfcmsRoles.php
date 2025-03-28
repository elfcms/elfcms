<?php

namespace Elfcms\Elfcms\Console\Commands;

use Elfcms\Elfcms\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ElfcmsRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Default user roles writing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $roles = new Role();

        $roles->start();

        $this->info('Default user roles writing');
    }
}
