<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'theme',
        'text',
        'date_from',
        'date_to',
        'is_popup',
        'close_remember',
        'active',
    ];
}
