<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Aux\Views;
use Elfcms\Elfcms\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    private array $availableModules;

    public function __construct()
    {
        $this->availableModules = config('elfcms.elfcms.page_modules');
        if (empty($this->availableModules)) {
            $this->availableModules = [];
        }
    }
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

        return view('elfcms::admin.page.pages.index', [
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
        $templates = Views::list('elfcms/public/pages', 'publicviews');
        if (empty($templates)) {
            $templates = array_merge($templates, Views::list('pages', 'elfcmsviews', 'elfcms'));
        }
        if (empty($templates)) {
            $templates = array_merge($templates, Views::list('resources/views/pages', 'elfcmsdev', 'elfcms'));
        }
        $templates = array_merge($templates, Views::list('public/pages', 'publicviews'));

        return view('elfcms::admin.page.pages.create', [
            'page' => [
                'title' => __('elfcms::default.create_page'),
                'current' => url()->current(),
            ],
            'templates' => $templates,
            'modules' => $this->availableModules,
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
            'module_id' => empty($request->module) || $request->module == 'standard' ? 'nullable' : 'required',
        ], [
            'module_id.required' => __('elfcms::default.module_element_must_be_specified'),
        ]);
        $module = $this->availableModules[$request->module] ?? null;

        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image_path = $request->file()['image']->store('pages/image');
        }

        $existsPage = null;
        if ($request->module !== 'standard' && !empty($request->module_id)) {
            $existsPage = Page::where('module', $request->module)->where('module_id', $request->module_id)->first();
        }
        if (!empty($existsPage) && !empty($existsPage->id)) {
            return back()->withErrors(['error' => __('elfcms::default.page_already_exists')]);
        }

        $path = $request->path;

        if (empty($path) && !empty($request->module) && $request->module != 'standard') {
            if (!empty($request->module_id) && !empty($module) && !empty($module['class'])) {
                $moduleData = null;
                try {
                    $moduleData = $module['class']::find($request->module_id);
                    $column = !empty($module['search_column']) ? $module['search_column'] : 'slug';
                    if (!empty($moduleData)) $path = $moduleData->$column ?? null;
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }

        if (empty($path)) $path = $validated['slug'] ?? $request->module;

        if (!empty($path)) {
            $path = '/' . trim($path, '/');
        }

        $validated['image'] = $image_path;
        $validated['title'] = $request->title;
        $validated['content'] = $request->content;
        $validated['template'] = $request->template;
        $validated['browser_title'] = $request->browser_title;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;
        $validated['is_dynamic'] = empty($request->is_dynamic) ? 0 : 1;
        $validated['active'] = empty($request->active) ? 0 : 1;

        $prepared = $this->prepareData($request->all());
        $validated = array_merge($validated, $prepared);
        $validated['path'] = $path;

        $page = Page::create($validated);

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.page.pages'))->with('success', __('elfcms::default.page_created_successfully'));
        }

        return redirect(route('admin.page.pages.edit', $page->id))->with('success', __('elfcms::default.page_created_successfully'));
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
        $templates = Views::list('elfcms/public/pages', 'publicviews');
        if (empty($templates)) {
            $templates = array_merge($templates, Views::list('pages', 'elfcmsviews', 'elfcms'));
        }
        if (empty($templates)) {
            $templates = array_merge($templates, Views::list('resources/views/pages', 'elfcmsdev', 'elfcms'));
        }
        $templates = array_merge($templates, Views::list('public/pages', 'publicviews'));

        $moduleName = 'standard';
        if (!empty($page->module)) $moduleName = $page->module;
        $module = $this->availableModules[$moduleName] ?? [];
        $moduleTemplates = [];
        if ($moduleName == 'standard') {
            $module = [
                'name' => __('elfcms::default.standard_page'),
            ];
        } else {
            $moduleTemplates = Views::list('elfcms/public/' . $moduleName, 'publicviews');
            $moduleTemplates = array_merge($moduleTemplates, Views::list('public/' . $moduleName, 'publicviews'));
        }
        $class = null;
        if (!empty($module) && !empty($module['class'])) {
            $class = $module['class'];
        }
        $moduleItemList = $class ? $class::all() : [];

        return view('elfcms::admin.page.pages.edit', [
            'page' => [
                'title' => __('elfcms::default.edit_page') . ' #' . $page->id,
                'current' => url()->current(),
            ],
            'pageData' => $page,
            'templates' => $templates,
            'module' => $module,
            'moduleName' => $moduleName,
            'module_options' => $page->module_options,
            'moduleItemList' => $moduleItemList,
            'moduleTemplates' => $moduleTemplates,
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


        $image_path = $request->image_path;
        if (!empty($request->file()['image'])) {
            $image_path = $request->file()['image']->store('pages/image');
        }

        $prepared = $this->prepareData($request->all());

        $page->image = $image_path;
        $page->name = $validated['name'];
        $page->slug = $validated['slug'];
        $page->title = $request->title;
        $page->content = $request->content;
        $page->template = $request->template;
        $page->browser_title = $request->browser_title;
        $page->meta_keywords = $request->meta_keywords;
        $page->meta_description = $request->meta_description;
        $page->is_dynamic = empty($request->is_dynamic) ? 0 : 1;
        $page->module = $prepared['module'];
        $page->module_id = $prepared['module_id'];
        $page->module_options = $prepared['module_options'];
        $page->active = empty($request->active) ? 0 : 1;
        if (!empty($request->path)) {
            $page->path = '/' . trim(trim($request->path),'/');
        }

        $page->save();

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.page.pages'))->with('success', __('elfcms::default.page_edited_successfully'));
        }

        return redirect(route('admin.page.pages.edit', $page->id))->with('success', __('elfcms::default.page_edited_successfully'));
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
            return redirect(route('admin.page.pages'))->withErrors(['pagedelerror' => 'Error of page deleting']);
        }

        return redirect(route('admin.page.pages'))->with('success', 'Page deleted successfully');
    }

    protected function prepareData(array $data): array
    {
        $prepared = $data;

        if (!empty($data['module'])) {
            $prepared['module'] = $data['module'];
            $prepared['module_id'] = $data['module_id'] ?? null;
            $prepared['module_options'] = !empty($data['module_options']) ? $data['module_options'] : [];
        } else {
            $prepared['module'] = null;
            $prepared['module_id'] = null;
            $prepared['module_options'] = null;
        }

        return $prepared;
    }

    public function getModuleOptions(string $module)
    {
        if (!isset($this->availableModules[$module])) {
            //abort(404);
            return null;
        }
        $class = null;
        if (!empty($this->availableModules[$module]['class'])) {
            $class = $this->availableModules[$module]['class'];
        }

        $moduleItemList = $class ? $class::all() : [];

        $moduleTemplates = [];
        if ($module != 'standard') {
            $moduleTemplates = Views::list('elfcms/public/' . $module, 'publicviews');
            $moduleTemplates = array_merge($moduleTemplates, Views::list('public/' . $module, 'publicviews'));
        }

        return view($this->availableModules[$module]['options_view'], [
            'moduleItemList' => $moduleItemList,
            'moduleTemplates' => $moduleTemplates,
        ]);
    }
}
