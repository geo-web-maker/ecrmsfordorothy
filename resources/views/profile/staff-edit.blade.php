<x-officer-admin-layout active-nav="account" page-title="Account Settings">
    <div class="p-4 sm:p-6 max-w-3xl mx-auto w-full space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-portal-on-surface m-0">Account Settings</h1>
            <p class="text-sm text-portal-on-surface-variant mt-1 mb-0">Manage your personal information, password, and account preferences.</p>
        </div>

        @include('partials.flash')
        @if (session('status') === 'profile-updated')
            <div class="rounded-lg border px-4 py-3 text-sm font-semibold" style="border-color:rgba(94,139,61,0.35); background:#EAF1DD; color:#1F3318;">
                Profile updated successfully.
            </div>
        @endif

        <div class="bg-white rounded-xl border border-portal-outline-variant shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-portal-outline-variant bg-portal-surface">
                <h3 class="text-sm font-bold text-portal-ink m-0">Profile Information</h3>
                <p class="text-xs text-portal-on-surface-variant m-0 mt-0.5">Update your name and email address</p>
            </div>
            <div class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="bg-white rounded-xl border border-portal-outline-variant shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-portal-outline-variant bg-portal-surface">
                <h3 class="text-sm font-bold text-portal-ink m-0">Update Password</h3>
                <p class="text-xs text-portal-on-surface-variant m-0 mt-0.5">Use a long, secure password to protect your account</p>
            </div>
            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-white rounded-xl border border-red-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-red-100 bg-red-50">
                <h3 class="text-sm font-bold text-red-700 m-0">Delete Account</h3>
                <p class="text-xs text-portal-on-surface-variant m-0 mt-0.5">Permanently remove your account and all data</p>
            </div>
            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-officer-admin-layout>
