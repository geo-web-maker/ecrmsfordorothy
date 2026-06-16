<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Submit Crime Report — {{ config('app.name', 'ECRMS') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    <style>
        #crime-map { height: 380px; border-radius: 0.75rem; z-index: 0; }
        .step-pane { display: none; }
        .step-pane.active { display: block; }
        .preview-thumb { width: 88px; height: 88px; object-fit: cover; border-radius: 0.5rem; }
        .step-tab.disabled { cursor: not-allowed; opacity: .55; }
        .step-tab:not(.disabled) { cursor: pointer; }
    </style>
</head>
<body class="bg-[#F6F6EE] font-sans text-[#1F2A1C] antialiased">
    <nav class="bg-[#2C4424] text-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a class="flex items-center gap-2 font-semibold text-lg" href="{{ route('citizen.dashboard') }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 4 6.5V11c0 5 3.4 8.4 8 9.9 4.6-1.5 8-4.9 8-9.9V6.5L12 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                    </svg>
                    NEMA ECRMS
                </a>
                <div class="flex items-center gap-4">
                    <a class="text-white/90 hover:text-white text-sm font-medium" href="{{ route('citizen.dashboard') }}">My Reports</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-lg border border-white/70 text-white hover:bg-[#1F3318] transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto py-10 px-4">
        <div class="mb-7">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#4A6B3A]">Citizen Portal</p>
            <h1 class="mt-1 text-3xl font-bold text-[#1F3318]">Submit environmental crime report</h1>
            <p class="mt-2 text-[#5F6B57]">Provide details, mark the location on the map, and attach photo evidence.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-[#EAF1DD] border border-[#CFDFB7] text-[#27500A] rounded-xl flex items-start justify-between gap-3">
                <span class="text-sm">{{ session('success') }}</span>
                <button type="button" class="text-[#27500A]/70 hover:text-[#27500A]" onclick="this.closest('div').style.display='none';" aria-label="Dismiss">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.3 5.3a1 1 0 0 1 1.4 0L10 8.6l3.3-3.3a1 1 0 1 1 1.4 1.4L11.4 10l3.3 3.3a1 1 0 0 1-1.4 1.4L10 11.4l-3.3 3.3a1 1 0 0 1-1.4-1.4L8.6 10 5.3 6.7a1 1 0 0 1 0-1.4z" clip-rule="evenodd" /></svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
                <strong class="block mb-2 text-sm font-semibold">Please fix the following:</strong>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-[#E1E7D6] overflow-hidden">
            <div class="border-b border-[#EDF0E5] px-6 pt-6 pb-5">
                <div class="flex" id="stepTabs" role="tablist">
                    @php $stepLabels = ['Category', 'Details', 'Location', 'Evidence', 'Review']; @endphp
                    @foreach ($stepLabels as $i => $label)
                        @php $n = $i + 1; @endphp
                        @if ($n > 1)
                            <div class="step-line relative -ml-[50%] mt-5 h-0.5 flex-1 bg-[#D8DECB]" style="z-index:0;" data-line="{{ $n }}"></div>
                        @endif
                        <div class="relative flex-1">
                            <button type="button" class="step-tab relative z-10 flex w-full flex-col items-center gap-1.5 bg-transparent" data-step="{{ $n }}">
                                <span class="step-circle flex h-10 w-10 items-center justify-center rounded-full border-2 text-sm font-semibold transition-colors {{ $n === 1 ? 'border-[#2C4424] bg-white text-[#2C4424] ring-2 ring-[#2C4424]/15' : 'border-[#D8DECB] bg-white text-[#9CA68C]' }}">{{ $n }}</span>
                                <span class="step-label text-xs font-medium {{ $n === 1 ? 'text-[#1F3318]' : 'text-[#9CA68C]' }}">{{ $label }}</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-6">
                <form id="report-form" method="POST" action="{{ route('citizen.report.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf

                    {{-- Step 1: Category --}}
                    <div class="step-pane active" data-step="1">
                        <label for="crime_category_id" class="block text-sm font-semibold text-[#1F3318] mb-2">Crime category <span class="text-red-600">*</span></label>
                        <select name="crime_category_id" id="crime_category_id" class="w-full px-4 py-3 text-base border border-[#D8DECB] rounded-xl @error('crime_category_id') border-red-400 @enderror focus:border-[#2C4424] focus:ring-2 focus:ring-[#2C4424]/20 outline-none transition" required>
                            <option value="">— Select a category —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('crime_category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('crime_category_id')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                        <div class="text-[#6B7568] text-sm mt-3">Choose the type of environmental violation you are reporting.</div>
                    </div>

                    {{-- Step 2: Description --}}
                    <div class="step-pane" data-step="2">
                        <label for="description" class="block text-sm font-semibold text-[#1F3318] mb-2">Incident description <span class="text-red-600">*</span></label>
                        <textarea name="description" id="description" rows="6" class="w-full px-4 py-3 border border-[#D8DECB] rounded-xl @error('description') border-red-400 @enderror focus:border-[#2C4424] focus:ring-2 focus:ring-[#2C4424]/20 outline-none transition" placeholder="Describe what happened, when, and who was involved (minimum 20 characters)…" required minlength="20">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                        <div class="flex justify-between text-sm text-[#6B7568] mt-2">
                            <span>Be as specific as possible.</span>
                            <span><span id="char-count">0</span> / 20 min</span>
                        </div>
                    </div>

                    {{-- Step 3: Location (Leaflet) --}}
                    <div class="step-pane" data-step="3">
                        <p class="flex items-center gap-1.5 text-sm text-[#5F6B57] mb-4">
                            <svg class="h-4 w-4 text-[#3F5C34]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a6 6 0 0 0-6 6c0 4.2 6 10 6 10s6-5.8 6-10a6 6 0 0 0-6-6zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" clip-rule="evenodd" /></svg>
                            Click the map or drag the marker to set the incident location.
                        </p>
                        <div id="crime-map" class="border border-[#D8DECB] mb-4"></div>
                        <input type="hidden" name="location_latitude" id="location_latitude" value="{{ old('location_latitude', '0.347596') }}">
                        <input type="hidden" name="location_longitude" id="location_longitude" value="{{ old('location_longitude', '32.582520') }}">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm text-[#5F6B57] font-medium mb-1">Latitude</label>
                                <input type="text" id="lat_display" class="w-full px-3 py-2 text-sm border border-[#D8DECB] rounded-xl bg-[#F6F8F1] text-[#3A4233]" readonly>
                            </div>
                            <div>
                                <label class="block text-sm text-[#5F6B57] font-medium mb-1">Longitude</label>
                                <input type="text" id="lng_display" class="w-full px-3 py-2 text-sm border border-[#D8DECB] rounded-xl bg-[#F6F8F1] text-[#3A4233]" readonly>
                            </div>
                        </div>
                        <div>
                            <label for="location_address" class="block text-sm font-semibold text-[#1F3318] mb-2">Address / landmark (optional)</label>
                            <input type="text" name="location_address" id="location_address" value="{{ old('location_address') }}" class="w-full px-4 py-2 border border-[#D8DECB] rounded-xl @error('location_address') border-red-400 @enderror focus:border-[#2C4424] focus:ring-2 focus:ring-[#2C4424]/20 outline-none transition" placeholder="e.g. Near Mabira Forest, Buikwe District">
                            @error('location_address')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Step 4: Evidence upload --}}
                    <div class="step-pane" data-step="4">
                        <label for="evidence" class="block text-sm font-semibold text-[#1F3318] mb-2">Photo / video evidence (optional)</label>
                        <div class="rounded-xl border-2 border-dashed border-[#D8DECB] bg-[#FAFBF7] p-4">
                            <input type="file" name="evidence[]" id="evidence" class="w-full text-sm text-[#5F6B57] file:mr-4 file:rounded-lg file:border-0 file:bg-[#EAF1DD] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#2C4424] hover:file:bg-[#DCE5CC] @error('evidence') border-red-400 @enderror @error('evidence.*') border-red-400 @enderror" accept="image/jpeg,image/png,image/jpg,video/mp4,video/quicktime" multiple>
                        </div>
                        @error('evidence')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                        @error('evidence.*')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                        <div class="text-[#6B7568] text-sm mt-2">JPG, PNG, MP4, MOV — max 20 MB per file. You may select multiple files.</div>
                        <div id="file-preview" class="flex flex-wrap gap-2 mt-4"></div>
                    </div>

                    {{-- Step 5: Review --}}
                    <div class="step-pane" data-step="5">
                        <div class="p-5 bg-[#F6F8F1] border border-[#E1E7D6] rounded-xl mb-4">
                            <h2 class="font-semibold text-[#1F3318] mb-3">Review your report</h2>
                            <dl class="grid grid-cols-3 gap-y-3 text-sm" id="review-summary"></dl>
                        </div>
                        <p class="text-sm text-[#6B7568] mb-0">
                            By submitting, you confirm this information is accurate to the best of your knowledge.
                        </p>
                    </div>

                    <div class="flex justify-between mt-6 pt-4 border-t border-[#EDF0E5]">
                        <button type="button" class="inline-flex items-center gap-1.5 px-4 py-2 text-[#3A4233] border border-[#D8DECB] rounded-lg font-semibold hover:bg-[#F6F8F1] transition" id="btn-prev" style="display: none;">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1 0 1.06L9.06 10l3.73 3.71a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0z" clip-rule="evenodd" /></svg>
                            Previous
                        </button>
                        <div class="ml-auto flex gap-3">
                            <button type="button" class="inline-flex items-center gap-1.5 px-6 py-2 bg-[#2C4424] text-white rounded-lg font-semibold hover:bg-[#1F3318] transition" id="btn-next">
                                Next
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 0-1.06L10.94 10 7.21 6.29a.75.75 0 1 1 1.06-1.06l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0z" clip-rule="evenodd" /></svg>
                            </button>
                            <button type="submit" class="inline-flex items-center gap-1.5 px-6 py-2 bg-[#2C4424] text-white rounded-lg font-semibold hover:bg-[#1F3318] transition" id="btn-submit" style="display: none;">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 1 1 1.4-1.4L9 11.6l6.3-6.3a1 1 0 0 1 1.4 0z" clip-rule="evenodd" /></svg>
                                Submit report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        (function () {
            const totalSteps = 5;
            let currentStep = 1;
            let map = null;
            let marker = null;

            const form = document.getElementById('report-form');
            const description = document.getElementById('description');
            const charCount = document.getElementById('char-count');
            const checkIcon = '<svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 1 1 1.4-1.4L9 11.6l6.3-6.3a1 1 0 0 1 1.4 0z" clip-rule="evenodd"/></svg>';

            const showStep = (step) => {
                currentStep = step;
                document.querySelectorAll('.step-pane').forEach(p => p.classList.remove('active'));
                document.querySelector(`.step-pane[data-step="${step}"]`)?.classList.add('active');

                document.querySelectorAll('.step-tab').forEach(tab => {
                    const n = parseInt(tab.dataset.step, 10);
                    const circle = tab.querySelector('.step-circle');
                    const label = tab.querySelector('.step-label');
                    tab.classList.toggle('disabled', n > step);

                    circle.classList.remove('border-[#2C4424]', 'bg-[#2C4424]', 'bg-white', 'text-white', 'text-[#2C4424]', 'text-[#9CA68C]', 'border-[#D8DECB]', 'ring-2', 'ring-[#2C4424]/15');
                    label.classList.remove('text-[#1F3318]', 'text-[#9CA68C]');

                    if (n < step) {
                        circle.classList.add('border-[#2C4424]', 'bg-[#2C4424]', 'text-white');
                        circle.innerHTML = checkIcon;
                        label.classList.add('text-[#1F3318]');
                    } else if (n === step) {
                        circle.classList.add('border-[#2C4424]', 'bg-white', 'text-[#2C4424]', 'ring-2', 'ring-[#2C4424]/15');
                        circle.textContent = n;
                        label.classList.add('text-[#1F3318]');
                    } else {
                        circle.classList.add('border-[#D8DECB]', 'bg-white', 'text-[#9CA68C]');
                        circle.textContent = n;
                        label.classList.add('text-[#9CA68C]');
                    }
                });

                document.querySelectorAll('.step-line').forEach(line => {
                    const n = parseInt(line.dataset.line, 10);
                    line.classList.toggle('bg-[#2C4424]', n <= step);
                    line.classList.toggle('bg-[#D8DECB]', n > step);
                });

                document.getElementById('btn-prev').style.display = step === 1 ? 'none' : 'inline-flex';
                document.getElementById('btn-next').style.display = step === totalSteps ? 'none' : 'inline-flex';
                document.getElementById('btn-submit').style.display = step === totalSteps ? 'inline-flex' : 'none';
                if (step === 3) initMap();
                if (step === 5) buildReview();
            };

            const initMap = () => {
                if (map) {
                    setTimeout(() => map.invalidateSize(), 150);
                    return;
                }
                const latInput = document.getElementById('location_latitude');
                const lngInput = document.getElementById('location_longitude');
                const lat = parseFloat(latInput.value) || 0.347596;
                const lng = parseFloat(lngInput.value) || 32.582520;

                map = L.map('crime-map').setView([lat, lng], 8);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                marker = L.marker([lat, lng], { draggable: true }).addTo(map);

                const setCoords = (la, ln) => {
                    latInput.value = la.toFixed(8);
                    lngInput.value = ln.toFixed(8);
                    document.getElementById('lat_display').value = la.toFixed(6);
                    document.getElementById('lng_display').value = ln.toFixed(6);
                };

                setCoords(lat, lng);
                map.on('click', (e) => {
                    marker.setLatLng(e.latlng);
                    setCoords(e.latlng.lat, e.latlng.lng);
                });
                marker.on('dragend', () => {
                    const p = marker.getLatLng();
                    setCoords(p.lat, p.lng);
                });
                setTimeout(() => map.invalidateSize(), 200);
            };

            const buildReview = () => {
                const cat = form.crime_category_id.selectedOptions[0]?.text?.trim() || '—';
                const files = form.querySelector('#evidence').files;
                document.getElementById('review-summary').innerHTML = `
                    <dt class="text-[#5F6B57] font-medium">Category</dt><dd class="col-span-2 text-[#1F3318]">${cat}</dd>
                    <dt class="text-[#5F6B57] font-medium">Description</dt><dd class="col-span-2 text-[#1F3318]">${description.value || '—'}</dd>
                    <dt class="text-[#5F6B57] font-medium">Coordinates</dt><dd class="col-span-2 text-[#1F3318]">${form.location_latitude.value}, ${form.location_longitude.value}</dd>
                    <dt class="text-[#5F6B57] font-medium">Address</dt><dd class="col-span-2 text-[#1F3318]">${form.location_address.value || '—'}</dd>
                    <dt class="text-[#5F6B57] font-medium">Evidence files</dt><dd class="col-span-2 text-[#1F3318]">${files.length} file(s)</dd>
                `;
            };

            description?.addEventListener('input', () => {
                charCount.textContent = description.value.length;
            });
            charCount.textContent = description?.value.length || 0;

            document.getElementById('evidence')?.addEventListener('change', (e) => {
                const preview = document.getElementById('file-preview');
                preview.innerHTML = '';
                [...e.target.files].forEach(file => {
                    const wrap = document.createElement('div');
                    wrap.className = 'relative';
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.className = 'preview-thumb border border-[#D8DECB]';
                        img.src = URL.createObjectURL(file);
                        wrap.appendChild(img);
                    } else {
                        wrap.innerHTML = `<span class="inline-flex items-center gap-1.5 bg-[#3A4233] text-white p-3 rounded-lg text-sm"><svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-2.5l3.3 2.5a.7.7 0 0 0 1.1-.6V6.6a.7.7 0 0 0-1.1-.6L14 8.5V6a2 2 0 0 0-2-2H4z"/></svg> ${file.name}</span>`;
                    }
                    preview.appendChild(wrap);
                });
            });

            document.getElementById('btn-next').addEventListener('click', () => {
                const pane = document.querySelector(`.step-pane[data-step="${currentStep}"]`);
                const inputs = pane.querySelectorAll('input, select, textarea');
                for (const input of inputs) {
                    if (!input.checkValidity()) {
                        input.reportValidity();
                        return;
                    }
                }
                if (currentStep < totalSteps) showStep(currentStep + 1);
            });

            document.getElementById('btn-prev').addEventListener('click', () => {
                if (currentStep > 1) showStep(currentStep - 1);
            });

            document.querySelectorAll('.step-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = parseInt(tab.dataset.step, 10);
                    if (target <= currentStep) showStep(target);
                });
            });

            showStep(1);
        })();
    </script>
</body>
</html>