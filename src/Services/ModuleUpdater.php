<?php

namespace Elfcms\Elfcms\Services;

use Elfcms\Elfcms\Models\Module;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ModuleUpdater
{
    public function checkAll(): void
    {
        $modules = Module::all();

        foreach ($modules as $module) {
            try {
                $this->check($module);
            } catch (\Throwable $e) {
                Log::error(__('elfcms::default.error_checking_for_module_updates',['module'=>$module->name,'error'=>$e->getMessage()]));
            }
        }
    }

    public function check(Module $module): void
    {
        if (!$module->source || !Str::contains($module->source, 'github.com')) {
            return; // GitHub
        }

        $repo = $this->extractRepo($module->source);
        if (!$repo) {
            return;
        }

        $url = "https://api.github.com/repos/{$repo}/releases/latest";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.v3+json',
            // 'Authorization' => 'token ' . config('services.github.token'),
        ])->get($url);

        if ($response->failed()) {
            throw new \Exception("GitHub API error ({$response->status()}): " . $response->body());
        }

        $latest = $response->json('tag_name');

        $module->latest_version = $latest;
        $module->update_available = version_compare($latest, $module->current_version, '>');
        $module->last_checked_at = now();
        $module->save();
    }

    protected function extractRepo(string $url): ?string
    {
        // Example: https://github.com/elfcms/blog → elfcms/blog
        if (preg_match('#github\.com/([^/]+/[^/]+)#', $url, $m)) {
            return $m[1];
        }
        return null;
    }
}
