<?php

namespace App\Http\Controllers;

use App\Models\Caseassignment;
use App\Models\Report;
use App\Models\Status;
use App\Models\Stuff;
use App\Services\CitizenNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class OfficerController extends Controller
{
    public function __construct(private CitizenNotificationService $notifications) {}

    public function dashboard(): View
    {
        $user = Auth::user();
        if ($user->role === 'Officer') {
            $stats = [
                'total'        => Report::whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id))->count(),
                'submitted'    => Report::where('status', 'Submitted')->whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id))->count(),
                'under_review' => Report::where('status', 'Under Review')->whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id))->count(),
                'resolved'     => Report::where('status', 'Resolved')->whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id))->count(),
            ];
            $recent = Report::whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id))
                            ->with(['crime', 'stuff'])
                            ->latest('created_at')->take(10)->get();
        } else {
            $stats = [
                'total'        => Report::count(),
                'submitted'    => Report::where('status', 'Submitted')->count(),
                'under_review' => Report::where('status', 'Under Review')->count(),
                'resolved'     => Report::where('status', 'Resolved')->count(),
            ];
            $recent = Report::with(['crime', 'stuff'])
                            ->latest('created_at')->take(10)->get();
        }
        return view('officer.dashboard', compact('stats', 'recent'));
    }

    public function reports(): View
    {
        $user = Auth::user();
        $query = Report::with(['stuff', 'crime']);
        if ($user->role === 'Officer') {
            $query->whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id));
        }
        return view('officer.reports', [
            'reports' => $query->latest('created_at')->get(),
        ]);
    }

    public function show(Report $report): View
    {
        $user = Auth::user();
        if ($user->role === 'Officer' && !$report->caseAssignments()->where('stuff_id', $user->stuff_id)->exists()) {
            abort(403, 'Unauthorized action. You are not assigned to this report.');
        }

        $report->load(['stuff', 'crime', 'evidence', 'statusHistory.changedBy', 'caseAssignments']);

        $officers = Stuff::where('role', 'Officer')->where('is_active', true)->orderBy('email')->get();

        return view('officer.show-report', compact('report', 'officers'));
    }

    public function assign(Request $request, Report $report): RedirectResponse
    {
        $user = Auth::user();
        if ($user->role !== 'Admin') {
            abort(403, 'Unauthorized action. Only admins can assign cases.');
        }

        $request->validate([
            'stuff_id' => 'required|exists:stuff,stuff_id',
            'priority' => 'required|in:Low,Medium,High,Urgent',
        ]);

        $oldStatus = $report->status;

        Caseassignment::updateOrCreate(
            ['report_id' => $report->report_id],
            [
                'stuff_id' => $request->stuff_id,
                'priority' => $request->priority,
            ]
        );

        $report->update(['priority' => $request->priority, 'status' => 'Assigned']);

        // Log status change
        Status::create([
            'report_id'  => $report->report_id,
            'changed_by' => $user->stuff_id,
            'old_status' => $oldStatus,
            'new_status' => 'Assigned',
            'remarks'    => $request->input('notes'),
        ]);

        $report->refresh();
        $this->notifications->notifyStatusUpdated($report);

        return back()->with('success', 'Case assigned successfully.');
    }

    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $user = Auth::user();
        if ($user->role === 'Officer' && !$report->caseAssignments()->where('stuff_id', $user->stuff_id)->exists()) {
            abort(403, 'Unauthorized action. You are not assigned to this report.');
        }

        $request->validate([
            'status'  => 'required|in:Submitted,Under Review,Assigned,Resolved,Closed',
            'remarks' => 'nullable|string',
        ]);

        $oldStatus = $report->status;
        $report->update(['status' => $request->status]);

        Status::create([
            'report_id'  => $report->report_id,
            'changed_by' => $user->stuff_id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'remarks'    => $request->input('remarks'),
        ]);

        $report->refresh();
        $this->notifications->notifyStatusUpdated($report);

        return back()->with('success', 'Status updated to '.$request->status);
    }

    public function crimeMap(): View
    {
        $user = Auth::user();
        $query = Report::with('crime')
            ->whereHas('crime', fn($q) => $q->whereNotNull('latitude')->whereNotNull('longitude'));

        if ($user->role === 'Officer') {
            $query->whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id));
        }

        $mapReports = $query->get();

        return view('officer.map', [
            'reports'    => $mapReports,
            'mapReports' => $mapReports,
        ]);
    }

    public function analytics(): View
    {
        $user = Auth::user();

        $queryCat = Report::query()
            ->join('crime', 'report.crime_id', '=', 'crime.crime_id')
            ->select('crime.category_name as name', DB::raw('count(*) as total'));

        $queryStatus   = Report::select('status', DB::raw('count(*) as total'));
        $queryPriority = Report::select('priority', DB::raw('count(*) as total'));

        if ($user->role === 'Officer') {
            $queryCat->whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id));
            $queryStatus->whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id));
            $queryPriority->whereHas('caseAssignments', fn($q) => $q->where('stuff_id', $user->stuff_id));
        }

        $byCategory = $queryCat->groupBy('crime.category_name')->orderByDesc('total')->get();
        $byStatus   = $queryStatus->groupBy('status')->get();
        $byPriority = $queryPriority->groupBy('priority')->get();

        return view('officer.analytics', compact('byCategory', 'byStatus', 'byPriority'));
    }
}
