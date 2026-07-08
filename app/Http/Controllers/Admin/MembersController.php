<?php

namespace App\Http\Controllers\Admin;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // The card list is loaded and paginated client-side via the search
        // endpoint, so we no longer fetch every member up front.
        return view('admin.memebrs.index');
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nickName' => 'nullable|string|max:100',
            'father_name'=> 'nullable|string|max:255',
            'phone'=> 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'spouse_name'=> 'nullable|string|max:255',
            'kollan_id'=> 'required|string|max:50|unique:members,kollan_id',
            'kollan_khedmot' => 'nullable|min:0',
            'bloodType'=> 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ],[
            'name.required' => 'নাম প্রয়োজন',
            'kollan_id.required' => 'কল্যাণ নাম্বার প্রয়োজন',
            'kollan_id.unique' => 'এই কল্যাণ নাম্বার ইতিমধ্যে বিদ্যমান',
            'phone.regex' => 'সঠিক ফোন নাম্বার লিখুন',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $validatedData['kollan_id'] . '.webp';
                $imagePath = 'upload/images/' . $imageName;
                $fullPath = storage_path('app/public/' . $imagePath);

                // Ensure directory exists
                $directory = dirname($fullPath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Process image: resize to 300x300 (square avatar), optimize and convert to WebP
                Image::read($image)
                    ->cover(300, 300) // Crop and resize to square
                    ->toWebp(80) // Convert to WebP with 80% quality
                    ->save($fullPath);

                $validatedData['image'] = $imagePath;
            }

            $member = Member::create($validatedData);

            // if(!auth()->user()->hasRole(['Super Admin','Admin'])){
            //     auth()->user()->assignMember($member);
            // }

            DB::commit();

            // Quick-entry flow: return the new record so JS can prepend it without a full reload.
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'নতুন জাকের যোগ করা সফল হয়ছে।',
                    'member' => $member->load('user'),
                ]);
            }

            return redirect()->back()->with('status', ['type' => 'success', 'message' => 'নতুন জাকের যোগ করা সফল হয়ছে।']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = implode('<br>', $e->validator->errors()->all());

            return redirect()->back()
                ->withInput()
                ->with('status', [
                    'type' => 'danger',
                    'message' => $errorMessages
                ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Member::findOrFail($id);
        $khedmots = $member->khedmots;
        return view('admin.memebrs.show',compact('member', 'khedmots'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = Member::findOrFail($id);
        return response()->json($member);
        // return view('admin.memebrs.edit',compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        // Authorization check: only admins or assigned users can update
        if (!auth()->user()->isAdminLevel()) {
            $hasAccess = $member->memberAssigns()->where('user_id', auth()->id())->exists();
            if (!$hasAccess) {
                abort(403, 'Unauthorized action.');
            }
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nickName' => 'nullable|string|max:100',
            'father_name'=> 'nullable|string|max:255',
            'phone'=> 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'spouse_name'=> 'nullable|string|max:255',
            'kollan_id'=> 'required|string|max:50|unique:members,kollan_id,' . $id,
            'bloodType'=> 'nullable|string|max:10',
            'kollan_khedmot' => 'nullable|min:0',
            'image2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        if ($request->hasFile('image2')) {
            $image = $request->file('image2');
            $imageName = $validatedData['kollan_id'] . '.webp';
            $imagePath = 'upload/images/' . $imageName;
            $fullPath = storage_path('app/public/' . $imagePath);

            // Delete old image if exists
            if($member->image && Storage::disk('public')->exists($member->image)){
                Storage::disk('public')->delete($member->image);
            }

            // Ensure directory exists
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Process image: resize to 300x300 (square avatar), optimize and convert to WebP
            Image::read($image)
                ->cover(300, 300) // Crop and resize to square
                ->toWebp(80) // Convert to WebP with 80% quality
                ->save($fullPath);

            $validatedData['image'] = $imagePath;
        }
        $validatedData['status'] = $request->status ? '1' : '0';
        $member->update($validatedData);
        // $member->save();
        session()->flash('status', ['type' => 'success', 'message' => 'জাকের সফলভাবে আপডেট হয়েছে']);
        $member->load(['user', 'memberAssigns.user']);
        return response()->json(['status' => 'success', 'message' => 'জাকের সফলভাবে আপডেট হয়েছে', 'member' => $member]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);

        // Authorization check: only admins can delete
        if (!auth()->user()->isAdminLevel()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated image if exists
        if($member->image && Storage::disk('public')->exists($member->image)){
            Storage::disk('public')->delete($member->image);
        }

        $member->delete();
        return response()->json(['status' => 'success', 'message' => 'জাকের সফলভাবে ডিলেট হয়েছে']);
    }

    public function status(string $id)
    {
        $member = Member::findOrFail($id);

        // Authorization check: only admins can change status
        if (!auth()->user()->isAdminLevel()) {
            abort(403, 'Unauthorized action.');
        }

        $member->status = !$member->status;
        $member->save();
        return redirect()->back()->with('status', ['type' => 'success', 'message' => 'জাকের সফলভাবে স্টেটাস আপডেট হয়েছে']);
    }

    public function memberSearch(Request $request)
    {
        // Now doubles as the paginated browse endpoint: an empty term lists
        // everyone (respecting visibility), a term filters the same set.
        $request->validate([
            'term' => 'nullable|string|max:100'
        ]);

        $perPage = 24;

        $term = $request->filled('term')
            ? htmlspecialchars(strip_tags($request->input('term')), ENT_QUOTES, 'UTF-8')
            : null;

        $query = Member::query();

        if (auth()->user()->isAdminLevel()) {
            $query->with('user');
        } else {
            // Non-admins: restrict to members assigned to the logged-in user
            $query->whereHas('memberAssigns', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->with('memberAssigns.user');
        }

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                    ->orWhere('kollan_id', 'like', '%' . $term . '%')
                    ->orWhere('phone', 'like', '%' . $term . '%')
                    ->orWhere('kollan_khedmot', 'like', '%' . $term . '%');
            });
        }

        $total = (clone $query)->count();
        $members = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'data' => $members->items(),
            'meta' => [
                'current_page' => $members->currentPage(),
                'last_page' => $members->lastPage(),
                'total' => $total,
            ],
        ]);
    }

}

