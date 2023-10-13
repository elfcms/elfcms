<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Form;
use Elfcms\Elfcms\Models\FormField;
use Elfcms\Elfcms\Models\FormFieldGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormFieldGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->form_id)) {
                return FormFieldGroup::all()->toJson();
            }
            else {
                return FormFieldGroup::where('form_id',$request->form_id)->get()->toJson();
            }
        }
        $groups = FormFieldGroup::where('id','>','0')->orderBy('form_id')->get();
        return view('elfcms::admin.form.groups.index',[
            'page' => [
                'title' => __('elfcms::default.form_field_groups'),
                'current' => url()->current(),
            ],
            'groups' => $groups
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $forms = Form::all();
        return view('elfcms::admin.form.groups.create',[
            'page' => [
                'title' => __('elfcms::default.create_form_field_group'),
                'current' => url()->current(),
            ],
            'forms' => $forms,
            'form_id' => $request->form_id
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
            'code' => Str::slug($request->code,'_'),
            'name' => Str::slug($request->name),
            'position' => intval($request->position)
        ]);
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:Elfcms\Elfcms\Models\FormFieldGroup,code',
            'form_id' => 'integer|required'
        ]);
        $validated['description'] = $request->description;
        $validated['title'] = $request->title;
        $validated['position'] = $request->position;

        //dd($validated);

        $group = FormFieldGroup::create($validated);

        //dd($group);

        return redirect(route('admin.form.groups.edit',$group->id))->with('groupcreated','Field group created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FormFieldGroup $group)
    {
        $fields = FormField::where('group_id',$group->id)->get();
        return view('elfcms::admin.form.groups.show',[
            'page' => [
                'title' => __('elfcms::default.form_field_group').' #' . $group->id,
                'current' => url()->current(),
            ],
            'fields' => $fields,
            'group' => $group
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormFieldGroup $group)
    {
        $forms = Form::all();
        return view('elfcms::admin.form.groups.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_form_field_group').' #' . $group->id,
                'current' => url()->current(),
            ],
            'group' => $group,
            'forms' => $forms
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormFieldGroup $group)
    {
        $request->merge([
            'code' => Str::slug($request->code,'_'),
            'name' => Str::slug($request->name),
            'position' => intval($request->position),
        ]);
        if ($request->code == $group->code) {
            $validated = $request->validate([
                'name' => 'required',
                'code' => 'required',
                'form_id' => 'integer|required',
            ]);
        }
        else {
            $validated = $request->validate([
                'name' => 'required',
                'code' => 'required|unique:Elfcms\Elfcms\Models\FormFieldGroup,code',
                'form_id' => 'integer|required',
            ]);
        }
        //dd($request->position);

        $group->name = $validated['name'];
        $group->code = $validated['code'];
        $group->description = $request->description;
        $group->title = $request->title;
        $group->position = $request->position;

        $group->save();

        return redirect(route('admin.form.groups.edit',$group->id))->with('groupedited','Group edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormFieldGroup $group)
    {
        if (!$group->delete()) {
            return redirect(route('admin.form.groups'))->withErrors(['groupdelerror'=>'Error of group deleting']);
        }

        return redirect(route('admin.form.groups'))->with('groupdeleted','Group deleted successfully');
    }
}
