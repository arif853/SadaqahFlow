<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Member;
use App\Models\Khedmot;
use Illuminate\Http\Request;
use App\Models\Receive;
use App\Http\Controllers\Controller;
use App\Models\Pay;
use App\Models\ProgramType;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $activeProgram = ProgramType::where('status', 1)->first();
        // no program can legitimately have id 0, so this cleanly yields
        // "match nothing" below when there's no active program at all
        $activeProgramId = $activeProgram->id ?? 0;

        $totalCentralFunds='';
        if (Auth::user()->isAdminLevel()) {
            $khedmots = Khedmot::where('program_id', $activeProgramId)->orderBy('created_at', 'desc')->limit(10)
            ->get();
            $totalMembers = Member::count();

            $collectedKhedmots = Khedmot::where('program_id', $activeProgramId)->where('is_collected', true)->get();
            $nonCollectedKhedmots = Khedmot::where('program_id', $activeProgramId)->where('is_collected', false)->get();
            $centralFunds = Receive::where('status', 'collected')->where('program_id', $activeProgramId)->get();
            $totalPayments = Pay::sum('total_paid');

            $totalCentralFunds = $centralFunds->sum('total_amount')-$totalPayments;

            $totalKhedmotAmount =$collectedKhedmots->sum('khedmot_amount');
            $totalRentAmount =$collectedKhedmots->sum('rent_amount');
            $totalKalyanAmount =$collectedKhedmots->sum('kalyan_amount');
            $totalManatAmount =$collectedKhedmots->sum('manat_amount');

            $KhedmotAmount =$nonCollectedKhedmots->sum('khedmot_amount');
            $RentAmount =$nonCollectedKhedmots->sum('rent_amount');
            $KalyanAmount =$nonCollectedKhedmots->sum('kalyan_amount');
            $ManatAmount =$nonCollectedKhedmots->sum('manat_amount');

        } else {
            $user = Auth::user();
            $khedmots = Khedmot::where('user_id', $user->id)->where('program_id', $activeProgramId)->orderBy('created_at', 'desc')->limit(10)->get();
            $totalMembers = $user->members->count();

            $collectedKhedmots = $user->khedmots()->where('program_id', $activeProgramId)->where('is_collected', true)->get();
            $nonCollectedKhedmots = $user->khedmots()->where('program_id', $activeProgramId)->where('is_collected', false)->get();
            $centralFunds = Receive::where('status', 'collected')->where('submitted_by', $user->id)->where('program_id', $activeProgramId)->get();

            $totalKhedmotAmount = $collectedKhedmots->sum('khedmot_amount');
            $totalRentAmount = $collectedKhedmots->sum('rent_amount');
            $totalKalyanAmount = $collectedKhedmots->sum('kalyan_amount');
            $totalManatAmount = $collectedKhedmots->sum('manat_amount');

            $KhedmotAmount = $nonCollectedKhedmots->sum('khedmot_amount');
            $RentAmount = $nonCollectedKhedmots->sum('rent_amount');
            $KalyanAmount = $nonCollectedKhedmots->sum('kalyan_amount');
            $ManatAmount = $nonCollectedKhedmots->sum('manat_amount');
        }

        $users = User::latest('created_at')->limit(10)->get();
        $chartUsers = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'Super Admin');
        })
        ->with(['khedmots' => function ($query) use ($activeProgramId) {
            $query->where('program_id', $activeProgramId);
        }])
        ->orderBy('id', 'desc')
        ->get();

        return view('admin.dashboard', compact(
            'users',
            'khedmots',
            'chartUsers',
            'centralFunds',
            'totalMembers',
            'collectedKhedmots',
            'nonCollectedKhedmots',
            'totalKhedmotAmount',
            'totalRentAmount',
            'totalKalyanAmount',
            'totalManatAmount',
            'KhedmotAmount',
            'RentAmount',
            'KalyanAmount',
            'ManatAmount',
            'totalCentralFunds',
            'activeProgram'
        ));
    }
}
