<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Form;
use Elfcms\Elfcms\Models\FormFieldType;
use Elfcms\Elfcms\Models\FormResult;
use Illuminate\Http\Request;

class FormResultController extends Controller
{
    public function index()
    {
        $results = FormResult::all();
        //dd($results[0]->data);
        return view('elfcms::admin.form_results.index',[
            'page' => [
                'title' => __('elfcms::default.form_results'),
                'current' => url()->current(),
            ],
            'results' => $results
        ]);
    }

    public function form(Form $form) {
        $types = [
            'text',
            'textarea',
            'email'
        ];
        $fields = $form->allfields()->active()->whereIn('type_id', FormFieldType::whereIn('name',$types)->pluck('id')->toArray())->limit(5)->orderBy('required','DESC')->pluck('title','name')->toArray();
        return view('elfcms::admin.form_results.form',[
            'page' => [
                'title' => __('elfcms::default.form_results') . ': ' . ($form->title ? '"' . $form->title .'"' : '#' . $form->id),
                'current' => url()->current(),
            ],
            'results' => $form->results()->orderBy('created_at', 'DESC')->get(),
            'fields' => $fields
        ]);
    }

    public function show(Form $form, FormResult $result) {
        $excludedTypes = [
            'password',
            'hidden',
            'submit',
            'reset',
            'button'
        ];
        $fields = $form->allfields()->whereIn('type_id', FormFieldType::whereNotIn('name',$excludedTypes)->pluck('id')->toArray())->limit(5)->orderBy('required','DESC')->get()/* ->pluck('title','name')->toArray() */;
        return view('elfcms::admin.form_results.show',[
            'page' => [
                'title' => __('elfcms::default.form_results') . ': ' . ($form->title ? '"' . $form->title .'"' : '#' . $form->id) . ' #' .$result->id,
                'current' => url()->current(),
            ],
            'result' => $result,
            'fields' => $fields
        ]);
    }
}
