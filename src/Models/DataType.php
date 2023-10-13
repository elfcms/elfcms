<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataType extends DefaultModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'lang_name',
    ];

    protected $fields = [
        'bool' => ['checkbox'],
        'int' => ['number','text'],
        'float' => ['text'],
        'string' => ['text'],
        'text' => ['textarea'],
        'date' => ['date','text'],
        'time' => ['time','text'],
        'datetime' => ['datetime','text'],
        'json' => ['text','textarea'],
        'list' => ['select','radio','checkbox'],
        'file' => ['file'],
        'image' => ['file','text','textarea'],
        'color' => ['color','text'],
    ];

    protected $strings = [
        ['code' => 'bool', 'name' => 'Boolean', 'lang_name' => 'boolean'],
        ['code' => 'int', 'name' => 'Integer', 'lang_name' => 'integer'],
        ['code' => 'float', 'name' => 'Float', 'lang_name' => 'float'],
        ['code' => 'string', 'name' => 'String', 'lang_name' => 'string'],
        ['code' => 'text', 'name' => 'Text', 'lang_name' => 'text'],
        ['code' => 'date', 'name' => 'Date', 'lang_name' => 'date'],
        ['code' => 'time', 'name' => 'Time', 'lang_name' => 'time'],
        ['code' => 'datetime', 'name' => 'Datetime', 'lang_name' => 'datetime'],
        ['code' => 'json', 'name' => 'JSON', 'lang_name' => 'json'],
        ['code' => 'list', 'name' => 'List', 'lang_name' => 'list'],
        ['code' => 'file', 'name' => 'File', 'lang_name' => 'file'],
        ['code' => 'image', 'name' => 'Image', 'lang_name' => 'image'],
        ['code' => 'color', 'name' => 'Color', 'lang_name' => 'color'],
    ];

    /* public function start()
    {
        foreach($this->strings as $string) {
            $exists = $this->where('code',$string['code'])->count();
            if ($exists && $exists > 0) {
                continue;
            }

            $newString = $this->create($string);

            if (!$newString) {
                return false;
            }
        }
    } */

    public function getFieldAttribute()
    {
        return $this->fields[$this->code];
    }
}
