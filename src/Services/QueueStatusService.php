<?php

namespace Elfcms\Elfcms\Services;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Elfcms\Elfcms\Jobs\QueueHeartbeatJob;

class QueueStatusService
{
    public static function isWorkerActive(): bool
    {
        $start = time();
        sleep(1);
        QueueHeartbeatJob::dispatch();
        sleep(7);
        $time = Cache::pull('queue_heartbeat');
        return $time && $time - $start > 0;
    }
}
