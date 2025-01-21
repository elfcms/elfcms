<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilestorageFile extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'path',
        'extension',
        'download_name',
        'alt_text',
        'link_title',
        'link',
        'mimetype',
        'size',
        'width',
        'height',
        'length',
        'bitrate',
        'fps',
        'quality',
        'description',
        'storage_id',
        'type_id',
        'group_id'
    ];

    protected $table = 'filestorage_files';

    protected $primaryKey = 'id';

    public $incrementing = false;
}
