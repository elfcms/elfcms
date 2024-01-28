<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'title',
        'action',
        'method',
        'enctype',
        'email',
        'redirect_to',
        'success_text',
        'error_text',
        'submit_button',
        'submit_name',
        'submit_title',
        'submit_value',
        'reset_button',
        'reset_title',
        'reset_value',
        'additional_buttons',
        'event_id',
        'active'
    ];

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function fields()
    {
        return $this->hasMany(FormField::class, 'form_id')->orderBy('position','asc');
    }

    public function allfields()
    {
        return $this->hasMany(FormField::class, 'form_id');
    }

    public function fieldsWithoutGroup()
    {
        return $this->hasMany(FormField::class, 'form_id')->where('group_id',null)->orderBy('position','asc');
    }

    public function groups()
    {
        return $this->hasMany(FormFieldGroup::class, 'form_id')->orderByRaw('position ASC');
    }
}
