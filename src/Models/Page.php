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
        'browser_title',
        'path',
        'image',
        'is_dynamic',
        'meta_keywords',
        'meta_description',
        'content',
        'template',
        'active',
        'module',
        'module_id',
        'module_options',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'meta_description' => 'array',
        'module_options' => 'array',
        'active' => 'boolean',
        'is_dynamic' => 'boolean',
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
