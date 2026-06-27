<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SplashController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('splash_seen')) {
            return redirect()->to($this->destinationUrl($request));
        }

        return view('splash', [
            'continueUrl' => route('splash.continue'),
        ]);
    }

    public function continue(Request $request): RedirectResponse
    {
        $request->session()->put('splash_seen', true);

        $destination = $this->destinationUrl($request);
        $request->session()->forget('splash_intended_url');

        return redirect()->to($destination);
    }

    private function destinationUrl(Request $request): string
    {
        $intended = $request->session()->pull('splash_intended_url');

        if (is_string($intended) && $this->isSafeInternalUrl($intended)) {
            return str_starts_with($intended, '/')
                ? url($intended)
                : $intended;
        }

        $user = Auth::user();

        if ($user) {
            if ($user->isAdmin() || $user->isOfficer()) {
                return route('officer.dashboard');
            }

            if ($user->isCitizen()) {
                return route('citizen.dashboard');
            }
        }

        return route('home');
    }

    private function isSafeInternalUrl(string $url): bool
    {
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        $targetHost = parse_url($url, PHP_URL_HOST);

        if ($appHost && $targetHost && strcasecmp($appHost, $targetHost) !== 0) {
            return false;
        }

        return str_starts_with($url, config('app.url'))
            || str_starts_with($url, '/');
    }
}
