<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Form;
use Elfcms\Elfcms\Models\FormResult;
use Illuminate\Http\Request;

class FormResultController extends Controller
{
    public function index()
    {
        $results = FormResult::all();
        dd($results[0]->data);
        return view('elfcms::admin.form_results.index',[
            'page' => [
                'title' => __('elfcms::default.form_results'),
                'current' => url()->current(),
            ],
            'results' => $results
        ]);
    }

    public function form(Form $form) {

    }

    public function show(Form $form, FormResult $formResult) {

    }
}
