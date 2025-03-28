<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\FilestorageFilegroup;
use Elfcms\Elfcms\Models\FilestorageFiletype;
use Illuminate\Http\Request;

class FilestorageFiletypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = FilestorageFiletype::all();
        $groups = FilestorageFilegroup::all();
        return view('elfcms::admin.filestorage.types.index',[
            'page' => [
                'title' => __('elfcms::default.types'),
                'current' => url()->current(),
            ],
            'types' => $types,
            'groups' => $groups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FilestorageFiletype $filestorageFiletype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FilestorageFiletype $filestorageFiletype)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FilestorageFiletype $filestorageFiletype)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FilestorageFiletype $filestorageFiletype)
    {
        //
    }
}
