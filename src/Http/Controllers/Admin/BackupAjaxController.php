<?php

namespace Elfcms\Elfcms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BackupAjaxController extends Controller
{
    public function start(Request $request)
    {
        Cache::put('backup_progress', [
            'step' => 'Starting backup...',
            'percent' => 0,
        ], now()->addMinutes(10));

        dispatch(function () {
            try {
                Cache::put('backup_progress', ['step' => 'Dumping database...', 'percent' => 15], now()->addMinutes(10));
                Artisan::call('elfcms:backup', ['--step' => 'sql']);

                Cache::put('backup_progress', ['step' => 'Zipping files...', 'percent' => 50], now()->addMinutes(10));
                Artisan::call('elfcms:backup', ['--step' => 'files']);

                Cache::put('backup_progress', ['step' => 'Finalizing...', 'percent' => 90], now()->addMinutes(10));
                sleep(1);

                Cache::put('backup_progress', ['step' => 'Completed', 'percent' => 100], now()->addMinutes(5));
                Log::info('Backup completed successfully');
            } catch (\Exception $e) {
                Log::error('Backup failed: ' . $e->getMessage());
                Cache::put('backup_progress', ['step' => 'Error: ' . $e->getMessage(), 'percent' => 0], now()->addMinutes(5));
            }
        });

        return response()->json(['status' => 'started']);
    }

    public function progress()
    {
        return response()->json(Cache::get('backup_progress', [
            'step' => 'Idle',
            'percent' => 0,
        ]));
    }
}