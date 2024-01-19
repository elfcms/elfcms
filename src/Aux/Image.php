<?php

namespace Elfcms\Elfcms\Aux;

use GdImage;
use Illuminate\Support\Facades\Storage;

class Image
{

    public static function cropCache(string $file, int $width, int $height, array $position = ['center', 'center'])
    {
        $file = str_replace('/storage/', 'public/', $file);
        $cacheDir = 'public/images/cache/';
        $isDir = Storage::makeDirectory($cacheDir);
        if (!$isDir) {
            return $file;
        }
        $pathinfo = pathinfo($file);
        $newFile = $cacheDir . $pathinfo['filename'] . '_' . $width . '_' . $height . '.' . $pathinfo['extension'];
        if (Storage::exists($newFile)) {
            return str_replace('public/', '/storage/', $newFile);
        }
        $crop = self::crop($file, $cacheDir, $width, $height, $position);
        if (!$crop) {
            return $file;
        } else {
            return str_replace('public/', '/storage/', $crop);
        }
    }

    public static function crop(string $file, string $destination, int $width, int $height, array $position = ['center', 'center'])
    {
        $filePath = Storage::path($file);
        if (empty($file) || empty($filePath) || !file_exists($filePath)) {
            return false;
        }
        $imageData = getimagesize($filePath);
        if (!in_array($imageData[2], [1, 2, 3, 18])) {
            return false;
        }

        if (empty($position)) {
            $position = ['center', 'center'];
        }
        if (empty($position[0])) {
            $position[0] = 'center';
        }
        if (!in_array($position[0], ['center', 'top', 'bottom'])) {
            $position[0] = 'center';
        }
        if (empty($position[1])) {
            $position[1] = 'center';
        }
        if (!in_array($position[1], ['center', 'left', 'right'])) {
            $position[1] = 'center';
        }

        $imageWidth = $imageData[0];
        $imageHeight = $imageData[1];
        $imageKoef = $imageWidth / $imageHeight;

        $koef = $width / $height;

        $tmpWidth = $width;
        $tmpHeight = $height;

        $dstX = 0;
        $dstY = 0;

        if ($koef < $imageKoef) {
            $tmpHeight = $height;
            $tmpWidth = intval(round($tmpHeight * $imageKoef));
            if ($position[1] == 'center') {
                $dstX = intval(floor(($tmpWidth - $width) / 2)) * -1;
            } elseif ($position[1] == 'left') {
                $dstX = 0;
            } elseif ($position[1] == 'right') {
                $dstX = $width - $tmpWidth;
            }
        } else {
            $tmpWidth = $width;
            $tmpHeight = intval(round($tmpWidth / $imageKoef));
            if ($position[0] == 'center') {
                $dstY = intval(floor(($tmpHeight - $height) / 2)) * -1;
            } elseif ($position[0] == 'top') {
                $dstY = 0;
            } elseif ($position[0] == 'bottom') {
                $dstY = $height - $tmpHeight;
            }
        }

        $extension = image_type_to_extension($imageData[2], false);
        $crateFunction = 'imagecreatefrom' . $extension;
        $saveFunction = 'image' . $extension;
        $image = $crateFunction($filePath);
        $tmpImage = imagecreatetruecolor($tmpWidth, $tmpHeight);
        if ($imageData[2] == 1 || $imageData[2] == 3 || $imageData[2] == 18) {
            imagealphablending($tmpImage, true);
            imagesavealpha($tmpImage, true);
            $transparent = imagecolorallocatealpha($tmpImage, 0, 0, 0, 127);
            imagefill($tmpImage, 0, 0, $transparent);
            imagecolortransparent($tmpImage, $transparent);
        }
        imagecopyresampled($tmpImage, $image, 0, 0, 0, 0, $tmpWidth, $tmpHeight, $imageWidth, $imageHeight);
        $resultImage = imagecreatetruecolor($width, $height);
        if ($imageData[2] == 1 || $imageData[2] == 3 || $imageData[2] == 18) {
            imagealphablending($resultImage, true);
            imagesavealpha($resultImage, true);
            $transparent = imagecolorallocatealpha($resultImage, 0, 0, 0, 127);
            imagefill($resultImage, 0, 0, $transparent);
            imagecolortransparent($resultImage, $transparent);
        } else {
            $white = imagecolorallocate($resultImage, 255, 255, 255);
            imagefill($resultImage, 0, 0, $white);
        }

        imagecopy($resultImage, $tmpImage, $dstX, $dstY, 0, 0, $tmpWidth, $tmpHeight);

        if ($extension == 'jpeg') {
            $extension = 'jpg';
        }

        $resultName = basename($filePath, '.' . $extension) . '_' . $width . '_' . $height . '.' . $extension;
        $resultPath = Storage::path($destination) . $resultName;
        if (!file_exists($destination)) {
            Storage::makeDirectory($destination);
        }
        $result = $saveFunction($resultImage, $resultPath);

        imagedestroy($tmpImage);
        imagedestroy($image);
        imagedestroy($resultImage);

        if ($result) {
            $result = $destination . $resultName;
        }

        return $result;
    }

