<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="is-loading">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#3F6B2A">

        <title>{{ config('app.name', 'NEMA eCRMS') }}</title>

        @include('partials.optimized-head')

        <style>
            /* ── Base reset ────────────────────────────── */
            *, *::before, *::after { box-sizing: border-box; }

            html {
                -webkit-text-size-adjust: 100%;
                scroll-behavior: smooth;
            }

            body, input, button, textarea, select, optgroup {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                font-size: 14px;
                line-height: 1.5;
                color: #1F3318;
            }

            body {
                background: #F3F5EA;
                min-height: 100vh;
                margin: 0;
                padding: 0;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                overflow-x: hidden;
            }

            /* ── Native form element normalisation (iOS fix) ── */
            input, textarea, select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                border-radius: 12px;
                border: 2px solid #D8DECB;
                background: white;
                color: #1F3318;
                padding: 10px 14px;
                width: 100%;
                font-size: 14px;
                font-weight: 500;
                transition: border-color 0.15s, box-shadow 0.15s;
                outline: none;
            }

            select {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%235E8B3D' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                padding-right: 40px;
            }

            input:focus, textarea:focus, select:focus {
                border-color: #5E8B3D;
                box-shadow: 0 0 0 3px rgba(94,139,61,0.15);
            }

            input::placeholder, textarea::placeholder {
                color: #9CA68C;
                font-weight: 400;
            }

            button {
                cursor: pointer;
                border: none;
                font-weight: 600;
            }

            a { text-decoration: none; }

            img, video { max-width: 100%; height: auto; display: block; }
            img[loading="lazy"] { content-visibility: auto; }

            /* ── System card ───────────────────────────── */
            .sys-card {
                background: white;
                border: 1px solid rgba(94,139,61,0.15);
                border-radius: 16px;
                box-shadow: 0 1px 4px rgba(63,107,42,0.06);
                overflow: hidden;
            }

            .sys-card-header {
                padding: 14px 20px;
                background: #FAFBF7;
                border-bottom: 1px solid rgba(94,139,61,0.10);
            }

            .sys-card-body { padding: 20px; }

            /* ── System button ─────────────────────────── */
            .sys-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 10px 20px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 700;
                transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
                min-height: 44px;   /* touch target */
                white-space: nowrap;
            }

            .sys-btn-primary {
                background: #3F6B2A;
                color: white;
                box-shadow: 0 2px 8px rgba(63,107,42,0.25);
            }

            .sys-btn-primary:hover {
                background: #2C4424;
                box-shadow: 0 4px 16px rgba(63,107,42,0.35);
                transform: translateY(-1px);
            }

            .sys-btn-secondary {
                background: white;
                color: #3F6B2A;
                border: 2px solid #D8DECB;
            }

            .sys-btn-secondary:hover {
                background: #F3F5EA;
                border-color: rgba(94,139,61,0.4);
            }

            .sys-btn-danger {
                background: #c0392b;
                color: white;
            }

            .sys-btn-danger:hover { background: #a63022; }

            /* ── Page container ────────────────────────── */
            .page-wrap {
                padding: 24px 16px;
                max-width: 1200px;
                margin: 0 auto;
            }

            @media (min-width: 640px) {
                .page-wrap { padding: 32px 24px; }
            }

            @media (min-width: 1024px) {
                .page-wrap { padding: 40px 32px; }
            }

            /* ── Section label ─────────────────────────── */
            .sys-label {
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 1.2px;
                text-transform: uppercase;
                color: #3F6B2A;
                display: block;
                margin-bottom: 6px;
            }

            /* ── Status timeline ───────────────────────── */
            .sys-timeline { position: relative; padding-left: 20px; }
            .sys-timeline::before {
                content: '';
                position: absolute;
                left: 6px;
                top: 6px;
                bottom: 6px;
                width: 2px;
                background: rgba(94,139,61,0.2);
            }
            .sys-timeline-dot {
                position: absolute;
                left: -20px;
                top: 4px;
                width: 14px;
                height: 14px;
                border-radius: 50%;
                background: #EAF1DD;
                border: 2px solid #3F6B2A;
            }

            /* ── Badge ─────────────────────────────────── */
            .sys-badge {
                display: inline-flex;
                align-items: center;
                padding: 2px 10px;
                border-radius: 999px;
                font-size: 11px;
                font-weight: 700;
            }

            /* ── Prevent horizontal scroll on mobile ──── */
            .overflow-x-safe { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        </style>
        @stack('styles')
    </head>
    <body>
        @include('partials.page-skeleton')

        @include('layouts.navigation')

        <div class="min-h-screen page-content">

            <!-- Page Heading -->
            @isset($header)
                <header style="background: white; border-bottom: 1px solid rgba(94,139,61,0.12); box-shadow: 0 1px 4px rgba(63,107,42,0.06);">
                    <div style="max-width:1200px; margin:0 auto; padding: 16px 16px;">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
        @include('partials.logout-confirm')
    </body>
</html>
