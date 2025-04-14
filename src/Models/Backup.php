<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class Backup extends Model
{
    protected $fillable = [
        'name',
        'type',
        'status_id',
        'file_path',
        'file_size',
    ];

    protected function isFile(): Attribute
    {
        return Attribute::make(
            get: fn() => file_exists(storage_path(trim($this->file_path))),
        );
    }

    protected function size(): Attribute
    {
        $size = $this->file_size ?? 0;
        if (empty($size) && $this->isFile) {
            $size = filesize(storage_path(trim($this->file_path)));
            $this->file_size = $size;
            $this->save();
        }
        if ($size > 1073741824) {
            $size = number_format(round($size / 1073741824, 2), 2, ',', ' ') . ' GiB';
        } elseif ($size > 1048576) {
            $size = number_format(round($size / 1048576, 2), 2, ',', ' ') . ' MiB';
        } elseif ($size > 1024) {
            $size = number_format(round($size / 1024, 2), 2, ',', ' ') . ' KiB';
        } else {
            $size = number_format($size, 2, ',', ' ') . ' B';
        }
        return Attribute::make(
            get: fn() => $size,
        );
    }

    public function status()
    {
        return $this->belongsTo(BackupStatus::class, 'status_id');
    }

    public function setStatus($status)
    {
        $type = gettype($status);
        if ($type == 'integer' || is_numeric($status)) {
            if (!BackupStatus::find($status)) return false;
            $this->status_id = $status;
        } elseif ($type == 'string') {
            $status = BackupStatus::where('name', $status)->first();
            if (!$status) return false;
            $this->status_id = $status->id;
        }
        $this->save();
    }

    protected static function booted()
    {
        static::deleting(function (Backup $backup) {
            if ($backup->file_path && file_exists(storage_path('app/elfcms/backups/' . $backup->file_path))) {
                unlink(storage_path('app/elfcms/backups/' . $backup->file_path));
            }
        });
    }
}
