<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Role;
use Elfcms\Elfcms\Models\RoleUser;
use Elfcms\Elfcms\Models\User;
use Elfcms\Elfcms\Models\UserData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trend = 'asc';
        $order = 'id';
        $roleId = null;
        $role = null;
        $search = $request->search ?? '';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        if (!empty($request->count)) {
            $count = intval($request->count);
        }
        if (empty($count)) {
            $count = 30;
        }

        if (!empty($request->role)) {
            $roleId = intval($request->role);
        }

        if (!empty($roleId)) {
            /* if (!empty($search)) {
                $users = User::whereHas('roles',function(Builder $query) use ($roleId){
                    $query->where('role_id', $roleId);
                })->orderBy($order, $trend)->paginate($count);
                $role = Role::where('id',$roleId)->first()
            } */
            $users = User::whereHas('roles',function(Builder $query) use ($roleId){
                $query->where('role_id', $roleId);
            })->orderBy($order, $trend)->paginate($count);
            $role = Role::where('id',$roleId)->first();
        }
        else {
            if (!empty ($search)) {
                $users = User::where('name','like',"%{$search}%")->orWhere('email','like',"%{$search}%")->orderBy($order, $trend)->paginate($count);
            }
            else {
                $users = User::orderBy($order, $trend)->paginate($count);
            }

        }

        return view('elfcms::admin.users.index',[
            'page' => [
                'title' => __('elfcms::default.users'),
                'current' => url()->current(),
            ],
            'users' => $users,
            'role' => $role,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();

        $defaultRoleCode = Config::get('elfcms.elfcms.user_default_role');

        if (!$defaultRoleCode) {
            $defaultRoleCode = 'users';
        }
        return view('elfcms::admin.users.create',[
            'page' => [
                'title' => __('elfcms::default.create_new_user')
            ],
            'roles' => $roles,
            'default_role_code' => $defaultRoleCode
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
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);
        if (User::where('email',$validated['email'])->exists()) {
            return redirect(route('admin.users.create'))->withErrors([
                'email' => 'User already exists'
            ]);
        }

        $user = User::create($validated);

        if ($user) {


            $roles = $request->role;
            if (empty($roles)) {
                $roleCode = Config::get('elfcms.elfcms.user_default_role');
                if (!$roleCode) {
                    $roleCode = 'users';
                }
                $defualtRole = Role::where('code',$roleCode)->first();
                $user->assignRole($defualtRole);
            }
            else {
                foreach ($roles as $roleCode) {
                    $role = Role::where('code',$roleCode)->first();
                    $user->assignRole($role);
                }
            }

            $user->is_confirmed = empty($request->is_confirmed) ? 0 : 1;
            $user->save();

            return redirect(route('admin.users.edit',['user'=>$user->id]))->with('usercreated','User created successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Elfcms\Elfcms\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('elfcms::admin.users.show',[
            'page' => [
                'title' => __('elfcms::default.show_user')
            ]
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
        //dd(UserData::where('id',$user->data->id)->first());
        //dd(UserData::find($user->data->id));
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
        return view('elfcms::admin.users.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_user')
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
    public function update(Request $request, User $user)
    {

        //dd($request->file('data')['thumbnail']);
        /* dd(Validator::make($request->data, [
            'thumbnail' => 'file|size:50',
        ])->validate()); */
        //dd($request->data);

        if (isset($request->notedit)) {
            $user->is_confirmed = empty($request->is_confirmed) ? 0 : $request->is_confirmed;

            $userAction = empty($request->is_confirmed) ? 'deactivated' : 'activated';

            $user->save();

            return redirect(route('admin.users'))->with('useredited','User ' . $userAction . ' successfully');
        }

        $defaultRoleCode = Config::get('elfcms.elfcms.user_default_role');
        if (!$defaultRoleCode) {
            $defaultRoleCode = 'users';
        }
        $defaultRole = Role::where('code',$defaultRoleCode)->first();
        $defaultRoleId = $defaultRole->id;
        if (empty($defaultRoleId)) {
            $defaultRoleId = 2;
        }

        $roles = $request->role;
        if (empty($roles)) {
            $roles = [$defaultRoleId];
        }

        //$userRolesCodes = [];
        $userRolesIds = [];
        foreach ($user->roles as $role) {
            //$userRolesCodes[] = $role->code;
            $userRolesIds[] = $role->id;
            if (!in_array($role->id,$roles)) {
                $user->roles()->detach($role->id);
                $roleKey = array_search($role->id,$roles);
                if ($roleKey !== false) {
                    unset($roles[$roleKey]);
                }
            }
        }

        if (!empty($roles)) {
            foreach ($roles as $roleId) {
                if (!in_array($roleId,$userRolesIds)) {
                    $user->roles()->attach($roleId);
                }
            }
        }

        if (empty($request->password) && empty($request->password_confirmation)) {
            $validated = $request->validate([
                'email' => 'required|email'
            ]);
        }
        else {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed'
            ]);

            $user->password = Hash::make($request->password);
        }


        if (User::where('email',$validated['email'])->where('id','<>',$user->id)->first()) {
            return redirect(route('admin.users.create'))->withErrors([
                'email' => 'User already exists'
            ]);
        }

        $user->email = $validated['email'];

        $user->is_confirmed = empty($request->is_confirmed) ? 0 : 1;

        $result = $user->save();
        if (!$result) {
            return redirect(route('admin.users.edit',['user'=>$user->id]))->withErrors([
                'email' => __('elfcms::default.error_of_editing_user')
            ]);
        }
        $user->setConfirmationToken();
        //Data

        /* $dataValidated = $request->validate([
            'data.first_name' => 'nullable',
            'data.second_name' => 'nullable',
            'data.last_name' => 'nullable',
            'data.zip_code' => 'nullable|numeric|max:4',
            'data.country' => 'nullable',
            'data.city' => 'nullable',
            'data.street' => 'nullable',
            'data.house' => 'nullable',
            'data.full_address' => 'nullable',
            'data.phone_code' => 'nullable|numeric|max:4',
            'data.phone_number' => 'nullable|numeric|max:15',
            'data.photo' => 'nullable|file|max:512',
            'data.thumbnail' => 'nullable|file|max:128'
        ]); */
        $dataValidated = Validator::make($request->data,[
            'first_name' => 'nullable',
            'second_name' => 'nullable',
            'last_name' => 'nullable',
            'zip_code' => 'nullable|numeric|max:4',
            'country' => 'nullable',
            'city' => 'nullable',
            'street' => 'nullable',
            'house' => 'nullable',
            'full_address' => 'nullable',
            'phone_code' => 'nullable|numeric|max:4',
            'phone_number' => 'nullable|numeric|max:15',
            'photo' => 'nullable|file|max:512',
            'thumbnail' => 'nullable|file|max:128'
        ])->validate();

        if (!empty($request->file('data')['thumbnail'])) {
            $thumb = $request->file('data')['thumbnail']->store('public/elfcms/users/avatars/thumbnails');
            $thumb = str_ireplace('public/','/storage/',$thumb);
        }
        if (!empty($request->file('data')['photo'])) {
            $photo = $request->file('data')['photo']->store('public/elfcms/users/avatars/photos');
            $photo = str_ireplace('public/','/storage/',$photo);
        }

        $dataValidated['thumbnail'] = null;
        $dataValidated['photo'] = null;
        if (!empty($thumb)) {
            $dataValidated['thumbnail'] = $thumb;
        }
        else {
            $dataValidated['thumbnail'] = $request->data['thumbnail_path'];
        }
        if (!empty($photo)) {
            $dataValidated['photo'] = $photo;
        }
        else {
            $dataValidated['photo'] = $request->data['photo_path'];
        }

        if ($user->data) {
            /* $userData = UserData::where('id',$user->data->id)->first();
            foreach ($dataValidated as $field => $value) {
                $userData[$field] = $value;
            } */
            UserData::where('id',$user->data->id)->update($dataValidated);
        }
        else {
            $user->data()->create($dataValidated);
        }

        return redirect(route('admin.users.edit',['user'=>$user->id]))->with('useredited','User edited successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Elfcms\Elfcms\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //dd($user->roles);

        /* DB::beginTransaction();

        if ($user->data) {
            if (!$user->data()->delete()) {
                DB::rollBack();
                return redirect(route('admin.users'))->withErrors(['userdelerror'=>'Error of user data deleting']);
            }
        }
        if ($user->roles) {
            foreach ($user->roles as $role) {
                if (RoleUser::find($role->id)->delete()) {
                    DB::rollBack();
                    return redirect(route('admin.users'))->withErrors(['userdelerror'=>'Error of roles of user deleting']);
                }
            }
        }

        if (!$user->delete()) {
            DB::rollBack();
            return redirect(route('admin.users'))->withErrors(['userdelerror'=>'Error of user deleting']);
        }

        DB::commit(); */
        if (!$user->delete()) {
            return redirect(route('admin.users'))->withErrors(['userdelerror'=>'Error of user deleting']);
        }

        return redirect(route('admin.users'))->with('userdeleted','User deleted successfully');
    }
}
