<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',
        'title',
        'current_version',
        'latest_version',
        'source',
        'update_method',
        'update_available',
        'last_checked_at',
    ];

    protected $casts = [
        'update_available' => 'boolean',
        'last_checked_at' => 'datetime',
    ];

    public function needsUpdate(): bool
    {
        return version_compare($this->latest_version, $this->current_version, '>');
    }

    public function scopeWithUpdates($query)
    {
        return $query->where('update_available', true);
    }

    public function start(array $modules = [])
    {
        if (empty($modules)) {
            $configs = config('elfcms');
            $modules = [];
            if (is_array($configs) && count($configs) > 1) {
                $modules = $configs;
                if (!empty($modules['elfcms'])) unset($modules['elfcms']);
            }
        }
        foreach ($modules as $module => $config) {
            $this->startModule($module, $config);
        }
    }

    public function startModule(string $name, array $config = [])
    {
        if (empty($config)) {
            $config = config("elfcms.$name");
        }
        if (empty($config)) return;
        $exists = $this->where('name', $name)->pluck('id')->first();
        if ($exists) return;
        $this->create([
            'name' => $name,
            'title' => $config['title'] ?? $name,
            'current_version' => $config['version'] ?? '1.0.0',
            'latest_version' => $config['version'] ?? null,
            'source' => $config['source'] ?? null,
            'update_method' => $config['update_method'] ?? 'composer',
            'update_available' => false,
            'last_checked_at' => now(),
        ]);
    }
}
