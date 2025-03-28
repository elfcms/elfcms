<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'permission',
        'route',
        'read',
        'write'
    ];
}
