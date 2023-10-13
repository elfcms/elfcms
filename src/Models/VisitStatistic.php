<?php

namespace Elfcms\Elfcms\Models;

use Elfcms\Elfcms\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitStatistic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tmp_user_uuid',
        'uri',
        'ip',
        'agent',
        'referer',
        'platform',
        'mobile',
        'browser_full',
        'browser',
        'method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
