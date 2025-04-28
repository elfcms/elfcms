<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;
use Elfcms\Elfcms\Models\Module;
use Elfcms\Elfcms\Models\ModuleUpdate;
use Elfcms\Elfcms\Services\ComposerService;
use Elfcms\Elfcms\Services\ModuleUpdater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SystemController extends Controller
{
    public function index(Request $request)
    {
        if (Session::has('pending_module_installation')) {
            $moduleName = Session::pull('pending_module_installation');
            if (!empty($moduleName)) {
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('elfcms:publish', ['module' => $moduleName]);
                $success = __('elfcms::default.module_name_has_been_installed_successfully', ['module' => $moduleName]);
                if (Session::has('success')) {
                    $success = Session::get('success');
                }
                return redirect(route('admin.system.index'))->with('success', $success);
            }
        }

        $configs = config('elfcms');
        $modules = [];
        if (is_array($configs) && count($configs) > 1) {
            $modules = $configs;
            if (!empty($modules['elfcms'])) unset($modules['elfcms']);
        }
        $data = $configs['elfcms'];

        $availableModules = [];
        try {
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
        } catch (\Throwable $e) {
            Session::put('warning',__('elfcms::default.error_loading_modules_information_missing'));
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
        if (Session::has('pending_module_update')) {
            $moduleName = Session::pull('pending_module_update');
            if (!empty($moduleName)) {
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('elfcms:publish', ['module' => $moduleName]);
                $success = __('elfcms::default.updated_successfully');
                if (Session::has('success')) {
                    $success = Session::get('success');
                }
                return redirect(route('admin.system.updates'))->with('success', $success);
            }
        }
        if (Session::has('pending_modules_update')) {
            $moduleNames = Session::pull('pending_modules_update');
            if (!empty($moduleNames)) $moduleNames = explode(',', $moduleNames);
            if (!empty($moduleNames)) {
                Artisan::call('migrate', ['--force' => true]);
                foreach ($moduleNames as $moduleName) {
                    if (!empty($moduleName)) {
                        Artisan::call('elfcms:publish', ['module' => $moduleName]);
                    }
                }
                $success = __('elfcms::default.updated_successfully');
                if (Session::has('success')) {
                    $success = Session::get('success');
                }
                return redirect(route('admin.system.updates'))->with('success', $success);
            }
        }
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

    public function install(string $moduleName)
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
            return back()->withErrors(['error' => __('elfcms::default.error_installing_module', ['module' => $moduleName]) . ': Could not fetch modules.json: ' . $response->status()]);
        }
        $module = null;
        if (!empty($availableModules) && !empty($availableModules['modules'])) {
            foreach ($availableModules['modules'] as $moduleData) {
                if (strtolower($moduleData['name']) == strtolower($moduleName)) {
                    $module = $moduleData;
                }
            }
        }

        if (empty($module)) {
            return back()->withErrors(['error' => __('elfcms::default.error_installing_module', ['module' => $moduleName]) . ': ' . __('elfcms::default.module_not_found')]);
        }

        $composer = new ComposerService;
        $result = $composer->require($module['package']);

        if (!$result->success()) {
            Log::warning($result->error());
            return back()->withErrors(['error' => __('elfcms::default.error_installing_module', ['module' => $module['package']]) . ': ' . $result->error()]);
        }

        $composer->dumpAutoload();

        $version = $this->getLatestTag($module['package']) ?? null;
        try {
            $newModule = Module::create([
                'name' => $module['name'],
                'title' => $module['title'],
                'current_version' => $version ?? __('elfcms::default.unknown'),
                'latest_version' => $version,
                'source' => $module['repo'],
                'update_method' => $module['install_via'],
                'update_available' => 0,
                'last_checked_at' => now(),
            ]);
        } catch (\Throwable $e) {
            //
        }

        return back()->with('success', __('elfcms::default.module_name_has_been_installed_successfully', ['module' => $moduleName]))->with('pending_module_installation', $moduleName);
    }

    public function updateAll(Request $request)
    {
        $errors = [];
        $success = [];
        $composer = new ComposerService;
        $names = [];

        if (!empty($request->modules)) {
            foreach ($request->modules as $moduleName) {
                $module = Module::where('name', $moduleName)->first();
                if (empty($module) || empty($module->id)) {
                    $errors[] = __('elfcms::default.module_name_not_found', ['module' => $moduleName]);
                    continue;
                }
                $oldVersion = $module->current_version;
                $result = $composer->require($module->package);
                if (!$result->success()) {
                    ModuleUpdate::create([
                        'module_id' => $module->id,
                        'user_id' => Auth::id(),
                        'old_version' => $oldVersion,
                        'new_version' => $module->latest_version ?? __('elfcms::default.unknown'),
                        'method' => $module->update_method ?? 'composer',
                        'success' => false,
                        'message' => $result->error(),
                    ]);
                    $errors[] = __('elfcms::default.update_error_text', ['error' => $result->error()]);
                }
                ModuleUpdate::create([
                    'module_id' => $module->id,
                    'user_id' => Auth::id() ?? null,
                    'old_version' => $oldVersion,
                    'new_version' => $module->latest_version,
                    'method' => $module->update_method ?? 'composer',
                    'success' => true,
                    'message' => __('elfcms::default.updated_successfully'),
                ]);
                $success[] = __('elfcms::default.updated_successfully') . ': ' . $module->name;
                $names[] = $module->name;
            }
        }

        $redirect = back();
        if (!empty($success)) {
            $composer->dumpAutoload();
            $redirect->with('success', implode('<br>', $success));
            if (!empty($names)) {
                $redirect->with('pending_modules_update', implode(',', $names));
            }
        }
        if (!empty($errors)) {
            $redirect->withErrors($errors);
        }
        return $redirect;
    }

    public function update(Module $module)
    {
        $oldVersion = $module->current_version;

        $composer = new ComposerService;
        $result = $composer->require($module['package']);
        if (!$result->success()) {
            ModuleUpdate::create([
                'module_id' => $module->id,
                'user_id' => Auth::id(),
                'old_version' => $oldVersion,
                'new_version' => $module->latest_version ?? __('elfcms::default.unknown'),
                'method' => $module->update_method ?? 'composer',
                'success' => false,
                'message' => $result->error(),
            ]);
            return back()->withErrors(['error' => __('elfcms::default.update_error_text', ['error' => $result->error()])]);
        }
        $composer->dumpAutoload();
        ModuleUpdate::create([
            'module_id' => $module->id,
            'user_id' => Auth::id() ?? null,
            'old_version' => $oldVersion,
            'new_version' => $module->latest_version,
            'method' => $module->update_method ?? 'composer',
            'success' => true,
            'message' => __('elfcms::default.updated_successfully'),
        ]);
        return back()->with('success', __('elfcms::default.updated_successfully'))->with('pending_module_update', $module->name);
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
}
