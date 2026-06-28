@auth
<div id="logout-confirm-modal"
     class="hidden fixed inset-0 z-[20000] flex items-center justify-center p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="logout-confirm-title"
     aria-hidden="true">
    <div class="absolute inset-0 bg-[#0d150b]/60 backdrop-blur-sm" data-logout-cancel></div>
    <div class="relative w-full max-w-sm rounded-2xl bg-white shadow-2xl border border-[rgba(94,139,61,0.2)] overflow-hidden">
        <div class="px-6 pt-6 pb-4 text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[rgba(220,53,69,0.1)]">
                <svg class="h-6 w-6 text-[#c0392b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </div>
            <h2 id="logout-confirm-title" class="text-lg font-bold text-[#1F3318] m-0">Log out?</h2>
            <p class="mt-2 text-sm text-[#5F6B57] m-0 leading-relaxed">
                Are you sure you want to log out of NEMA eCRMS?
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
            <button type="button"
                    data-logout-cancel
                    class="flex-1 rounded-xl border border-[rgba(94,139,61,0.25)] bg-white px-4 py-2.5 text-sm font-semibold text-[#3F6B2A] cursor-pointer hover:bg-[#F3F5EA] transition-colors">
                Cancel
            </button>
            <button type="button"
                    data-logout-confirm
                    class="flex-1 rounded-xl border-none bg-[#c0392b] px-4 py-2.5 text-sm font-bold text-white cursor-pointer hover:bg-[#a93226] transition-colors">
                Yes, log out
            </button>
        </div>
    </div>
</div>
@endauth
