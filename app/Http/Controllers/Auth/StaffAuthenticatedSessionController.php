<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StaffAuthenticatedSessionController extends Controller
{
    /**
     * Display the staff login view.
     */
    public function create(): View
    {
        $totalReports = Report::count();
        $resolvedCount = Report::whereIn('status', ['Resolved', 'Closed'])->count();
        $resolvedPercent = $totalReports > 0
            ? (int) round(($resolvedCount / $totalReports) * 100)
            : 0;

        $recentReports = Report::with('crime')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $liveFeed = $recentReports->map(function (Report $report) {
            $status = $report->status ?? 'Submitted';
            $color = match ($status) {
                'Resolved', 'Closed' => 'res',
                'Under Review', 'Assigned' => 'prog',
                default => 'new',
            };

            $category = $report->crime?->category_name ?? 'Environmental incident';
            $type = $status === 'Resolved'
                ? $category.' — Resolved'
                : $category;

            return [
                'id'    => $report->tracking_code
                    ? '#'.$report->tracking_code
                    : '#RPT-'.$report->report_id,
                'type'  => $type,
                'time'  => $report->created_at?->diffForHumans(short: true) ?? 'recently',
                'color' => $color,
            ];
        });

        if ($liveFeed->isEmpty()) {
            $liveFeed = collect([
                ['id' => '#NEMA-7392A', 'type' => 'Illegal Logging — Mabira', 'time' => '2m ago', 'color' => 'new'],
                ['id' => '#NEMA-7388C', 'type' => 'Wetland Encroachment', 'time' => '14m ago', 'color' => 'prog'],
                ['id' => '#NEMA-7381B', 'type' => 'Water Pollution — Resolved', 'time' => '1h ago', 'color' => 'res'],
            ]);
        }

        return view('auth.admin-login', [
            'totalReports'    => $totalReports,
            'resolvedPercent' => $resolvedPercent,
            'liveFeed'        => $liveFeed,
        ]);
    }

    /**
     * Handle an incoming staff authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->isOfficer()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'This portal is for NEMA staff only. Citizens should use the public login.',
            ]);
        }

        $destination = $user->isOfficer()
            ? route('officer.reports')
            : route('officer.dashboard');

        return redirect()->intended($destination);
    }
}
