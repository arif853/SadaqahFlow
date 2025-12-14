<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.users.role.index',['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('status',['success', 'Role Added Successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return response()->json(['status' => 200, 'role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role_name' => 'required|string'
        ]);

        $role->update([
            'name' => $request->role_name,
        ]);

        Session::flash('success', 'Role Updated Successfully.');
        return response()->json(['status' => 200]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        Session::flash('success','Role deleted Successfully.');

        return redirect()->back();
    }

    public function addPermission($roleId)
    {
        function parsePermissionName($permissionName)
        {
            $actions = ['create', 'update', 'delete', 'view', 'show'];
            foreach ($actions as $action) {
                if (strpos($permissionName, $action) !== false) {
                    $category = trim(str_replace($action, '', $permissionName));
                    return [
                        'action' => $action,
                        'category' => $category,
                    ];
                }
            }
            return [
                'action' => 'Other',
                'category' => $permissionName,
            ];
        }

        $role = Role::findOrFail($roleId);
        $permissions = Permission::get();

        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parsed = parsePermissionName($permission->name);
            return $parsed['category']; // Group by category
        })->map(function ($group) {
            return $group->groupBy(function ($permission) {
                $parsed = parsePermissionName($permission->name);
                return $parsed['action']; // Group by action within category
            });
        });
        // $rolePermission = $role->permissions->pluck('name');
        $rolePermission = $role->permissions;

        return view('admin.users.give-role-permission',compact('role','permissions','groupedPermissions','rolePermission'));
    }

    public function addPermissionToRole(Request $request, $roleId)
    {
        $request->validate([
            'permission' => 'required',
        ]);

        try{
            $role = Role::findById($roleId);
            $role->syncPermissions($request->permission);

            Session::flash('status',['type' => 'success','message' => 'Permission Assigned successfully']);
            return redirect()->back();
        }catch(\Exception $e){
            Session::flash('status',['type' => 'danger','message' => $e->getMessage()]);
            return redirect()->back();
        }

    }
}
