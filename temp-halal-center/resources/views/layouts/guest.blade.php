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
        @include('components.watermark-overlay', ['setting' => $setting ?? null])
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-8">
            <div class="organic-shape left-[-100px] top-[-120px] h-[380px] w-[380px] bg-emerald-200/60"></div>
            <div class="organic-shape right-[-120px] top-[10%] h-[420px] w-[420px] bg-cyan-200/50"></div>

            <div class="relative z-10 grid w-full max-w-6xl items-center gap-8 lg:grid-cols-[0.95fr,1.05fr]">
                <div class="hidden rounded-[2rem] py-[2.15rem] border border-slate-200/95 bg-white/55 p-8 shadow-[0_10px_50px_rgba(15,23,42,0.05)] backdrop-blur-xl lg:block">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('storage/'.data_get($setting, 'logo_path', 'branding/logo.png')) }}" class="w-[16rem]" alt="KDEKS Kaltim" class="h-10 object-contain sm:h-11">
                    </a>

                    <div class="-mt-4 max-w-lg">
                        <h1 class="mt-6 font-heading text-xl font-extrabold leading-tight tracking-tight text-slate-900">Sistem kerja yang terasa resmi, terang, dan siap dipakai operasional harian.</h1>
                        {{-- <p class="mt-4 text-sm leading-7 text-slate-500">
                            Kelola seluruh aspek ekosistem syariah Kalimantan Timur — mulai dari proses sertifikasi, pengelolaan produk, hingga pemetaan spasial — dalam satu panel terpadu yang konsisten dengan portal publik, memastikan kesinambungan identitas antara sisi internal dan eksternal sistem:
                        </p> --}}
                        <div class="mt-3 space-y-1">
                            <div class="group flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 transition-all duration-200 hover:border-emerald-200/60 hover:bg-emerald-50/40">
                                <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 transition-colors duration-200 group-hover:bg-emerald-200">
                                    <i data-lucide="file-check-2" class="h-3.5 w-3.5"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold text-slate-800">Pengajuan SEHATI</span>
                                    <p class="mt-0.5 text-xs leading-relaxed text-slate-400">Pantau dan proses pengajuan dari registrasi hingga pensertifikasiatan</p>
                                </div>
                            </div>
                            <div class="group flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 transition-all duration-200 hover:border-emerald-200/60 hover:bg-emerald-50/40">
                                <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 transition-colors duration-200 group-hover:bg-emerald-200">
                                    <i data-lucide="book-open-text" class="h-3.5 w-3.5"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold text-slate-800">Dokumen Regulasi</span>
                                    <p class="mt-0.5 text-xs leading-relaxed text-slate-400">Akses dan kelola peraturan, fatwa, serta kebijakan terkait</p>
                                </div>
                            </div>
                            <div class="group flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 transition-all duration-200 hover:border-emerald-200/60 hover:bg-emerald-50/40">
                                <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 transition-colors duration-200 group-hover:bg-emerald-200">
                                    <i data-lucide="map-pin" class="h-3.5 w-3.5"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold text-slate-800">WebGIS</span>
                                    <p class="mt-0.5 text-xs leading-relaxed text-slate-400">Visualisasikan sebaran industri dan infrastruktur halal berbasis peta</p>
                                </div>
                            </div>
                            <div class="group flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 transition-all duration-200 hover:border-emerald-200/60 hover:bg-emerald-50/40">
                                <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 transition-colors duration-200 group-hover:bg-emerald-200">
                                    <i data-lucide="database" class="h-3.5 w-3.5"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold text-slate-800">Data Ekosistem Syariah</span>
                                    <p class="mt-0.5 text-xs leading-relaxed text-slate-400">Integrasi data komprehensif untuk mendukung pengambilan keputusan strategis</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="mt-10 grid gap-4 sm:grid-cols-2">
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
                    </div> --}}
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
