<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Model;

class BackupStatus extends Model
{
    protected $fillable = [
        'name',
        'description',
        'success',
        'lang_title',
        'lang_description',
    ];

    protected static $strings = [
        [
            'name' => 'success',
            'description' => 'Backup was successful',
            'success' => true,
            'lang_title' => 'elfcms::default.success',
            'lang_description' => 'elfcms::default.success_description',
        ],
        [
            'name' => 'failed',
            'description' => 'Backup failed',
            'success' => false,
            'lang_title' => 'elfcms::default.failed',
            'lang_description' => 'elfcms::default.failed_description',
        ],
        [
            'name' => 'pending',
            'description' => 'Backup is pending',
            'success' => null,
            'lang_title' => 'elfcms::default.pending',
            'lang_description' => 'elfcms::default.pending_description',
        ],
        [
            'name' => 'progress',
            'description' => 'Backup in progress',
            'success' => null,
            'lang_title' => 'elfcms::default.progress',
            'lang_description' => 'elfcms::default.progress_description',
        ],
    ];

    public function start()
    {
        foreach(self::$strings as $string) {
            $exists = $this->where('name',$string['name'])->count();
            if ($exists && $exists > 0) {
                continue;
            }

            $newString = $this->create($string);

            if (!$newString) {
                return false;
            }
        }
    }
}
