<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Aux\Views;
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
        $templates = Views::list('elfcms/public/pages','publicviews');
        if (empty($templates)) {
            $templates = array_merge($templates,Views::list('pages','elfcmsviews','elfcms'));
        }
        if (empty($templates)) {
            $templates = array_merge($templates,Views::list('resources/views/pages','elfcmsdev','elfcms'));
        }
        $templates = array_merge($templates,Views::list('public/pages','publicviews'));
        return view('elfcms::admin.page.pages.create',[
            'page' => [
                'title' => __('elfcms::default.create_page'),
                'current' => url()->current(),
            ],
            'templates' => $templates
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
            'image' => 'nullable|file|max:2024',
        ]);

        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image_path = $request->file()['image']->store('pages/image');
        }


        $path = $request->path;
        if (!empty($request->path) && !Str::startsWith($request->path,'/')) {
            $path = '/' . $request->path;
        }

        $validated['image'] = $image_path;
        $validated['path'] = $path;
        $validated['title'] = $request->title;
        $validated['content'] = $request->content;
        $validated['template'] = $request->template;
        $validated['browser_title'] = $request->browser_title;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;
        $validated['is_dynamic'] = empty($request->is_dynamic) ? 0 : 1;
        $validated['active'] = empty($request->active) ? 0 : 1;

        $page = Page::create($validated);

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.page.pages'))->with('success',__('elfcms::default.page_created_successfully'));
        }

        return redirect(route('admin.page.pages.edit',$page->id))->with('success',__('elfcms::default.page_created_successfully'));
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
        $templates = Views::list('elfcms/public/pages','publicviews');
        if (empty($templates)) {
            $templates = array_merge($templates,Views::list('pages','elfcmsviews','elfcms'));
        }
        if (empty($templates)) {
            $templates = array_merge($templates,Views::list('resources/views/pages','elfcmsdev','elfcms'));
        }
        $templates = array_merge($templates,Views::list('public/pages','publicviews'));
        return view('elfcms::admin.page.pages.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_page').' #' . $page->id,
                'current' => url()->current(),
            ],
            'pageData' => $page,
            'templates' => $templates,
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
            'image' => 'nullable|file|max:2024',
        ]);

        $path = $request->path;
        if (!empty($request->path) && !Str::startsWith($request->path,'/')) {
            $path = '/' . $request->path;
        }

        $image_path = $request->image_path;
        if (!empty($request->file()['image'])) {
            $image_path = $request->file()['image']->store('pages/image');
        }

        $page->image = $image_path;
        $page->name = $validated['name'];
        $page->slug = $validated['slug'];
        $page->path = $path;
        $page->title = $request->title;
        $page->content = $request->content;
        $page->template = $request->template;
        $page->browser_title = $request->browser_title;
        $page->meta_keywords = $request->meta_keywords;
        $page->meta_description = $request->meta_description;
        $page->is_dynamic = empty($request->is_dynamic) ? 0 : 1;
        $page->active = empty($request->active) ? 0 : 1;

        $page->save();

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.page.pages'))->with('success',__('elfcms::default.page_edited_successfully'));
        }

        return redirect(route('admin.page.pages.edit',$page->id))->with('success',__('elfcms::default.page_edited_successfully'));
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

        return redirect(route('admin.page.pages'))->with('success','Page deleted successfully');
    }
}
