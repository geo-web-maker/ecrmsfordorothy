<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anonymous Report — {{ config('app.name', 'ECRMS') }}</title>
    <meta name="description" content="Securely and anonymously report environmental crimes to NEMA. Your identity is fully protected.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(150deg,
                #7B8F69 0%,
                #DDE8C8 55%,
                #F3F5EA 100%);
        }
        .report-page::before,
        .report-page::after {
            content: '';
            position: fixed;
            border-radius: 9999px;
            pointer-events: none;
            z-index: 0;
        }
        .report-page::before {
            width: 520px; height: 520px;
            top: -140px; left: -140px;
            background: radial-gradient(circle, rgba(94,139,61,0.18) 0%, transparent 70%);
        }
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #map { width: 100%; }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">

<!-- Shared nav bar -->
<header class="fixed top-0 left-0 w-full z-[1000] transition-all duration-300" style="background: rgba(255, 255, 255, 0.88); backdrop-filter: blur(18px); border-bottom: 1px solid rgba(94, 139, 61, 0.15);">
    <div class="max-w-screen-xl mx-auto flex items-center justify-between px-8 py-[0.6rem]">
        <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline font-extrabold text-lg text-gray-900 tracking-tight">🌿 NEMA <span class="text-[#5E8B3D]">eCRMS</span></a>
        <nav class="flex items-center gap-8">
            <a href="{{ route('report.anonymous') }}" class="no-underline text-[#5E8B3D] font-bold text-sm transition-colors hover:text-[#3F6B2A]">Report Crime</a>
            <a href="{{ route('report.track') }}" class="no-underline text-gray-600 font-semibold text-sm transition-colors hover:text-[#5E8B3D]">Track Case</a>
        </nav>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ auth()->user()->isCitizen() ? route('citizen.dashboard') : route('officer.dashboard') }}" class="inline-flex items-center justify-center px-[1.4rem] py-[0.6rem] rounded-full font-semibold text-sm no-underline border-2 border-[#5E8B3D] text-[#5E8B3D] bg-transparent transition-all duration-300 hover:bg-[#5E8B3D] hover:text-white hover:-translate-y-0.5">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="font-semibold text-sm cursor-pointer rounded-full px-[1.4rem] py-[0.6rem] transition-all duration-300 border-1.5" style="background: rgba(220, 53, 69, 0.08); color: #c0392b; border: 1.5px solid rgba(220, 53, 69, 0.3);" onmouseover="this.style.background='#dc3545'; this.style.color='#fff'; this.style.borderColor='#dc3545';" onmouseout="this.style.background='rgba(220, 53, 69, 0.08)'; this.style.color='#c0392b'; this.style.borderColor='rgba(220, 53, 69, 0.3)';">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="no-underline text-gray-600 font-semibold text-sm transition-colors hover:text-[#5E8B3D]">Log in</a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-[1.4rem] py-[0.6rem] rounded-full font-semibold text-sm no-underline bg-[#5E8B3D] text-white border-2 border-[#5E8B3D] transition-all duration-300 hover:bg-[#3F6B2A] hover:-translate-y-0.5" style="box-shadow: 0 2px 8px rgba(94, 139, 61, 0.3);">Register</a>
            @endauth
        </div>
    </div>
</header>

<!-- Main page -->
<div class="report-page min-h-screen px-6 pt-32 pb-16 relative overflow-x-hidden font-sans">
    <!-- Decorative leaves -->
    <span class="fixed pointer-events-none opacity-5 z-0" style="font-size: 160px; line-height: 1; top: 5%; left: 2%; transform: rotate(-25deg);">🌿</span>
    <span class="fixed pointer-events-none opacity-5 z-0" style="font-size: 160px; line-height: 1; bottom: 5%; right: 2%; transform: rotate(155deg);">🌿</span>

    <div class="relative max-w-[760px] mx-auto" style="z-index: 1;">

        <!-- Back link -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-[0.4rem] text-[#3F6B2A] text-sm font-semibold no-underline mb-8 transition-all hover:gap-[0.65rem]">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Home
        </a>

        <!-- Heading -->
        <div class="text-center mb-10" style="animation: fadeInUp 0.8s ease-out;">
            <div class="inline-flex items-center gap-2 text-[#3F6B2A] font-bold text-[0.78rem] uppercase tracking-[1px] mb-5 px-[1.1rem] py-[0.4rem] rounded-full" style="background: rgba(94,139,61,0.12); border: 1.5px solid rgba(94,139,61,0.25);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                100% Anonymous &amp; Encrypted
            </div>
            <h1 class="text-[2.4rem] font-extrabold text-gray-900 tracking-[-0.75px] leading-[1.2] mb-3">Anonymous Environmental<br>Crime Report</h1>
            <p class="text-base text-gray-600 max-w-[540px] mx-auto leading-[1.65]">Your identity is protected. Submit environmental violations safely and anonymously — you'll receive a secure tracking code after submission.</p>
        </div>

        <!-- Flash messages -->
        @include('partials.flash')

        <!-- Form card -->
        <div class="rounded-[30px] p-11" style="animation: fadeInUp 0.8s ease-out 0.1s both; background: rgba(243, 245, 234, 0.82); backdrop-filter: blur(20px); border: 1.5px solid rgba(94, 139, 61, 0.18); box-shadow: 0 8px 40px rgba(63, 107, 42, 0.12), 0 1px 0 rgba(255,255,255,0.8) inset;">
            <form method="POST" action="{{ route('report.anonymous.store') }}" enctype="multipart/form-data" id="reportForm" novalidate>
                @csrf

                <!-- Section: Basic Info -->
                <div class="text-[0.7rem] font-bold uppercase tracking-[1px] text-[#7B8F69] mb-5 flex items-center gap-2">
                    Incident Information
                    <div class="flex-1 h-px" style="background: rgba(94,139,61,0.15);"></div>
                </div>

                <!-- Incident Title -->
                <div class="mb-[1.6rem]">
                    <label class="flex items-center gap-[0.45rem] text-sm font-semibold text-[#9aad8a] mb-2" for="title">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Incident Title
                    </label>
                    <input type="text" name="title" id="title" class="w-full text-gray-900 font-sans text-[0.93rem] outline-none px-5 py-[0.85rem] border-2 rounded-full border-[rgba(94,139,61,0.22)] bg-[rgba(255,255,255,0.80)] transition-all hover:border-[rgba(94,139,61,0.45)] focus:bg-white focus:border-[#5E8B3D] focus:ring-2 focus:ring-[rgba(94,139,61,0.12)] @error('title') border-[rgba(192,57,43,0.55)] @enderror" placeholder="e.g., Illegal logging in Mabira Forest Reserve" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="flex items-center gap-[0.35rem] text-[0.78rem] mt-[0.4rem] pl-2 text-red-600">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Two-column: Category + Date -->
                <div class="grid gap-5 mb-[1.6rem]" style="grid-template-columns: 1fr 1fr;">
                    <!-- Crime Category -->
                    <div>
                        <label class="flex items-center gap-[0.45rem] text-sm font-semibold text-[#9aad8a] mb-2" for="crime_category_id">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            Crime Category
                        </label>
                        <div class="relative">
                            <select name="crime_category_id" id="crime_category_id" class="w-full text-gray-900 font-sans text-[0.93rem] outline-none px-5 py-[0.85rem] border-2 rounded-full border-[rgba(94,139,61,0.22)] bg-[rgba(255,255,255,0.80)] appearance-none pr-10 transition-all hover:border-[rgba(94,139,61,0.45)] focus:bg-white focus:border-[#5E8B3D] focus:ring-2 focus:ring-[rgba(94,139,61,0.12)] @error('crime_category_id') border-[rgba(192,57,43,0.55)] @enderror cursor-pointer" required>
                                <option value="">Select category…</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('crime_category_id') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none w-0 h-0" style="border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 6px solid #5E8B3D;"></div>
                        </div>
                        @error('crime_category_id')
                            <div class="flex items-center gap-[0.35rem] text-[0.78rem] mt-[0.4rem] pl-2 text-red-600">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Date of Incident -->
                    <div>
                        <label class="flex items-center gap-[0.45rem] text-sm font-semibold text-[#9aad8a] mb-2" for="incident_date">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Date of Incident
                        </label>
                        <input type="date" name="incident_date" id="incident_date" class="w-full text-gray-900 font-sans text-[0.93rem] outline-none px-5 py-[0.85rem] border-2 rounded-full border-[rgba(94,139,61,0.22)] bg-[rgba(255,255,255,0.80)] transition-all hover:border-[rgba(94,139,61,0.45)] focus:bg-white focus:border-[#5E8B3D] focus:ring-2 focus:ring-[rgba(94,139,61,0.12)] @error('incident_date') border-[rgba(192,57,43,0.55)] @enderror" value="{{ old('incident_date') }}" max="{{ date('Y-m-d') }}">
                        @error('incident_date')
                            <div class="flex items-center gap-[0.35rem] text-[0.78rem] mt-[0.4rem] pl-2 text-red-600">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-[1.6rem]">
                    <label class="flex items-center gap-[0.45rem] text-sm font-semibold text-[#9aad8a] mb-2" for="description">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                        Full Description
                    </label>
                    <textarea name="description" id="description" rows="6" class="w-full text-gray-900 font-sans text-[0.93rem] outline-none px-5 py-4 border-2 rounded-[20px] border-[rgba(94,139,61,0.22)] bg-[rgba(255,255,255,0.80)] min-h-[140px] box-shadow: inset 0 2px 6px rgba(0,0,0,0.04) resize-y transition-all hover:border-[rgba(94,139,61,0.45)] focus:bg-white focus:border-[#5E8B3D] focus:ring-2 focus:ring-[rgba(94,139,61,0.12)] @error('description') border-[rgba(192,57,43,0.55)] @enderror" placeholder="Describe what you witnessed in detail — include what happened, how many people were involved, any vehicle or equipment details, and anything else that could help investigators..." required minlength="20">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="flex items-center gap-[0.35rem] text-[0.78rem] mt-[0.4rem] pl-2 text-red-600">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <hr class="border-none my-8" style="border-top: 1.5px solid rgba(94,139,61,0.12);">

                <!-- Section: Location -->
                <div class="text-[0.7rem] font-bold uppercase tracking-[1px] text-[#7B8F69] mb-5 flex items-center gap-2">
                    Location
                    <div class="flex-1 h-px" style="background: rgba(94,139,61,0.15);"></div>
                </div>

                <!-- Location text input -->
                <div class="mb-[1.6rem]">
                    <label class="flex items-center gap-[0.45rem] text-sm font-semibold text-[#9aad8a] mb-2" for="location_name">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Location / Coordinates
                    </label>
                    <input type="text" name="location_name" id="location_name" class="w-full text-gray-900 font-sans text-[0.93rem] outline-none px-5 py-[0.85rem] border-2 rounded-full border-[rgba(94,139,61,0.22)] bg-[rgba(255,255,255,0.80)] transition-all hover:border-[rgba(94,139,61,0.45)] focus:bg-white focus:border-[#5E8B3D] focus:ring-2 focus:ring-[rgba(94,139,61,0.12)]" placeholder="e.g., Mabira Forest Reserve, or coordinates 0.3476, 32.5825" value="{{ old('location_name') }}">
                </div>

                <!-- Interactive Map -->
                <div class="mb-[1.6rem]">
                    <div class="overflow-hidden relative rounded-[20px] border-2 border-[rgba(94,139,61,0.22)]" style="box-shadow: 0 4px 18px rgba(63,107,42,0.10);">
                        <div id="map" class="w-full" style="height: 240px; z-index: 0;"></div>
                        <button type="button" class="absolute bottom-3 right-3 text-white border-none rounded-full text-[0.8rem] font-semibold cursor-pointer flex items-center gap-[0.4rem] font-sans z-[400] px-[1.1rem] py-2 transition-all hover:-translate-y-0.5" id="useMyLocation" style="background: rgba(94,139,61,0.92); backdrop-filter: blur(6px);" onmouseover="this.style.background='#3F6B2A';" onmouseout="this.style.background='rgba(94,139,61,0.92)';">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><circle cx="12" cy="12" r="1"/></svg>
                            Use My Location
                        </button>
                    </div>
                    <div class="text-[0.78rem] text-[#7B8F69] mt-2 flex items-center gap-[0.35rem]">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Click or drag the pin on the map to set the exact incident location
                    </div>
                    <input type="hidden" name="location_latitude" id="location_latitude" value="{{ old('location_latitude', '0.3476') }}">
                    <input type="hidden" name="location_longitude" id="location_longitude" value="{{ old('location_longitude', '32.5825') }}">
                </div>

                <hr class="border-none my-8" style="border-top: 1.5px solid rgba(94,139,61,0.12);">

                <!-- Section: Evidence -->
                <div class="text-[0.7rem] font-bold uppercase tracking-[1px] text-[#7B8F69] mb-5 flex items-center gap-2">
                    Evidence Upload
                    <div class="flex-1 h-px" style="background: rgba(94,139,61,0.15);"></div>
                </div>

                <!-- File Upload -->
                <div class="mb-[1.6rem]">
                    <label class="flex items-center gap-[0.45rem] text-sm font-semibold text-[#9aad8a] mb-2">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                        Photos &amp; Evidence
                        <span style="font-weight:400; color:#9aad8a;">(optional)</span>
                    </label>
                    <div class="text-center cursor-pointer relative border-2 border-dashed border-[rgba(94,139,61,0.35)] rounded-[20px] px-6 py-8 bg-[rgba(255,255,255,0.55)] transition-all hover:border-[#5E8B3D] hover:bg-[rgba(94,139,61,0.06)]" id="dropZone" style="appearance: none;">
                        <input type="file" name="evidence[]" id="evidenceInput" multiple accept="image/jpeg,image/png,image/jpg,video/mp4" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                        <span class="text-[2.2rem] mb-[0.6rem] block">🌿</span>
                        <div class="font-bold text-[#9aad8a] text-[0.95rem] mb-[0.25rem]">Drag &amp; drop files here</div>
                        <div class="text-[0.8rem] text-[#7B8F69]">or click to browse — JPG, PNG, MP4 &bull; Max 50MB each</div>
                    </div>
                    <div class="flex flex-wrap gap-3 mt-4" id="filePreviews"></div>
                </div>

                <hr class="border-none my-8" style="border-top: 1.5px solid rgba(94,139,61,0.12);">

                <!-- Submit -->
                <button type="submit" class="w-full text-white font-sans text-base font-bold border-none rounded-full cursor-pointer flex items-center justify-center gap-[0.6rem] mt-2 px-8 py-4 transition-all hover:-translate-y-0.5" style="background: #5E8B3D; box-shadow: 0 6px 20px rgba(94,139,61,0.35);" onmouseover="this.style.background='#3F6B2A'; this.style.boxShadow='0 10px 28px rgba(63,107,42,0.40)';" onmouseout="this.style.background='#5E8B3D'; this.style.boxShadow='0 6px 20px rgba(94,139,61,0.35)';">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Submit Anonymous Report
                </button>
            </form>
        </div>

        <!-- Trust Badges -->
        <div class="flex justify-center flex-wrap gap-4 mt-8" style="animation: fadeInUp 0.8s ease-out 0.3s both;">
            <div class="inline-flex items-center gap-2 text-[#9aad8a] text-[0.78rem] font-semibold rounded-full px-4 py-[0.45rem]" style="background: rgba(255,255,255,0.70); backdrop-filter: blur(8px); border: 1px solid rgba(94,139,61,0.18); box-shadow: 0 2px 8px rgba(63,107,42,0.08);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#5E8B3D]"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                256-bit Encryption
            </div>
            <div class="inline-flex items-center gap-2 text-[#9aad8a] text-[0.78rem] font-semibold rounded-full px-4 py-[0.45rem]" style="background: rgba(255,255,255,0.70); backdrop-filter: blur(8px); border: 1px solid rgba(94,139,61,0.18); box-shadow: 0 2px 8px rgba(63,107,42,0.08);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#5E8B3D]"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                No Personal Data Stored
            </div>
            <div class="inline-flex items-center gap-2 text-[#9aad8a] text-[0.78rem] font-semibold rounded-full px-4 py-[0.45rem]" style="background: rgba(255,255,255,0.70); backdrop-filter: blur(8px); border: 1px solid rgba(94,139,61,0.18); box-shadow: 0 2px 8px rgba(63,107,42,0.08);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#5E8B3D]"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Hash-Generated Tracking Code
            </div>
        </div>

        <!-- Footer note -->
        <div class="text-center mt-6 text-[0.82rem] text-gray-600 flex items-center justify-center gap-[0.4rem]" style="animation: fadeInUp 0.8s ease-out 0.35s both;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            After submission, you will receive an anonymous tracking code to monitor your case.
        </div>

    </div>
</div>

<!-- Leaflet Map JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ── Leaflet Map ──
    const initLat = parseFloat(document.getElementById('location_latitude').value);
    const initLng = parseFloat(document.getElementById('location_longitude').value);
    const map = L.map('map').setView([initLat, initLng], 8);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Custom green marker icon
    const greenIcon = L.divIcon({
        html: `<div style="
            width:28px;height:28px;background:#5E8B3D;
            border:3px solid #fff;border-radius:50% 50% 50% 0;
            transform:rotate(-45deg);
            box-shadow:0 3px 10px rgba(0,0,0,0.25);">
        </div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        className: ''
    });

    const marker = L.marker([initLat, initLng], { draggable: true, icon: greenIcon }).addTo(map);

    const setCoords = (lat, lng) => {
        document.getElementById('location_latitude').value = lat.toFixed(8);
        document.getElementById('location_longitude').value = lng.toFixed(8);
    };

    map.on('click', (e) => { marker.setLatLng(e.latlng); setCoords(e.latlng.lat, e.latlng.lng); });
    marker.on('dragend', () => { const p = marker.getLatLng(); setCoords(p.lat, p.lng); });

    // Use My Location button
    document.getElementById('useMyLocation').addEventListener('click', () => {
        if (!navigator.geolocation) return alert('Geolocation not supported by your browser.');
        navigator.geolocation.getCurrentPosition((pos) => {
            const { latitude, longitude } = pos.coords;
            marker.setLatLng([latitude, longitude]);
            map.setView([latitude, longitude], 13);
            setCoords(latitude, longitude);
        }, () => alert('Unable to retrieve your location. Please pin it manually on the map.'));
    });

    // ── File drag-and-drop & image previews ──
    const dropZone  = document.getElementById('dropZone');
    const fileInput = document.getElementById('evidenceInput');
    const previews  = document.getElementById('filePreviews');

    const renderPreviews = (files) => {
        previews.innerHTML = '';
        Array.from(files).forEach((file) => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const card = document.createElement('div');
                card.className = 'file-preview-card';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = file.name;
                card.appendChild(img);
                previews.appendChild(card);
            };
            reader.readAsDataURL(file);
        });
    };

    fileInput.addEventListener('change', () => renderPreviews(fileInput.files));

    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        fileInput.files = e.dataTransfer.files;
        renderPreviews(e.dataTransfer.files);
    });
</script>

</body>
</html>
