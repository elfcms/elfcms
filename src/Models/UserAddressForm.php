<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddressForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'form',
        'full_form',
        'lang_string',
    ];

    protected $strings = [
        ['form' => 'Mr.', 'lang_string' => 'mr'],
        ['form' => 'Mrs.', 'lang_string' => 'mrs'],
        ['form' => 'Ms.', 'lang_string' => 'ms'],
        ['form' => 'Miss.', 'lang_string' => 'miss'],
        ['form' => 'Dr', 'lang_string' => 'dr'],
    ];

    public function start()
    {
        foreach($this->strings as $string) {
            $exists = $this->where('form',$string['form'])->count();
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
