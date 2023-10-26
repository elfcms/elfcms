<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'placeholder',
        'value',
        'disabled',
        'checked',
        'position',
        'attributes',
        'type_id',
        'form_id',
        'group_id',
        'active'
    ];

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function group()
    {
        return $this->belongsTo(FormFieldGroup::class, 'group_id');
    }

    public function type()
    {
        return $this->belongsTo(FormFieldType::class, 'type_id');
    }

    public function options()
    {
        return $this->hasMany(FormFieldOption::class, 'field_id');
    }
}
