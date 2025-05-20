<?php

namespace Elfcms\Elfcms\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Services\QueueStatusService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function queueHeartbit() {
        return response()->json([
            'result' => QueueStatusService::isWorkerActive()
        ]);
    }
}
