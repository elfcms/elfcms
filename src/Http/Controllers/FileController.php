<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function show($filename)
    {
        $path = storage_path('app/uploads/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        // Check owner
        // if (auth()->id() !== $fileOwnerId) { abort(403); }

        return response()->file($path);
    }

    public function download($filename)
    {
        $path = storage_path('app/uploads/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->download($path);
    }
}