<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KDEKS Kaltim') }}</title>

        <script src="https://unpkg.com/lucide@latest"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-shell font-sans text-slate-900 antialiased">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-8">
            <div class="organic-shape left-[-100px] top-[-120px] h-[380px] w-[380px] bg-emerald-200/60"></div>
            <div class="organic-shape right-[-120px] top-[10%] h-[420px] w-[420px] bg-cyan-200/50"></div>

            <div class="relative z-10 grid w-full max-w-6xl items-center gap-8 lg:grid-cols-[0.95fr,1.05fr]">
                <div class="hidden rounded-[2rem] border border-white/80 bg-white/55 p-8 shadow-[0_10px_50px_rgba(15,23,42,0.05)] backdrop-blur-xl lg:block">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('storage/branding/logo.png') }}" alt="KDEKS Kaltim" class="h-10 w-auto object-contain sm:h-11">
                    </a>

                    <div class="mt-12 max-w-lg">
                        <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-500 shadow-sm">
                            <i data-lucide="shield-check" class="h-3 w-3 text-emerald-500"></i>
                            Portal Admin & Auth Terproteksi
                        </div>
                        <h1 class="mt-6 font-heading text-4xl font-extrabold leading-tight tracking-tight text-slate-900">Sistem kerja yang terasa resmi, terang, dan siap dipakai operasional harian.</h1>
                        <p class="mt-4 text-sm font-medium leading-7 text-slate-500">
                            Kelola pengajuan SEHATI, direktori produk halal, dokumen regulasi, WebGIS, dan data ekosistem syariah Kaltim dalam satu panel yang konsisten dengan portal publik.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white bg-white/85 p-5 shadow-sm">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.24em] text-emerald-600">Keamanan</p>
                            <h3 class="mt-2 text-base font-bold text-slate-900">Autentikasi Laravel</h3>
                            <p class="mt-2 text-xs leading-6 text-slate-500">CSRF, validation, session auth, dan alur reset password tetap mengikuti best practice framework.</p>
                        </div>
                        <div class="rounded-2xl border border-white bg-white/85 p-5 shadow-sm">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.24em] text-cyan-600">Operasional</p>
                            <h3 class="mt-2 text-base font-bold text-slate-900">Admin Siap Pakai</h3>
                            <p class="mt-2 text-xs leading-6 text-slate-500">Panel internal dirancang terang dan cepat dibaca, cocok untuk penggunaan pemerintahan dan lembaga resmi.</p>
                        </div>
                    </div>
                </div>

                <div class="auth-card w-full rounded-[2rem] p-6 sm:p-8 lg:p-10">
                    <div class="mb-8 text-center lg:hidden">
                        <a href="/" class="inline-flex items-center">
                            <img src="{{ asset('storage/branding/logo.png') }}" alt="KDEKS Kaltim" class="h-9 w-auto object-contain sm:h-10">
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
