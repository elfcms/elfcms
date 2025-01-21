<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Filestorage;
use Illuminate\Http\Request;

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
    public function show(Filestorage $filestorage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filestorage $filestorage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filestorage $filestorage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filestorage $filestorage)
    {
        //
    }
}
