<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;
use Elfcms\Elfcms\Models\Module;
use Elfcms\Elfcms\Models\ModuleUpdate;
use Elfcms\Elfcms\Services\ModuleUpdater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SystemController extends Controller
{
    public function index(Request $request)
    {
        $configs = config('elfcms');
        $modules = [];
        if (is_array($configs) && count($configs) > 1) {
            $modules = $configs;
            if (!empty($modules['elfcms'])) unset($modules['elfcms']);
        }
        $data = $configs['elfcms'];

        $availableModules = [];
        $response = Http::timeout(5)->get('https://raw.githubusercontent.com/elfcms/modules-list/main/modules.json');
        if ($response->successful()) {
            $body = $response->body();
            $availableModules = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON in modules.json: ' . json_last_error_msg());
                $availableModules = [];
            }
            $availableModules = $response->json();
        } else {
            Log::warning('Could not fetch modules.json: ' . $response->status());
        }

        $modulesToInstall = [];

        if (empty($modules) && !empty($availableModules) && !empty($availableModules['modules'])) {
            $modulesToInstall = $availableModules['modules'];
        } elseif (!empty($modules) && !empty($availableModules) && !empty($availableModules['modules'])) {
            foreach ($availableModules['modules'] as $availableModule) {
                //if ($availableModule['version'] == 'dev') continue;
                $isset = 0;
                foreach ($modules as $module) {
                    if ($module['github'] == $availableModule['repo']) $isset++;
                }
                if ($isset === 0) {
                    $modulesToInstall[] = $availableModule;
                }
            }
        }

        return view('elfcms::admin.system.index', [
            'page' => [
                'title' => __('elfcms::default.system'),
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'data' => $data,
            'modules' => $modules,
            'modulesToInstall' => $modulesToInstall,
        ]);
    }

    public function updates()
    {
        $updater = new ModuleUpdater();
        $updater->checkAll();
        $modules = Module::withUpdates()->get();
        return view('elfcms::admin.system.updates.index', [
            'page' => [
                'title' => __('elfcms::default.updates'),
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'modules' => $modules,
        ]);
    }

    public function checkUpdates(Request $request, ModuleUpdater $updater)
    {
        try {
            $updater->checkAll();
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => __('elfcms::default.update_check_completed_successfully')]);
            }
            return back()->with('success', __('elfcms::default.update_check_completed_successfully'));
        } catch (\Throwable $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => __('elfcms::default.error_checking_for_updates', ['error' => $e->getMessage()])]);
            }
            return back()->with('error', __('elfcms::default.error_checking_for_updates', ['error' => $e->getMessage()]));
        }
    }

    public function updateAll(Request $request)
    {
        $errors = [];
        $success = [];
        if (!empty($request->modules)) {
            foreach ($request->modules as $module) {
                $moduleResult = $this->update($module, $request);
                if (isset($moduleResult['success'])) {
                    if (isset($moduleResult['success']) && $moduleResult['success'] === false) {
                        $errors[] = $moduleResult['message'] ?? null;
                    } else {
                        $success[] = $moduleResult['message'] ?? null;
                    }
                }
            }
        }
        if (!empty($success)) {
            return back()->with('success', implode('<br>', $success));
        }
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
    }

    public function update(string $moduleName, Request $request)
    {
        $module = Module::where('name', $moduleName)->firstOrFail();
        $oldVersion = $module->current_version;

        try {
            if ($module->update_method === 'composer') {
                $this->updateViaComposer($module);
            } elseif ($module->update_method === 'zip') {
                $this->updateViaZip($module);
            } else {
                throw new \Exception(__('elfcms::default.update_method_not_defined'));
            }

            $module->current_version = $module->latest_version;
            $module->update_available = false;
            $module->save();

            ModuleUpdate::create([
                'module_id' => $module->id,
                'user_id' => Auth::id(),
                'old_version' => $oldVersion,
                'new_version' => $module->latest_version,
                'method' => $module->update_method,
                'success' => true,
                'message' => __('elfcms::default.updated_successfully'),
            ]);

            $result = ['success' => true, 'message' => __('elfcms::default.module_successfully_updated', ['module' => $module->title])];
            if ($request->ajax()) {
                return response()->json($result);
            }
            return $result;
        } catch (\Throwable $e) {
            ModuleUpdate::create([
                'module_id' => $module->id,
                'user_id' => Auth::id(),
                'old_version' => $oldVersion,
                'new_version' => $module->latest_version ?? __('elfcms::default.unknown'),
                'method' => $module->update_method,
                'success' => false,
                'message' => $e->getMessage(),
            ]);

            $result = ['success' => false, 'message' => __('elfcms::default.update_error_text', ['error' => $e->getMessage()])];
            if ($request->ajax()) {
                return response()->json($result);
            }
            return $result;
        }
    }

    protected function updateViaComposer(Module $module): void
    {
        $package = 'elfcms/' . $module->name;
        if (!is_dir(base_path('.composer'))) {
            Storage::makeDirectory(base_path('.composer'));
        }
        $env = ['COMPOSER_HOME' => base_path('.composer')];

        $process = new Process(['composer', 'update', $package], base_path(), $env);
        $process->setTimeout(300); // 5 minute
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }

        Process::fromShellCommandline('php artisan optimize:clear', base_path(), $env)->run();
        Process::fromShellCommandline('php artisan migrate --force', base_path(), $env)->run();
    }

    protected function updateViaZip(Module $module): void
    {
        $url = $this->buildZipUrl($module->source, $module->latest_version);
        $tempZip = storage_path("app/tmp/{$module->name}.zip");
        $tempDir = storage_path("app/tmp/{$module->name}");

        // zip download
        File::ensureDirectoryExists(dirname($tempZip));
        file_put_contents($tempZip, file_get_contents($url));

        // unzip
        $zip = new \ZipArchive();
        if ($zip->open($tempZip) === true) {
            $zip->extractTo($tempDir);
            $zip->close();
        } else {
            throw new \Exception(__('elfcms::default.failed_to_unzip_zip_file'));
        }

        $subdirs = File::directories($tempDir);
        if (empty($subdirs)) {
            throw new \Exception(__('elfcms::default.zip_file_does_not_contain_module_folder'));
        }
        $newModulePath = $subdirs[0];

        // rewrite module
        $targetPath = base_path("vendor/{$module->package}");
        if (File::exists($targetPath)) {
            File::deleteDirectory($targetPath);
        }
        File::copyDirectory($newModulePath, $targetPath);

        Process::fromShellCommandline('php artisan optimize:clear')->run();
        Process::fromShellCommandline('php artisan migrate --force')->run();

        // Remove temp
        File::delete($tempZip);
        File::deleteDirectory($tempDir);
    }

    protected function buildZipUrl(string $source, string $version): string
    {
        if (Str::contains($source, 'github.com')) {
            // Example: https://github.com/elfcms/infobox → https://github.com/elfcms/infobox/archive/refs/tags/1.1.0.zip
            if (preg_match('#github\.com/([^/]+/[^/]+)#', $source, $m)) {
                return "https://github.com/{$m[1]}/archive/refs/tags/{$version}.zip";
            }
        }

        return $source; // full link
    }

    protected function getLatestTag(string $repo)
    {
        $url = "https://api.github.com/repos/{$repo}/releases/latest";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.v3+json',
            // 'Authorization' => 'token ' . config('services.github.token'),
        ])->get($url);

        if ($response->failed()) {
            throw new \Exception("GitHub API error ({$response->status()}): " . $response->body());
        }

        return $response->json('tag_name');
    }

    public function isComposerAvailable(): bool
    {
        try {
            $process = new Process(['composer', '--version']);
            $process->setTimeout(10)->run();

            return $process->isSuccessful();
        } catch (\Throwable $e) {
            return false;
        }
    }

    // Installing
    public function installViaComposer(string $package): void
    {
        $env = ['COMPOSER_HOME' => base_path('.composer')];
        $process = new Process(['composer', 'require', $package], base_path(), $env);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception("Composer install failed: " . $process->getErrorOutput());
        }

        // Можно оптимизировать Laravel
        Process::fromShellCommandline('php artisan optimize:clear', base_path(), $env)->run();
        Process::fromShellCommandline('php artisan migrate --force', base_path(), $env)->run();
        try {
            Process::fromShellCommandline('php artisan elfcms:publish ' . $package, base_path(), $env)->run();
        }
        catch (\Throwable $e) {
            //
        }
    }

    public function installFromZip(string $url, array $module): void
    {
        $tempZip = storage_path("app/tmp/{$module['name']}.zip");
        $tempDir = storage_path("app/tmp/{$module['name']}");

        // Download ZIP
        File::ensureDirectoryExists(dirname($tempZip));
        file_put_contents($tempZip, file_get_contents($url));

        // Unzip
        $zip = new \ZipArchive();
        if ($zip->open($tempZip) === true) {
            $zip->extractTo($tempDir);
            $zip->close();
        } else {
            throw new \Exception(__('elfcms::default.failed_unzip'));
        }

        // Detect module folder
        $subdirs = File::directories($tempDir);
        if (empty($subdirs)) {
            throw new \Exception(__('elfcms::default.zip_file_does_not_contain_module_folder'));
        }
        $moduleSourcePath = $subdirs[0];

        // Copy to modules directory
        $targetPath = base_path("vendor/{$module['package']}");
        if (File::exists($targetPath)) {
            File::deleteDirectory($targetPath);
        }
        File::copyDirectory($moduleSourcePath, $targetPath);

        $meta = $this->extractModuleMeta($targetPath);
        if (!empty($meta['namespace']) && !empty($meta['psr4_path'])) {
            $this->registerPsr4Autoload($meta['namespace'], $meta['psr4_path']);
        }

        // Run Laravel optimizations
        Process::fromShellCommandline('php artisan optimize:clear')->run();
        Process::fromShellCommandline('php artisan migrate --force')->run();
        try {
            Process::fromShellCommandline('php artisan elfcms:publish ' . $module['name'])->run();
        }
        catch (\Throwable $e) {
            //
        }

        // Clean up
        File::delete($tempZip);
        File::deleteDirectory($tempDir);
    }

    protected function extractModuleMeta(string $modulePath): array
    {
        $composerPath = $modulePath . '/composer.json';
        if (!File::exists($composerPath)) {
            throw new \Exception("Module composer.json not found");
        }

        $composer = json_decode(file_get_contents($composerPath), true);
        $psr4 = $composer['autoload']['psr-4'] ?? null;

        if (!$psr4 || count($psr4) === 0) {
            throw new \Exception("No PSR-4 autoload section found in module");
        }

        $namespace = array_key_first($psr4);
        $path = rtrim('modules/' . basename($modulePath) . '/' . trim($psr4[$namespace], '/'), '/') . '/';

        return [
            'namespace' => $namespace,
            'psr4_path' => $path
        ];
    }

    protected function registerPsr4Autoload(string $namespace, string $path): void
    {
        $composerJsonPath = base_path('composer.json');
        $composer = json_decode(file_get_contents($composerJsonPath), true);

        $autoload = $composer['autoload']['psr-4'] ?? [];

        if (!isset($autoload[$namespace])) {
            $composer['autoload']['psr-4'][$namespace] = $path;
            file_put_contents($composerJsonPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            Process::fromShellCommandline('composer dump-autoload')->run();
        }
    }

    public function installModule(array $module, Request $request)
    {
        try {
            if (!empty($module['composer_package']) && $this->isComposerAvailable()) {
                try {
                    $this->installViaComposer($module['composer_package']);
                    return;
                } catch (\Throwable $e) {
                    Log::warning(__('elfcms::default.composer_install_failed_for', ['module' => $module['composer_package']]) . ": " . $e->getMessage());
                }
            }

            $zipUrl = $module['zip_url'] ?? null;

            $version = $this->getLatestTag($module['package']);
            if (empty($zipUrl) && !empty($version)) {
                $zipUrl = "https://github.com/{$module['package']}/archive/refs/tags/{$version}.zip";
            }

            if (!empty($zipUrl)) {
                $this->installFromZip($zipUrl, $module);
            } else {
                throw new \Exception(__('elfcms::default.no_valid_installation_method_available_for_module', ['module' => $module['title']]));
            }

            $newModule = Module::create([
                'name' => $module['name'],
                'title' => $module['title'],
                'current_version' => $version,
                'latest_version' => $version,
                'source' => $module['repo'],
                'update_method' => $module['install_via'],
                'update_available' => 0,
                'last_checked_at' => now(),
            ]);

            $result = ['success' => true, 'message' => __('elfcms::default.module_has_been_installed_successfully', ['module' => $module['title']])];
            if ($request->ajax()) {
                return response()->json($result);
            }
            return $result;
        } catch (\Throwable $e) {
            $result = ['success' => false, 'message' => __('elfcms::default.update_error_text', ['error' => $e->getMessage()])];
            if ($request->ajax()) {
                return response()->json($result);
            }
            return $result;
        }
    }

    public function install(Request $request, string $moduleName)
    {
        $availableModules = [];
        $response = Http::timeout(5)->get('https://raw.githubusercontent.com/elfcms/modules-list/main/modules.json');
        if ($response->successful()) {
            $body = $response->body();
            $availableModules = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON in modules.json: ' . json_last_error_msg());
                $availableModules = [];
            }
            $availableModules = $response->json();
        } else {
            Log::warning('Could not fetch modules.json: ' . $response->status());
            return back()->withErrors(['error'=>__('elfcms::default.error_installing_module',['module'=>$moduleName]) . ': Could not fetch modules.json: ' . $response->status()]);
        }
        $module = null;
        if (!empty($availableModules) && !empty($availableModules['modules'])) {
            foreach($availableModules['modules'] as $moduleData) {
                if (strtolower($moduleData['name']) == strtolower($moduleName)) {
                    $module = $moduleData;
                }
            }
        }
        if (empty($module)) {
            return back()->withErrors(['error'=>__('elfcms::default.error_installing_module',['module'=>$moduleName]) . ': ' . __('elfcms::default.module_not_found')]);
        }
        $result = $this->installModule($module, $request);
        if (!$result['success']) {
            return back()->withErrors(['error'=>$result['message']]);
        }
        return back()->with('success',$result['message']);
    }
}
