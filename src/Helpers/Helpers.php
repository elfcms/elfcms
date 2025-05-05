<?php

use Elfcms\Elfcms\Aux\Files;
use Elfcms\Elfcms\Aux\Filestorage;
use Elfcms\Elfcms\Aux\Fragment;
use Elfcms\Elfcms\Aux\FS;
use Elfcms\Elfcms\Aux\Image;
use Elfcms\Elfcms\Models\BackupSetting;
use Elfcms\Elfcms\Models\ElfcmsContact;
use Elfcms\Elfcms\Models\Setting;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/* Settings */

if (!function_exists('settings')) {

    function settings()
    {
        return Setting::values();
    }
}

if (!function_exists('setting')) {

    function setting(string $code)
    {
        return Setting::value($code);
    }
}

if (!function_exists('contacts')) {

    function contacts()
    {
        return ElfcmsContact::values();
    }
}

if (!function_exists('contact')) {

    function contact(string $code)
    {
        return ElfcmsContact::value($code);
    }
}

if (!function_exists('phone')) {

    function phone($phone, $code = 49)
    {
        $nums = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($nums, 0)) {
            $nums = substr($nums, 1);
        }
        return '+' . $code . $nums;
    }
}

/* /Settings */

if (!function_exists('fragment')) {

    function fragment(string $code)
    {
        return Fragment::get($code);
    }
}

/* Image */

if (!function_exists('imgCrop')) {

    function imgCrop(string $file, string $destination, int $width, int $height, array $position = ['center', 'center'])
    {
        return Image::crop($file, $destination, $width, $height, $position);
    }
}

if (!function_exists('imgCropCache')) {

    function imgCropCache(string $file, int $width, int $height, array $position = ['center', 'center'])
    {
        return Image::cropCache($file, $width, $height, $position);
    }
}

if (!function_exists('imgResize')) {

    function imgResize($filePath, $width = null, $height = null, $coef = 1, $resultFile = null, $gd = false, $maxDimension = null)
    {
        return Image::adaptResize($filePath, $width, $height, $coef, $resultFile, $gd, $maxDimension);
    }
}

if (!function_exists('imgResizeCache')) {

    function imgResizeCache($filePath, $width = null, $height = null, $coef = 1, $maxDimension = null)
    {
        return Image::adaptResizeCache($filePath, $width, $height, $coef, $maxDimension);
    }
}

/* /Image */

/* Files */

if (!function_exists('data_path')) {

    function data_path(string|null $path = null)
    {
        return Files::data_path($path);
    }
}

if (!function_exists('file_path')) {

    function file_path(string|null $file = null, bool $full = false, $disk = null)
    {
        return Files::file_path($file, $full, $disk);
    }
}

if (!function_exists('iconHtml')) {

    function iconHtml(string|null $file = null, int|null $width = null, int|null $height = null, bool $svg = false)
    {
        return Files::iconHtml($file, $width, $height, $svg);
    }
}

if (!function_exists('iconHtmlLocal')) {

    function iconHtmlLocal(string|null $file = null, int|null $width = null, int|null $height = null, bool $svg = false)
    {
        return Files::iconHtmlLocal($file, $width, $height, $svg);
    }
}

/* /Files */

/* Cookies */

if (!function_exists('cookieConsent')) {

    function cookieConsent()
    {
        return json_decode(Cookie::get('cookie_consent'), true);
    }
}
if (!function_exists('cookieIsCategory')) {

    function cookieIsCategory($name)
    {
        $data = json_decode(Cookie::get('cookie_consent'), true);
        if (is_array($data) && !empty($data['categories']) && !empty($data['categories'][$name])) {
            return true;
        }
        return false;
    }
}

if (!function_exists('cookieGet')) {

    function cookieGet($name)
    {
        return Cookie::get($name);
    }
}

/* /Cookies */


/* Filestorage */

if (!function_exists('fsExtension')) { //! To remove

    function fsExtension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }
}

if (!function_exists('fsIcon')) { //! To remove

    function fsIcon($extension)
    {
        return Filestorage::icon($extension);
    }
}

if (!function_exists('fsMime')) { //! To remove

    function fsMime($file)
    {
        return mime_content_type($file);
    }
}

if (!function_exists('fsFile')) { //! To remove

    function fsFile($file, $asString)
    {
        return Filestorage::file($file, $asString);
    }
}

if (!function_exists('fsPreview')) {

    function fsPreview($file)
    {
        return FS::preview($file);
    }
}

if (!function_exists('fsPublic')) {

    function fsPublic($file)
    {
        return FS::public($file);
    }
}

if (!function_exists('fsPath')) {

    function fsPath($file)
    {
        return FS::path($file);
    }
}

/* /Filestorage */

/* Backup */

function backupSettings()
{
    $records = BackupSetting::all()->toArray();
    $result = [];
    if (empty($records)) {
        $configs = config("elfcms.elfcms.backup");
        if (!empty($configs) && is_array($configs)) {
            foreach ($configs as $key => $data) {
                if ($key == 'exclude_patterns') continue;
                if (is_array($data) && $key == 'database' || $key == 'paths') {
                    foreach ($data as $dataKey => $value) {
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $result[$key . '__' . $dataKey] = $value;
                    }
                } else {
                    $result[$key] = $data;
                }
            }
        }
    }
    else {
        foreach($records as $record) {
            $result[$record['key']] = $record['value'];
        }
    }
    return $result;
}

