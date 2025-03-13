<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'action',
        'context',
        'ip',
    ];
}
