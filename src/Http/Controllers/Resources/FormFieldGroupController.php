<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Form;
use Elfcms\Elfcms\Models\FormField;
use Elfcms\Elfcms\Models\FormFieldGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FormFieldGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Form $form)
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
            'groups' => $groups,
            'form' => $form,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Form $form)
    {
        $errorFields = [];
        if(session()->has('errors')) {
            $errorFields = session('errors')->getBags()['default']->toArray();
        }
        $fields = $request->old();

        if (empty($fields)) {
            $formModel = new Form();
            $fields = $formModel->getFillable();
        }

        return view('elfcms::admin.form.groups.create',[
            'page' => [
                'title' => __('elfcms::default.create_form_field_group'),
                'current' => url()->current(),
            ],
            //'forms' => $forms,
            //'form_id' => $request->form_id
            'form' => $form,
            'fields' => $fields,
            'errorFields' => $errorFields,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Form $form)
    {

        $request->merge([
            'code' => Str::slug($request->code,'_'),
            'name' => Str::slug($request->name),
            'position' => intval($request->position)
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:Elfcms\Elfcms\Models\FormFieldGroup,code'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $validated = $validator->validated();

        $validated['description'] = $request->description;
        $validated['title'] = $request->title;
        $validated['position'] = $request->position;
        $validated['form_id'] = $form->id;
        $validated['active'] = empty($request->active) ? 0 : 1;

        $group = FormFieldGroup::create($validated);

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.forms.show',$form))->with('success',__('elfcms::default.field_group_created_successfully'));
        }

        return redirect(route('admin.forms.groups.edit',['form'=>$form,'group'=>$group]))->with('success',__('elfcms::default.field_group_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Elfcms\Elfcms\Models\FormFieldGroup  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form, FormFieldGroup $group)
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
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @param  Elfcms\Elfcms\Models\FormFieldGroup  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form, FormFieldGroup $group)
    {
        return view('elfcms::admin.form.groups.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_form_field_group').' #' . $group->id,
                'current' => url()->current(),
            ],
            'group' => $group,
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @param  Elfcms\Elfcms\Models\FormFieldGroup  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form, FormFieldGroup $group)
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
        $group->active = empty($request->active) ? 0 : 1;

        $group->save();

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.form.forms.show',$form))->with('success',__('elfcms::default.form_edited_successfully'));
        }

        return redirect(route('admin.forms.groups.edit',['form'=>$form,'group'=>$group]))->with('success',__('elfcms::default.field_group_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @param  Elfcms\Elfcms\Models\FormFieldGroup  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form  $form, FormFieldGroup $group)
    {
        if (!$group->delete()) {
            return redirect(route('admin.forms.show', $form))->withErrors(['groupdelerror'=>__('elfcms::default.field_group_delete_error')]);
        }

        return redirect(route('admin.forms.show', $form))->with('success',__('elfcms::default.field_group_deleted_successfully'));
    }
}
