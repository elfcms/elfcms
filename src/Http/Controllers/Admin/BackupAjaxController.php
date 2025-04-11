<?php

namespace Elfcms\Elfcms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Jobs\CreateBackupJob;
use Elfcms\Elfcms\Models\Backup;

class BackupAjaxController extends Controller
{
    public function start(Request $request)
    {
        if (Cache::has('backup_lock')) {
            //return response()->json(['status' => 'locked', 'message' => 'Backup already running.']);
            $started = Cache::get('backup_lock_time');
            if ($started && now()->diffInMinutes($started) > 30) {
                Cache::forget('backup_lock');
                Cache::forget('backup_lock_time');
                Cache::put('backup_progress', ['step' => 'Error: stale process reset', 'percent' => 0]);
            } else {
                return response()->json([
                    'status' => 'locked',
                    'message' => 'Backup already running.'
                ]);
            }
        }
        Cache::put('backup_lock', true, now()->addHours(1));
        Cache::put('backup_lock_time', now(), now()->addHours(1));
        Cache::put('backup_progress', ['step' => 'Starting backup...', 'percent' => 0], now()->addMinutes(10));

        CreateBackupJob::dispatch();

        return response()->json(['status' => 'started']);
    }

    public function progress()
    {
        /* return response()->json(Cache::get('backup_progress', [
            'step' => __('elfcms::default.idle'),
            'percent' => 0,
        ])); */
        $progress = Cache::get('backup_progress', ['step' => 'Idle', 'percent' => 0]);

        if (Cache::has('backup_lock')) {
            $lockTime = Cache::get('backup_lock_time');
            if ($lockTime && now()->diffInMinutes($lockTime) > 30) {
                Cache::forget('backup_lock');
                Cache::forget('backup_lock_time');
                Cache::put('backup_progress', ['step' => 'Error: interrupted or timeout', 'percent' => 0]);
                $progress = Cache::get('backup_progress');
                Log::warning('Backup lock reset due to timeout');
            }
        }
        return response()->json($progress);
    }

    public function result()
    {
        $result = Cache::get('backup_result') ?? [];
        if (empty($result)) {
            $result['name'] = '';
            $result['sql'] = null;
            $result['zip'] = null;
        }
        else {
            $path = 'app/elfcms/backups/';
            $sql = 'db_'.$result['name'].'.sql';
            $sqlId = null;
            $zip = 'backup_'.$result['name'].'.zip';
            $zipId = null;
            $sqlData = Backup::where('name',$result['name'])->where('type','database')->first();
            $zipData = Backup::where('name',$result['name'])->where('type','files')->first();
            if ($sqlData && $sqlData->id) {
                $sql = $sqlData->isFile ? $sql : null;
                $sqlId = $sqlData->id;
            }
            if ($zipData && $zipData->id) {
                $zip = $zipData->isFile ? $zip : null;
                $zipId = $zipData->id;
            }
            $result['sql'] = $sql;
            $result['sql_file'] = route('admin.backup.download',$sqlId);
            $result['zip'] = $zip;
            $result['zip_file'] = route('admin.backup.download',$zipId);
        }
        return response()->json($result);
    }
}
