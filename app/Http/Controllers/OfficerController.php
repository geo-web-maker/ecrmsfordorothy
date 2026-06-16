<?php

namespace App\Http\Controllers;

use App\Models\CaseAssignment;
use App\Models\Report;
use App\Models\StatusHistory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportStatusUpdated;
use Illuminate\Support\Facades\Auth;

class OfficerController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        if ($user->role === 'officer') {
            $stats = [
                'total'       => Report::whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id))->count(),
                'submitted'   => Report::where('status', 'Submitted')->whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id))->count(),
                'under_review'=> Report::where('status', 'Under Review')->whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id))->count(),
                'resolved'    => Report::where('status', 'Resolved')->whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id))->count(),
            ];
            $recent = Report::whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id))
                            ->with(['crimeCategory', 'user'])
                            ->latest()->take(10)->get();
        } else {
            $stats = [
                'total'       => Report::count(),
                'submitted'   => Report::where('status', 'Submitted')->count(),
                'under_review'=> Report::where('status', 'Under Review')->count(),
                'resolved'    => Report::where('status', 'Resolved')->count(),
            ];
            $recent = Report::with(['crimeCategory', 'user'])
                            ->latest()->take(10)->get();
        }
        return view('officer.dashboard', compact('stats', 'recent'));
    }

    public function reports(): View
    {
        $user = Auth::user();
        $query = Report::with(['user', 'crimeCategory']);
        if ($user->role === 'officer') {
            $query->whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id));
        }
        return view('officer.reports', [
            'reports' => $query->latest()->get(),
        ]);
    }

    public function show(Report $report): View
    {
        $user = Auth::user();
        if ($user->role === 'officer' && !$report->caseAssignments()->where('officer_id', $user->id)->exists()) {
            abort(403, 'Unauthorized action. You are not assigned to this report.');
        }

        $report->load(['user', 'crimeCategory', 'evidence', 'statusHistory.changedByUser', 'caseAssignments']);

        $officers = User::where('role', 'officer')->where('is_active', true)->orderBy('name')->get();

        return view('officer.show-report', compact('report', 'officers'));
    }

    public function assign(Request $request, Report $report): RedirectResponse
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized action. Only admins can assign cases.');
        }

        $request->validate([
            'officer_id' => 'required|exists:users,id',
            'priority'   => 'required|in:Low,Medium,High,Critical',
            'notes'      => 'nullable|string',
        ]);

        $oldStatus = $report->status;

        CaseAssignment::updateOrCreate(
            ['report_id' => $report->id],
            [
                'officer_id'  => $request->officer_id,
                'assigned_by' => $user->id,
                'notes'       => $request->notes,
            ]
        );

        $report->update(['priority' => $request->priority, 'status' => 'Assigned']);

        // Log status change
        StatusHistory::create([
            'report_id'  => $report->id,
            'changed_by' => $user->id,
            'old_status' => $oldStatus,
            'new_status' => 'Assigned',
            'remarks'    => $request->notes,
        ]);

        return back()->with('success', 'Case assigned successfully.');
    }

    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $user = Auth::user();
        if ($user->role === 'officer' && !$report->caseAssignments()->where('officer_id', $user->id)->exists()) {
            abort(403, 'Unauthorized action. You are not assigned to this report.');
        }

        $request->validate([
            'status' => 'required|in:Submitted,Under Review,Assigned,Resolved,Closed',
            'remarks' => 'nullable|string',
        ]);

        $oldStatus = $report->status;
        $report->update(['status' => $request->status]);

        StatusHistory::create([
            'report_id' => $report->id,
            'changed_by' => $user->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        if ($report->user && $report->user->email) {
            Mail::to($report->user->email)->send(new ReportStatusUpdated($report));
        }

        return back()->with('success', 'Status updated to '.$request->status);
    }

    public function crimeMap(): View
    {
        $user = Auth::user();
        $query = Report::with('crimeCategory')
            ->whereNotNull('location_latitude')
            ->whereNotNull('location_longitude');

        if ($user->role === 'officer') {
            $query->whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id));
        }

        $mapReports = $query->get();

        return view('officer.map', [
            'reports' => $mapReports,
            'mapReports' => $mapReports,
        ]);
    }

    public function analytics(): View
    {
        $user = Auth::user();

        $queryCat = Report::query()
            ->join('crime_categories', 'reports.crime_category_id', '=', 'crime_categories.id')
            ->select('crime_categories.name as name', DB::raw('count(*) as total'));

        $queryStatus = Report::select('status', DB::raw('count(*) as total'));

        $queryPriority = Report::select('priority', DB::raw('count(*) as total'));

        if ($user->role === 'officer') {
            $queryCat->whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id));
            $queryStatus->whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id));
            $queryPriority->whereHas('caseAssignments', fn($q) => $q->where('officer_id', $user->id));
        }

        $byCategory = $queryCat->groupBy('crime_categories.name')
            ->orderByDesc('total')
            ->get();

        $byStatus = $queryStatus->groupBy('status')
            ->get();

        $byPriority = $queryPriority->groupBy('priority')
            ->get();

        return view('officer.analytics', compact('byCategory', 'byStatus', 'byPriority'));
    }
}
