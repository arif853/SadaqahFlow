<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Member;
use App\Models\Khedmot;
use Illuminate\Http\Request;
use App\Models\Receive;
use App\Http\Controllers\Controller;
use App\Models\Pay;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCentralFunds='';
        if (Auth::user()->getRoleNames()->contains('Super Admin') || Auth::user()->getRoleNames()->contains('Admin')) {
            $khedmots = Khedmot::with('member','user')->orderBy('created_at', 'desc')->limit(10)
            ->get();
            $totalMembers = Member::count();

            $collectedKhedmots = Khedmot::where('is_collected', true)->get();
            $nonCollectedKhedmots = Khedmot::where('is_collected', false)->get();
            $centralFunds = Receive::where('status', 'collected')->get();
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
            $khedmots = Khedmot::with('member','user')->where('user_id', $user->id)->orderBy('created_at', 'desc')->limit(10)->get();
            $totalMembers = $user->members->count();

            $collectedKhedmots = $user->khedmots()->where('is_collected', true)->get();
            $nonCollectedKhedmots = $user->khedmots()->where('is_collected', false)->get();
            $centralFunds = Receive::where('status', 'collected')->where('submitted_by', $user->id)->get();

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
        $chartUsers = User::with('khedmots')->whereDoesntHave('roles', function($query) {
            $query->where('name', 'Super Admin');
        })
        ->orderBy('id', 'desc')
        ->get();

        return Inertia::render('Dashboard', [
            'users' => $users,
            'khedmots' => $khedmots,
            'chartUsers' => $chartUsers,
            'centralFunds' => $centralFunds,
            'totalMembers' => $totalMembers,
            'totalKhedmotAmount' => $totalKhedmotAmount,
            'totalRentAmount' => $totalRentAmount,
            'totalKalyanAmount' => $totalKalyanAmount,
            'totalManatAmount' => $totalManatAmount,
            'KhedmotAmount' => $KhedmotAmount,
            'RentAmount' => $RentAmount,
            'KalyanAmount' => $KalyanAmount,
            'ManatAmount' => $ManatAmount,
            'totalCentralFunds' => $totalCentralFunds,
        ]);
    }
}
