<?php
namespace Elfcms\Elfcms\Traits\Models;

use Elfcms\Elfcms\Models\User;

trait LogEntity
{    
    protected $fillable = [
        'entity_model',
        'entity_id',
        'user_id',
        'user_id_stat',
        'entity_data',
        'comment'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function entity()
    {
        return $this->morphTo(__FUNCTION__, 'entity_model', 'entity_id');
    }
}