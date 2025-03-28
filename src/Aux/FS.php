<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\FilestorageFile;

class FS
{
    /* public $file;
    public function __construct(FilestorageFile|string $filestorageFile)
    {
        if (gettype($filestorageFile) == 'string') {
            $this->file = self::get($filestorageFile);
        } else {
            $this->file = $filestorageFile;
        }

        return $this->file;
    } */

    private static function checkFile(string|FilestorageFile|null $file = null) {
        if (!$file) return null;
        if (gettype($file) == 'string') {
            $file = self::get($file);
        }
        if (!($file instanceof FilestorageFile)) return null;
        return $file;
    }

    public static function get($id): FilestorageFile|null
    {
        return FilestorageFile::find($id) ?? null;
    }

    public static function path(string|FilestorageFile|null $file = null)
    {
        $file = self::checkFile($file);
        return config('filesystems.disks.filestorage.root') . '/' . $file->path;
    }

    public static function public(string|FilestorageFile|null $file = null)
    {
        $file = self::checkFile($file);
        return config('elfcms.elfcms.file_path') . '/' . $file->path;
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
            'any' => 'any',
            'none' => 'none',
        ];

        if (!isset($icons[$extension])) {
            $extension = 'default';
        }
        $file = '/elfcms/admin/images/icons/filestorage/' . $icons[$extension] . '.svg';
        file_exists(public_path($file)) ? $icon = $file : $icon = '/elfcms/admin/images/icons/filestorage/any.svg';
        return $icon;
    }

    public static function preview(string|FilestorageFile|null $file = null, $icon = false) {
        $file = self::checkFile($file);
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if (!file_exists($file->full_path)) {
            return self::icon('none');
        }
        if (!$icon && in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'svg+xml', 'webp'])) {
            return $file->public_path;
        }
        return self::icon($extension);
    }
}
