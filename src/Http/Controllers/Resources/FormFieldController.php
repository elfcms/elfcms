<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Form;
use Elfcms\Elfcms\Models\FormField;
use Elfcms\Elfcms\Models\FormFieldGroup;
use Elfcms\Elfcms\Models\FormFieldOption;
use Elfcms\Elfcms\Models\FormFieldType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = FormField::where('id','>','0')->orderBy('form_id')->orderBy('group_id')->get();
        return view('elfcms::admin.form.fields.index',[
            'page' => [
                'title' => __('elfcms::default.form_fields'),
                'current' => url()->current(),
            ],
            'fields' => $fields
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
        //$forms = Form::all();
        $types = FormFieldType::all();
        //$form_id = !empty($request->form_id) ? $request->form_id : null;
        /* if (empty($form_id) && !empty($forms[0])) {
            $form_id = $forms[0]->id;
        } */
        //dd($request->form_id);
        $group = !empty($request->group) ? $request->group : null;
        /* if (empty($form_id)) {
            $groups = FormFieldGroup::all();
        } */
        //else {
            $groups = FormFieldGroup::where('form_id',$form->id)->get();
        //}
        return view('elfcms::admin.form.fields.create',[
            'page' => [
                'title' => __('elfcms::default.create_field'),
                'current' => url()->current(),
            ],
            'groups' => $groups,
            'form' => $form,
            //'form_id' => $form_id,
            'group' => $group,
            'types' => $types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Form  $form)
    {
        $request->merge([
            'name' => Str::slug($request->name),
            'position' => intval($request->position),
            'group_id' => intval($request->group_id),
            'attributes' => empty($request->attributes) ? '[{}]' : json_encode($request->attributes),
            'required' => empty($request->required) ? 0 : 1,
            'disabled' => empty($request->disabled) ? 0 : 1,
            'checked' => empty($request->checked) ? 0 : 1,
            'readonly' => empty($request->readonly) ? 0 : 1,
        ]);
        $validated = $request->validate([
            'name' => 'required',
            //'form_id' => 'integer|required',
            'type_id' => 'integer|required',
            'position' => 'integer|max:999999',
            'attributes' => 'json',
        ]);
        $validated['form_id'] = $form->id;
        $validated['description'] = $request->description;
        $validated['title'] = $request->title;
        $validated['position'] = $request->position;
        $validated['placeholder'] = $request->placeholder;
        $validated['value'] = $request->value;
        $validated['required'] = $request->required;
        $validated['disabled'] = $request->disabled;
        $validated['checked'] = $request->checked;
        $validated['readonly'] = $request->readonly;
        $validated['group_id'] = $request->group_id > 0 ? $request->group_id : null;

        //dd($validated);

        $field = FormField::create($validated);

        if ($field && !empty($request->options_new)) {
            foreach ($request->options_new as $i => $param) {
                if (!empty($param['deleted']) || (empty($param['value']) && empty($param['text']))) {
                    continue;
                }
                $optionData = [
                    'value' => $param['value'],
                    'text' => $param['text'],
                    'selected' => empty($param['selected']) ? 0 : 1,
                    'disabled' => empty($param['disabled']) ? 0 : 1,
                ];
                $field->options()->create($optionData);
            }
        }

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.forms.forms.show',$form))->with('success',__('elfcms::default.field_created_successfully'));
        }

        return redirect(route('admin.forms.fields.edit',['form'=>$form, 'field'=>$field]))->with('success',__('elfcms::default.field_created_successfully'));
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
     * @param  Elfcms\Elfcms\Models\FormField  $field
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function edit(FormField $field, Form  $form)
    {
        //dd($field->group->id);
        $types = FormFieldType::all();
        $groups = FormFieldGroup::where('form_id',$field->form->id)->get();
        return view('elfcms::admin.form.fields.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_field') . ' #' . $field->id,
                'current' => url()->current(),
            ],
            'field' => $field,
            'groups' => $groups,
            'types' => $types,
            'form' => $form,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Elfcms\Elfcms\Models\FormField  $field
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormField $field, Form  $form)
    {
        $request->merge([
            'name' => Str::slug($request->name),
            'position' => intval($request->position),
            'group_id' => intval($request->group_id),
            'attributes' => empty($request->attributes) ? '[{}]' : json_encode($request->attributes),
            'required' => empty($request->required) ? 0 : 1,
            'disabled' => empty($request->disabled) ? 0 : 1,
            'checked' => empty($request->checked) ? 0 : 1,
            'readonly' => empty($request->readonly) ? 0 : 1,
        ]);
        $validated = $request->validate([
            'name' => 'required',
            //'form_id' => 'integer|required',
            'type_id' => 'integer|required',
            'attributes' => 'json',
            'position' => 'nullable|integer|max:999999',
        ]);

        $field->name = $validated['name'];
        //$field->form_id = $validated['form_id'];
        $field->type_id = $validated['type_id'];
        $field->attributes = $validated['attributes'];
        $field->required = $request->required;
        $field->disabled = $request->disabled;
        $field->checked = $request->checked;
        $field->readonly = $request->readonly;
        $field->description = $request->description;
        $field->title = $request->title;
        $field->position = $request->position;
        $field->placeholder = $request->placeholder;
        $field->value = $request->value;
        /* $field->disabled = $request->disabled;
        $field->checked = $request->checked;
        $field->readonly = $request->readonly; */
        $field->group_id = $request->group_id > 0 ? $request->group_id : null;

        if (!empty($request->options_exist)) {
            foreach ($request->options_exist as $oid => $param) {
                if (!empty($param['deleted']) && $oid > 0) {
                    $field->options()->find($oid)->delete();
                    continue;
                }

                $option = FormFieldOption::find($oid);
                if ($option) {
                    $option->value = $param['value'];
                    $option->text = $param['text'];
                    $option->selected = empty($param['selected']) ? 0 : 1;
                    $option->disabled = empty($param['disabled']) ? 0 : 1;
                    $option->save();
                }
            }
        }

        if (!empty($request->options_new)) {
            foreach ($request->options_new as $i => $param) {
                if (!empty($param['deleted']) || (empty($param['value']) && empty($param['text']))) {
                    continue;
                }
                $optionData = [
                    'value' => $param['value'],
                    'text' => $param['text'],
                    'selected' => empty($param['selected']) ? 0 : 1,
                    'disabled' => empty($param['disabled']) ? 0 : 1,
                ];
                $field->options()->create($optionData);
            }
        }

        $field->save();

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.forms.forms.show',$form))->with('success',__('elfcms::default.field_edited_successfully'));
        }

        return redirect(route('admin.forms.fields.edit',$field))->with('success',__('elfcms::default.field_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormField $field)
    {
        $form = $field->form;
        if (!$field->delete()) {
            return redirect(route('admin.forms.schow',$form))->withErrors(['fielddelerror'=>__('elfcms::default.field_delete_error')]);
        }

        return redirect(route('admin.forms.schow',$form))->with('success',__('elfcms::default.field_deleted_successfully'));
    }
}
