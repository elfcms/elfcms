<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilestorageFilegroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'mime_prefix'
    ];

    protected $data = [
        ['name' => 'Mixed', 'code' => 'mixed', 'mime_prefix' => null],
        ['name' => 'Bilder', 'code' => 'image', 'mime_prefix' => 'image'],
        ['name' => 'Text', 'code' => 'text', 'mime_prefix' => 'text'],
        ['name' => 'Dokument', 'code' => 'document', 'mime_prefix' => 'application'],
        ['name' => 'Video', 'code' => 'video', 'mime_prefix' => 'video'],
        ['name' => 'Audio', 'code' => 'audio', 'mime_prefix' => 'audio'],
        ['name' => 'Archiv', 'code' => 'archive', 'mime_prefix' => 'application'],
    ];

    public function data()
    {
        return $this->data;
    }

    public function start() {
        $result = [];
        foreach ($this->data as $item) {
            if (!(bool)self::where('code',$item['code'])->first()) $result[] = $this->create($item);
        }
        return $result;
    }

    public function types()
    {
        return $this->hasMany(FilestorageFiletype::class, 'group_id');
    }

    public function files()
    {
        return $this->hasMany(FilestorageFile::class, 'group_id');
    }

    public function storages()
    {
        return $this->hasMany(Filestorage::class, 'group_id');
    }

}
