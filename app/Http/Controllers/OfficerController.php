<?php

namespace App\Http\Controllers;

use App\Models\Caseassignment;
use App\Models\Crime;
use App\Models\Report;
use App\Models\Status;
use App\Models\Stuff;
use App\Services\CitizenNotificationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OfficerController extends Controller
{
    public function __construct(private CitizenNotificationService $notifications) {}

    public function dashboard(): View
    {
        $user  = Auth::user();
        $query = $this->scopedReportsQuery();

        $stats = [
            'total'        => (clone $query)->count(),
            'submitted'    => (clone $query)->where('status', 'Submitted')->count(),
            'under_review' => (clone $query)->where('status', 'Under Review')->count(),
            'resolved'     => (clone $query)->where('status', 'Resolved')->count(),
        ];

        $recent = (clone $query)
            ->with(['crime', 'stuff'])
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('officer.dashboard', compact('stats', 'recent'));
    }

    public function reports(Request $request): View
    {
        $query = $this->scopedReportsQuery()->with(['stuff', 'crime']);

        if ($category = $request->input('category')) {
            $query->whereHas('crime', fn (Builder $q) => $q->where('category_name', $category));
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('tracking_code', 'like', "%{$search}%")
                    ->orWhere('report_id', $search)
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $totalCount = (clone $query)->count();

        $reports = $query->latest('created_at')->paginate(10)->withQueryString();

        $categories = Crime::query()
            ->whereIn('crime_id', $this->scopedReportsQuery()->select('crime_id'))
            ->orderBy('category_name')
            ->pluck('category_name')
            ->unique()
            ->values();

        $priorityBase = $this->scopedReportsQuery();
        $prioritySummary = [
            'critical' => (clone $priorityBase)->whereIn('priority', ['Urgent', 'Critical'])->count(),
            'high'     => (clone $priorityBase)->where('priority', 'High')->count(),
            'total'    => (clone $priorityBase)->count(),
        ];

        $mapReports = $this->mapReportsQuery()
            ->latest('created_at')
            ->take(50)
            ->get();

        $mapMarkers = $this->formatMapMarkers($mapReports);

        return view('officer.reports', compact(
            'reports',
            'categories',
            'totalCount',
            'prioritySummary',
            'mapReports',
            'mapMarkers',
        ));
    }

    public function show(Report $report): View
    {
        $this->authorizeReportAccess($report);

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
            'notes'    => 'nullable|string|max:2000',
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
        $this->authorizeReportAccess($report);

        $request->validate([
            'status'  => 'required|in:Submitted,Under Review,Assigned,Resolved,Closed',
            'remarks' => 'nullable|string|max:2000',
        ]);

        $oldStatus = $report->status;
        $report->update(['status' => $request->status]);

        Status::create([
            'report_id'  => $report->report_id,
            'changed_by' => Auth::user()->stuff_id,
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
        $mapReports = $this->mapReportsQuery()->get();
        $mapMarkers = $this->formatMapMarkers($mapReports);

        return view('officer.map', [
            'reports'    => $mapReports,
            'mapReports' => $mapReports,
            'mapMarkers' => $mapMarkers,
        ]);
    }

    public function analytics(): View
    {
        $user = Auth::user();

        if ($user->role === 'Officer') {
            abort(403, 'Analytics are available to administrators only.');
        }

        $base = $this->scopedReportsQuery();

        $byCategory = (clone $base)
            ->join('crime', 'report.crime_id', '=', 'crime.crime_id')
            ->select('crime.category_name as name', DB::raw('count(*) as total'))
            ->groupBy('crime.category_name')
            ->orderByDesc('total')
            ->get();

        $byStatus = (clone $base)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $byPriority = (clone $base)
            ->select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->get();

        return view('officer.analytics', compact('byCategory', 'byStatus', 'byPriority'));
    }

    private function scopedReportsQuery(): Builder
    {
        $query = Report::query();
        $user  = Auth::user();

        if ($user->role === 'Officer') {
            $query->whereHas('caseAssignments', fn (Builder $q) => $q->where('stuff_id', $user->stuff_id));
        }

        return $query;
    }

    private function mapReportsQuery(): Builder
    {
        return $this->scopedReportsQuery()
            ->with('crime')
            ->where(function (Builder $query) {
                $query->where(function (Builder $located) {
                    $located->whereNotNull('location_latitude')
                        ->whereNotNull('location_longitude');
                })->orWhereHas('crime', function (Builder $crime) {
                    $crime->whereNotNull('latitude')
                        ->whereNotNull('longitude');
                });
            });
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function formatMapMarkers(iterable $reports): array
    {
        $markers = [];

        foreach ($reports as $report) {
            $latitude = $report->mapLatitude();
            $longitude = $report->mapLongitude();

            if ($latitude === null || $longitude === null) {
                continue;
            }

            $markers[] = [
                'report_id'     => $report->report_id,
                'status'        => $report->status,
                'category_name' => $report->crime?->category_name,
                'latitude'      => $latitude,
                'longitude'     => $longitude,
                'url'           => route('officer.report.show', $report),
            ];
        }

        return $markers;
    }

    private function authorizeReportAccess(Report $report): void
    {
        $user = Auth::user();

        if ($user->role === 'Officer' && ! $report->caseAssignments()->where('stuff_id', $user->stuff_id)->exists()) {
            abort(403, 'Unauthorized action. You are not assigned to this report.');
        }
    }
}
