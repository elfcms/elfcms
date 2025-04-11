<?php

namespace Elfcms\Elfcms\Console\Commands;

use Elfcms\Elfcms\Models\Backup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ElfcmsBackupFileExists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:backup-file-exists {--chunk=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chunkSize = (int) $this->option('chunk');

        Backup::chunk($chunkSize, function ($backups) {
            foreach ($backups as $backup) {
                $exists = file_exists(storage_path(trim($backup->file_path)));
                if ($backup->file_exists !== $exists) {
                    $backup->file_exists = $exists;
                    $backup->save();
                }
            }
        });

        $this->info('Updated.');
    }
}
