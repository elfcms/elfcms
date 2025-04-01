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
    protected $signature = 'elfcms:restore {marker} {--force}';

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
        $mark = $this->argument('marker');
        $force = $this->option('force');
        $file = 'backup_' . $mark . '.zip';
        $path = storage_path('app/elfcms/backups/' . $file);
        $sqlFile = storage_path('app/elfcms/backups/db_' . $mark . '.sql');

        if (!file_exists($path)) {
            $this->error("Backup file not found: $file");
            return 1;
        }

        $this->info("Restoring from backup: $file");

        if (!$force && !$this->confirm('This will overwrite existing files and database. Continue?')) {
            $this->warn('Restore cancelled.');
            return 0;
        }

        $tempDir = storage_path('app/tmp_restore');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $unzip = "unzip -oq '$path' -d '$tempDir'";
        exec($unzip, $output, $result);

        if ($result !== 0) {
            $this->error("Failed to unzip archive");
            Log::channel('backup')->error("Failed to unzip archive", ['cmd' => $unzip, 'output' => $output]);
            return 1;
        }

        $this->info("Files extracted. Applying changes...");

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
                Log::channel('backup')->info("Restored $subdir to $target");
                $this->line("Restored: $subdir");
            }
        }

        if ($sqlFile && file_exists($sqlFile)) {
            $this->info("Importing database dump: " . basename($sqlFile));
            $cmd = sprintf(
                'mysql -u%s -p%s %s < %s 2>/dev/null',
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                env('DB_DATABASE'),
                escapeshellarg($sqlFile)
            );
            exec($cmd, $out, $res);
            if ($res !== 0) {
                $this->error("Failed to import SQL dump");
                Log::channel('backup')->error("SQL import failed", ['cmd' => $cmd, 'out' => $out]);
            } else {
                $this->info("Database restored successfully");
            }
        }

        $this->info("Cleanup temporary files");
        exec("rm -rf $tempDir");
        $this->info("Restore complete");

        return 0;
    }
}
