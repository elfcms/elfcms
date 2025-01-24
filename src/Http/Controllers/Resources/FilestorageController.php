<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Filestorage;
use Elfcms\Elfcms\Models\FilestorageFilegroup;
use Elfcms\Elfcms\Models\FilestorageFiletype;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class FilestorageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $storages = Filestorage::all();
        return view('elfcms::admin.filestorage.index',[
            'page' => [
                'title' => __('elfcms::default.filestorage'),
                'current' => url()->current(),
            ],
            'storages' => $storages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = FilestorageFilegroup::with('types')->get()->keyBy('id');
        $types = FilestorageFiletype::all();
        $storages = Filestorage::all();

        return view('elfcms::admin.filestorage.create',[
            'page' => [
                'title' => __('elfcms::default.filestorage'),
                'current' => url()->current(),
            ],
            'groups' => $groups,
            'types' => $types,
            'storages' => $storages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'code' => Str::slug($request->code,'_'),
            'path' => Str::slug($request->path),
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:Elfcms\Elfcms\Models\Filestorage,code',
            'path' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $validated = $validator->validated();

        $types = $request->types;

        $newGroup = FilestorageFilegroup::find($request->group_id)->load('types');

        if ($newGroup->code != 'mixed') {
            $types = array_intersect($types,$newGroup->types->pluck('id')->toArray());
        }

        $filestorage = new Filestorage();
        $filestorage->name = $request->name;
        $filestorage->code = $validated['code'];
        $filestorage->path = $validated['path'];
        $filestorage->group_id = $request->group_id;
        $filestorage->description = $request->description;
        $filestorage->save();
        $filestorage->types()->sync($types);

        if ($request->input('submit') == 'save_and_open') {
            return redirect(route('admin.filestorage.show',$filestorage))->with('success',__('elfcms::default.storage_created_successfully'));
        }

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.filestorage.index'))->with('success',__('elfcms::default.storage_created_successfully'));
        }

        return redirect(route('admin.filestorage.edit',$filestorage))->with('success',__('elfcms::default.storage_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Filestorage $filestorage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filestorage $filestorage)
    {
        $groups = FilestorageFilegroup::with('types')->get()->keyBy('id');
        $types = FilestorageFiletype::all();
        $filestorage->load('types');
        $storages = Filestorage::all()->except($filestorage->id);

        return view('elfcms::admin.filestorage.edit',[
            'page' => [
                'title' => __('elfcms::default.filestorage'),
                'current' => url()->current(),
            ],
            'storage' => $filestorage,
            'groups' => $groups,
            'types' => $types,
            'storages' => $storages
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filestorage $filestorage)
    {
        $request->merge([
            'code' => Str::slug($request->code,'_'),
            'path' => Str::slug($request->path),
        ]);
        if ($request->code == $filestorage->code) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required',
                'path' => 'nullable',
            ]);
        }
        else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required|unique:Elfcms\Elfcms\Models\Filestorage,code',
                'path' => 'nullable',
            ]);
        }

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $validated = $validator->validated();

        $types = $request->types;

        $newGroup = FilestorageFilegroup::find($request->group_id)->load('types');

        if ($newGroup->code != 'mixed') {
            $types = array_intersect($types,$newGroup->types->pluck('id')->toArray());
        }

        $filestorage->name = $request->name;
        $filestorage->code = $validated['code'];
        $filestorage->path = $validated['path'];
        $filestorage->group_id = $request->group_id;
        $filestorage->description = $request->description;
        $filestorage->save();
        $filestorage->types()->sync($types);

        if ($request->input('submit') == 'save_and_open') {
            return redirect(route('admin.filestorage.show',$filestorage))->with('success',__('elfcms::default.storage_edited_successfully'));
        }

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.filestorage.index'))->with('success',__('elfcms::default.storage_edited_successfully'));
        }

        return redirect(route('admin.filestorage.edit',$filestorage))->with('success',__('elfcms::default.storage_edited_successfully'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filestorage $filestorage)
    {
        //
    }
}
