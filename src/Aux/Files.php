<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\FileCatalog;

class Files
{

    public static function name(string $file)
    {
        $name = FileCatalog::name($file);

        if (!$name) {
            $name = basename($file);
        }

        return $name;
    }

    public static function data_path(string $path = null)
    {
        if(!empty($path)) $path = '/' . trim($path);
        return public_path('data' . $path);
    }

    public static function file_path(string $file = null, bool $full = false, $disk = null)
    {
        if (!$disk) $disk = env('FILESYSTEM_DISK');
        $path = config('filesystems.disks.' . $disk . '.root');
        if (str_starts_with($path, storage_path())) $path = public_path('storage');
        if (!$full) {
            $path = str_replace(public_path(), '', $path);
        }
        empty($file) ? $file = $path : $file = $path . '/' . ltrim($file, '/');

        return $file;
    }
}
