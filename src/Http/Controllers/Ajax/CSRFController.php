<?php

namespace Elfcms\Elfcms\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Illuminate\Http\Request;

class CSRFController extends Controller
{
    public function get(Request $request)
    {

        if ($request->ajax()) return response()->json(['token' => csrf_token()], 200);
        return response()->json(['error' => 'Forbidden'], 403);

    }

}
