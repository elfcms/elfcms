<?php

namespace Elfcms\Elfcms\Console\Commands;

use Elfcms\Elfcms\Models\Setting;
use Illuminate\Console\Command;

class ElfcmsSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set default settings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = new Setting();

        $model->start();

        $this->info('Default settings writing');
    }
}
