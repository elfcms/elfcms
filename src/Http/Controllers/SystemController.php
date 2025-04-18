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
        return view('elfcms::admin.system.index', [
            'page' => [
                'title' => __('elfcms::default.system'),
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'data' => $data,
            'modules' => $modules,
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
                    }
                    else {
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
                'message' => 'Успешно обновлено',
            ]);

            //return back()->with('success', __('elfcms::default.module_successfully_updated',['module'=>$module->title]));
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

            //return back()->with('error', __('elfcms::default.update_error_text',['error'=>$e->getMessage()]));
            $result = ['success' => false, 'message' => __('elfcms::default.update_error_text', ['error' => $e->getMessage()])];
            if ($request->ajax()) {
                return response()->json($result);
            }
            return $result;
        }
    }

    protected function updateViaComposer(Module $module): void
    {
        // Например: elfcms/blog → composer update elfcms/blog
        $package = 'elfcms/' . $module->name;

        $process = new Process(['composer', 'update', $package]);
        $process->setTimeout(300); // 5 минут
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception($process->getErrorOutput());
        }

        // Очистка кэшей (на всякий случай)
        Process::fromShellCommandline('php artisan optimize:clear')->run();
        Process::fromShellCommandline('php artisan migrate --force')->run();
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
        $targetPath = base_path("modules/{$module->name}");
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
}
