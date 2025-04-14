<?php

namespace Elfcms\Elfcms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Backup;
use Elfcms\Elfcms\Models\BackupSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        $count = intval($request->count);
        if (empty($count) || $count < 10) {
            $count = 10;
        }
        $order = $request->order && strtolower($request->order) == 'asc' ? 'asc' : 'desc';
        if ($request->show && strtolower($request->show) == 'all') {
            $backups = Backup::orderBy('id', $order)->paginate($count);
        } else {
            $backups = Backup::whereHas('status', function ($query) {
                $query->where('name', 'success');
            })->where('file_exists', 1)->orderBy('id', $order)->paginate($count);
        }

        return view('elfcms::admin.backup.index', [
            'page' => [
                'title' => __('elfcms::default.backups'),
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'backups' => $backups,
        ]);
    }

    public function settings(Request $request)
    {
        //dd(1);
        //dd(backupSetting('paths'));
        //dd(backupSettings());
        $settings = backupSettings();
        //dd($settings);
        //$setConf = backupSetConfig($settings);
        //dd($setConf);
        $shedule = parseCron($settings['schedule']);
        //dd($shedule);
        $monthNames = [
            1 => __('elfcms::default.january'),
            __('elfcms::default.february'),
            __('elfcms::default.march'),
            __('elfcms::default.april'),
            __('elfcms::default.may'),
            __('elfcms::default.june'),
            __('elfcms::default.july'),
            __('elfcms::default.august'),
            __('elfcms::default.september'),
            __('elfcms::default.october'),
            __('elfcms::default.november'),
            __('elfcms::default.december')
        ];
        $weekdayNames = [
            0 => __('elfcms::default.sunday'),
            __('elfcms::default.monday'),
            __('elfcms::default.tuesday'),
            __('elfcms::default.wednesday'),
            __('elfcms::default.thursday'),
            __('elfcms::default.friday'),
            __('elfcms::default.saturday')
        ];
        return view('elfcms::admin.backup.settings', [
            'page' => [
                'title' => __('elfcms::default.backups'),
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'settings' => $settings,
            'shedule' => $shedule,
            'monthNames' => $monthNames,
            'weekdayNames' => $weekdayNames,
        ]);
    }

    public function settingsSave(Request $request)
    {
        $values = [
            'enabled' => $request->enabled ? 1 : 0,
            'database__enabled' => $request->database__enabled ? 1 : 0,
            'database__exclude_tables' => $request->database__exclude_tables,
            'paths__database' => $request->paths__database ? 1 : 0,
            'paths__public' => $request->paths__public ? 1 : 0,
            'paths__public_storage' => $request->paths__public_storage ? 1 : 0,
            'paths__public_files' => $request->paths__public_files ? 1 : 0,
            'paths__resources' => $request->paths__resources ? 1 : 0,
            'paths__app' => $request->paths__app ? 1 : 0,
            'paths__config' => $request->paths__config ? 1 : 0,
            'paths__routes' => $request->paths__routes ? 1 : 0,
            'paths__modules' => $request->paths__modules ? 1 : 0,
            'paths__include' => $request->paths__include,
            'paths__exclude' => $request->paths__exclude,
            'schedule' => $request->schedule,
        ];
        foreach ($values as $key => $value) {
            BackupSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', __('elfcms::default.settings_edited_successfully'));
    }

    public function download(Backup $backup)
    {
        return response()->download(storage_path($backup->file_path));
    }

    public function delete(Backup $backup)
    {
        //$backup->delete();
    }

    public function restorePage(Backup $backup)
    {
        if (!empty($backup) && !empty($backup->id)) {
            $altBackup = Backup::where('name', $backup->name)->whereNotIn('id', [$backup->id])->first();
        }
        return view('elfcms::admin.backup.restore', [
            'page' => [
                'title' => __('elfcms::default.backup_restoring'),
                'current' => url()->current(),
            ],
            'backup' => $backup,
            'altBackup' => $altBackup,
        ]);
    }

    public function restore(Request $request, Backup $backup)
    {
        $types = $request->types ?? [];
        $command = 'elfcms:restore ' . $backup->name;
        if (!empty($request->type)) {
            $command .= ' --type=' . $request->type;
        }
        $command .= ' --force';
        if ((in_array('database', $types) || in_array('sql', $types)) && (in_array('files', $types) || in_array('zip', $types))) {
            $restoreType = 'all';
        } elseif (in_array('database', $types) || in_array('sql', $types)) {
            $restoreType = 'database';
        } elseif (in_array('files', $types) || in_array('zip', $types)) {
            $restoreType = 'files';
        }
        if ($request->ajax()) {
            $commandResult = Artisan::call($command);
            $result = $commandResult > 0 ? 'error' : 'success';
            if ($commandResult > 0) {
                $result = 'error';
                $message =  __('elfcms::default.restore_completed_with_error');
            }
            else {
                $result = 'success';
                $message =  __('elfcms::default.restore_completed');
            }
            return response()->json(['result' => $result, 'message' => $message]);
        }
        if (!empty($request->confirm) && $request->confirm == 1) {
            $result = Artisan::call($command);
            if ($result > 0) {
                return redirect()->route('admin.backup.restore_result', $backup)->with('errorrestore', __('elfcms::default.restore_completed_with_error'));
            } else {
                return redirect()->route('admin.backup.restore_result', $backup)->with('successrestore', __('elfcms::default.restore_completed'));
            }
        } else {
            return view('elfcms::admin.backup.restore-confirm', [
                'page' => [
                    'title' => __('elfcms::default.backup_restoring'),
                    'current' => url()->current(),
                ],
                'backup' => $backup,
                'restoreType' => $restoreType ?? null,
                'types' => $types,
            ]);
        }
    }

    public function restoreResult(Backup $backup)
    {
        return view('elfcms::admin.backup.restore-result', [
            'page' => [
                'title' => __('elfcms::default.backup_restoring'),
                'current' => url()->current(),
            ],
            'backup' => $backup,
        ]);
    }
}
