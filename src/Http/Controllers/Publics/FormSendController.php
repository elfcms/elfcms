<?php

namespace Elfcms\Elfcms\Http\Controllers\Publics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Aux\FormSaver;
use Elfcms\Elfcms\Models\Form;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

class FormSendController extends Controller
{
    public function send(Request $request)
    {
        $redirect = url()->previous();
        if (!empty($request->redirect_to)) {
            if (Route::has($request->redirect_to)) {
                $redirect = route($request->redirect_to);
            }
            else {
                $redirect = $request->redirect_to;
            }
        }
        $saver = new FormSaver;
        $saver->save($request);
        if ($request->ajax()) {
            return $saver->toJson();
        }
        if ($saver->success) {
            return redirect($redirect)->with($saver->name,$saver->text);
        }
        return redirect($redirect)->withErrors([$saver->text]);
    }

    public function result()
    {
        return view('basic::public.form.result',[
            'page' => [
                'title' => Lang::get('elfcms::default.form_sending')
            ],
        ]);
    }
}
