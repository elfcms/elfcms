<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filestorage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'description',
        'type_id',
        'group_id'
    ];
}
