<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\User;
use App\Models\Khedmot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProgramType;

class ReportController extends Controller
{
    public function index()
    {
        $users = User::where('status', 1)->get();
        $programs = ProgramType::where('status', 1)->get();
        return view('admin.reports.user-wise-report', compact('users', 'programs'));
    }
    public function userWiseReport(Request $request)
    {
        $userID = $request->userId ?? $request->userid;
        $programID = $request->programId ?? $request->programID ?? null;
        $date = $request->date ?? null;
        $name = $request->name ?? null;

        // Find program (if provided)
        $program = null;
        if (!empty($programID)) {
            $program = ProgramType::where('status', 1)->where('id', $programID)->first();
        }

        // Find user (if provided)
        $user = null;
        if (!empty($userID)) {
            $user = User::where('status', 1)->where('id', $userID)->first();
        }

        $khedmots = Khedmot::with('member','user','program')
            ->filterBy($date, $name)
            ->when($program, fn ($query) => $query->where('program_id', $program->id))
            ->visibleTo(auth()->user(), $userID)
            ->orderBy('date','desc')
            ->get();

        // Generate PDF and return download
        $pdf = PDF::loadView('admin.reports.user-report', compact('khedmots','user'));
        return $pdf->stream('user-wise-report.pdf');
    }

    /**
     * AJAX endpoint to fetch khedmots as JSON for preview
     */
    public function fetchKhedmot(Request $request)
    {
        $userID = $request->userid ?? $request->userId;
        $programID = $request->programId ?? $request->programID ?? null;
        $date = $request->date ?? null;
        $name = $request->name ?? null;

        $program = null;
        if (!empty($programID)) {
            $program = ProgramType::where('status', 1)->where('id', $programID)->first();
        }

        $khedmots = Khedmot::with('member','user','program')
            ->filterBy($date, $name)
            ->when($program, fn ($query) => $query->where('program_id', $program->id))
            ->visibleTo(auth()->user(), $userID)
            ->orderBy('date','desc')
            ->get();

        // return under both keys to be compatible with existing JS
        return response()->json(['khedmots' => $khedmots]);
    }

}
