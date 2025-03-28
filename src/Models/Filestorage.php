<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filestorage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'description',
        'type_id',
        'group_id'
    ];

    /* public function type()
    {
        return $this->belongsTo(FilestorageFiletype::class, 'type_id');
    } */

    public function types()
    {
        return $this->belongsToMany(FilestorageFiletype::class, 'filestorage_storage_groups', 'storage_id', 'type_id');
    }

    public function group()
    {
        return $this->belongsTo(FilestorageFilegroup::class, 'group_id');
    }

    public function files()
    {
        return $this->hasMany(FilestorageFile::class, 'storage_id');
    }

    public function scopeGroup($query, $group)
    {
        return $query->where('group_id', $group);
    }
}
