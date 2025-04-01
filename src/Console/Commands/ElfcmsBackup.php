<?php

namespace Elfcms\Elfcms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ElfcmsBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elfcms:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create site backup';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting backup creation...');
        Log::channel('backup')->info('Backup started using elfcms:backup');

        $backupDir = storage_path('app/elfcms/backups');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $date = date('Ymd_His');

        if (backup_settings('database.enabled')) {
            $this->info('Database backup creating');
            $excluded = backup_settings('database.exclude_tables', []);
            $excludeOptions = collect($excluded)->map(fn($t) => "--ignore-table=" . env('DB_DATABASE') . ".$t")->implode(' ');
            $filename = $backupDir . '/db_' . $date . '.sql';
            $cmd = sprintf(
                'mysqldump -u%s -p%s %s %s > %s 2>/dev/null',
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                env('DB_DATABASE'),
                $excludeOptions,
                $filename
            );
            $output = null;
            $result = null;
            exec($cmd, $output, $result);
            if ($result !== 0) {
                Log::channel('backup')->error("Failed to create SQL dump", ['command' => $cmd, 'output' => $output]);
                $this->error("Failed to create database dump");
            } else {
                Log::channel('backup')->info("Database dump created: $filename");
            }
        }

        $this->info('Files backup creating');

        $backupName = 'backup_' . $date . '.zip';
        $zipPath = $backupDir . '/' . $backupName;

        $paths = collect([
            'database' => 'database',
            'resources' => 'resources',
            'app' => 'app',
            'config' => 'config',
            'routes' => 'routes',
        ])->filter(fn($_, $k) => backup_settings("paths.$k"))->values()->all();

        $include = backup_settings('paths.include', []);
        $exclude = array_merge(
            backup_settings('paths.exclude', []),
            backup_settings('exclude_patterns', [])
        );

        $excludeArg = implode(' ', array_map(fn($e) => "-x '$e'", $exclude));

        $this->info('Zip archive creating');

        foreach ($paths as $path) {
            $fullPath = base_path($path);
            if (file_exists($fullPath)) {
                $cmd = "cd " . base_path() . " && zip -r -q $zipPath $path $excludeArg";
                $this->line("Adding $path to zip archive");
                exec($cmd, $out, $res);
                Log::channel('backup')->info("Added $path to archive", ['cmd' => $cmd, 'result' => $res]);
            }
        }

        // public
        if (backup_settings('paths.public')) {
            $this->line("Adding public folder to zip archive");
            $publicPath = public_path();
            $excludeArgument = $excludeArg;
            if (!backup_settings('paths.public_storage')) {
                if ($excludeArgument == '') $excludeArgument = '-x';
                $excludeArgument .= " 'public/storage/*'";
            }
            if (!backup_settings('paths.public_files')) {
                if ($excludeArgument == '') $excludeArgument = '-x';
                $excludeArgument .= " 'public/" . config('elfcms.elfcms.file_path') . "/*'";
            }
            if (is_dir($publicPath)) {
                $cmd = "cd " . base_path() . " && zip -r -q $zipPath public $excludeArgument";
                exec($cmd, $out, $res);
                Log::channel('backup')->info("Added public/", ['cmd' => $cmd, 'result' => $res]);
            }
        }

        // ELF CMS
        if (backup_settings('paths.modules')) {
            $this->line("Adding ELF CMS modules to zip archive");
            $packagesPath = base_path('vendor/elfcms');
            if (is_dir($packagesPath)) {
                $cmd = "cd " . base_path() . " && zip -r -q $zipPath packages/elfcms $excludeArg";
                exec($cmd, $out, $res);
                Log::channel('backup')->info("Added modules", ['cmd' => $cmd, 'result' => $res]);
            } else {
                $packagesPath = base_path('packages/elfcms');
                if (is_dir($packagesPath)) {
                    $cmd = "cd " . base_path() . " && zip -r -q $zipPath packages/elfcms $excludeArg";
                    exec($cmd, $out, $res);
                    Log::channel('backup')->info("Added modules", ['cmd' => $cmd, 'result' => $res]);
                }
            }
        }

        $this->line("Adding custom path");

        foreach ($include as $addPath) {
            $cmd = "zip -r -q $zipPath $addPath $excludeArg";
            exec($cmd, $out, $res);
            Log::channel('backup')->info("Added custom path", ['cmd' => $cmd, 'result' => $res]);
        }

        if (!file_exists($zipPath)) {
            $this->error('Backup creation failed.');
            Log::channel('backup')->error("Backup failed");
        } else {
            $this->info("Backup created: $backupName");
            Log::channel('backup')->info("Backup completed", ['path' => $zipPath]);
        }
    }
}
