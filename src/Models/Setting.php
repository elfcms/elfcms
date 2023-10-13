<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends DefaultModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'params',
        'value',
        'description_code',
    ];

    protected $strings = [
        ['code' => 'site_name', 'name' => 'Site name', 'params' => '{}'],
        ['code' => 'site_title', 'name' => 'Site title', 'params' => '{}'],
        ['code' => 'site_logo', 'name' => 'Site logo', 'params' => '{"type": "image"}'],
        ['code' => 'site_icon', 'name' => 'Site icon', 'params' => '{"type": "image"}'],
        ['code' => 'site_slogan', 'name' => 'Site slogan', 'params' => '{}'],
        ['code' => 'site_keywords', 'name' => 'Site keywords', 'params' => '{"type": "text"}'],
        ['code' => 'site_description', 'name' => 'Site description', 'params' => '{"type": "text"}'],
        ['code' => 'site_locale', 'name' => 'Site locale', 'params' => '{"type": "list"}'],
        ['code' => 'site_statistics_use', 'name' => 'Use statistics', 'params' => '{"type": "checkbox"}', 'value' => 0],
    ];

    public static function value($code)
    {
        return self::where('code',$code)->first()->value ?? null;
    }

    public static function get()
    {
        $all = self::all();
        $result = [];
        foreach ($all as $item) {
            $result[$item['code']] = $item;
        }

        return $result;
    }

    public static function values()
    {
        $all = self::all();
        $result = [];
        foreach ($all as $item) {
            $result[str_ireplace('site_','',$item['code'])] = $item->value;
        }

        return $result;
    }


}
