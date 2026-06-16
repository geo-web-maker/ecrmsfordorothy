<?php

namespace App\Http\Controllers;

use App\Models\CrimeCategory;
use App\Models\Evidence;
use App\Models\Report;
use App\Rules\SafeEvidenceFile;
use App\Services\SecureEvidenceUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private SecureEvidenceUploader $evidenceUploader) {}

    public function trackAnonymous(): View
    {
        return view('citizen.track');
    }

    public function showTracking(Request $request): View|RedirectResponse
    {
        $report = Report::with('crimeCategory')
            ->where('tracking_code', $request->input('tracking_code'))
            ->first();

        if (! $report) {
            return back()->withErrors(['tracking_code' => 'No report found with that tracking code.']);
        }

        return view('citizen.track', compact('report'));
    }

    public function createAnonymous(): View
    {
        return view('citizen.anonymous-report', [
            'categories' => CrimeCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function storeAnonymous(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'crime_category_id' => 'required|exists:crime_categories,id',
            'description' => 'required|min:20',
            'location_latitude' => 'nullable|numeric',
            'location_longitude' => 'nullable|numeric',
            'evidence' => 'nullable|array',
            'evidence.*' => ['nullable', 'file', 'max:20480', new SafeEvidenceFile(allowVideo: false)],
        ]);

        $trackingCode = strtoupper(substr(md5(uniqid()), 0, 10));

        $report = Report::create([
            ...collect($validated)->except(['evidence'])->all(),
            'user_id' => null,
            'tracking_code' => $trackingCode,
            'status' => 'Submitted',
        ]);

        $this->storeEvidenceFiles($report, $request->file('evidence', []), allowVideo: false);

        return redirect()->route('home')->with([
            'success' => 'Anonymous report submitted!',
            'tracking_code' => $trackingCode,
        ]);
    }

    public function create(): View
    {
        return view('citizen.create-report', [
            'categories' => CrimeCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'crime_category_id' => 'required|exists:crime_categories,id',
            'description' => 'required|min:20',
            'location_latitude' => 'nullable|numeric',
            'location_longitude' => 'nullable|numeric',
            'location_address' => 'nullable|string|max:500',
            'evidence' => 'nullable|array',
            'evidence.*' => ['nullable', 'file', 'max:20480', new SafeEvidenceFile(allowVideo: true)],
        ]);

        $report = Report::create([
            ...collect($validated)->except(['evidence'])->all(),
            'user_id' => auth()->id(),
            'status' => 'Submitted',
        ]);

        $this->storeEvidenceFiles($report, $request->file('evidence', []), allowVideo: true);

        return redirect()->route('citizen.report.show', $report)->with('success', 'Report submitted successfully!');
    }

    public function show(Report $report): View
    {
        abort_unless($report->user_id === auth()->id(), 403);

        $report->load(['crimeCategory', 'evidence', 'statusHistory']);

        return view('citizen.show-report', compact('report'));
    }

    /**
     * @param  array<int, \Illuminate\Http\UploadedFile>|null  $files
     */
    private function storeEvidenceFiles(Report $report, ?array $files, bool $allowVideo): void
    {
        if (empty($files)) {
            return;
        }

        foreach ($this->evidenceUploader->storeMany($files, $allowVideo) as $uploaded) {
            Evidence::create([
                'report_id' => $report->id,
                'file_path' => $uploaded['path'],
                'file_type' => $uploaded['file_type'],
            ]);
        }
    }
}
