<?php

namespace Elfcms\Elfcms\Console\Commands;

use Elfcms\Elfcms\Models\FormFieldType;
use Illuminate\Console\Command;

class ElfcmsFieldTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:fieldtypes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set form field types';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = new FormFieldType();

        $model->start();

        $this->info('Form field types writing');
    }
}
