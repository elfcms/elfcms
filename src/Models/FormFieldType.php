<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormFieldType extends DefaultModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $strings = [
        ['name' => 'text', 'description' => ''],
        ['name' => 'password', 'description' => ''],
        ['name' => 'radio', 'description' => ''],
        ['name' => 'checkbox', 'description' => ''],
        ['name' => 'email', 'description' => ''],
        ['name' => 'file', 'description' => ''],
        ['name' => 'hidden', 'description' => ''],
        ['name' => 'button', 'description' => ''],
        ['name' => 'textarea', 'description' => ''],
        ['name' => 'select', 'description' => ''],
        ['name' => 'number', 'description' => ''],
        ['name' => 'range', 'description' => ''],
        ['name' => 'reset', 'description' => ''],
        ['name' => 'search', 'description' => ''],
        ['name' => 'submit', 'description' => ''],
        ['name' => 'image', 'description' => ''],
        ['name' => 'tel', 'description' => ''],
        ['name' => 'date', 'description' => ''],
        ['name' => 'month', 'description' => ''],
        ['name' => 'week', 'description' => ''],
        ['name' => 'time', 'description' => ''],
        ['name' => 'datetime', 'description' => ''],
        ['name' => 'datetime-local', 'description' => ''],
        ['name' => 'url', 'description' => ''],
        ['name' => 'color', 'description' => ''],
    ];

    /* public function start()
    {
        foreach($this->strings as $string) {
            $exists = $this->where('name',$string['name'])->count();
            if ($exists && $exists > 0) {
                continue;
            }

            $newString = $this->create($string);

            if (!$newString) {
                return false;
            }
        }
    } */

    public function start($field = 'name')
    {
        return parent::start('name');
    }
}
