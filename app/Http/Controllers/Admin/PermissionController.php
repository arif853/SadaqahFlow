<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.users.permission.index',compact('permissions'));
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
            'name' => 'required|string|unique:permissions,name'
        ]);

        Permission::create([
            'name' => strtolower($request->name),
        ]);

        session()->flash('status',['success' => 'Permission Created Successfully.']);
        return redirect()->back();
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
    public function edit(Permission $permission)
    {
        return response()->json(['status' => 200, 'permission' => $permission]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'permission_name' => 'required|string'
        ]);

        $permission->update([
            'name' => strtolower($request->permission_name),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Permission Updated Successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $permissionIds = $request->input('selected_permissions');
        dd($permissionIds);
        // Permission::whereIn('id', $permissionIds)->delete();

        // if ($request->ajax()) {
        //     return response()->json(['success' => 'Permissions deleted successfully.']);
        // }

        // Session::flash('success', 'Permissions deleted successfully.');
        // return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::find($id);
        $permission->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Permission deleted successfully.']);
        }

        session()->flash('status',['success' => 'Permission deleted Successfully.']);
        return redirect()->back();
    }



}
