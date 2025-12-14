<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProgramType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProgramTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programTypes = ProgramType::all();

        return view('admin.program-type.index', compact('programTypes'));
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
            'name' => 'required|string',
            'date' => 'required|date',
        ],[
            'name.required' => 'অনুষ্ঠানের নাম প্রয়োজন',
        ]);

        try {
            DB::beginTransaction();

            $validatedData['status'] = '1';
            ProgramType::create($validatedData);

            DB::commit();

            return redirect()->back()->with('status', ['type' => 'success', 'message' => 'নতুন অনুষ্ঠান যোগ করা সফল হয়ছে।']);

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $program = ProgramType::findOrFail($id);
        return response()->json($program);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $program = ProgramType::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
        ]);

        $validatedData['status'] = $request->status ? '1' : '0';
        $program->update($validatedData);
        // $member->save();
        session()->flash('status', ['type' => 'success', 'message' => 'অনুষ্ঠান সফলভাবে আপডেট হয়েছে']);
        return response()->json(['status' => 'success', 'message' => 'অনুষ্ঠান সফলভাবে আপডেট হয়েছে']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = ProgramType::findOrFail($id);
        $program->delete();
        return response()->json(['status' => 'success', 'message' => 'অনুষ্ঠান টাইপ সফলভাবে ডিলেট হয়েছে']);
    }

    public function status(string $id)
    {
        $program = ProgramType::findOrFail($id);
        $program->status = !$program->status;
        $program->save();
        // return response()->json(['status' => 'success', 'message' => 'জাকের সফলভাবে স্টেটাস আপডেট হয়েছে']);
        return redirect()->back()->with('status', ['type' => 'success', 'message' => 'অনুষ্ঠান সফলভাবে স্টেটাস আপডেট হয়েছে']);
    }
}
