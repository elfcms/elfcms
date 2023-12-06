<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $pages = Page::orderBy($order, $trend)->paginate(30);

        return view('elfcms::admin.page.pages.index',[
            'page' => [
                'title' => __('elfcms::default.pages'),
                'current' => url()->current(),
            ],
            'pages' => $pages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('elfcms::admin.page.pages.create',[
            'page' => [
                'title' => __('elfcms::default.create_page'),
                'current' => url()->current(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:Elfcms\Elfcms\Models\Page,name',
            'slug' => 'required|unique:Elfcms\Elfcms\Models\Page,slug',
            'content' => 'required',
            'image' => 'nullable|file|max:2024',
        ]);

        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image = $request->file()['image']->store('public/pages/image');
            $image_path = str_ireplace('public/','/storage/',$image);
        }


        $path = $request->path;
        if (!empty($request->path) && !Str::startsWith($request->path,'/')) {
            $path = '/' . $request->path;
        }

        $validated['image'] = $image_path;
        $validated['path'] = $path;
        $validated['title'] = $request->title;
        $validated['browser_title'] = $request->browser_title;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;
        $validated['is_dynamic'] = empty($request->is_dynamic) ? 0 : 1;

        $page = Page::create($validated);

        return redirect(route('admin.page.pages.edit',$page->id))->with('pageedited',__('elfcms::default.page_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //$page->content = $page->getOriginal('content');
        return view('elfcms::admin.page.pages.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_page').' #' . $page->id,
                'current' => url()->current(),
            ],
            'pageData' => $page
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'content' => 'required',
            'image' => 'nullable|file|max:2024',
        ]);

        $path = $request->path;
        if (!empty($request->path) && !Str::startsWith($request->path,'/')) {
            $path = '/' . $request->path;
        }

        $image_path = $request->image_path;
        if (!empty($request->file()['image'])) {
            $image = $request->file()['image']->store('public/pages/image');
            $image_path = str_ireplace('public/','/storage/',$image);
        }

        $page->image = $image_path;
        $page->name = $validated['name'];
        $page->slug = $validated['slug'];
        $page->path = $path;
        $page->content = $validated['content'];
        $page->title = $request->title;
        $page->browser_title = $request->browser_title;
        $page->meta_keywords = $request->meta_keywords;
        $page->meta_description = $request->meta_description;
        $page->is_dynamic = empty($request->is_dynamic) ? 0 : 1;

        $page->save();

        return redirect(route('admin.page.pages.edit',$page->id))->with('pageedited',__('elfcms::default.page_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        if (!$page->delete()) {
            return redirect(route('admin.page.pages'))->withErrors(['pagedelerror'=>'Error of page deleting']);
        }

        return redirect(route('admin.page.pages'))->with('pagedeleted','Page deleted successfully');
    }
}
