<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\FilestorageFilegroup;
use Illuminate\Http\Request;

class FilestorageFilegroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = FilestorageFilegroup::all();
        return view('elfcms::admin.filestorage.groups.index',[
            'page' => [
                'title' => __('elfcms::default.groups'),
                'current' => url()->current(),
            ],
            'groups' => $groups
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
    public function show(FilestorageFilegroup $filestorageFilegroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FilestorageFilegroup $filestorageFilegroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FilestorageFilegroup $filestorageFilegroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FilestorageFilegroup $filestorageFilegroup)
    {
        //
    }
}
