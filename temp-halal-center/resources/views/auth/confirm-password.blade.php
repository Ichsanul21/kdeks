<x-guest-layout>
    <div class="mb-8">
        <p class="text-[10px] font-extrabold uppercase tracking-[0.24em] text-emerald-600">Konfirmasi</p>
        <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-slate-900">Konfirmasi kata sandi Anda</h2>
        <p class="mt-3 text-sm font-medium leading-7 text-slate-500">Langkah ini dibutuhkan untuk memastikan area sensitif hanya diakses oleh pengguna yang benar.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
