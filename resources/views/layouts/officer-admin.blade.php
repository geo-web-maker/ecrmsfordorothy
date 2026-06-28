<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="is-loading">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#012d1d">
    <title>{{ $pageTitle ? "{$pageTitle} — " : '' }}{{ config('app.name', 'NEMA eCRMS') }}</title>
    @include('partials.optimized-head')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,1&display=swap" rel="stylesheet">
    @vite(['resources/css/officer-admin.css', 'resources/js/officer-map.js'])
    @stack('styles')
</head>
<body class="bg-portal-surface text-portal-on-surface min-h-screen antialiased"
      x-data="{ sidebarOpen: false }"
      x-effect="document.body.classList.toggle('officer-sidebar-open', sidebarOpen)"
      @keydown.escape.window="sidebarOpen = false">

@include('partials.page-skeleton')

{{-- Mobile sidebar backdrop --}}
<div x-show="sidebarOpen" x-cloak
     @click="sidebarOpen = false"
     class="fixed inset-0 z-[60] bg-black/50 md:hidden"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"></div>

{{-- Mobile sidebar drawer --}}
<aside x-show="sidebarOpen" x-cloak
       class="fixed inset-y-0 left-0 z-[70] w-64 bg-portal-ink flex flex-col py-6 md:hidden shadow-2xl"
       x-transition:enter="transition ease-out duration-300 transform"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-200 transform"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full">
    @include('partials.officer-sidebar-nav', ['sidebarClose' => true])
</aside>

<div class="flex min-h-screen page-content">
    {{-- Desktop sidebar --}}
    <aside class="hidden md:flex h-screen w-64 fixed left-0 top-0 bg-portal-ink flex-col py-6 z-50 shadow-md">
        @include('partials.officer-sidebar-nav')
    </aside>

  <div class="flex flex-col flex-grow md:ml-64 min-h-screen w-full min-w-0">
        @include('partials.officer-topbar')

        <main class="flex-grow">
            {{ $slot }}
        </main>

        <footer class="py-6 px-4 sm:px-6 border-t border-portal-outline-variant bg-white text-portal-on-surface-variant flex flex-col md:flex-row justify-between items-center gap-3 text-xs">
            <div class="flex items-center gap-2 text-center md:text-left">
                <span class="material-symbols-outlined text-portal-secondary text-xl">shield_person</span>
                <span class="font-bold tracking-tight">NEMA Environmental Compliance &amp; Reporting Management System</span>
            </div>
            <p class="m-0">&copy; {{ date('Y') }} National Environment Management Authority. Internal Only.</p>
        </footer>
    </div>
</div>

@stack('scripts')
@include('partials.logout-confirm')
</body>
</html>
