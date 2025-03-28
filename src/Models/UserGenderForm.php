<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGenderForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'short_name',
        'lang_string',
    ];

    protected $strings = [
        ['code' => 'm', 'name' => 'Male', 'short_name' => 'M', 'lang_string' => 'gender_m'],
        ['code' => 'f', 'name' => 'Female', 'short_name' => 'F', 'lang_string' => 'gender_f'],
        ['code' => 'o', 'name' => 'Other', 'short_name' => 'O', 'lang_string' => 'gender_o'],
    ];

    public function start()
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
    }
}
