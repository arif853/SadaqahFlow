<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pay;
use App\Models\Member;
use App\Models\Khedmot;
use App\Models\Receive;
use Illuminate\Http\Request;
use App\Models\FundCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Xenon\LaravelBDSms\Facades\SMS;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FundCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function receiveIndex()
    {
        if (Auth::user()->getRoleNames()->contains('Super Admin') || Auth::user()->getRoleNames()->contains('Admin')) {
            $fundCollections = Receive::orderBy('created_at', 'desc')->get();
            foreach ($fundCollections as $fundCollection) {
                $fundCollection->khedmots = Khedmot::whereIn('id', explode(',', $fundCollection->khedmot_ids))->get();
            }
        }else{
            $user = Auth::user();
            $fundCollections = Receive::orderBy('created_at', 'desc')->where('submitted_by', $user->id)->get();
            foreach ($fundCollections as $fundCollection) {
                $fundCollection->khedmots = Khedmot::whereIn('id', explode(',', $fundCollection->khedmot_ids))->get();
            }
        }
        return view('admin.fund_collection.receive.index', compact('fundCollections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function receiveCreate()
    {
        $user = Auth::user();
        $khedmotsQuery = Khedmot::where('status', 1)->where('is_collected', 0);
        $membersQuery = Member::query();

        // exclude khedmots that are already included in existing receive requests (except canceled)
        $requestedIds = Receive::where('status', '<>', 'canceled')->pluck('khedmot_ids')
                ->filter()
                ->flatMap(function($ids){
                    return array_map('trim', explode(',', $ids));
                })->unique()->values()->toArray();

        if (Auth::user()->getRoleNames()->contains('Super Admin') || Auth::user()->getRoleNames()->contains('Admin')) {
            if (!empty($requestedIds)) {
                $khedmots = $khedmotsQuery->whereNotIn('id', $requestedIds)->get();
            } else {
                $khedmots = $khedmotsQuery->get();
            }
            $members = $membersQuery->get();
        }else{
            if (!empty($requestedIds)) {
                $khedmots = $khedmotsQuery->where('user_id', $user->id)->whereNotIn('id', $requestedIds)->get();
            } else {
                $khedmots = $khedmotsQuery->where('user_id', $user->id)->get();
            }
            $members = $user->memberAssigns()->get();
            // dd($members, $khedmots);
        }
        return view('admin.fund_collection.receive.receive', compact('khedmots', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function receive(Request $request): \Illuminate\Http\RedirectResponse
    {
        $fundCollectionData = $request->only([
            'date',
            'program_id',
            'other_program_name',
            'khedmot_amount',
            'manat_amount',
            'kollan_amount',
            'rent_amount',
            'total_amount',
            'comment',
        ]);

        $khedmotIds = explode(',', $request->khedmot_id);

        try {
            // Start a database transaction
            DB::beginTransaction();

            $fundCollectionData['submitted_by'] = Auth::user()->id;
            $fundCollectionData['submitted_at'] = now();
            $fundCollectionData['khedmot_ids'] = $request->khedmot_id;
            // dd($fundCollectionData);
            // Create a new fund collection
            Receive::create($fundCollectionData);
            // Commit the transaction
            DB::commit();

            // Redirect to the index page with a success message
            return redirect()->route('fund.receive.index')->with(
                'status', [
                'type' => 'success',
                'message' => 'খেদমত জমা পেন্ডিং রয়েছে।'
            ]
            );
        } catch (\Exception $e) {
            // Rollback the transaction if there is an error
            DB::rollback();

            // Log the error
            Log::error($e->getMessage());

            // Redirect back with an error message
            return redirect()->route('fund.receive.index')->with('status', [
                'type' => 'danger',
                'message' => 'খেদমত জমা পেন্ডিং রয়েছে।'. $e->getMessage(),
            ]);
        }
    }

    public function approve(Request $request, $id)
    {
        $fundCollection = Receive::findOrFail($id);
        try {
            DB::beginTransaction();
            $khedmotIds = explode(',', $fundCollection->khedmot_ids);
            // Update the khedmot status to collected
            $khedmot = Khedmot::whereIn('id', $khedmotIds)->update([
                'is_collected' => true,
            ]);

            foreach ($khedmotIds as $khedmotId) {
                $khedmot = Khedmot::find($khedmotId);
                $member = $khedmot->member;
                $kalyanid = $member->kollan_id;
                $khedmotAmount = $khedmot->khedmot_amount> 0 ? 'খেদমতঃ'.$khedmot->khedmot_amount : '';
                $manotAmount = $khedmot->manat_amount > 0 ? 'মানতঃ'.$khedmot->manat_amount : '';
                $programName = $khedmot->program ? $khedmot->program->name : 'অন্যান্য';
                $message = "শুকরিয়া $programName, \n$member->name, ID:$kalyanid, $khedmotAmount,$manotAmount খেদমত জমা সফল হয়েছে ।\n\n-বঃ স্থাঃ দক্ষিণ কুতুবখালী।";

                // dd($message);
                // if($khedmotAmount>0)
                // {
                //     if ($member->phone)
                //     {
                //         $sms = SMS::shoot($member->phone, $message);
                //         Log::info('SMS sent: '.$sms);
                //     }
                // }
            }

            $fundCollection->status = 'collected';
            $fundCollection->collected_by = Auth::user()->id;
            $fundCollection->collected_at = now();
            $fundCollection->save();

            DB::commit();
            return response()->json([
                'message' => 'খেদমত গ্রহণ করা সফল হয়েছে।'
            ]);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'খেদমত গ্রহণ করা যায়নি। '.$e->getMessage()
            ]);
        }

    }

    public function cancel($id)
    {
        try {
            $fundCollection = Receive::findOrFail($id);
            $khedmotIds = explode(',', $fundCollection->khedmot_ids);
            // Update the khedmot status to collected
            Khedmot::whereIn('id', $khedmotIds)->update([
                'is_collected' => false,
            ]);
            $fundCollection->canceled_by = Auth::user()->id;
            $fundCollection->canceled_at = now();
            $fundCollection->status = 'canceled';
            $fundCollection->save();
            session()->flash('status', [
                'type' => 'danger',
                'message' => 'খেদমত বাতিল করা হয়েছে।'
            ]);
            return response()->json(['status' => 'danger', 'message' => 'খেদমত বাতিল করা হয়েছে।']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('status', [
                'type' => 'danger',
                'message' => 'খেদমত বাতিল করা যায়নি। '.$e->getMessage()
            ]);
        }

    }

    public function payIndex()
    {
        $pays = Pay::all();
        return view('admin.fund_collection.pay.index', compact('pays'));
    }

    public function payCreate()
    {
        $khedmots = Khedmot::where('status', 1)->where('is_collected', 1)->get();
        $receives = Receive::where('status', 'collected')->get();
        return view('admin.fund_collection.pay.pay', compact('khedmots', 'receives'));
    }

    public function pay(Request $request)
    {
        $paymentData = $request->only([
            'date',
            'pay_to',
            'khedmot_amount',
            'manat_amount',
            'kalyan_amount',
            'rent_amount',
            'total_paid',
            'left_amount',
            'comment',
        ]);

        try {
            // Start a database transaction
            \DB::beginTransaction();

            $paymentData['paid_by'] = Auth::user()->id;
            $paymentData['paid_at'] = now();

            // Create a new fund collection
            Pay::create($paymentData);

            // Commit the transaction
            \DB::commit();

            // Redirect to the index page with a success message
            return redirect()->route('fund.pay.index')->with(
                'status', [
                'type' => 'success',
                'message' => 'খেদমত প্রদান সফল হয়েছে।'
            ]
            );
        } catch (\Exception $e) {
            // Rollback the transaction if there is an error
            \DB::rollback();

            // Log the error
            \Log::error($e->getMessage());

            // Redirect back with an error message
            return redirect()->route('fund.pay.index')->with('status', [
                'type' => 'danger',
                'message' => 'খেদমত প্রদান অসফল হয়েছে'. $e->getMessage(),
            ]);
        }
    }
}
