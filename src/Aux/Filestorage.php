<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\FileCatalog;

class Filestorage
{

    public static function extension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
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
