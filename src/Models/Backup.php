<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = [
        'name',
        'type',
        'status_id',
        'file_path',
        'file_size',
    ];

    protected function fileExists(): Attribute
    {
        return Attribute::make(
            get: fn () => file_exists(storage_path($this->file_path)),
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
        }
        elseif ($type == 'string') {
            $status = BackupStatus::where('name', $status)->first();
            if (!$status) return false;
            $this->status_id = $status->id;
        }
        $this->save();
    }
}
