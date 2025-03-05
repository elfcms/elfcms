<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\FileCatalog;
use Elfcms\Elfcms\Models\FilestorageFile;

class Filestorage
{

    public static function extension(null|string|FilestorageFile $file)
    {
        if (empty($file)) {
            return null;
        }
        if (is_string($file) && !file_exists($file)) {
            $file = FilestorageFile::find($file);
            if (empty($file)) {
                return null;
            }
            $file = $file->path;
        }
        elseif ($file instanceof FilestorageFile) {
            $file = $file->path;
        }
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    public static function file_path(string|null $file = null, bool $full = false, $disk = null)
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


    public static function file(null|string|FilestorageFile $file, $asString = false)
    {
        if (is_string($file)) {
            $file = FilestorageFile::find($file);
        }
        if (empty($file) || !($file instanceof FilestorageFile)) {
            return null;
        }

        $result = config('filesystems.disks.filestorage.root') . '/' . $file->path;

        if (!file_exists($result)) {
            return null;
        }

        if ($asString) {
            return $result;
        }
        return response()->file($result,['Content-Type' => $file->mimetype]);
    }

    public static function icon($extension) {
        $icons = [
            'jpg' => 'jpeg',
            'jpeg' => 'jpeg',
            'png' => 'png',
            'gif' => 'gif',
            'bmp' => 'bmp',
            'svg' => 'svg',
            'svg+xml' => 'svg',
            'pdf' => 'pdf',
            'doc' => 'doc',
            'docx' => 'doc',
            'xls' => 'xls',
            'xlsx' => 'xls',
            'odt' => 'odt',
            'ods' => 'ods',
            'txt' => 'txt',
            'html' => 'html',
            'css' => 'css',
            'js' => 'js',
            'mp4' => 'mp4',
            'avi' => 'avi',
            'webm' => 'webm',
            'mp3' => 'mp3',
            'wav' => 'wav',
            'zip' => 'zip',
            'rar' => 'rar',
            '7z' => '7z',
            'tar' => 'tar',
            'default' => 'any',
            'none' => 'none',
        ];

        if (!isset($icons[$extension])) {
            $extension = 'default';
        }
        $file = '/elfcms/admin/images/icons/filestorage/' . $icons[$extension] . '.svg';
        file_exists(public_path($file)) ? $icon = $file : $icon = '/elfcms/admin/images/icons/filestorage/any.svg';
        return $icon;
    }

    /* public static function image($ulid) {
        $file = FilestorageFile::find($ulid);
        return $file;
    } */

    // TODO: Implement image preview cache
    public static function preview($file, $width = 0, $height = 0, $coef = 1, $maxDimension = 0)
    {
        $extension = self::extension($file);
        $fileResource = self::file($file);
        if (empty($fileResource)) {
            return response()->file(public_path(self::icon('none'), true));
        }
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'svg+xml', 'webp'])) {
            return $fileResource;
            /* $file = Image::adaptResizeCache(route('files',$file), $width, $height, $coef, $maxDimension);
            dd($file);
            return response()->file($file,['Content-Type' => $file->mimetype]); */
        }
        return response()->file(public_path(self::icon($extension), true));
    }
}
