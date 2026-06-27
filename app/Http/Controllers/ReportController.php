<?php

namespace App\Http\Controllers;

use App\Models\Crime;
use App\Models\Evidence;
use App\Models\Report;
use App\Rules\SafeEvidenceFile;
use App\Services\CitizenNotificationService;
use App\Services\SecureEvidenceUploader;
use App\Support\PhoneNumber;
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
        $request->merge([
            'reporter_phone' => PhoneNumber::stripSpaces((string) $request->input('reporter_phone', '')),
        ]);

        $validated = $request->validate([
            'crime_id'       => 'required|exists:crime,crime_id',
            'description'    => 'required|min:20',
            'reporter_phone' => PhoneNumber::validationRules(),
            'evidence'       => 'nullable|array',
            'evidence.*'     => ['nullable', 'file', 'max:20480', new SafeEvidenceFile(allowVideo: false)],
        ], [
            'reporter_phone.required' => 'A phone number is required to receive your tracking code by SMS.',
            'reporter_phone.regex'    => 'Enter a valid Ugandan mobile number (e.g. 0712345678 or +256712345678).',
        ]);

        $trackingCode = $this->generateTrackingCode();

        $report = Report::create([
            'crime_id'       => $validated['crime_id'],
            'description'    => $validated['description'],
            'stuff_id'       => null,
            'tracking_code'  => $trackingCode,
            'reporter_phone' => PhoneNumber::normalize($validated['reporter_phone']),
            'status'         => 'Submitted',
        ]);

        $this->storeEvidenceFiles($report, $request->file('evidence', []), allowVideo: false);

        $delivery = $this->notifications->notifyReportSubmitted($report);

        return redirect()->route('home')->with(
            $this->submittedFlashPayload($trackingCode, $delivery, anonymous: true)
        );
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
            'crime_id'    => 'required|exists:crime,crime_id',
            'description' => 'required|min:20',
            'evidence'    => 'nullable|array',
            'evidence.*'  => ['nullable', 'file', 'max:20480', new SafeEvidenceFile(allowVideo: true)],
        ]);

        $report = Report::create([
            ...collect($validated)->except(['evidence'])->all(),
            'stuff_id'      => auth()->user()->stuff_id,
            'tracking_code' => $this->generateTrackingCode(),
            'status'        => 'Submitted',
        ]);

        $this->storeEvidenceFiles($report, $request->file('evidence', []), allowVideo: true);

        $delivery = $this->notifications->notifyReportSubmitted($report);

        return redirect()
            ->route('citizen.report.show', $report->report_id)
            ->with($this->submittedFlashPayload($report->tracking_code, $delivery, anonymous: false));
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

    /**
     * @param  array{sms_sent: bool, email_sent: bool, has_phone: bool, has_email: bool}  $delivery
     * @return array<string, string>
     */
    private function submittedFlashPayload(string $trackingCode, array $delivery, bool $anonymous): array
    {
        $prefix = $anonymous
            ? 'Anonymous report submitted!'
            : 'Report submitted successfully!';

        $payload = ['tracking_code' => $trackingCode];

        if ($delivery['sms_sent'] && $delivery['email_sent']) {
            $payload['success'] = "{$prefix} Your tracking code is {$trackingCode}. A copy was sent to your phone and email.";

            return $payload;
        }

        if ($delivery['sms_sent']) {
            $payload['success'] = "{$prefix} Your tracking code is {$trackingCode}. A copy was sent to your phone.";

            return $payload;
        }

        if ($delivery['email_sent']) {
            $payload['success'] = "{$prefix} Your tracking code is {$trackingCode}. A copy was sent to your email.";

            return $payload;
        }

        $payload['warning'] = "{$prefix} Your tracking code is {$trackingCode}. We could not deliver it by SMS"
            .($delivery['has_email'] ? ' or email' : '')
            .' — please save the code below.';

        return $payload;
    }
}
