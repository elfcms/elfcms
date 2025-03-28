<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieSetting extends DefaultModel
{
    use HasFactory;

    protected $fillable = [
        'active',
        'use_default_text',
        'text',
        'privacy_path',
        'cookie_lifetime',
        'ignored_paths'
    ];

    protected $string = [
        'active' => 1,
        'use_default_text' => 1,
        'text' => null,
        'privacy_path' => null,
        'cookie_lifetime' => null,
        'ignored_paths' => null
    ];

    public function start($field = 'active')
    {
        $exists = $this->count();
        if ($exists && $exists > 0) {
            return false;
        }

        $newString = $this->create($this->string);

        if (!$newString) {
            return false;
        }

        return true;
    }
}
