<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Role;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    //TODO: Make NOT resource controller
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users = User::paginate(30);
        $roles = Role::paginate(30);
        $notEdit = ['admin','users'];
        return view('elfcms::admin.users.roles.index',[
            'page' => [
                'title' => 'Roles',
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
                'title' => 'Create role'
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

        $role = Role::create($validated);

        if ($role) {
            return redirect(route('admin.users.roles.edit',['role'=>$role->id]))->with('rolecreated','Role created successfully');
        }
        return redirect(route('admin.users.roles.create'))->withErrors([
            'code' => 'Role already exists'
        ]);
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
