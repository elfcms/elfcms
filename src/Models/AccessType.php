<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'lang_name',
        'description',
        'lang_description',
    ];

    static protected $strings = [
        ['code' => 'r', 'name' => 'Read', 'lang_name' => 'access_read', 'description' => 'Access for reading only', 'lang_description' => 'access_read_description'],
        ['code' => 'w', 'name' => 'Write', 'lang_name' => 'access_write', 'description' => 'Access for reading and writing', 'lang_description' => 'access_write_description'],
        ['code' => 'd', 'name' => 'Denied', 'lang_name' => 'access_denied', 'description' => 'Access denied', 'lang_description' => 'access_denied_description'],
    ];

    public function start()
    {
        foreach(self::$strings as $string) {
            $exists = $this->where('code',$string['code'])->count();
            if ($exists && $exists > 0) {
                continue;
            }

            $newString = $this->create($string);

            if (!$newString) {
                return false;
            }
        }
    }

    static public function defaults() {
        return self::$strings;
    }
}
