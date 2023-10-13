<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'form_data',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
