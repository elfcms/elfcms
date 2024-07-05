<?php

namespace Elfcms\Elfcms\Aux;

use GdImage;
use Illuminate\Support\Facades\Storage;

class Image
{

    public static function cropCache(string $file, int $width, int $height, array $position = ['center', 'center'])
    {
        //$file = str_replace('/storage/', 'public/', $file);
        $cacheDir = 'elfcms/images/cache';
        $isDir = is_dir(Storage::path($cacheDir));
        if (!$isDir) {
            $isDir = Storage::makeDirectory(Storage::path($cacheDir));
        }

        if (!$isDir) {
            return $file;
        }

        $pathinfo = pathinfo($file);
        $newFile = $cacheDir . '/' . $pathinfo['filename'] . '_' . $width . '_' . $height . '.' . $pathinfo['extension'];
        if (Storage::exists($newFile)) {
            return $newFile;
        }
        $crop = self::crop($file, $cacheDir, $width, $height, $position);
        if (!$crop) {
            return file_path($file);
        } else {
            return $crop;
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
        if ($extension == 'jpeg') {
            $exif = exif_read_data($filePath);
            if ($image && $exif && isset($exif['Orientation'])) {
                $ort = $exif['Orientation'];

                if ($ort == 6 || $ort == 5) {
                    $image = imagerotate($image, 270, 0);
                    $tw = $tmpWidth;
                    $iw = $imageWidth;
                    $w = $width;
                    $tmpWidth = $tmpHeight;
                    $imageWidth = $imageHeight;
                    $width = $height;
                    $tmpHeight = $tw;
                    $imageHeight = $iw;
                    $height = $w;
                    $x = $dstX;
                    $dstX = $dstY;
                    $dstY = $x;
                }
                if ($ort == 3 || $ort == 4) {
                    $image = imagerotate($image, 180, 0);
                }
                if ($ort == 8 || $ort == 7) {
                    $image = imagerotate($image, 90, 0);
                    $tw = $tmpWidth;
                    $iw = $imageWidth;
                    $w = $width;
                    $tmpWidth = $tmpHeight;
                    $imageWidth = $imageHeight;
                    $width = $height;
                    $tmpHeight = $tw;
                    $imageHeight = $iw;
                    $height = $w;
                    $x = $dstX;
                    $dstX = $dstY;
                    $dstY = $x;
                }

                if ($ort == 5 || $ort == 4 || $ort == 7) {
                    imageflip($image, IMG_FLIP_HORIZONTAL);
                }
            }
        }

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
        $resultPath = $destination . '/' . $resultName;

        if (!file_exists($destination)) {
            Storage::makeDirectory($destination);
        }
        $result = $saveFunction($resultImage, Storage::path($resultPath));

        imagedestroy($tmpImage);
        imagedestroy($image);
        imagedestroy($resultImage);

        if ($result) {
            $result = $destination . '/' . $resultName;
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

    public static function adaptResizeCache($filePath, $width = null, $height = null, $coef = 1, $maxDimension = null) {
        //$file = str_replace('/storage/', 'public/', $filePath);
        $file = $filePath;
        $cacheDir = 'elfcms/images/cache/resized/';
        $isDir = Storage::makeDirectory($cacheDir);
        if (!$isDir) {
            return $file;
        }
        $pathinfo = pathinfo($file);
        $addInfo = '';
        if ($width) {
            $addInfo .= '_w' . $width;
        }
        if ($height) {
            $addInfo .= '_h' . $height;
        }
        if ($coef && $coef != 1) {
            $addInfo .= '_c' . $coef;
        }
        if ($maxDimension) {
            $addInfo .= '_m' . $maxDimension;
        }
        $newFile = $cacheDir . $pathinfo['filename'] . $addInfo . '.' . $pathinfo['extension'];
        if (Storage::exists($newFile)) {
            //return str_replace('public/', '/storage/', $newFile);
            return  $newFile;
        }
        $resized = self::adaptResize($filePath, $width, $height, $coef, $newFile, $maxDimension);
        if (!$resized) {
            return $file;
        } else {
            //return str_replace('public/', '/storage/', $resized);
            return $resized;
        }
    }

    public static function adaptResize($file, $width = null, $height = null, $coef = 1, $resultFile = null, $gd = false, $maxDimension = null) {
        /* $basePath = base_path();
        if (!file_exists($basePath . '/'. trim($filePath,'/'))) {
            $filePath = str_replace('/storage/', $basePath . '/storage/app/public/', $filePath);
        } */
        $filePath = Storage::path($file);
        if (!file_exists($filePath)) {
            return null;
        }
        $imageData = getimagesize($filePath);
        $extension = image_type_to_extension($imageData[2], false);
        $crateFunction = 'imagecreatefrom' . $extension;
        $saveFunction = 'image' . $extension;
        if ($extension == 'jpeg') {
            $extension = 'jpg';
        }
        $image = $crateFunction($filePath);

        $ratio = $imageData[0] / $imageData[1];
        if ($maxDimension) {
            if ($ratio > 1) {
                $maxDim = $imageData[0];
            }
            else {
                $maxDim = $imageData[1];
            }
            if ($maxDimension < $maxDim) {
                $coef = $maxDimension / $maxDim;
            }
        }

        if ($extension == 'jpg') {
            $exif = exif_read_data($filePath);
            if ($image && $exif && isset($exif['Orientation'])) {
                $ort = $exif['Orientation'];

                if ($ort == 6 || $ort == 5) {
                    $image = imagerotate($image, 270, 0);
                    $id0 = $imageData[0];
                    $imageData[0] = $imageData[1];
                    $imageData[1] = $id0;
                }
                if ($ort == 3 || $ort == 4) {
                    $image = imagerotate($image, 180, 0);
                }
                if ($ort == 8 || $ort == 7) {
                    $image = imagerotate($image, 90, 0);
                    $id0 = $imageData[0];
                    $imageData[0] = $imageData[1];
                    $imageData[1] = $id0;
                }

                if ($ort == 5 || $ort == 4 || $ort == 7) {
                    imageflip($image, IMG_FLIP_HORIZONTAL);
                }
            }
        }


        if (empty($width) && empty($height) && (empty($coef) || $coef == 1)) {
            $newWidth = $imageData[0];
            $newHeight = $imageData[1];
        }
        elseif (!empty($coef) && $coef != 1) {
            $newWidth = round($imageData[0] * $coef);
            $newHeight = round($imageData[1] * $coef);
        }
        elseif (!empty($width) && $width != $imageData[0]) {
            $newWidth = $width;
            $newHeight = round($newWidth / $ratio);
        }
        elseif (!empty($height) && $height != $imageData[1]) {
            $newHeight = $height;
            $newWidth = round($newHeight * $ratio);
        }

        if (empty($newWidth) || $newWidth < 1 || !is_numeric($newWidth) || empty($newHeight) || $newHeight < 1 || !is_numeric($newHeight)) {
            $newWidth = $imageData[0];
            $newHeight = $imageData[1];
        }

        if ($newWidth == $imageData[0] || $newHeight == $imageData[1]) {
            $result = $image;
        }
        else {
            $result = imagecreatetruecolor ($newWidth, $newHeight);
            imagealphablending($result, false);
            imagesavealpha($result, true);
            imagecopyresampled($result, $image, 0, 0, 0, 0, $newWidth, $newHeight, $imageData[0], $imageData[1]);
        }
        imagedestroy($image);
        imagedestroy($result);

        if ($gd === true) return $result;

        if (empty($resultFile)) {
            $dir = is_dir(Storage::path('elfcms/images/cache/')) ? 'elfcms/images/cache/' : Storage::makeDirectory('elfcms/images/cache/');
            $resultFile = $dir . uniqid() . '.' . $extension;
        }
        $filePath = Storage::path($resultFile) ?? Storage::path('/elfcms/images/cache/' . uniqid() . '.' . $extension) ?? $filePath;

        $file = $saveFunction($result, $filePath);

        if (!$file) return false;

        return $resultFile;
    }


    public static function adaptSize(string|object $image, $width = null, $height = null, $coef = 1, $resultFile = null, $gd = false) {
        if (is_string($image)){
            $imageData = getimagesize($image);
            $extension = image_type_to_extension($imageData[2], false);
            $crateFunction = 'imagecreatefrom' . $extension;
            /* $saveFunction = 'image' . $extension;
            if ($extension == 'jpeg') {
                $extension = 'jpg';
            } */
            $imageWidth = $imageData[0] ?? 0;
            $imageHeight = $imageData[1] ?? 0;
            $image = $crateFunction($image);
        }
        if (is_object($image) && ($image instanceof GdImage)) {
            $imageWidth = imagesx($image) ?? 0;
            $imageHeight = imagesy($image) ?? 0;
        }
        else {
            return false;
        }

        if ($imageWidth == 0 || $imageHeight == 0) {
            return false;
        }

        $ratio = $imageWidth / $imageHeight;

        if (empty($width) && empty($height) && (empty($coef) || $coef == 1)) {
            $newWidth = $imageWidth;
            $newHeight = $imageHeight;
        }
        elseif (!empty($coef) && $coef != 1) {
            $newWidth = round($imageWidth * $coef);
            $newHeight = round($imageHeight * $coef);
        }
        elseif (!empty($width) && $width != $imageWidth) {
            $newWidth = $width;
            $newHeight = round($newWidth / $ratio);
        }
        elseif (!empty($height) && $height != $imageHeight) {
            $newHeight = $height;
            $newWidth = round($newHeight * $ratio);
        }

        if (empty($newWidth) || $newWidth < 1 || !is_numeric($newWidth) || empty($newHeight) || $newHeight < 1 || !is_numeric($newHeight)) {
            $newWidth = $imageWidth;
            $newHeight = $imageHeight;
        }

        return [round($newWidth), round($newHeight)];
    }

    public static function stamp(string $image, string $stamp, int|string $stampsize = 50, int|string $indentH = 0, int|string $indentV = 0, string $horizontal = null, string $vertical = null, string $h = null, string $v = null, string $x = null, string $y = null, string $savePath = null, string $module = null) {
        if (!$vertical && $v) $vertical = $v;
        if (!$vertical && $y) $vertical = $y;
        if (!$vertical || !in_array($vertical,['top','center','bottom'])) $vertical = 'center';
        if (!$horizontal && $h) $horizontal = $h;
        if (!$horizontal && $x) $horizontal = $x;
        if (!$horizontal || !in_array($horizontal,['left','center','right'])) $horizontal = 'center';

        $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        if (!in_array($extension, ['gif', 'jpeg', 'jpg', 'png', 'webp'])) {
            return false;
        }
        if ($extension == 'jpg') {
            $extension = 'jpeg';
        }
        $saveFunction = 'image' . $extension;

        $image = self::fromFile($image);

        if (gettype($image) !== 'object' || !($image instanceof GdImage)) {
            return false;
        }

        $stamp = self::fromFile($stamp);

        if (gettype($stamp) !== 'object' || !($stamp instanceof GdImage)) {
            return false;
        }

        $stampsize = intval($stampsize);

        $imageWidth = imagesx($image) ?? 0;
        $imageHeight = imagesy($image) ?? 0;

        $stampWidth = imagesx($stamp) ?? 0;
        $stampHeight = imagesy($stamp) ?? 0;

        $imageRatio = $imageWidth / $imageHeight;
        $stampRatio = $stampWidth / $stampHeight;

        $maxStampWidth = $imageWidth - (2 * $indentH);
        $maxStampHeight = $imageHeight - (2 * $indentV);

        if ($imageRatio > 1) {
            $stampNewWidth = min($maxStampWidth, $imageWidth * $stampsize / 100);
            $stampNewHeight = $stampNewWidth / $stampRatio;
            if ($stampNewHeight > $maxStampHeight) {
                $stampNewHeight = $maxStampHeight;
                $stampNewWidth = $stampNewHeight * $stampRatio;
            }
        } else {
            $stampNewHeight = min($maxStampHeight, $imageHeight * $stampsize / 100);
            $stampNewWidth = $stampNewHeight * $stampRatio;
            if ($stampNewHeight > $maxStampWidth) {
                $stampNewWidth = $maxStampWidth;
                $stampNewHeight = $stampNewWidth / $stampRatio;
            }
        }

        $left = 0;
        $top = 0;

        switch ($horizontal) {
            case 'left':
                $left = $indentH;
                break;
            case 'center':
                $left = floor(($imageWidth - $stampNewWidth) / 2);
                break;
            case 'right':
                $left = $imageWidth - $stampNewWidth - $indentH;
                break;
        }

        switch ($vertical) {
            case 'top':
                $top = $indentV;
                break;
            case 'center':
                $top = floor(($imageHeight - $stampNewHeight) / 2);
                break;
            case 'bottom':
                $top = $imageHeight - $stampNewHeight - $indentV;
                break;
        }

        $stampNewWidth = round($stampNewWidth);
        $stampNewHeight= round($stampNewHeight);

        imagecopyresampled($image, $stamp, $left, $top, 0, 0, $stampNewWidth, $stampNewHeight,  $stampWidth, $stampHeight);

        if (empty($savePath)) {
            if (!empty($module)) {
                $savePath = 'public/elfcms/' . $module . '/images/stamped/';
            }
            else {
                $savePath =  'public/elfcms/images/stamped/';
            }
        }

        $dir = is_dir(Storage::path($savePath)) ? $savePath : Storage::makeDirectory($savePath);
        $file = $dir . uniqid() . '.' . $extension;

        if ($saveFunction($image    , Storage::path($file))) {
            return $file;
        }

        return false;
    }

    public static function watermarkGD(string|object $image, string|object $stamp, int|bool $top = null, int|bool $left = null, int $bottom = null, int $right = null)
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

        if ($saveFunction(self::watermarkGD($image, $stamp, $top, $left, $bottom, $right), Storage::path($file))) {
            return $file;
        }

        return false;
    }

    public static function fromFile(string $file)
    {
        if (file_exists($file)) {
            $filePath = $file;
        }
        elseif (file_exists($_SERVER['DOCUMENT_ROOT']. '/'. trim($file,'/'))) {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/' .trim($file,'/');
        }
        else {
            $filePath = Storage::path($file);
        }

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
