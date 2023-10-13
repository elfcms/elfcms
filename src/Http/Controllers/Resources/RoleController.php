<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$users = User::paginate(30);
        //dd(Auth::user());
        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $roles = Role::orderBy($order, $trend)->paginate(30);
        $notEdit = ['admin','users'];
        return view('elfcms::admin.users.roles.index',[
            'page' => [
                'title' => __('elfcms::default.roles'),
                'current' => url()->current(),
            ],
            'notedit' => $notEdit,
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('elfcms::admin.users.roles.create',[
            'page' => [
                'title' => __('elfcms::default.create_new_role')
            ]
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
            'name' => 'required',
            'code' => 'required'
        ]);
        if (Role::where('name',$validated['name'])->exists()) {
            return redirect(route('admin.users.roles.create'))->withErrors([
                'name' => 'Role already exists'
            ]);
        }
        if (Role::where('code',$validated['code'])->exists()) {
            return redirect(route('admin.users.roles.create'))->withErrors([
                'code' => 'Role already exists'
            ]);
        }

        $validated['description'] = $request->description;

        $role = Role::create($validated);

        if ($role) {
            return redirect(route('admin.users.roles.edit',['role'=>$role->id]))->with('rolecreated','Role created successfully');
        }
        return redirect(route('admin.users.roles.create'))->withErrors([
            'rolecreateerror' => 'Role creating error'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Elfcms\Elfcms\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Elfcms\Elfcms\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('elfcms::admin.users.roles.edit',[
            'page' => [
                'title' => __('elfcms::default.edit_role')
            ],
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Elfcms\Elfcms\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required'
        ]);

        $role->name = $validated['name'];
        $role->code = $validated['code'];
        $role->description = empty($request['description']) ? '' : $request['description'];

        $role->save();

        return redirect(route('admin.users.roles.edit',['role'=>$role->id]))->with('roleedited','Role edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Elfcms\Elfcms\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if (!$role->delete()) {
            return redirect(route('admin.users.roles'))->withErrors(['roledelerror'=>'Error of role deleting']);
        }

        return redirect(route('admin.users.roles'))->with('roledeleted','Role deleted successfully');
    }
}
