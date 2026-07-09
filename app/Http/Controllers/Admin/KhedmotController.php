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
                'type' => 'khedmot',
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
        // Only plain খেদমত records here — কল্যাণ/ভাড়া have their own screens.
        $query = Khedmot::with('member','user','program')
            ->where('type', 'khedmot')
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

    // ---------- কল্যাণ / ভাড়া collections ----------
    // Stored in the khedmots table (type = kalyan|rent) but on their own
    // screens. No program; month + collection date are the key fields.

    public function kolyanIndex()
    {
        abort_unless(auth()->user()->can('view kollyan'), 403);
        return $this->collectionIndexView('kalyan');
    }

    public function rentIndex()
    {
        abort_unless(auth()->user()->can('view rent'), 403);
        return $this->collectionIndexView('rent');
    }

    private function collectionIndexView(string $type)
    {
        $user = auth()->user();
        $users = User::where('status', 1)->get();
        $members = $user->isAdminLevel() ? Member::where('status', 1)->get() : $user->members;

        $config = $type === 'rent'
            ? [
                'type' => 'rent',
                'amountField' => 'rent_amount',
                'title' => 'ভাড়া',
                'storeRoute' => route('khedmots.rent.store'),
                'updateBase' => url('khedmots/rent/update'),
                'searchRoute' => route('collections.search', 'rent'),
            ]
            : [
                'type' => 'kalyan',
                'amountField' => 'kalyan_amount',
                'title' => 'কল্যাণ',
                'storeRoute' => route('khedmots.kolyan.store'),
                'updateBase' => url('khedmots/kolyan/update'),
                'searchRoute' => route('collections.search', 'kalyan'),
            ];

        return view('admin.collections.index', compact('members', 'users', 'config'));
    }

    public function collectionSearch(Request $request, string $type)
    {
        $type = $type === 'rent' ? 'rent' : 'kalyan';
        abort_unless(auth()->user()->can($type === 'rent' ? 'view rent' : 'view kollyan'), 403);

        $amountField = $type === 'rent' ? 'rent_amount' : 'kalyan_amount';
        $perPage = 24;

        $query = Khedmot::with('member', 'user')
            ->where('type', $type)
            ->filterBy($request->date, $request->name)
            ->when($request->month, fn ($q) => $q->where('month', $request->month))
            ->visibleTo(auth()->user(), $request->userid);

        $total = (clone $query)->count();
        $amountSum = (clone $query)->sum($amountField);

        $records = $query->orderBy('date', 'desc')->paginate($perPage);

        return response()->json([
            'data' => $records->items(),
            'meta' => [
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'total' => $total,
                'amount_sum' => (float) $amountSum,
            ],
        ]);
    }

    public function kolyanStore(Request $request)
    {
        return $this->storeCollection($request, 'kalyan');
    }

    public function rentStore(Request $request)
    {
        return $this->storeCollection($request, 'rent');
    }

    private function storeCollection(Request $request, string $type)
    {
        abort_unless(auth()->user()->can('create khedmot'), 403);

        $amountField = $type === 'rent' ? 'rent_amount' : 'kalyan_amount';
        $label = $type === 'rent' ? 'ভাড়া' : 'কল্যাণ';

        try {
            DB::beginTransaction();
            $request->validate([
                'date' => 'required|date',
                'month' => 'required|string|max:7',
                'member_id' => 'required|exists:members,id',
                $amountField => 'required|numeric|min:0',
            ], [
                'date.required' => 'তারিখ প্রয়োজন',
                'month.required' => 'মাস প্রয়োজন',
                'member_id.required' => 'জাকের নির্বাচন করা প্রয়োজন',
                'member_id.exists' => 'জাকের খুঁজে পাওয়া যায়নি',
                $amountField.'.required' => $label.' পরিমাণ প্রয়োজন',
                $amountField.'.numeric' => $label.' পরিমাণ সংখ্যা হতে হবে',
                $amountField.'.min' => $label.' পরিমাণ ০ এর চেয়ে বেশি হতে হবে',
            ]);

            $this->authorizeMember($request->member_id);

            // Guard against collecting the same month twice for one member.
            $duplicate = Khedmot::where('type', $type)
                ->where('member_id', $request->member_id)
                ->where('month', $request->month)
                ->where('is_collected', false)
                ->exists();
            if ($duplicate) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'month' => 'এই মাসের '.$label.' ইতিমধ্যে সংগ্রহ করা হয়েছে।',
                ]);
            }

            $khedmot = Khedmot::create([
                'date' => $request->date,
                'month' => $request->month,
                'type' => $type,
                'member_id' => $request->member_id,
                $amountField => $request->input($amountField),
                'comment' => $request->comment,
                'user_id' => auth()->id(),
                'is_collected' => false,
            ]);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $label.' যোগ করা সফল হয়েছে।',
                    'record' => $khedmot->load('member', 'user'),
                ]);
            }

            return redirect()->back()->with('status', ['type' => 'success', 'message' => $label.' যোগ করা সফল হয়েছে।']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $errorMessages = implode('<br>', $e->validator->errors()->all());
            if ($request->wantsJson()) {
                return response()->json(['status' => 'danger', 'message' => $errorMessages], 422);
            }
            return redirect()->back()->withInput()->with('status', ['type' => 'danger', 'message' => $errorMessages]);
        }
    }

    public function kolyanUpdate(Request $request, string $id)
    {
        return $this->updateCollection($request, $id, 'kalyan');
    }

    public function rentUpdate(Request $request, string $id)
    {
        return $this->updateCollection($request, $id, 'rent');
    }

    private function updateCollection(Request $request, string $id, string $type)
    {
        abort_unless(auth()->user()->can('update khedmot'), 403);

        $amountField = $type === 'rent' ? 'rent_amount' : 'kalyan_amount';
        $label = $type === 'rent' ? 'ভাড়া' : 'কল্যাণ';

        try {
            $khedmot = Khedmot::where('type', $type)->findOrFail($id);
            $request->validate([
                'date' => 'required|date',
                'month' => 'required|string|max:7',
                $amountField => 'required|numeric|min:0',
            ], [
                'date.required' => 'তারিখ প্রয়োজন',
                'month.required' => 'মাস প্রয়োজন',
                $amountField.'.required' => $label.' পরিমাণ প্রয়োজন',
                $amountField.'.numeric' => $label.' পরিমাণ সংখ্যা হতে হবে',
                $amountField.'.min' => $label.' পরিমাণ ০ এর চেয়ে বেশি হতে হবে',
            ]);

            $this->authorizeMember($khedmot->member_id);

            $khedmot->update([
                'date' => $request->date,
                'month' => $request->month,
                $amountField => $request->input($amountField),
                'comment' => $request->comment,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $label.' আপডেট করা সফল হয়েছে।',
                'record' => $khedmot->load('member', 'user'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = implode('<br>', $e->validator->errors()->all());
            if ($request->wantsJson()) {
                return response()->json(['status' => 'danger', 'message' => $errorMessages], 422);
            }
            return redirect()->back()->withInput()->with('status', ['type' => 'danger', 'message' => $errorMessages]);
        }
    }

    // A non-admin may only collect for members assigned to them.
    private function authorizeMember($memberId): void
    {
        $user = auth()->user();
        if ($user->isAdminLevel()) {
            return;
        }
        abort_unless($user->members()->whereKey($memberId)->exists(), 403, 'Unauthorized member.');
    }

}
