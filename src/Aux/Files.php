<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\FileCatalog;
use SimpleXMLElement;

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
        if (!empty($path)) $path = '/' . trim($path);
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

    public static function iconHtml(string $file = null, int $width = null, int $height = null, bool $svg = false)
    {
        $fullPath = self::file_path($file, true);
        $file = self::file_path($file);
        if (!file_exists($fullPath)) return '';

        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if ($extension == 'svg' && $svg === true) {
            $html = file_get_contents($fullPath);
            $xml = new SimpleXMLElement($html);
            if ($width) {
                $xml['width'] = $width;
            }
            if ($height) {
                $xml['height'] = $height;
            }
            $html = $xml->asXML();
        } else {
            $html = '<img src="' . $file . '"';
            if ($width) $html .= ' width="' . $width . '"';
            if ($height) $html .= ' height="' . $height . '"';
            $html .= '>';
        }
        return $html;
    }

    public static function iconHtmlLocal(string $file = null, int $width = null, int $height = null, bool $svg = false)
    {
        $fullPath = public_path($file);
        if (!file_exists($fullPath)) return '';

        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if ($extension == 'svg' && $svg === true) {
            $html = file_get_contents($fullPath);
            $xml = new SimpleXMLElement($html);
            if ($width) {
                $xml['width'] = $width;
            }
            if ($height) {
                $xml['height'] = $height;
            }
            $html = $xml->asXML();
        } else {
            $html = '<img src="' . $file . '"';
            if ($width) $html .= ' width="' . $width . '"';
            if ($height) $html .= ' height="' . $height . '"';
            $html .= '>';
        }
        return $html;
    }
}