    public static function resize(string $file, string $destination, int $width = null, int $height = null)
    {
        //TODO: check for resizing
        $filePath = Storage::path($file);
        if (empty($file) || empty($filePath) || !file_exists($filePath)) {
            return false;
        }
        $imageData = getimagesize($filePath);
        if (!in_array($imageData[2], [1, 2, 3, 18])) {
            return false;
        }
        $imageWidth = $imageData[0];
        $imageHeight = $imageData[1];
        $imageKoef = $imageWidth / $imageHeight;

        $dstX = 0;
        $dstY = 0;

        $tmpWidth = $width;
        $tmpHeight = $height;

        if (empty($width) && empty($height)) {
            $width = $tmpWidth = $imageWidth;
            $height = $tmpHeight = $imageHeight;
        } elseif (empty($width)) {
            $width = $tmpWidth = $imageWidth / ($imageHeight / $height);
        } elseif (empty($height)) {
            $height = $tmpHeight = $imageHeight / ($imageWidth / $width);
        } else {
            $koef = $width / $height;
            if ($imageKoef < $koef) {
                $tmpWidth = round($height * $imageKoef);
                $dstX = round(abs($width - $tmpWidth) / 2);
            } elseif ($imageKoef > $koef) {
                $tmpHeight = round($width / $imageKoef);
                $dstY = round(abs($height - $tmpHeight) / 2);
            }
        }
        $extension = image_type_to_extension($imageData[2], false);
        $crateFunction = 'imagecreatefrom' . $extension;
        $saveFunction = 'image' . $extension;
        $image = $crateFunction($filePath);
        $tmpImage = imagecreatetruecolor($tmpWidth, $tmpHeight);
        if ($imageData[2] == 1 || $imageData[2] == 3 || $imageData[2] == 18) {
            imagealphablending($tmpImage, true);
            imagesavealpha($tmpImage, true);
            $transparent = imagecolorallocatealpha($tmpImage, 0, 0, 0, 127);
            imagefill($tmpImage, 0, 0, $transparent);
            imagecolortransparent($tmpImage, $transparent);
        }
        imagecopyresampled($tmpImage, $image, 0, 0, 0, 0, $tmpWidth, $tmpHeight, $imageWidth, $imageHeight);
        $resultImage = imagecreatetruecolor($width, $height);
        if ($imageData[2] == 1 || $imageData[2] == 3 || $imageData[2] == 18) {
            imagealphablending($resultImage, true);
            imagesavealpha($resultImage, true);
            $transparent = imagecolorallocatealpha($resultImage, 0, 0, 0, 127);
            imagefill($resultImage, 0, 0, $transparent);
            imagecolortransparent($resultImage, $transparent);
        } else {
            $white = imagecolorallocate($resultImage, 255, 255, 255);
            imagefill($resultImage, 0, 0, $white);
        }

        imagecopy($resultImage, $tmpImage, $dstX, $dstY, 0, 0, $tmpWidth, $tmpHeight);

        if ($extension == 'jpeg') {
            $extension = 'jpg';
        }

        $resultName = basename($filePath, '.' . $extension) . '_' . $width . '_' . $height . '.' . $extension;
        $resultPath = Storage::path($destination) . $resultName;
        if (!file_exists($destination)) {
            Storage::makeDirectory($destination);
        }
        $result = $saveFunction($resultImage, $resultPath);

        imagedestroy($tmpImage);
        imagedestroy($image);
        imagedestroy($resultImage);

        if ($result) {
            $result = $destination . $resultName;
        }

        return $result;
    }


    public static function watermarkGd(string|object $image, string|object $stamp, int|bool $top = null, int|bool $left = null, int $bottom = null, int $right = null)
    {
        if (gettype($image) === 'string') {
            $image = self::fromFile($image);
        }

        if (gettype($image) !== 'object' || !($image instanceof GdImage)) {
            return false;
        }

        if (gettype($stamp) === 'string') {
            $stamp = self::fromFile($stamp);
        }

        if (gettype($stamp) !== 'object' || !($stamp instanceof GdImage)) {
            return false;
        }

        $imageWidth = imagesx($image) ?? 0;
        $imageHeight = imagesy($image) ?? 0;

        $stampWidth = imagesx($stamp) ?? 0;
        $stampHeight = imagesy($stamp) ?? 0;

        if ($right !== null && $right !== false && ($left === null || $left === false)) {
            $left = $imageWidth - $stampWidth - $right;
        } else {
            $left = 0;
        }

        if ($bottom !== null && $bottom !== false && ($top === null || $top === false)) {
            $top = $imageHeight - $stampHeight - $bottom;
        } else {
            $top = 0;
        }

        imagecopy($image, $stamp, $left, $top, 0, 0, $stampWidth, $stampHeight);

        imagedestroy($stamp);

        return $image;
    }

    public static function watermark(string|object $image, string|object $stamp, string $file, int|bool $top = null, int|bool $left = null, int $bottom = null, int $right = null)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($extension, ['gif', 'jpeg', 'jpg', 'png', 'webp'])) {
            return false;
        }
        if ($extension == 'jpg') {
            $extension = 'jpeg';
        }
        $saveFunction = 'image' . $extension;

        if ($saveFunction(self::watermarkGd($image, $stamp, $top, $left, $bottom, $right), Storage::path($file))) {
            return $file;
        }

        return false;
    }

    public static function fromFile(string $file)
    {
        $filePath = Storage::path($file);
        if (empty($file) || empty($filePath) || !file_exists($filePath)) {
            return false;
        }
        $imageData = getimagesize($filePath);
        if (!in_array($imageData[2], [1, 2, 3, 18])) {
            return false;
        }
        $extension = image_type_to_extension($imageData[2], false);
        $crateFunction = 'imagecreatefrom' . $extension;

        return $crateFunction($filePath);
    }
}
