<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormFieldGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'position',
        'form_id',
        'title',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function fields()
    {
        return $this->hasMany(FormField::class, 'group_id')->orderBy('position');
    }

}
