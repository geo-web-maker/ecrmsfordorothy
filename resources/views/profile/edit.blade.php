<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider" style="color:#5E8B3D;">Account Settings</p>
            <h2 class="mt-1 text-2xl font-bold" style="color:#1F3318;">Your Profile</h2>
            <p class="mt-1 text-sm" style="color:#5F6B57;">Manage your personal information, password, and account preferences.</p>
        </div>
    </x-slot>

    <div class="py-10" style="background:#F3F5EA;">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Information Card -->
            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden" style="border-color:rgba(94,139,61,0.15);">
                <div class="px-6 py-4 border-b" style="border-color:rgba(94,139,61,0.12); background:#FAFBF7;">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-xl" style="background:#EAF1DD;">
                            <svg class="w-5 h-5" style="color:#3F6B2A;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold" style="color:#1F3318;">Profile Information</h3>
                            <p class="text-xs" style="color:#7B8F69;">Update your name and email address</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden" style="border-color:rgba(94,139,61,0.15);">
                <div class="px-6 py-4 border-b" style="border-color:rgba(94,139,61,0.12); background:#FAFBF7;">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-xl" style="background:#EAF1DD;">
                            <svg class="w-5 h-5" style="color:#3F6B2A;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold" style="color:#1F3318;">Update Password</h3>
                            <p class="text-xs" style="color:#7B8F69;">Use a long, secure password to protect your account</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden" style="border-color:rgba(220,53,69,0.15);">
                <div class="px-6 py-4 border-b" style="border-color:rgba(220,53,69,0.10); background:#FFF9F9;">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-xl" style="background:rgba(220,53,69,0.08);">
                            <svg class="w-5 h-5" style="color:#c0392b;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold" style="color:#c0392b;">Delete Account</h3>
                            <p class="text-xs" style="color:#7B8F69;">Permanently remove your account and all data</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
