<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\EmailEvent;
use Elfcms\Elfcms\Models\Form;
use Elfcms\Elfcms\Models\FormField;
use Elfcms\Elfcms\Models\FormFieldGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $enctypes = [
        'application/x-www-form-urlencoded',
        'multipart/form-data',
        'text/plain'
    ];

    public function index()
    {
        $forms = Form::all();
        return view('elfcms::admin.form.forms.index',[
            'page' => [
                'title' => __('elfcms::default.forms'),
                'current' => url()->current(),
            ],
            'forms' => $forms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $errorFields = [];
        if(session()->has('errors')) {
            $errorFields = session('errors')->getBags()['default']->toArray();
        }
        $fields = $request->old();

        if (empty($fields)) {
            $form = new Form();
            $fields = $form->getFillable();
        }
        //dd($fields);

        $events = EmailEvent::all();
        return view('elfcms::admin.form.forms.create',[
            'page' => [
                'title' => __('elfcms::default.create_form'),
                'current' => url()->current(),
            ],
            'enctypes' => $this->enctypes,
            'events' => $events,
            'fields' => $fields,
            'errorFields' => $errorFields,
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
        ]);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:Elfcms\Elfcms\Models\Form,code',
            'event_id' => 'integer|nullable',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $validated = $validator->validated();

        $validated['description'] = $request->description;
        $validated['title'] = $request->title;
        $validated['action'] = $request->action;
        $validated['enctype'] = $request->enctype;
        $validated['method'] = "post";
        $validated['redirect_to'] = $request->redirect_to;
        $validated['success_text'] = $request->success_text;
        $validated['error_text'] = $request->error_text;
        $validated['submit_button'] = $request->submit_button;
        $validated['submit_name'] = $request->submit_name;
        $validated['submit_title'] = $request->submit_title;
        $validated['submit_value'] = $request->submit_value;
        $validated['reset_button'] = $request->reset_button;
        $validated['reset_title'] = $request->reset_title;
        $validated['reset_value'] = $request->reset_value;
        $validated['additional_buttons'] = '[{}]';

        $form = Form::create($validated);

        if ($form) {
            if ($request->input('submit') == 'save_and_close') {
                return redirect(route('admin.form.forms.show',$form->id))->with('success',__('elfcms::default.form_created_successfully'));
            }
            return redirect(route('admin.form.forms.edit',$form->id))->with('success',__('elfcms::default.form_created_successfully'));
        }

        return redirect(route('admin.form.forms'))->withInput()->withErrors(['store_error'=>__('elfcms::default.form_save_error')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        $groups = FormFieldGroup::where('form_id',$form->id)->get();
        $fields = FormField::where('form_id',$form->id)->get();
        return view('elfcms::admin.form.forms.show',[
            'page' => [
                'title' => __('elfcms::default.form').' #' . $form->id,
                'current' => url()->current(),
            ],
            'groups' => $groups,
            'fields' => $fields,
            'form' => $form
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        $events = EmailEvent::all();
        return view('elfcms::admin.form.forms.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_form').' #' . $form->id,
                'current' => url()->current(),
            ],
            'form' => $form,
            'enctypes' => $this->enctypes,
            'events' => $events,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        $request->merge([
            'code' => Str::slug($request->code,'_'),
            'name' => Str::slug($request->name),
        ]);
        if ($request->code == $form->code) {
            /* $validated = $request->validate([
                'name' => 'required',
                'code' => 'required',
            ]); */
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required',
                'event_id' => 'integer|nullable',
            ]);
        }
        else {
            /* $validated = $request->validate([
                'name' => 'required',
                'code' => 'required|unique:Elfcms\Elfcms\Models\Form,code',
            ]); */
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required|unique:Elfcms\Elfcms\Models\Form,code',
                'event_id' => 'integer|nullable',
            ]);
        }


        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $validated = $validator->validated();

        $form->name = $validated['name'];
        $form->code = $validated['code'];
        $form->description = $request->description;
        $form->title = $request->title;
        $form->action = $request->action;
        $form->enctype = $request->enctype;
        $form->method = "post";
        $form->redirect_to = $request->redirect_to;
        $form->success_text = $request->success_text;
        $form->error_text = $request->error_text;
        $form->submit_button = $request->submit_button;
        $form->submit_name = $request->submit_name;
        $form->submit_title = $request->submit_title;
        $form->submit_value = $request->submit_value;
        $form->reset_button = $request->reset_button;
        $form->reset_title = $request->reset_title;
        $form->reset_value = $request->reset_value;
        $form->event_id = $request->event_id;

        $form->save();

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.form.forms.show',$form->id))->with('success',__('elfcms::default.form_edited_successfully'));
        }

        return redirect(route('admin.form.forms.edit',$form->id))->with('success',__('elfcms::default.form_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        if (!$form->delete()) {
            return redirect(route('admin.form.forms'))->withErrors(['formdelerror'=>__('elfcms::default.form_delete_error')]);
        }

        return redirect(route('admin.form.forms'))->with('success',__('elfcms::default.form_deleted_successfully'));
    }
}
