<?php

namespace Elfcms\Elfcms\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\FilestorageFile as ModelsFilestorageFile;

class FilestorageFile extends Controller
{
    public static function show(string|ModelsFilestorageFile $file)
    {
        if (is_numeric($file)) {
            $file = ModelsFilestorageFile::find($file);
        }
        elseif (is_string($file)) {
            $file = ModelsFilestorageFile::where('path',$file)->first();
        }
        if (empty($file || !($file instanceof ModelsFilestorageFile))) {
            return null;
        }
        return fsPublic($file);
    }

    public static function preview(null|string|ModelsFilestorageFile $file = null)
    {
        if (is_numeric($file)) {
            $file = ModelsFilestorageFile::find($file);
        }
        elseif (is_string($file)) {
            $file = ModelsFilestorageFile::where('path',$file)->get();
        }
        if (empty($file || !($file instanceof ModelsFilestorageFile))) {
            return null;
        }
        return fsPreview($file);
    }

    public static function stream(null|string|ModelsFilestorageFile $file = null)
    {
        if (is_numeric($file)) {
            $file = ModelsFilestorageFile::find($file);
        }
        elseif (is_string($file)) {
            $file = ModelsFilestorageFile::where('path',$file)->first();
        }
        if (empty($file || !($file instanceof ModelsFilestorageFile))) {
            return null;
        }
        return response()->file(fsPath($file),['Content-Type' => $file->mimetype]);
    }
}