function backupSetConfig(array $settings)
{
    $result = [];
    $prefix = 'elfcms.elfcms.backup';

    foreach ($settings as $key => $value) {
        if (str_starts_with($key, 'database__')) {
            $subKey = str_replace('database__', '', $key);
            if ($subKey === 'exclude_tables') {
                $value = empty($value) ? [] : array_map('trim', explode(',', $value));
            }
            $result['database'][$subKey] = $value;
            config(["$prefix.database.$subKey" => $value]);
        } elseif (str_starts_with($key, 'paths__')) {
            $subKey = str_replace('paths__', '', $key);
            if (in_array($subKey, ['exclude', 'include'])) {
                $value = empty($value) ? [] : array_map('trim', explode(',', $value));
            }
            $result['paths'][$subKey] = $value;
            config(["$prefix.paths.$subKey" => $value]);
        } else {
            $result[$key] = $value;
            config(["$prefix.$key" => $value]);
        }
    }

    return $result;
}

function backupSetting($key, $default = null)
{
    $dbKey = str_replace('paths.','paths__',str_replace('database.','database__',$key));
    $record = BackupSetting::where('key', $dbKey)->first();
    if (!$record) return config("elfcms.elfcms.backup.$key", $default);
    $value = $record->value;
    return str_starts_with($value, '[') || str_starts_with($value, '{') ? json_decode($value, true) : $value;
}

function generateCron(array $input)
{
    $cron = [];
    $limits = [
        'minute' => [0, 59],
        'hour' => [0, 23],
        'day' => [1, 31],
        'month' => [1, 12],
        'weekday' => [0, 6],
    ];

    foreach (['minute', 'hour', 'day', 'month', 'weekday'] as $field) {
        $mode = $input[$field . '_mode'] ?? 'exact';
        if ($mode === 'every') {
            $step = max(1, intval($input[$field . '_every'] ?? 1));
            $cron[] = "*/$step";
        } else {
            $value = $input[$field] ?? '*';
            if (is_numeric($value)) {
                $min = $limits[$field][0];
                $max = $limits[$field][1];
                $value = max($min, min($max, intval($value)));
            }
            $cron[] = $value;
        }
    }

    return implode(' ', $cron);
}

function parseCron(string $cron)
{
    $parts = preg_split('/\s+/', trim($cron));
    if (count($parts) !== 5) {
        throw new InvalidArgumentException("Invalid cron string: must contain exactly 5 segments.");
    }

    [$min, $hour, $day, $month, $weekday] = $parts;

    return [
        'minute_mode' => str_starts_with($min, '*/') ? 'every' : 'exact',
        'minute' => $min,
        'minute_every' => str_replace('*/', '', $min),

        'hour_mode' => str_starts_with($hour, '*/') ? 'every' : 'exact',
        'hour' => $hour,
        'hour_every' => str_replace('*/', '', $hour),

        'day_mode' => str_starts_with($day, '*/') ? 'every' : 'exact',
        'day' => $day,
        'day_every' => str_replace('*/', '', $day),

        'month_mode' => str_starts_with($month, '*/') ? 'every' : 'exact',
        'month' => $month,
        'month_every' => str_replace('*/', '', $month),

        'weekday_mode' => str_starts_with($weekday, '*/') ? 'every' : 'exact',
        'weekday' => $weekday,
        'weekday_every' => str_replace('*/', '', $weekday),
    ];
}

// Force dispatch fallback (synchronous alternative if queue not working)
if (!function_exists('dispatch_backup_now')) {
    function dispatch_backup_now() {
        try {
            Cache::put('backup_progress', ['step' => 'Dumping database...', 'percent' => 15], now()->addMinutes(10));
            Artisan::call('elfcms:backup');
            Cache::put('backup_progress', ['step' => 'Finalizing...', 'percent' => 90], now()->addMinutes(5));
            sleep(1);
            Cache::put('backup_progress', ['step' => 'Completed', 'percent' => 100], now()->addMinutes(5));
        } catch (\Throwable $e) {
            Log::error('Sync backup failed', ['error' => $e->getMessage()]);
            Cache::put('backup_progress', ['step' => 'Error: ' . $e->getMessage(), 'percent' => 0]);
        }
    }
}


/* /Backup */

/* function page_config(): \Elfcms\Elfcms\Services\PageConfigService
{
    return app('pageconfig');
} */

if (!function_exists('page_config')) {
    function page_config(null|string|array $key = null, mixed $value = null): mixed
    {
        $config = app('pageconfig');

        // page_config() → all
        if (is_null($key)) {
            return $config->all();
        }

        // page_config(['title' => '...', ...]) → merge
        if (is_array($key)) {
            $config->merge($key);
            return $config->all();
        }

        // page_config('key') → get
        if (func_num_args() === 1) {
            return $config->get($key);
        }

        // page_config('key', 'value') → set
        $config->set($key, $value);
        return $value;
    }
}
