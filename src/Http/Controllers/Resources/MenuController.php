<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Menu;
use Elfcms\Elfcms\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::all();
        return view('elfcms::admin.menu.menus.index',[
            'page' => [
                'title' => __('elfcms::default.menu'),
                'current' => url()->current(),
            ],
            'menus' => $menus
        ]);
    }

    /**
     * Show the menu for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('elfcms::admin.menu.menus.create',[
            'page' => [
                'title' => __('elfcms::default.create_menu'),
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
            'name' => 'required|unique:Elfcms\Elfcms\Models\Menu,name',
            'code' => 'required|unique:Elfcms\Elfcms\Models\Menu,code',
        ]);

        $validated['description'] = $request->description;

        $menu = Menu::create($validated);

        if ($request->input('submit') == 'save_and_open') {
            return redirect(route('admin.menus.show',$menu))->with('success',__('elfcms::default.menu_edited_successfully'));
        }

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.menus.index'))->with('success',__('elfcms::default.menu_edited_successfully'));
        }

        return redirect(route('admin.menus.edit',$menu->id))->with('success',__('elfcms::default.menu_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Elfcms\Elfcms\Models\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu, Request $request)
    {
        if ($request->ajax()) {
            return Menu::find($menu->id)->toJson();
        }

        return view('elfcms::admin.menu.menus.show',[
            'page' => [
                'title' => __('elfcms::default.menu') . ' ' . ($menu->name ?? '#'.$menu->id), //__('elfcms::default.menu_items'),
                'current' => url()->current(),
            ],
            'menu' => $menu
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Elfcms\Elfcms\Models\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        return view('elfcms::admin.menu.menus.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_menu').' #' . $menu->id,
                'current' => url()->current(),
            ],
            'menu' => $menu
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Elfcms\Elfcms\Models\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required'
        ]);

        $menu->name = $validated['name'];
        $menu->code = $validated['code'];
        $menu->description = $request->description;

        $menu->save();

        if ($request->input('submit') == 'save_and_open') {
            return redirect(route('admin.menus.show',$menu))->with('success',__('elfcms::default.menu_edited_successfully'));
        }

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.menus.index'))->with('success',__('elfcms::default.menu_edited_successfully'));
        }

        return redirect(route('admin.menus.edit',$menu))->with('success',__('elfcms::default.menu_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Elfcms\Elfcms\Models\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        if (!$menu->delete()) {
            return redirect(route('admin.menus.menus'))->withErrors(['menudelerror'=>'Error of menu deleting']);
        }

        return redirect(route('admin.menus.menus'))->with('success','Menu deleted successfully');
    }
}
