<?php

namespace Elfcms\Elfcms\Jobs;

use Elfcms\Elfcms\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $backupName = date('Ymd_His');

        try {
            Cache::put('backup_progress', ['step' => 'Dumping database...', 'percent' => 15], now()->addMinutes(10));

            Artisan::call('elfcms:backup', ['--name' => $backupName]);

            Cache::put('backup_result', [
                'name' => $backupName,
            ], now()->addMinutes(10));

            Cache::put('backup_progress', ['step' => 'Finalizing...', 'percent' => 90], now()->addMinutes(5));
            sleep(1);

            Cache::put('backup_progress', ['step' => 'Completed', 'percent' => 100], now()->addMinutes(5));
        } catch (\Throwable $e) {
            $backups = Backup::where('name',$backupName)->get();
            if (!empty($backups)) {
                foreach($backups as $backup) {
                    $backup->setStatus('failed');
                }
            }
            Log::error('Backup job failed', ['error' => $e->getMessage()]);
            Cache::put('backup_progress', ['step' => 'Error: ' . $e->getMessage(), 'percent' => 0]);
        } finally {
            Cache::forget('backup_lock');
            Cache::forget('backup_lock_time');
        }
    }
}
