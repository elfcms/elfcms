<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilestorageFiletype extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'mime_prefix',
        'mime_type',
        'group_id'
    ];
}
