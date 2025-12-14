<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Member;
use App\Models\Khedmot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KhedmotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('status',1)->get();
        if(Auth::user()->hasRole(['Super Admin','Admin'])){
            $members = Member::where('status',1)->get();
            $khedmots = Khedmot::with('member','program')->orderBy('date','desc')->get();
        }else{
            $members = auth()->user()->members;
            $khedmots = Khedmot::with('member','program')->orderBy('date','desc')->where('user_id',auth()->user()->id)->get();
        }
        return view('admin.khedmots.index',compact('khedmots','members','users'));
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
        try {
            DB::beginTransaction();
            $request->validate([
                'date' => 'required|date',
                'program_id' => 'required|exists:program_types,id',
                'khedmot_amount' => 'nullable|numeric|min:0',
                'manat_amount' => 'nullable|numeric|min:0',

            ], [
                'date.required' => 'তারিখ প্রয়োজন',
                'program_id.required' => 'প্রোগ্রামের নাম প্রয়োজন',
                'khedmot_amount.required' => 'খেদমত পরিমাণ প্রয়োজন',
                'khedmot_amount.numeric' => 'খেদমত পরিমাণ সংখ্যা হতে হবে',
                'khedmot_amount.min' => 'খেদমত পরিমাণ 0 এর চেয়ে বেশি হতে হবে',
                'manat_amount.numeric' => 'মানত পরিমাণ সংখ্যা হতে হবে',
                'manat_amount.min' => 'মানত পরিমাণ 0 এর চেয়ে বেশি হতে হবে',

            ]);
            $request->except('_token', '_method');
            $existingKhedmot = Khedmot::where('program_id', $request->program_id)
                ->where('member_id', $request->member_id)->where('is_collected', false)
                ->first();
            //check if khedmot already exists for the member and program
            if ($existingKhedmot) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'member_id' => 'এই সদস্যের জন্য ইতিমধ্যে এই অনুষ্ঠানের খেদমত রেকর্ড করা হয়েছে। দয়া করে আপডেট করুন।',
                ]);
            }
            //create new khedmot record
            $khedmot = Khedmot::create([
                'date' => $request->date,
                'member_id' => $request->member_id,
                'program_id' => $request->program_id,
                'other_program_name' => $request->other_program_name,
                'khedmot_amount' => $request->khedmot_amount,
                'manat_amount' => $request->manat_amount,

                'comment' => $request->comment,
                'user_id' => auth()->user()->id,
                'is_collected' => false,
            ]);
            DB::commit();
            return redirect()->back()->with('status', [
                'type' => 'success',
                'message' => 'খেদমত যোগ করা সফল হয়েছে।'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = implode('<br>', $e->validator->errors()->all());
            DB::rollBack();
            Log::error('Khedmot Creation Error: '.$e->getMessage());
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
        $khedmot = Khedmot::with('member','user','program')->findOrFail($id);
        return response()->json($khedmot);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $khedmot = Khedmot::findOrFail($id);
        return response()->json($khedmot);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $khedmot = Khedmot::findOrFail($id);
            $request->validate([
                'date' => 'required|date',
                'program_id' => 'required|exists:program_types,id',
                'khedmot_amount' => 'required|numeric|min:0',

                ], [
                    'date.required' => 'তারিখ প্রয়োজন',
                    'program_id.required' => 'প্রোগ্রামের নাম প্রয়োজন',
                    'khedmot_amount.required' => 'খেদমত পরিমাণ প্রয়োজন',
                    'khedmot_amount.numeric' => 'খেদমত পরিমাণ সংখ্যা হতে হবে',
                    'khedmot_amount.min' => 'খেদমত পরিমাণ ০ এর চেয়ে বেশি হতে হবে',
                ]);
            $request->except('_token', '_method');
            $khedmot->update($request->all());
            session()->flash('status', [
                'type' => 'success',
                'message' => 'খেদমত আপডেট করা সফল হয়েছে।'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'খেদমত আপডেট করা সফল হয়েছে।'
            ]);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $khedmot = Khedmot::findOrFail($id);
        $khedmot->delete();
        session()->flash('status', [
            'type' => 'success',
            'message' => 'খেদমত ডিলিট করা সফল হয়েছে।'
        ]);
        return response()->json(['status' => 'success', 'message' => 'খেদমত ডিলিট করা সফল হয়েছে।']);
    }

    public function search(Request $request)
    {
        // dd($request->all());
        $date = $request->date;
        $name = $request->name;
        $userID = $request->userid;
        $khedmots = Khedmot::query();
        if(Auth::user()->hasRole(['Super Admin','Admin'])){
            if (!empty($date)) {
                $khedmots->where('date', $date);
            }
            if (!empty($name)) {
                $khedmots->whereHas('member', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%')
                    ->orWhere('kollan_id', 'like', '%' . $name . '%');
                });
            }
            if (!empty($userID)) {
                $khedmots->where('user_id', $userID);
            }
            $khedmots = $khedmots->with('member','user','program')->orderBy('date','desc')->get();
        }else{
            if (!empty($date)) {
                $khedmots->where('date', $date);
            }
            if (!empty($name)) {
                $khedmots->whereHas('member', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%')
                    ->orWhere('kollan_id', 'like', '%' . $name . '%');
                });
            }
            $khedmots = $khedmots->with('member','user','program')
            ->orderBy('date','desc')
            ->where('user_id',auth()->user()->id)
            ->get();
        }

        return response()->json($khedmots);
    }

    public function kolyanStore(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'date' => 'required|date',
                'member_id' => 'required|exists:members,id',
                'kalyan_amount' => 'required|numeric|min:0',
            ], [
                'date.required' => 'তারিখ প্রয়োজন',
                'member_id.required' => 'জাকের যোগ করতে হবে',
                'kalyan_amount.required' => 'কল্যাণের পরিমাণ প্রয়োজন',
                'kalyan_amount.numeric' => 'কল্যাণের পরিমাণ সংখ্যা হতে হবে',
                'kalyan_amount.min' => 'কল্যাণের পরিমাণ 0 এর চেয়ে বেশি হতে হবে',
            ]);
            $request->except('_token', '_method');

            //create new khedmot record
            $khedmot = Khedmot::create([
                'date' => $request->date,
                'member_id' => $request->member_id,
                'kalyan_amount' => $request->kalyan_amount,
                'comment' => $request->comment,
                'user_id' => auth()->user()->id,
                'is_collected' => false,
            ]);

            DB::commit();

            return redirect()->back()->with('status', [
                'type' => 'success',
                'message' => 'কল্যাণ যোগ করা সফল হয়েছে।'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = implode('<br>', $e->validator->errors()->all());
            DB::rollBack();
            Log::error('kollan Creation Error: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('status', [
                    'type' => 'danger',
                    'message' => $errorMessages
                ]);
        }
    }

    public function kolyanUpdate(Request $request, string $id)
    {
        try {
            $khedmot = Khedmot::findOrFail($id);
            $request->validate([
                'date' => 'required|date',
                'kalyan_amount' => 'nullable|numeric|min:0',
                ], [
                    'date.required' => 'তারিখ প্রয়োজন',
                    'kalyan_amount.required' => 'কল্যাণ পরিমাণ প্রয়োজন',
                    'kalyan_amount.numeric' => 'কল্যাণ পরিমাণ সংখ্যা হতে হবে',
                    'kalyan_amount.min' => 'কল্যাণ পরিমাণ ০ এর চেয়ে বেশি হতে হবে',
                ]);
            $request->except('_token', '_method');
            $khedmot->update($request->all());
            session()->flash('status', [
                'type' => 'success',
                'message' => 'কল্যাণ আপডেট করা সফল হয়েছে।'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'কল্যাণ আপডেট করা সফল হয়েছে।'
            ]);
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

    public function rentStore(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'date' => 'required|date',
                'member_id' => 'required|exists:members,id',
                'rent_amount' => 'nullable|numeric|min:0',
            ], [
                'date.required' => 'তারিখ প্রয়োজন',
                'member_id.required' => 'জাকের যোগ করতে হবে',
                'rent_amount.numeric' => 'রেন্ট পরিমাণ সংখ্যা হতে হবে',
                'rent_amount.min' => 'রেন্ট পরিমাণ 0 এর চেয়ে বেশি হতে হবে',
            ]);
            $request->except('_token', '_method');

            //create new khedmot record
            $khedmot = Khedmot::create([
                'date' => $request->date,
                'member_id' => $request->member_id,
                'rent_amount' => $request->rent_amount,
                'comment' => $request->comment,
                'user_id' => auth()->user()->id,
                'is_collected' => false,
            ]);
            DB::commit();
            return redirect()->back()->with('status', [
                'type' => 'success',
                'message' => 'ভাড়া যোগ করা সফল হয়েছে।'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = implode('<br>', $e->validator->errors()->all());
            DB::rollBack();
            Log::error('Rent Creation Error: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('status', [
                    'type' => 'danger',
                    'message' => $errorMessages
                ]);
        }
    }

    public function rentUpdate(Request $request, string $id)
    {
        try {
            $khedmot = Khedmot::findOrFail($id);
            $request->validate([
                'date' => 'required|date',
                'rent_amount' => 'nullable|numeric|min:0',
                ], [
                    'date.required' => 'তারিখ প্রয়োজন',
                    'rent_amount.required' => 'ভাড়া পরিমাণ প্রয়োজন',
                    'rent_amount.numeric' => 'ভাড়া পরিমাণ সংখ্যা হতে হবে',
                    'rent_amount.min' => 'ভাড়া পরিমাণ 0 এর চেয়ে বেশি হতে হবে',
                ]);
            $request->except('_token', '_method');
            $khedmot->update($request->all());
            session()->flash('status', [
                'type' => 'success',
                'message' => 'ভাড়া আপডেট করা সফল হয়েছে।'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'ভাড়া আপডেট করা সফল হয়েছে।'
            ]);
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
}
