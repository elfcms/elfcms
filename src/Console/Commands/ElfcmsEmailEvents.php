<?php

namespace Elfcms\Elfcms\Console\Commands;

use Elfcms\Elfcms\Models\EmailEvent;
use Illuminate\Console\Command;

class ElfcmsEmailEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:emailevents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set default email events';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = new EmailEvent();

        $model->start();

        $this->info('Default  email events writing');
    }
}
