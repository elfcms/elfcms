<?php

namespace Elfcms\Elfcms\Models;

use Elfcms\Elfcms\View\Components\Input\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilestorageFiletype extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'mime_prefix',
        'mime_type',
        'group_id'
    ];

    protected $data = [
        ['name' => 'Any', 'code' => 'any', 'group_code' => 'mixed', 'mime_prefix' => null, 'mime_type' => null],
        ['name' => 'JPEG', 'code' => 'jpeg', 'group_code' => 'image', 'mime_prefix' => 'image', 'mime_type' => 'jpeg'],
        ['name' => 'PNG', 'code' => 'png', 'group_code' => 'image', 'mime_prefix' => 'image', 'mime_type' => 'png'],
        ['name' => 'WebP', 'code' => 'webp', 'group_code' => 'image', 'mime_prefix' => 'image', 'mime_type' => 'webp'],
        ['name' => 'GIF', 'code' => 'gif', 'group_code' => 'image', 'mime_prefix' => 'image', 'mime_type' => 'gif'],
        ['name' => 'BMP', 'code' => 'bmp', 'group_code' => 'image', 'mime_prefix' => 'image', 'mime_type' => 'bmp'],
        ['name' => 'Plain Text', 'code' => 'plain_text', 'group_code' => 'text', 'mime_prefix' => 'text', 'mime_type' => 'plain'],
        ['name' => 'HTML', 'code' => 'html', 'group_code' => 'text', 'mime_prefix' => 'text', 'mime_type' => 'html'],
        ['name' => 'CSS', 'code' => 'css', 'group_code' => 'text', 'mime_prefix' => 'text', 'mime_type' => 'css'],
        ['name' => 'JavaScript', 'code' => 'javascript', 'group_code' => 'text', 'mime_prefix' => 'text', 'mime_type' => 'javascript'],
        ['name' => 'Docx', 'code' => 'docx', 'group_code' => 'document', 'mime_prefix' => 'application', 'mime_type' => 'vnd.openxmlformats-officedocument.wordprocessingml.document'],
        ['name' => 'Xlsx', 'code' => 'xlsx', 'group_code' => 'document', 'mime_prefix' => 'application', 'mime_type' => 'vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        ['name' => 'PDF', 'code' => 'pdf', 'group_code' => 'document', 'mime_prefix' => 'application', 'mime_type' => 'pdf'],
        ['name' => 'ODT', 'code' => 'odt', 'group_code' => 'document', 'mime_prefix' => 'application', 'mime_type' => 'vnd.oasis.opendocument.text'],
        ['name' => 'MP4', 'code' => 'mp4', 'group_code' => 'video', 'mime_prefix' => 'video', 'mime_type' => 'mp4'],
        ['name' => 'AVI', 'code' => 'avi', 'group_code' => 'video', 'mime_prefix' => 'video', 'mime_type' => 'avi'],
        ['name' => 'WEBM', 'code' => 'webm', 'group_code' => 'video', 'mime_prefix' => 'video', 'mime_type' => 'webm'],
        ['name' => 'MP3', 'code' => 'mp3', 'group_code' => 'audio', 'mime_prefix' => 'audio', 'mime_type' => 'mpeg'],
        ['name' => 'WAV', 'code' => 'wav', 'group_code' => 'audio', 'mime_prefix' => 'audio', 'mime_type' => 'wav'],
        ['name' => 'ZIP', 'code' => 'zip', 'group_code' => 'archive', 'mime_prefix' => 'application', 'mime_type' => 'zip'],
        ['name' => 'RAR', 'code' => 'rar', 'group_code' => 'archive', 'mime_prefix' => 'application', 'mime_type' => 'x-rar-compressed'],
        ['name' => '7z', 'code' => '7z', 'group_code' => 'archive', 'mime_prefix' => 'application', 'mime_type' => 'x-7z-compressed'],
        ['name' => 'TAR', 'code' => 'tar', 'group_code' => 'archive', 'mime_prefix' => 'application', 'mime_type' => 'x-tar'],
    ];

    public function data()
    {
        return $this->data;
    }

    public function start()
    {
        $result = [];
        foreach ($this->data as $item) {
            if (!(bool)self::where('code', $item['code'])->first()) {
                $item['group_id'] = FilestorageFilegroup::where('code', $item['group_code'])?->pluck('id')?->first() ?? null;
                unset($item['group_code']);
                $result[] = $this->create($item);
            }
        }
        return $result;
    }

    public function group()
    {
        return $this->belongsTo(FilestorageFilegroup::class, 'group_id');
    }

    public function storages()
    {
        return $this->belongsToMany(Filestorage::class, 'filestorage_storage_groups', 'type_id', 'storage_id');
    }

    public function files()
    {
        return $this->hasMany(FilestorageFile::class, 'type_id');
    }
}
