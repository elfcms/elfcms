<?php

namespace Elfcms\Elfcms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Backup;
use Elfcms\Elfcms\Models\BackupSetting;
use Illuminate\Http\Request;

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
                })->where('file_exists',1)->orderBy('id',$order)->paginate($count);
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
        return view('elfcms::admin.backup.restore',[
            'title' => __('elfcms::default.backups'),
            'current' => url()->current(),
            'backup' => $backup,
        ]);
    }

    public function restore(Backup $backup)
    {
        //
    }
}
