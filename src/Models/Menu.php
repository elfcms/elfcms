<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',

    ];

    public function items($orderBy = 'position', $trend = 'asc')
    {
        $trend = strtolower($trend);
        if ($trend == 'desc') {
            return $this->hasMany(MenuItem::class)->orderByDesc($orderBy);
        }
        return $this->hasMany(MenuItem::class)->orderBy($orderBy);
    }
}
