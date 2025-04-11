<?php

namespace Elfcms\Elfcms\Console\Commands;

use Elfcms\Elfcms\Models\Backup;
use Elfcms\Elfcms\Models\BackupStatus;
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
    protected $signature = 'elfcms:backup {--name=}';

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
        $name = $this->option('name') ?? date('Ymd_His');
        $this->info('Starting backup creation...');
        Log::channel('backup')->info('Backup started using elfcms:backup');

        $relativeDir = 'app/elfcms/backups';
        $backupDir = storage_path($relativeDir);
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        if (backupSetting('database.enabled')) {

            $dbBackup = Backup::create([
                'name' => $name,
                'type' => 'database',
                'status_id' => BackupStatus::where('name','progress')->first()->id ?? null,
                'file_path' => $relativeDir . '/db_' . $name . '.sql'
            ]);

            $this->info('Database backup creating');
            $excluded = backupSetting('database.exclude_tables', []);
            $excludeOptions = collect($excluded)->map(fn($t) => "--ignore-table=" . env('DB_DATABASE') . ".$t")->implode(' ');
            $filename = $backupDir . '/db_' . $name . '.sql';
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
                $dbBackup->setStatus('failed');
                Log::channel('backup')->error("Failed to create SQL dump", ['command' => $cmd, 'output' => $output]);
                $this->error("Failed to create database dump");
            } else {
                $dbBackup->setStatus('success');
                $this->info('Database backup created');
                Log::channel('backup')->info("Database dump created: $filename");
            }
            if (file_exists($filename)) {
                $dbBackup->file_size = filesize($filename);
                $dbBackup->save();
            }
        }

        $this->info('Files backup creating');

        $filesBackup = Backup::create([
            'name' => $name,
            'type' => 'files',
            'status_id' => BackupStatus::where('name','progress')->first()->id ?? null,
            'file_path' => $relativeDir . '/backup_' . $name . '.zip'
        ]);

        $backupName = 'backup_' . $name . '.zip';
        $zipPath = $backupDir . '/' . $backupName;

        $paths = collect([
            'database' => 'database',
            'resources' => 'resources',
            'app' => 'app',
            'config' => 'config',
            'routes' => 'routes',
        ])->filter(fn($_, $k) => backupSetting("paths.$k"))->values()->all();

        $include = backupSetting('paths.include', []);
        $exclude = array_merge(
            backupSetting('paths.exclude', []),
            backupSetting('exclude_patterns', [])
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
        if (backupSetting('paths.public')) {
            $this->line("Adding public folder to zip archive");
            $publicPath = public_path();
            $excludeArgument = $excludeArg;
            if (!backupSetting('paths.public_storage')) {
                if ($excludeArgument == '') $excludeArgument = '-x';
                $excludeArgument .= " 'public/storage/*'";
            }
            if (!backupSetting('paths.public_files')) {
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
        if (backupSetting('paths.modules')) {
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
            $filesBackup->setStatus('failed');
            $this->error('Backup creation failed.');
            Log::channel('backup')->error("Backup failed");
        } else {
            $filesBackup->setStatus('success');
            $filesBackup->file_size = filesize($zipPath);
            $filesBackup->save();
            $this->info("Backup created: $backupName");
            Log::channel('backup')->info("Backup completed", ['path' => $zipPath]);
        }
    }
}
