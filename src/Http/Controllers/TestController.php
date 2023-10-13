<?php

namespace Elfcms\Elfcms\Http\Controllers;

use Elfcms\Elfcms\Aux\FormSaver;
use App\Events\SomeMailEvent;
use App\Mail\EmailConfirmation;
use App\Mail\TestMailer;
use Elfcms\Elfcms\Models\Form;
use App\Models\FormField;
use App\Models\FormResult;
use App\Models\User;
use Elfcms\Elfcms\Aux\Views;
use Elfcms\Elfcms\Events\SomeMailEvent as EventsSomeMailEvent;
use Elfcms\Elfcms\Models\FormResult as ModelsFormResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class TestController extends \App\Http\Controllers\Controller
{
    public function test()
    {

        //dd(rand(18,23));
        //$emails = 'maxklassen@mail.ru';
        //$emails = 'maximklassen@mail.de';
        //$emails = 'himmelmaler@gmail.com';
        //$emails = 'mklassenalg@gmail.com';
        $emails = ['maximklassen@mail.de'];
        $cc = ['mklassenalg@gmail.com','himmelmaler@gmail.com'];
        //$emails = User::find(51);
        //dd($user->email);
        //Mail::to($user->email)->send(new EmailConfirmation($user));
        //Mail::to($emails)->cc($cc)->send(new TestMailer());
        //return new App\Mail\TestMailer();
        //return new EmailConfirmation($user);
        //dd(['a','b']);

        //$files = Storage::allFiles('/');

        //Test to set string param
        /* $string = [
            'a' => '<p style="color:darkblue">This param string <br>with param: :param</p>'
        ];
        echo __($string['a'],['param'=>'Wow']); */

        $formId = 1;
        $form = Form::find($formId);

        //$user = User::find(21);
        //dd($user);

        //event(new SomeMailEvent('test',['params'=>['first'=>'Wow'],'subject'=>'Some subject']));
        //Mail::to('himmelmaler@gmail.com')->send(new TestMailer());
        //Log::info('checking: test');
        //$fieldsWithoutGroup = FormField::where('form_id',$formId)->where('group_id',null)->get();
        //$formFields = FormField::where('form_id',$formId)->get();
        //dd($form);
        //dd($formFields);
        //dd($fieldsWithoutGroup);

        //dd(FormSaver::read(\Elfcms\Elfcms\Models\FormResult::find(5)));
        //dd(View::exists('basic::public.form.results'));
        //dd(Storage::disk('elfcmsviews')->files('basic/emails/events',true));
        //dd(Views::list('basic/emails/events'));
        //dd(config('elfcms.basic.disks.views'));
        //dd(config('filesystems.disks'));

        //event(new SomeMailEvent('userregisterconfirm',['params'=>['confirm_token'=>'Wow'],'subject'=>'Test subject','to'=>'maximklassen@mail.de','view'=>'register-confirm']));
        //event(new SomeMailEvent('userregisterconfirm',['params'=>['first'=>'Wow'],'subject'=>'Some subject']));
        //event(new SomeMailEvent('userregisterconfirm',['params'=>['first'=>'Wow'],'subject'=>'Some subject']));
        //event(new SomeMailEvent('userregisterconfirm',['params'=>['first'=>'Wow'],'to'=>'maxklassen@mail.ru','cc'=>'maximklassen@mail.de','subject'=>'Some subject']));
        //$frm = Form::find(1);
        //dd(FormSaver::read(ModelsFormResult::where('form_id',1)->first()));
        //event(new EventsSomeMailEvent('test',['params'=>['first'=>'Wow'],'subject'=>'Some subject']));

        return view('basic::test',[
            'page' => [
                'title' => 'Test page',
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'form' => $form
        ]);

    }

    public function testSave(Request $request) {
        $saver = new FormSaver;
        $saver->save($request);
        if ($saver->success) {
            return redirect(route('test'))->with($saver->name,$saver->text);
        }
        return redirect(route('test'))->withErrors([$saver->text]);
    }
}
