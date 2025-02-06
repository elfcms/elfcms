<?php

namespace Elfcms\Elfcms\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\FilestorageFile as ModelsFilestorageFile;

class FilestorageFile extends Controller
{
    public static function show(string|ModelsFilestorageFile $file)
    {
        if (is_string($file)) {
            $file = ModelsFilestorageFile::find($file);
        }
        if (empty($file || !($file instanceof ModelsFilestorageFile))) {
            return null;
        }
        return fsFile($file);
    }

    public static function preview(null|string|ModelsFilestorageFile $file = null)
    {
        if (is_string($file)) {
            $file = ModelsFilestorageFile::find($file);
        }
        if (empty($file || !($file instanceof ModelsFilestorageFile))) {
            return null;
        }
        return fsPreview($file);
    }
}
