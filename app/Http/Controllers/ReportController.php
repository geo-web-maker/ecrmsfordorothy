<?php

namespace App\Http\Controllers;

use App\Models\Crime;
use App\Models\Evidence;
use App\Models\Report;
use App\Rules\SafeEvidenceFile;
use App\Services\CitizenNotificationService;
use App\Services\SecureEvidenceUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private SecureEvidenceUploader $evidenceUploader,
        private CitizenNotificationService $notifications,
    ) {}

    public function trackAnonymous(): View
    {
        return view('citizen.track');
    }

    public function showTracking(Request $request): View|RedirectResponse
    {
        $report = Report::with('crime')
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
            'categories' => Crime::orderBy('category_name')->get(),
        ]);
    }

    public function storeAnonymous(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'crime_id'           => 'required|exists:crime,crime_id',
            'description'        => 'required|min:20',
            'location_latitude'  => 'required|numeric|between:-90,90',
            'location_longitude' => 'required|numeric|between:-180,180',
            'location_name'      => 'nullable|string|max:255',
            'evidence'             => 'nullable|array',
            'evidence.*'           => ['nullable', 'file', 'max:20480', new SafeEvidenceFile(allowVideo: false)],
        ]);

        $trackingCode = $this->generateTrackingCode();

        $report = Report::create([
            'crime_id'           => $validated['crime_id'],
            'description'        => $validated['description'],
            'location_address'   => $validated['location_name'] ?? null,
            'location_latitude'  => $validated['location_latitude'],
            'location_longitude' => $validated['location_longitude'],
            'stuff_id'           => null,
            'tracking_code'      => $trackingCode,
            'status'             => 'Submitted',
        ]);

        $this->storeEvidenceFiles($report, $request->file('evidence', []), allowVideo: false);

        return redirect()->route('home')->with([
            'success'       => "Anonymous report submitted! Save your tracking code to check case status later.",
            'tracking_code' => $trackingCode,
        ]);
    }

    public function create(): View
    {
        return view('citizen.create-report', [
            'categories' => Crime::orderBy('category_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'crime_id'           => 'required|exists:crime,crime_id',
            'description'        => 'required|min:20',
            'location_latitude'  => 'required|numeric|between:-90,90',
            'location_longitude' => 'required|numeric|between:-180,180',
            'location_address'   => 'nullable|string|max:255',
            'evidence'           => 'nullable|array',
            'evidence.*'         => ['nullable', 'file', 'max:20480', new SafeEvidenceFile(allowVideo: true)],
        ]);

        $report = Report::create([
            ...collect($validated)->except(['evidence'])->all(),
            'stuff_id'      => auth()->user()->stuff_id,
            'tracking_code' => $this->generateTrackingCode(),
            'status'        => 'Submitted',
        ]);

        $this->storeEvidenceFiles($report, $request->file('evidence', []), allowVideo: true);

        $emailSent = $this->notifications->notifyReportSubmitted($report);

        return redirect()
            ->route('citizen.report.show', $report->report_id)
            ->with($this->submittedFlashPayload($report->tracking_code, $emailSent));
    }

    public function show(Report $report): View
    {
        abort_unless($report->stuff_id === auth()->user()->stuff_id, 403);

        $report->load(['crime', 'evidence', 'statusHistory']);

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
                'report_id' => $report->report_id,
                'file_name' => $uploaded['file_name'] ?? basename($uploaded['path']),
                'file_path' => $uploaded['path'],
                'file_type' => $uploaded['file_type'],
            ]);
        }
    }

    private function generateTrackingCode(): string
    {
        return strtoupper(substr(md5(uniqid((string) mt_rand(), true)), 0, 10));
    }

    /** @return array<string, string> */
    private function submittedFlashPayload(string $trackingCode, bool $emailSent): array
    {
        $payload = ['tracking_code' => $trackingCode];

        if ($emailSent) {
            $payload['success'] = "Report submitted successfully! Your tracking code is {$trackingCode}. A copy was sent to your email.";

            return $payload;
        }

        $payload['warning'] = "Report submitted successfully! Your tracking code is {$trackingCode}. We could not send a confirmation email — please save the code below.";

        return $payload;
    }
}
