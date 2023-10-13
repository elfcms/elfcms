<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormFieldOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'text',
        'selected',
        'disabled',
        'field_id',
    ];

    public function field()
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }
}
