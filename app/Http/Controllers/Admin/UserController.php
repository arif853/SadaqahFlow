<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use App\Models\Member;
use App\Models\MemberAssign;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'Super Admin');
            })
            ->orderBy('id', 'desc')
            ->get();

        $roles = Role::where('name', '!=', 'Super Admin')->get();
        $members = Member::where('status', 1)
                    ->whereDoesntHave('memberAssigns') // Ensure no assignments exist for the member
                    ->get();

        return view('admin.users.index', compact('users', 'roles', 'members'));
    }

    public function getMember($id)
    {

        $user = User::findOrFail($id);
        $members = $user->members;
        return response()->json(['user' => $user, 'members' => $members]);
    }

    public function assignMember($id, Request $request)
    {
        $memberIds = collect($request->members)->map(fn ($memberId) => (int) $memberId)->unique()->values();

        $alreadyAssignedIds = MemberAssign::where('user_id', $id)
            ->whereIn('member_id', $memberIds)
            ->pluck('member_id');

        $newRows = $memberIds->diff($alreadyAssignedIds)->map(fn ($memberId) => [
            'user_id' => $id,
            'member_id' => $memberId,
            'created_at' => now(),
            'updated_at' => now(),
        ])->values()->all();

        if (!empty($newRows)) {
            MemberAssign::insert($newRows);
        }
        session()->flash('status', ['type' => 'success', 'message' => 'সদস্য সফলভাবে যোগ করা হয়েছে']);
        return response()->json(['status' => 'success', 'message' => 'সদস্য সফলভাবে যোগ করা হয়েছে']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'address' => 'nullable|string|max:255', // Optional field
            'phone' => 'required|string|min:8|max:15', // Adjusted phone number validation
            'email' => 'nullable|email|unique:users|max:255', // Optional field with email validation
            'bloodType' => 'nullable|string|max:3', // Optional field for blood type
            'password' => 'required|string|min:6', // Enforce password minimum length and confirmation
            'role' => 'required',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'bloodType' => $request->bloodType,
                'password' => Hash::make($request->password),
                'status' => $request->has('status'), // Set status based on checkbox value
            ]);
            $user->assignRole($request->role);
            return redirect()->route('users.index')->with('status', ['type' => 'success', 'message' => ' নতুন ইউজার যোগ করা সফল হয়ছে।']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('status', ['type' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $members = $user->members;
        return response()->json(['user' => $user, 'members' => $members]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $user->load('roles');
        return response()->json(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|min:8|max:15',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'bloodType' => 'nullable|string|max:3',
            'password' => 'nullable|string|min:6',
            'role' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'bloodType' => $request->bloodType,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->role);

        session()->flash('status', ['type' => 'success', 'message' => 'ইউজার সফলভাবে আপডেট হয়েছে']);
        return response()->json(['status' => 'success', 'message' => 'ইউজার সফলভাবে আপডেট হয়েছে']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        session()->flash('status', ['type' => 'success', 'message' => 'ইউজার সফলভাবে ডিলেট হয়েছে']);
        return response()->json(['status' => 'success', 'message' => 'ইউজার সফলভাবে ডিলেট হয়েছে']);
    }

    public function status($id){
        $user = User::findOrFail($id);
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();
        return redirect()->route('users.index')->with('status', ['type' => 'success', 'message' => 'ইউজার স্ট্যাটাস সফলভাবে আপডেট হয়েছে']);
    }

    public function assignMemberRemove($memberId, $userId){
        $user = User::findOrFail($userId);
        $user->removeMemberAssign($memberId);
        session()->flash('status', ['type' => 'success', 'message' => 'সদস্য সফলভাবে সরানো হয়েছে']);
        return response()->json(['status' => 'success', 'message' => 'সদস্য সফলভাবে সরানো হয়েছে']);
    }

    public function usersSearchByName(Request $request){
        $name = $request->name;
        $users = User::where('name', 'like', '%' . $name . '%')->with('roles')->get();
        return response()->json($users);
    }
}
