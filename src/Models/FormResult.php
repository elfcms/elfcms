<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'form_data',
    ];

    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => json_decode($this->form_data, true),
        );
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
