<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file',
    ];

    public static function set($file, $name = null)
    {
        if (empty($name)) {
            $name = basename($file);
        }

        self::create(['name'=>$name,'file'=>$file]);
    }

    public static function file($name)
    {
        return self::where('name',$name)->first()->file ?? null;
    }

    public static function name($file)
    {
        return self::where('file',$file)->first()->name ?? null;
    }
}
