<?php

namespace Elfcms\Elfcms\Console\Commands;

use Elfcms\Elfcms\Models\DataType;
use Illuminate\Console\Command;

class ElfcmsDataTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:datatypes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set default data types';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = new DataType();

        $model->start();

        $this->info('Default data types writing');
    }
}
