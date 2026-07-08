<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Member;
use App\Models\Khedmot;
use App\Models\ProgramType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class KhedmotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(auth()->user()->can('view khedmot'), 403);
        $user = auth()->user();
        $users = User::where('status',1)->get();
        $members = $user->isAdminLevel() ? Member::where('status',1)->get() : $user->members;
        $programTypes = ProgramType::orderBy('date','desc')->get();
        $activeProgram = ProgramType::where('status', 1)->first();
        // The card list is loaded (and paginated) client-side via the search
        // endpoint, so we no longer fetch every khedmot up front.
        return view('admin.khedmots.index',compact('members','users','programTypes','activeProgram'));
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
        abort_unless(auth()->user()->can('create khedmot'), 403);
        try {
            DB::beginTransaction();
            $request->validate([
                'date' => 'required|date',
                'member_id' => 'required|exists:members,id',
                'program_id' => 'required|exists:program_types,id',
                'khedmot_amount' => 'nullable|numeric|min:0',
                'manat_amount' => 'nullable|numeric|min:0',

            ], [
                'date.required' => 'তারিখ প্রয়োজন',
                'member_id.required' => 'জাকের নির্বাচন করা প্রয়োজন',
                'member_id.exists' => 'জাকের খুঁজে পাওয়া যায়নি',
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

            // For the in-page quick-entry flow: return the new record (with the
            // relations the card template needs) so JS can prepend it instead of
            // forcing a full page reload.
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'খেদমত যোগ করা সফল হয়েছে।',
                    'khedmot' => $khedmot->load('member', 'user', 'program'),
                ]);
            }

            return redirect()->back()->with('status', [
                'type' => 'success',
                'message' => 'খেদমত যোগ করা সফল হয়েছে।'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = implode('<br>', $e->validator->errors()->all());
            DB::rollBack();
            Log::error('Khedmot Creation Error: '.$e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'danger',
                    'message' => $errorMessages,
                ], 422);
            }

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
        abort_unless(auth()->user()->can('show khedmot'), 403);
        $khedmot = Khedmot::with('member','user','program')->findOrFail($id);
        return response()->json($khedmot);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(auth()->user()->can('update khedmot'), 403);
        $khedmot = Khedmot::findOrFail($id);
        return response()->json($khedmot);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(auth()->user()->can('update khedmot'), 403);
        try {
            $khedmot = Khedmot::findOrFail($id);
            $request->validate([
                'date' => 'required|date',
                'member_id' => 'required|exists:members,id',
                'program_id' => 'required|exists:program_types,id',
                'khedmot_amount' => 'nullable|numeric|min:0',
                'manat_amount' => 'nullable|numeric|min:0',

                ], [
                    'date.required' => 'তারিখ প্রয়োজন',
                    'member_id.required' => 'জাকের নির্বাচন করা প্রয়োজন',
                    'member_id.exists' => 'জাকের খুঁজে পাওয়া যায়নি',
                    'program_id.required' => 'প্রোগ্রামের নাম প্রয়োজন',
                    'khedmot_amount.numeric' => 'খেদমত পরিমাণ সংখ্যা হতে হবে',
                    'khedmot_amount.min' => 'খেদমত পরিমাণ ০ এর চেয়ে বেশি হতে হবে',
                    'manat_amount.numeric' => 'মানত পরিমাণ সংখ্যা হতে হবে',
                    'manat_amount.min' => 'মানত পরিমাণ ০ এর চেয়ে বেশি হতে হবে',
                ]);
            $khedmot->update([
                'date' => $request->date,
                'member_id' => $request->member_id,
                'program_id' => $request->program_id,
                'other_program_name' => $request->other_program_name,
                'khedmot_amount' => $request->khedmot_amount,
                'manat_amount' => $request->manat_amount,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'খেদমত আপডেট করা সফল হয়েছে।',
                // Return the fresh record (with relations) so JS can rebuild the
                // card in place instead of reloading the whole page.
                'khedmot' => $khedmot->load('member', 'user', 'program'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = implode('<br>', $e->validator->errors()->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'danger',
                    'message' => $errorMessages,
                ], 422);
            }

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
        abort_unless(auth()->user()->can('delete khedmot'), 403);
        $khedmot = Khedmot::findOrFail($id);

        if ($khedmot->is_collected) {
            return response()->json([
                'status' => 'danger',
                'message' => 'এই খেদমতটি ইতিমধ্যে জমা হিসেবে গৃহীত হয়েছে, তাই মুছে ফেলা যাবে না।',
            ]);
        }

        $isReferencedInReceive = \App\Models\Receive::where('status', '<>', 'canceled')
            ->get(['khedmot_ids'])
            ->contains(function ($receive) use ($id) {
                return in_array((string) $id, array_map('trim', explode(',', $receive->khedmot_ids ?? '')), true);
            });

        if ($isReferencedInReceive) {
            return response()->json([
                'status' => 'danger',
                'message' => 'এই খেদমতটি একটি পেন্ডিং জমার সাথে যুক্ত, তাই মুছে ফেলা যাবে না।',
            ]);
        }

        $khedmot->delete();
        session()->flash('status', [
            'type' => 'success',
            'message' => 'খেদমত ডিলিট করা সফল হয়েছে।'
        ]);
        return response()->json(['status' => 'success', 'message' => 'খেদমত ডিলিট করা সফল হয়েছে।']);
    }

    public function search(Request $request)
    {
        abort_unless(auth()->user()->can('view khedmot'), 403);

        $perPage = 30;

        // Build the filtered query once, then reuse it for both the aggregate
        // totals (over the whole filtered set) and the paginated page fetch.
        $query = Khedmot::with('member','user','program')
            ->filterBy($request->date, $request->name)
            ->when($request->program_id, fn ($q) => $q->where('program_id', $request->program_id))
            ->visibleTo(auth()->user(), $request->userid);

        $total = (clone $query)->count();
        $khedmotSum = (clone $query)->sum('khedmot_amount');
        $manatSum = (clone $query)->sum('manat_amount');

        $khedmots = $query->orderBy('date','desc')->paginate($perPage);

        return response()->json([
            'data' => $khedmots->items(),
            'meta' => [
                'current_page' => $khedmots->currentPage(),
                'last_page' => $khedmots->lastPage(),
                'total' => $total,
                'khedmot_sum' => (float) $khedmotSum,
                'manat_sum' => (float) $manatSum,
            ],
        ]);
    }

    public function kolyanStore(Request $request)
    {
        abort_unless(auth()->user()->can('create khedmot'), 403);
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
        abort_unless(auth()->user()->can('update khedmot'), 403);
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
            $khedmot->update([
                'date' => $request->date,
                'kalyan_amount' => $request->kalyan_amount,
                'comment' => $request->comment,
            ]);
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
        abort_unless(auth()->user()->can('create khedmot'), 403);
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
        abort_unless(auth()->user()->can('update khedmot'), 403);
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
            $khedmot->update([
                'date' => $request->date,
                'rent_amount' => $request->rent_amount,
                'comment' => $request->comment,
            ]);
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
