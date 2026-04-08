<x-guest-layout>
    <div class="mb-8">
        <p class="text-[10px] font-extrabold uppercase tracking-[0.24em] text-cyan-600">Reset Akses</p>
        <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-slate-900">Lupa kata sandi?</h2>
        <p class="mt-3 text-sm font-medium leading-7 text-slate-500">Masukkan email akun Anda. Sistem akan mengirimkan tautan reset password untuk memulihkan akses ke dashboard.</p>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@instansi.go.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
