<?php

namespace Elfcms\Elfcms\Models;

use Elfcms\Elfcms\Aux\TextPrepare;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'title',
        'content',
        'meta_keywords',
        'meta_description',
        'is_dynamic',
        'path',
        'image',
    ];

    /**
     * Prepare content with components
     *
     * @return string
     */
    protected function getHtmlAttribute()
    {
        return TextPrepare::components($this->content);
    }
}
