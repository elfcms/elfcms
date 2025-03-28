<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Aux\Filestorage as AuxFilestorage;
use Elfcms\Elfcms\Aux\Image;
use Elfcms\Elfcms\Http\Requests\Admin\FilestorageFileStorRequest;
use Elfcms\Elfcms\Http\Requests\Admin\FilestorageFileUpdateRequest;
use Elfcms\Elfcms\Models\Filestorage;
use Elfcms\Elfcms\Models\FilestorageFile;
use Elfcms\Elfcms\Models\FilestorageFilegroup;
use Elfcms\Elfcms\Models\FilestorageFiletype;
use Illuminate\Http\Request;

class FilestorageFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Filestorage $filestorage)
    {
        /* if (empty($filestorage->preview)) {
            $filestorage->preview = '/elfcms/admin/modules/filestorage/images/empty_270.png';
        }
        else {
            $filestorage->preview = Image::cropCache($filestorage->preview,270,270);
        } */
        if ($request->ajax()) {
            return view('elfcms::admin.filestorage.files.content.index', [
                'page' => [
                    'title' => __('elfcms::default.files'),
                    'current' => url()->current(),
                ],
                'filestorage' => $filestorage,
            ]);
        }
        return view('elfcms::admin.filestorage.files.index', [
            'page' => [
                'title' => __('elfcms::default.files'),
                'current' => url()->current(),
            ],
            'filestorage' => $filestorage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Filestorage $filestorage)
    {
        $maxPosition = FilestorageFile::where('storage_id', $filestorage->id)->max('position');
        $position = empty($maxPosition) && $maxPosition !== 0 ? 0 : $maxPosition + 1;

        $mimes = [];


        //dd(AuxFilestorage::image('01jjmerk586e9dd07n9xhk95s8'));

        if (!empty($filestorage->group_id) && $filestorage->group->code != 'mixed') {
            $groups = [$filestorage->group];
        } else {
            $groups = FilestorageFilegroup::all();
        }

        if (!empty($filestorage->types) && $filestorage->types->count() > 0) {
            $types = $filestorage->types;
        } elseif (!empty($filestorage->group_id) && $filestorage->group->code != 'mixed') {
            $types = $filestorage->group->types;
        } else {
            $types = FilestorageFiletype::all();
        }

        $acceptGroup = $filestorage->group->mime_prefix ?? '*';
        foreach ($types as $type) {
            $code = $type->code == 'any' ? '*' : $type->code;
            $mimes[] .= '.' . $code;
            $mimes[] .= ($type->mime_prefix ?? $acceptGroup ?? '*') . '/' . ($type->mime_type ?? '*');
        }

        $mimes = array_unique($mimes);
        if ($request->ajax()) {
            return view('elfcms::admin.filestorage.files.content.create', [
                'page' => [
                    'title' => __('elfcms::default.create_file'),
                    'current' => url()->current(),
                ],
                'filestorage' => $filestorage,
                'position' => $position,
                'mimes' => $mimes,
                'groups' => $groups,
                'types' => $types,
                'isAjax' => true,
            ]);
        }
        return view('elfcms::admin.filestorage.files.create', [
            'page' => [
                'title' => __('elfcms::default.create_file'),
                'current' => url()->current(),
            ],
            'filestorage' => $filestorage,
            'position' => $position,
            'mimes' => $mimes,
            'groups' => $groups,
            'types' => $types,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FilestorageFileStorRequest $request)
    {
        $request->validated();
        $validated = $request->all();
        $file = FilestorageFile::create($validated);

        if ($request->ajax()) {
            $data = $file->toArray();
            //if ($data['path']) $data['path'] = file_path($data['path']);
            $data['public_path'] = $file->public_path;
            return response()->json([
                'result' => 'success',
                'message' => __('elfcms::default.file_created_successfully'),
                'data' => $data,
            ]);
        }
        return redirect(route('admin.filestorage.files.index', $file->storage_id))->with('success', __('elfcms::default.file_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Filestorage $filestorage, FilestorageFile $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Filestorage $filestorage, FilestorageFile $file)
    {
        $mimes = [];
        if (!empty($filestorage->group_id) && $filestorage->group->code != 'mixed') {
            $groups = [$filestorage->group];
        } else {
            $groups = FilestorageFilegroup::all();
        }

        if (!empty($filestorage->types) && $filestorage->types->count() > 0) {
            $types = $filestorage->types;
        } elseif (!empty($filestorage->group_id) && $filestorage->group->code != 'mixed') {
            $types = $filestorage->group->types;
        } else {
            $types = FilestorageFiletype::all();
        }

        $acceptGroup = $filestorage->group->mime_prefix ?? '*';
        foreach ($types as $type) {
            $code = $type->code == 'any' ? '*' : $type->code;
            $mimes[] .= '.' . $code;
            $mimes[] .= ($type->mime_prefix ?? $acceptGroup ?? '*') . '/' . ($type->mime_type ?? '*');
        }

        $mimes = array_unique($mimes);
        if ($request->ajax()) {
            return view('elfcms::admin.filestorage.files.content.edit', [
                'page' => [
                    'title' => __('elfcms::default.edit_file'),
                    'current' => url()->current(),
                ],
                'filestorage' => $filestorage,
                'file' => $file,
                'mimes' => $mimes,
                'groups' => $groups,
                'types' => $types,
                'isAjax' => true,
            ]);
        }
        return view('elfcms::admin.filestorage.files.edit', [
            'page' => [
                'title' => __('elfcms::default.edit_file'),
                'current' => url()->current(),
            ],
            'filestorage' => $filestorage,
            'file' => $file,
            'mimes' => $mimes,
            'groups' => $groups,
            'types' => $types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FilestorageFileUpdateRequest $request, Filestorage $filestorage, FilestorageFile $file)
    {
        if ($request->notedit && $request->notedit == 1) {
            $file->active = empty($request->active) ? 0 : 1;
            $file->save();
            if ($request->ajax()) {
                return response()->json([
                    'result' => 'success',
                    'message' => __('elfcms::default.file_edited_successfully'),
                    'data' => $file->toArray(),
                ]);
            }
            if ($request->input('submit') == 'save_and_close') {
                return redirect(route('admin.filestorage.files.index', $file->storage_id))->with('success', __('elfcms::default.file_edited_successfully'));
            }
            return redirect(route('admin.filestorage.files.edit', [$filestorage, $file]))->with('success', __('elfcms::default.file_edited_successfully'));
        } elseif ($request->posedit && $request->posedit == 1) {
            $file->position = $request->position ?? 0;
            $file->save();
            if ($request->ajax()) {
                return response()->json([
                    'result' => 'success',
                    'message' => __('elfcms::default.file_edited_successfully'),
                    'data' => $file->toArray(),
                ]);
            }
            if ($request->input('submit') == 'save_and_close') {
                return redirect(route('admin.filestorage.files.index', $file->storage_id))->with('success', __('elfcms::default.file_edited_successfully'));
            }
            return redirect(route('admin.filestorage.files.edit', [$filestorage, $file]))->with('success', __('elfcms::default.file_edited_successfully'));
        } else {
            $request->validated();
            $validated = $request->all();
            $file->update($validated);

            if ($request->ajax()) {
                $data = $file->toArray();
                //if ($data['path']) $data['path'] = file_path($data['path']);
                $data['public_path'] = $file->public_path;
                return response()->json([
                    'result' => 'success',
                    'message' => __('elfcms::default.file_edited_successfully'),
                    'data' => $data,
                ]);
            }
            if ($request->input('submit') == 'save_and_close') {
                return redirect(route('admin.filestorage.files.index', $file->storage_id))->with('success', __('elfcms::default.file_edited_successfully'));
            }
            return redirect(route('admin.filestorage.files.edit', [$filestorage, $file]))->with('success', __('elfcms::default.file_edited_successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filestorage $filestorage, FilestorageFile $file)
    {
        //
    }
}
