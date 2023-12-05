<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Elfcms\Models\FragmentItem;
use Elfcms\Elfcms\Models\FragmentItemOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FragmentItemController extends Controller
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
        $search = $request->search ?? '';
        if (!empty($search)) {
            $items = FragmentItem::where('title', 'like', "%{$search}%")->orderBy($order, $trend)->paginate(30);
        } else {
            $items = FragmentItem::orderBy($order, $trend)->paginate(30);
        }

        return view('elfcms::admin.fragment.items.index', [
            'page' => [
                'title' => __('elfcms::default.fragment') . ' ' . __('elfcms::default.items'),
                'current' => url()->current(),
            ],
            'items' => $items,
            'params' => $request->all(),
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $filterData = ['bool', 'int', 'float', 'string', 'text', 'date', 'time', 'datetime', 'json'];
        $data_types = DataType::whereIn('code', $filterData)->get();
        return view('elfcms::admin.fragment.items.create', [
            'page' => [
                'title' => __('elfcms::default.fragment') . ' ' . __('elfcms::default.items'),
                'current' => url()->current(),
            ],
            'data_types' => $data_types
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
        $request->merge([
            'code' => Str::slug($request->code),
        ]);
        $validated = $request->validate([
            'title' => 'required',
            'code' => 'required|unique:Elfcms\Elfcms\Models\FragmentItem,code',
            'image' => 'nullable|file|max:2024',
        ]);

        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image = $request->file()['image']->store('public/fragment/items/image');
            $image_path = str_ireplace('public/', '/storage/', $image);
        }

        $validated['image'] = $image_path;
        $validated['text'] = $request->text;

        $item = FragmentItem::create($validated);

        if ($item && !empty($request->options_new)) {
            foreach ($request->options_new as $i => $param) {
                if (!empty($param['deleted']) || empty($param['type']) || empty($param['name'])) {
                    continue;
                }
                $typeCode = DataType::find($param['type']);
                $typeCodes = ['int', 'float', 'date', 'datetime'];
                $type = '';
                if (!empty($typeCode) && !empty($typeCode->code) && in_array($typeCode->code, $typeCodes)) {
                    $type = '_' . $typeCode->code;
                }
                $optionData = [
                    'name' => $param['name'],
                    'data_type_id' => $param['type'],
                    'value' . $type => $param['value'],
                ];
                $item->options()->create($optionData);
            }
        }

        return redirect(route('admin.fragment.items.edit', $item->id))->with('success', __('elfcms::default.item_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FragmentItem  $item
     * @return \Illuminate\Http\Response
     */
    public function show(FragmentItem $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FragmentItem  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(FragmentItem $item)
    {
        $filterData = ['bool', 'int', 'float', 'string', 'text', 'date', 'time', 'datetime', 'json'];
        $data_types = DataType::whereIn('code', $filterData)->get();

        $next_option_id = $item->options->max('id');
        if (empty($next_option_id)) {
            $next_option_id = 0;
        } else {
            $next_option_id++;
        }
        $typeCodes = ['int', 'float', 'date', 'datetime'];
        return view('elfcms::admin.fragment.items.edit', [
            'page' => [
                'title' => __('elfcms::default.fragment') . ' ' . __('elfcms::default.item') . '#' . $item->id,
                'current' => url()->current(),
            ],
            'item' => $item,
            'next_option_id' => $next_option_id,
            'data_types' => $data_types,
            'type_codes' => $typeCodes
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FragmentItem  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FragmentItem $item)
    {
        $request->merge([
            'code' => Str::slug($request->code),
        ]);
        $validated = $request->validate([
            'title' => 'required',
            'code' => 'required', //|unique:Elfcms\Elfcms\Models\FragmentItem,code',
            'image' => 'nullable|file|max:1024',
        ]);
        if (FragmentItem::where('code', $request->code)->where('id', '<>', $item->id)->first()) {
            return redirect(route('admin.fragment.item.edit', $item->id))->withErrors([
                'code' => __('elfcms::default.item_already_exists')
            ]);
        }
        $image_path = $request->image_path;
        if (!empty($request->file()['image'])) {
            $image = $request->file()['image']->store('public/fragment/items/image');
            $image_path = str_ireplace('public/', '/storage/', $image);
        }

        $item->code = $request->code;
        $item->title = $request->title;
        $item->image = $image_path;
        $item->text = $request->text;

        $typeCodes = ['int', 'float', 'date', 'datetime'];

        if (!empty($request->options_exist)) {
            foreach ($request->options_exist as $oid => $param) {
                if (!empty($param['deleted']) && $oid > 0) {
                    $item->options()->find($oid)->delete();
                    continue;
                }
                $typeCode = DataType::find($param['type']);
                $type = '';
                if (!empty($typeCode) && !empty($typeCode->code) && in_array($typeCode->code, $typeCodes)) {
                    $type = '_' . $typeCode->code;
                }
                $option = FragmentItemOption::find($oid);
                if ($option) {
                    $option['value' . $type] = $param['value'];
                    $option->name = $param['name'];
                    $option->data_type_id = $param['type'];
                    $option->save();
                }
            }
        }

        if (!empty($request->options_new)) {
            foreach ($request->options_new as $i => $param) {
                if (!empty($param['deleted']) || (empty($param['value']) && empty($param['text']))) {
                    continue;
                }
                $typeCode = DataType::find($param['type']);
                $type = '';
                if (!empty($typeCode) && !empty($typeCode->code) && in_array($typeCode->code, $typeCodes)) {
                    $type = '_' . $typeCode->code;
                }
                $optionData = [
                    'value' . $type => $param['value'],
                    'name' => $param['name'],
                    'data_type_id' => $param['type'],
                ];
                $item->options()->create($optionData);
            }
        }

        $item->save();

        return redirect(route('admin.fragment.items.edit', $item->id))->with('success', __('elfcms::default.item_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FragmentItem  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(FragmentItem $item)
    {
        if (!$item->delete()) {
            return redirect(route('admin.fragment.items'))->withErrors(['itemdelerror' => __('elfcms::default.error_of_item_deleting')]);
        }

        return redirect(route('admin.fragment.items'))->with('itemdeleted', __('elfcms::default.item_deleted_successfully'));
    }
}
