<?php

namespace Elfcms\Elfcms\Http\Controllers;

use Elfcms\Elfcms\Events\SomeMailEvent;
use Elfcms\Elfcms\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class LoginController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        if (Auth::check()) {
            return redirect(route('admin.index'));
        }
        return view('elfcms::admin.login.index',[
            'page' => [
                'title' => 'Login',
                'current' => url()->current(),
            ]
        ]);
    }

    public function login(Request $request)
    {
        $adminPath = Config::get('elfcms.elfcms.admin_path') ?? '/admin';

        if (!Auth::check()) {
            $fields = $request->only(['email','password']);

            $remember = false;
            if (!empty($request->remember)) {
                $remember = true;
            }
            $fields['is_confirmed'] = 1;
        }

        if (Auth::check() || Auth::attempt($fields,$remember)) {
            $default = '/';
            if (Auth::user()->roles->contains('code','admin')) {
                $default = $adminPath ?? '/admin';
            }
            return redirect()->intended($default);
        }

        return redirect()->back()->withErrors([
            'email' => __('elfcms::validation.failed')
        ]);
    }


    public function getRestoreForm()
    {
        return view('elfcms::admin.account.getrestore',[
            'page' => [
                'title' => __('elfcms::default.restore_password'),
                'current' => url()->current(),
            ]
        ]);
    }

    public function getRestore(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email',$request->email)->first();

        if ($user && $user->roles->contains('code','admin')) {
            $user->setConfirmationToken();
            event(new SomeMailEvent('passwordrecoveryrequest',['view'=>'elfcms::emails.events.password-recovery-request-admin','to'=>$user->email,'params'=>['confirm_token'=>$user->confirm_token,'email'=>$user->email]],$user));
            $message = __('elfcms::default.a_password_reset_link_has_been_sent_to_your_email_account');
            if (!$message) {
                $message = 'A password reset link has been sent to your email account';
            }
            return redirect(route('admin.getrestore'))->with('requestissended',$message);
        }

        return back()->withErrors(['email' => __('elfcms::default.email_not_found')]);

    }

    public function setRestoreForm(Request $request)
    {
        $linkError = false;
        //dd($request->token);
        $user = User::where('confirm_token',$request->token)->active()->first();
        if (!$user) {
            $linkError = true;
        }
        else {
            $period = Config::get('elfcms.elfcms.confirmation_period');
            if (!$period) {
                $period = 86400;
            }
            if (Carbon::parse($user->confirm_token_at)->diffInSeconds(now()) > $period) {
                $linkError = true;
            }
        }

        //dd(Config::get('elfcms.elfcms.confirmation_period'));
        //dd(Carbon::parse($user->confirm_token_at)->diffInSeconds(now()));
        return view('elfcms::admin.account.setrestore',[
            'page' => [
                'title' => __('elfcms::default.set_a_new_password'),
                'current' => url()->current(),
            ],
            'token' => $request->token,
            'linkError' => $linkError,
            'linkErrorText' => __('elfcms::default.your_link_is_invalid'),
        ]);
    }

    public function setRestore(Request $request)
    {
        $passwordLength = Config::get('elfcms.elfcms.password_length') ?? 8;

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:' . $passwordLength . '|confirmed',
        ]);

        $user = User::where('confirm_token',$request->token)->where('email',$request->email)->active()->first();

        if (!$user) {
            return back()->withErrors([__('elfcms::default.user_not_found')]);
        }

        $user->password = $request->password;
        if ($user->save()) {
            $user->setConfirmationToken();
            return back()->with('passwordchangesuccess',__('elfcms::default.password_changed_successfully'));
        }
        return back()->withErrors([__('elfcms::default.password_change_error')]);
    }

}
