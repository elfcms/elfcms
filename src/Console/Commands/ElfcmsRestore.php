<?php

namespace Elfcms\Elfcms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ElfcmsRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:restore {name} {--type=all} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore site from backup zip file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = strtolower($this->option('type'));

        if (!in_array($type,['all','both','zip','files','sql','database'])) {
            $this->info(__('elfcms::default.nothing_to_restore'));
            return 1;
        }

        $mark = $this->argument('name');
        $force = $this->option('force');
        $file = 'backup_' . $mark . '.zip';
        $path = storage_path('app/elfcms/backups/' . $file);
        $sqlFile = storage_path('app/elfcms/backups/db_' . $mark . '.sql');

        if ($type == 'all' || $type == 'both' || $type == 'zip' || $type == 'files') {
            if (!file_exists($path)) {
                $this->error(__('elfcms::default.backup_file_not_found',['file'=>$file]));
                return 1;
            }

            $this->info(__('elfcms::default.restoring_backup',['file'=>$file]));

            if (!$force && !$this->confirm(__('elfcms::default.confirm_restore'))) {
                $this->warn(__('elfcms::default.restore_cancelled'));
                return 0;
            }

            $tempDir = storage_path('app/tmp_restore');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $unzip = "unzip -oq '$path' -d '$tempDir'";
            exec($unzip, $output, $result);

            if ($result !== 0) {
                $this->error(__('elfcms::default.failed_unzip'));
                Log::channel('backup')->error(__('elfcms::default.failed_unzip'), ['cmd' => $unzip, 'output' => $output]);
                return 1;
            }

            $this->info(__('elfcms::default.extracted_files'));

            $map = [
                'app' => base_path('app'),
                'config' => base_path('config'),
                'routes' => base_path('routes'),
                'resources' => base_path('resources'),
                'public' => public_path(),
                'public/storage' => storage_path('app/public'),
                'public/files' => storage_path('app/' . env('FILESTORAGE_ROOT', 'elfcms/filestorage')),
                'packages/elfcms' => base_path('packages/elfcms'),
                'vendor/elfcms' => base_path('vendor/elfcms'),
            ];

            foreach ($map as $subdir => $target) {
                $src = $tempDir . DIRECTORY_SEPARATOR . $subdir;
                if (is_dir($src)) {
                    $cmd = "cp -r $src/. $target";
                    exec($cmd);
                    Log::channel('backup')->info(__('elfcms::default.directory_restored',['dir'=>$subdir,'target'=>$target]));
                    $this->line(__('elfcms::default.restored') . ": $subdir");
                }
            }
        }

        if ($type == 'all' || $type == 'both' || $type == 'sql' || $type == 'database') {
            if ($sqlFile && file_exists($sqlFile)) {
                $this->info(__('elfcms::default.importing_sql',['file'=>basename($sqlFile)]));
                $cmd = sprintf(
                    'mysql -u%s -p%s %s < %s 2>/dev/null',
                    env('DB_USERNAME'),
                    env('DB_PASSWORD'),
                    env('DB_DATABASE'),
                    escapeshellarg($sqlFile)
                );
                exec($cmd, $out, $res);
                if ($res !== 0) {
                    $this->error(__('elfcms::default.import_sql_failed'));
                    Log::channel('backup')->error(__('elfcms::default.sql_import_failed'), ['cmd' => $cmd, 'out' => $out]);
                } else {
                    $this->info(__('elfcms::default.database_restored'));
                }
            }
        }

        $this->info(__('elfcms::default.cleanup_temp'));
        exec("rm -rf $tempDir");
        $this->info(__('elfcms::default.restore_completed'));

        return 0;
    }
}
