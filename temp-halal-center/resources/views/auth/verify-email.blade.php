<x-guest-layout>
    <div class="mb-8">
        <p class="text-[10px] font-extrabold uppercase tracking-[0.24em] text-cyan-600">Verifikasi Email</p>
        <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-slate-900">Aktifkan email akun Anda</h2>
        <p class="mt-3 text-sm font-medium leading-7 text-slate-500">Sebelum mulai menggunakan sistem, verifikasi alamat email Anda melalui tautan yang sudah dikirimkan.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="auth-link">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
