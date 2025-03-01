<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Menu;
use Elfcms\Elfcms\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Menu $menu)
    {
        $items = MenuItem::flat();
        return view('elfcms::admin.menu.items.index',[
            'page' => [
                'title' => __('elfcms::default.menu_items'),
                'current' => url()->current(),
            ],
            'items' => $items,
            'menu' => $menu
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Menu $menu)
    {
        /* $menus = Menu::all();
        $menu_id = !empty($request->menu_id) ? $request->menu_id : null;
        if (empty($menu_id) && !empty($menus[0])) {
            $menu_id = $menus[0]->id;
        } */
        $parent_id = null;
        $menuItems = MenuItem::where('menu_id',$menu->id);
        if ($request->parent_id) {
            $parent_id = $request->parent_id;
            //$parent_menu = MenuItem::find($parent_id);
            //$menu_id = $parent_menu->menu_id;
            $menuItems->where('parent_id','<>',$parent_id);
        }
        $items = $menuItems->get();
        //dd($items);

        //dd($menu_id);
        return view('elfcms::admin.menu.items.create',[
            'page' => [
                'title' => __('elfcms::default.create_menu_item'),
                'current' => url()->current(),
            ],
            'menu' => $menu,
            'item_id' => $request->item,
            'items' => $items,
            'parent_id' => $parent_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Menu $menu)
    {
        $request->merge([
            'clickable' => empty($request->clickable) ? 0 : 1
        ]);

        $attributes = [];
        if (!empty($request->attributes_new)) {
            foreach ($request->attributes_new as $attribute) {
                if (!empty($attribute['name'])) {
                    $attributes[$attribute['name']] = $attribute['value'];
                }
            }
        }

        $validated = $request->validate([
            'text' => 'required',
            'position' => 'integer|nullable'
        ]);

        $validated['menu_id'] = $menu->id;
        $validated['parent_id'] = $request->parent_id;
        $validated['position'] = $request->position;
        $validated['link'] = $request->link;
        $validated['title'] = $request->title;
        $validated['handler'] = $request->handler;
        $validated['clickable'] = $request->clickable;
        $validated['attributes'] = $attributes;

        //dd($validated);
        $item = MenuItem::create($validated);

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.menus.show',$menu))->with('success',__('elfcms::default.menu_edited_successfully'));
        }

        return redirect(route('admin.menus.items.edit',['item'=>$item,'menu'=>$menu]))->with('success',__('elfcms::default.menu_item_created_successfully'));
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
    public function edit(Menu $menu, MenuItem $item)
    {
        //$menus = Menu::all();
        $items = MenuItem::where('id','<>',$item->id)->get();
        return view('elfcms::admin.menu.items.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_menu_item'),
                'current' => url()->current(),
            ],
            //'menus' => $menus,
            'menu' => $menu,
            'items' => $items,
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu, MenuItem $item)
    {
        $attributes = [];
        if (!empty($request->attributes_new)) {
            foreach ($request->attributes_new as $attribute) {
                if (!empty($attribute['name'])) {
                    $attributes[$attribute['name']] = $attribute['value'];
                }
            }
        }

        $request->merge([
            'clickable' => empty($request->clickable) ? 0 : 1,
        ]);

        $validated = $request->validate([
            'position' => 'integer|nullable'
        ]);

        $item->menu_id = $menu->id;
        $item->parent_id = $request->parent_id;
        $item->position = $request->position;
        $item->text = $request->text;
        $item->link = $request->link;
        $item->title = $request->title;
        $item->clickable = $request->clickable;
        $item->attributes = $attributes;

        $item->save();

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.menus.show',$menu))->with('success',__('elfcms::default.menu_edited_successfully'));
        }

        return redirect(route('admin.menus.items.edit',['item'=>$item,'menu'=>$menu]))->with('success',__('elfcms::default.menu_item_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu, MenuItem $item)
    {
        if (!$item->delete()) {
            return redirect(route('admin.menus.show', $menu))->withErrors(['menuitemdelerror'=>'Error of menu item deleting']);
        }

        return redirect(route('admin.menus.show', $menu))->with('success','Menu item deleted successfully');
    }
}
