<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilestorageFile extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'id',
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
        'group_id',
        'active',
        'position',
    ];

    protected $table = 'filestorage_files';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function storage()
    {
        return $this->belongsTo(Filestorage::class, 'storage_id');
    }

    public function type()
    {
        return $this->belongsTo(FilestorageFiletype::class, 'type_id');
    }

    public function group()
    {
        return $this->belongsTo(FilestorageFilegroup::class, 'group_id');
    }

    protected function fullPath(): Attribute
    {
        $path = '/' . trim(config('filesystems.disks.filestorage.root'),'/') . '/' . $this->path;
        return Attribute::make(
            get: fn() => $path,
        );
    }

    protected function publicPath(): Attribute
    {
        $path = '/' . trim(config('elfcms.elfcms.file_path'),'/') . '/' . $this->path;
        return Attribute::make(
            get: fn() => $path,
        );
    }
}
