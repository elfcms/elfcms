<?php

namespace Elfcms\Elfcms\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Aux\Admin\Permissions;
use Elfcms\Elfcms\Models\Role;
use Elfcms\Elfcms\Models\RolePermission;
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
        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $roles = Role::orderBy($order, $trend)->paginate(30);
        $notEdit = ['admin', 'users'];
        return view('elfcms::admin.user.roles.index', [
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
        $accessRoutes = Permissions::routes();
        return view('elfcms::admin.user.roles.create', [
            'page' => [
                'title' => __('elfcms::default.create_new_role')
            ],
            'accessRoutes' => $accessRoutes
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
        if (Role::where('name', $validated['name'])->exists()) {
            return redirect(route('admin.user.roles.create'))->withErrors([
                'name' => 'Role already exists'
            ]);
        }
        if (Role::where('code', $validated['code'])->exists()) {
            return redirect(route('admin.user.roles.create'))->withErrors([
                'code' => 'Role already exists'
            ]);
        }

        $validated['description'] = $request->description;

        $role = Role::create($validated);

        if ($role) {

            if (!empty($request->permissions)) {

                $routes = Permissions::routes();

                $permRows = [];

                foreach ($routes as $moduleRoutes) {
                    foreach ($moduleRoutes as $routeName => $routeData) {
                        $accessName = str_replace('.', '_', $routeName);
                        $read = 0;
                        $write = 0;
                        if (!empty($request->permissions[$accessName])) {
                            if (!empty($request->permissions[$accessName]['read'])) $read = 1;
                            if (!empty($request->permissions[$accessName]['write'])) $write = 1;
                        }
                        $permRows[] = [
                            'role_id' => $role->id,
                            'route' => $routeName,
                            'read' => $read,
                            'write' => $write,
                        ];
                    }
                }

                RolePermission::upsert(
                    $permRows,
                    ['role_id', 'route'],
                    ['read', 'write']
                );
            }

            return redirect(route('admin.user.roles.edit', ['role' => $role]))->with('rolecreated', 'Role created successfully');
        }
        return redirect(route('admin.user.roles.create'))->withErrors([
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
        $accessRoutes = Permissions::roleRoutes($role);
        return view('elfcms::admin.user.roles.edit', [
            'page' => [
                'title' => __('elfcms::default.edit_role')
            ],
            'role' => $role,
            'accessRoutes' => $accessRoutes,
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

        if (!empty($request->permissions)) {

            $routes = Permissions::routes();

            $permRows = [];


            foreach ($routes as $moduleRoutes) {
                foreach ($moduleRoutes as $routeName => $routeData) {
                    $accessName = str_replace('.', '_', $routeName);
                    $read = 0;
                    $write = 0;
                    if (!empty($request->permissions[$accessName])) {
                        if (!empty($request->permissions[$accessName]['read'])) $read = 1;
                        if (!empty($request->permissions[$accessName]['write'])) $write = 1;
                    }
                    $row = RolePermission::updateOrCreate(
                        [
                            'role_id' => $role->id,
                            'route' => $routeName,
                        ],
                        [
                            'read' => $read,
                            'write' => $write,
                        ]
                    );
                    $permRows[] = $row;
                }
            }
        }

        return redirect(route('admin.user.roles.edit', ['role' => $role]))->with('roleedited', 'Role edited successfully');
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
            return redirect(route('admin.user.roles'))->withErrors(['roledelerror' => 'Error of role deleting']);
        }

        return redirect(route('admin.user.roles'))->with('roledeleted', 'Role deleted successfully');
    }
}
