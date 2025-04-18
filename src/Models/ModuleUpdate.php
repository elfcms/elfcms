<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleUpdate extends Model
{
    protected $fillable = [
        'module_id',
        'user_id',
        'old_version',
        'new_version',
        'method',
        'success',
        'message',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
