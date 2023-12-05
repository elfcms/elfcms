<?php

namespace Elfcms\Elfcms\Http\Controllers\Publics;

use Elfcms\Elfcms\Events\SomeMailEvent;
use Elfcms\Elfcms\Models\Role;
use Elfcms\Elfcms\Models\User;
use Elfcms\Elfcms\Models\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AccountController extends \App\Http\Controllers\Controller
{
    public $passwordLength;

    public function __construct()
    {
        $this->passwordLength = Config::get('elfcms.basic.password_length') ?? 8;
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->intended('/account/login');
        }

        return view('elfcms::public.account.index',[
            'page' => [
                'title' => __('elfcms::default.account'),
                'current' => url()->current(),
            ],
            'user' => $user
        ]);
    }

    public function loginForm()
    {
        return view('elfcms::public.account.login',[
            'page' => [
                'title' => __('elfcms::default.login'),
                'current' => url()->current(),
            ]
        ]);
    }

    public function login(Request $request)
    {
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
            /* if (Auth::user()->roles->contains('code','admin')) {
                $default = '/admin';
            } */
            return redirect()->intended($default);
        }

        return redirect()->intended('/account/login')->withErrors([
            'email' => Lang::get('elfcms::default.error_of_authentication')
        ]);
    }

    public function getRestoreForm()
    {
        return view('elfcms::public.account.getrestore',[
            'page' => [
                'title' => __('elfcms::default.forgot_your_password'),
                'current' => url()->current(),
            ]
        ]);
    }

    public function getRestore(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email',$request->email)->first();

        if ($user) {
            $user->setConfirmationToken();
            event(new SomeMailEvent('passwordrecoveryrequest',['view'=>'elfcms::emails.events.password-recovery-request','to'=>$user->email,'params'=>['confirm_token'=>$user->confirm_token,'email'=>$user->email]],$user));
            $message = Lang::get('elfcms::default.a_password_reset_link_has_been_sent_to_your_email_account');
            if (!$message) {
                $message = 'A password reset link has been sent to your email account';
            }
            return redirect(route('account.getrestore'))->with('requestissended',$message);
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
            $period = Config::get('elfcms.basic.confirmation_period');
            if (!$period) {
                $period = 86400;
            }
            if (Carbon::parse($user->confirm_token_at)->diffInSeconds(now()) > $period) {
                $linkError = true;
            }
        }

        //dd(Config::get('elfcms.basic.confirmation_period'));
        //dd(Carbon::parse($user->confirm_token_at)->diffInSeconds(now()));
        return view('elfcms::public.account.setrestore',[
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
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:' . $this->passwordLength . '|confirmed',
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

    public function registerForm()
    {
        return view('elfcms::public.account.register',[
            'page' => [
                'title' => __('elfcms::default.registration'),
                'current' => url()->current(),
            ]
        ]);
    }

    public function register(Request $request)
    {

        if (Auth::check()) {
            return redirect(route('account.index'));
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:' . $this->passwordLength . '|confirmed'
        ]);

        if (User::where('email',$validated['email'])->exists()) {
            return redirect(route('account.register'))->withErrors([
                'email' => Lang::get('elfcms::default.user_already_exists')
            ]);
        }

        $roleCode = Config::get('elfcms.basic.user_default_role');

        if (!$roleCode) {
            $roleCode = 'users';
        }

        $role = Role::where('code',$roleCode)->first();

        $user = User::create($validated);
        if ($user) {

            $user->assignRole($role);

            if (Config::get('elfcms.basic.email_confirmation')) {
                $user->setConfirmationToken();
                event(new SomeMailEvent('userregisterconfirm',['view'=>'elfcms::emails.events.register-confirm','to'=>$validated['email'],'params'=>['confirm_token'=>$user->confirm_token,'email'=>$user->email]],$user));
                $message = Lang::get('elfcms::default.email_confirmation');
                if (!$message) {
                    $message = 'A verification link has been sent to your email account';
                }
                return redirect(route('account.login'))->with('toemailconfirm',$message);
            }

            Auth::login($user);
            //return redirect(route('user.private'))->withErrors([
            return redirect()->intended('/')->withErrors([
                'err' => $user
            ]);
        }

        return redirect(route('account.register'))->withErrors([
            'formError' => Lang::get('elfcms::default.error_of_creating_user')
        ]);
    }

    public function confirm(Request $request)
    {
        $user = User::where(['email'=>$request->email,'confirm_token'=>$request->token])->first();
        if ($user && $user->id > 0) {

            $user->is_confirmed = 1;
            $user->email_verified_at = time();
            $user->save();

            //return view('elfcms::public.account.confirm-email')->with('success',Lang::get('elfcms::default.email_confirmation_success'));
            return redirect(route('account.confirmation'))->with('success',Lang::get('elfcms::default.email_confirmation_success'));

        }
        //return redirect(route('user.confirm-email'))->withErrors(['error'=>'Error of email confirmation']);
        //return view('elfcms::public.account.confirm-email')->with('error',Lang::get('elfcms::default.error_of_email_confirmation'));
        return redirect(route('account.confirmation'))->with('error',Lang::get('elfcms::default.error_of_email_confirmation'));
    }

    public function confirmation()
    {
        return view('elfcms::public.account.confirm-email',[
            'page' => [
                'title' => Lang::get('elfcms::default.registration_confirmation')
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Elfcms\Elfcms\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::get();
        $userRolesIds = [];
        foreach ($user->roles as $role) {
            $userRolesIds[] = $role->id;
        }
        if (empty($user->data)) {
            $user->data = [
                'first_name' => null,
                'second_name' => null,
                'last_name' => null,
                'zip_code' => null,
                'country' => null,
                'city' => null,
                'street' => null,
                'house' => null,
                'full_address' => null,
                'phone_code' => null,
                'phone_number' => null,
                'photo' => null,
                'thumbnail' => null
            ];
        }
        return view('elfcms::public.account.edit',[
            'page' => [
                'title' => Lang::get('elfcms::default.edit_account')
            ],
            'user' => $user,
            'roles' => $roles,
            'user_roles' => $userRolesIds
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Elfcms\Elfcms\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $user = Auth::user();

        if (!empty($request->old_password) || !empty($request->password) || !empty($request->password_confirmation)) {
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:' . $this->passwordLength . '|confirmed'
            ]);
            if (Hash::check($validated['current_password'],$user->password)) {
                $user->password = Hash::make($request->password);
                if ($user->save()) {
                    return redirect(route('account.index'))->with('passwordsaved',__('elfcms::default.password_changed_successfully'));
                }
                else {
                    return redirect(route('account.index'))->with('passchangeerror',__('elfcms::default.password_change_error'));
                }
            }
            else {
                return redirect(route('account.index'))->with('incorrectpassword',__('elfcms::default.current_password_is_incorrect'));
            }

        }
        else {
            /* $validated = $request->validate([
                'email' => 'required|email'
            ]); */

            /* if (User::where('email',$validated['email'])->where('id','<>',$user->id)->first()) {
                return redirect(route('account.edit'))->withErrors([
                    'email' => Lang::get('elfcms::default.user_already_exists')
                ]);
            }

            $user->email = $validated['email'];

            $user->save(); */

            $dataValidated = Validator::make($request->data,[
                'first_name' => 'nullable',
                //'second_name' => 'nullable',
                'last_name' => 'nullable',
                //'zip_code' => 'nullable|numeric|max:4',
                //'country' => 'nullable',
                //'city' => 'nullable',
                //'street' => 'nullable',
                //'house' => 'nullable',
                //'full_address' => 'nullable',
                //'phone_code' => 'nullable|numeric|max:4',
                //'phone_number' => 'nullable|numeric|max:15',
                'photo' => 'nullable|file|max:1024',
                //'thumbnail' => 'nullable|file|max:128'
            ])->validate();

            /* if (!empty($request->file('data')['thumbnail'])) {
                $thumb = $request->file('data')['thumbnail']->store('public/elfcms/users/avatars/thumbnails');
                $thumb = str_ireplace('public/','/storage/',$thumb);
            } */
            if (!empty($request->file('data')['photo'])) {
                $photo = $request->file('data')['photo']->store('public/elfcms/users/avatars/photos');
                $photo = str_ireplace('public/','/storage/',$photo);
            }

            //$dataValidated['thumbnail'] = null;
            $dataValidated['photo'] = null;
            /* if (!empty($thumb)) {
                $dataValidated['thumbnail'] = $thumb;
            }
            else {
                $dataValidated['thumbnail'] = $request->data['thumbnail_path'];
            } */
            if (!empty($photo)) {
                $dataValidated['photo'] = $photo;
            }
            else {
                $dataValidated['photo'] = $request->data['photo_path'];
            }
            if ($user->data) {
                UserData::where('id',$user->data->id)->update($dataValidated);
            }
            else {
                $user->data()->create($dataValidated);
            }

            return redirect(route('account.index'))->with('useredited',__('elfcms::default.account_edited_successfully'));
        }

    }
}
