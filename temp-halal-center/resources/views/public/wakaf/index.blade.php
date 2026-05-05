<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <title>Gerakan Kaltim Berwakaf Digital | KDEKS Kalimantan Timur</title>
    <meta name="description" content="Portal Gerakan Kaltim Berwakaf Digital - Wujudkan kepedulian melalui instrumen wakaf produktif.">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .text-gradient {
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar for desktop */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Prevent layout shift from no scrollbar on mobile */
        @media (max-width: 640px) {
            body {
                -webkit-tap-highlight-color: transparent;
            }
        }
    </style>
</head>
<body class="relative w-full overflow-x-hidden bg-[#f8fafc] font-sans text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">
    
    <div class="relative w-full min-h-screen">
        {{-- Background Accents --}}
        <div class="pointer-events-none absolute left-0 top-0 -z-10 h-[600px] w-full max-w-full bg-[radial-gradient(circle_at_top_right,rgba(16,185,129,0.08),transparent_60%)]"></div>
        <div class="pointer-events-none absolute right-0 bottom-0 -z-10 h-[600px] w-full max-w-full bg-[radial-gradient(circle_at_bottom_left,rgba(6,182,212,0.08),transparent_60%)]"></div>

        <main class="mx-auto max-w-7xl px-5 sm:px-6 pb-20 sm:pb-24 pt-12 sm:pt-16">
            {{-- Header Section --}}
            <div class="mb-12 sm:mb-16 flex flex-col items-center text-center">
                
                {{-- Logos --}}
                <div class="flex items-center justify-center gap-6 sm:gap-8 mb-6">
                    <img src="{{ asset('logo.png') }}" alt="Logo Client 1" class="h-10 sm:h-12 md:h-14 w-auto object-contain">
                    <img src="{{ asset('logo2.png') }}" alt="Logo Client 2" class="h-10 sm:h-12 md:h-14 w-auto object-contain">
                </div>

                <h1 class="text-xl sm:text-2xl md:text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight whitespace-nowrap px-2">
                    Gerakan Kaltim <span class="text-gradient">Berwakaf Digital</span>
                </h1>
                <p class="mx-auto mt-4 sm:mt-6 max-w-2xl text-base sm:text-lg font-medium leading-relaxed text-slate-500 px-4">
                    Wujudkan kepedulian melalui instrumen wakaf produktif yang berkelanjutan untuk meningkatkan kesejahteraan umat di Kalimantan Timur.
                </p>
            </div>

            {{-- Cards Grid --}}
            <div class="relative w-full">
                {{-- Decorative elements --}}
                <div class="hidden sm:block absolute -left-20 top-1/2 -z-10 h-72 w-72 -translate-y-1/2 rounded-full bg-emerald-500/10 blur-[80px]"></div>
                <div class="hidden sm:block absolute -right-20 top-1/3 -z-10 h-72 w-72 rounded-full bg-cyan-500/10 blur-[80px]"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                    
                    {{-- Card 1 --}}
                    <a href="https://mitra.satuwakaf.id/dombaistiqomah" target="_blank" class="group relative flex flex-col overflow-hidden rounded-[1.75rem] sm:rounded-[2.5rem] border border-slate-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:border-emerald-300 min-w-0">
                        <div class="overflow-hidden h-48 sm:h-56 md:h-64 w-full shrink-0">
                            <img src="{{ asset('assets/img/wakaf/wakaf1.jpeg') }}" alt="TERNAK DOMBA PROGRAM KEMANDIRIAN PONDOK PESANTREN ISTIQOMAH SAMARINDA" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <div class="p-6 sm:p-8 flex flex-col flex-1 bg-white relative z-10">
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-600">MPW PWM Muhammadiyah</p>
                            <h3 class="mt-3 sm:mt-4 text-lg sm:text-xl font-extrabold leading-snug text-slate-900 group-hover:text-emerald-700 transition-colors">
                                TERNAK DOMBA PROGRAM KEMANDIRIAN PONDOK PESANTREN ISTIQOMAH SAMARINDA
                            </h3>
                            <p class="mt-3 sm:mt-4 text-sm leading-relaxed text-slate-500 line-clamp-3">
                                Ternak domba sebagai investasi berkah untuk mendukung pendidikan santri dan meningkatkan kesejahteraan umat
                            </p>
                            <div class="mt-auto pt-6 sm:pt-8">
                                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm transition-all duration-300 group-hover:bg-emerald-700 group-hover:shadow-md">
                                    <span>Berwakaf</span>
                                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Card 2 --}}
                    <a href="https://mitra.satuwakaf.id/minimarketBwi" target="_blank" class="group relative flex flex-col overflow-hidden rounded-[1.75rem] sm:rounded-[2.5rem] border border-slate-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:border-emerald-300 min-w-0">
                        <div class="overflow-hidden h-48 sm:h-56 md:h-64 w-full shrink-0">
                            <img src="{{ asset('assets/img/wakaf/wakaf2.jpeg') }}" alt="WAKAF PRODUKTIF BANGUNAN MINIMARKET PRODUK HALAL DAN HOTEL SYARIAH" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <div class="p-6 sm:p-8 flex flex-col flex-1 bg-white relative z-10">
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-600">Badan Wakaf Indonesia</p>
                            <h3 class="mt-3 sm:mt-4 text-lg sm:text-xl font-extrabold leading-snug text-slate-900 group-hover:text-emerald-700 transition-colors">
                                WAKAF PRODUKTIF BANGUNAN MINIMARKET PRODUK HALAL DAN HOTEL SYARIAH
                            </h3>
                            <p class="mt-3 sm:mt-4 text-sm leading-relaxed text-slate-500 line-clamp-3">
                                Wakaf Produktif Minimarket dan Penginapan Syariah di Kota Samarinda dikelola oleh Perwakilan Badan Wakaf Indonesia provinsi Kalimantan Timur
                            </p>
                            <div class="mt-auto pt-6 sm:pt-8">
                                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm transition-all duration-300 group-hover:bg-emerald-700 group-hover:shadow-md">
                                    <span>Berwakaf</span>
                                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Card 3 --}}
                    <a href="https://mitra.satuwakaf.id/ayampetelurDPU" target="_blank" class="group relative flex flex-col overflow-hidden rounded-[1.75rem] sm:rounded-[2.5rem] border border-slate-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-xl hover:border-emerald-300 min-w-0">
                        <div class="overflow-hidden h-48 sm:h-56 md:h-64 w-full shrink-0">
                            <img src="{{ asset('assets/img/wakaf/wakaf3.jpeg') }}" alt="Wakaf Produktif Peternakan Ayam Petelur" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <div class="p-6 sm:p-8 flex flex-col flex-1 bg-white relative z-10">
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-600">Yayasan DPU Kaltim</p>
                            <h3 class="mt-3 sm:mt-4 text-lg sm:text-xl font-extrabold leading-snug text-slate-900 group-hover:text-emerald-700 transition-colors">
                                Wakaf Produktif Peternakan Ayam Petelur di Pondok Pesantren Al Qur'an DPU Kaltim
                            </h3>
                            <p class="mt-3 sm:mt-4 text-sm leading-relaxed text-slate-500 line-clamp-3">
                                Peternakan ayam petelur sebagai investasi berkah dan program kemandirian pondok pesantren Al Qur'an DPU Kaltim
                            </p>
                            <div class="mt-auto pt-6 sm:pt-8">
                                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm transition-all duration-300 group-hover:bg-emerald-700 group-hover:shadow-md">
                                    <span>Berwakaf</span>
                                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- CTA Section --}}
            <div class="mt-16 sm:mt-24 rounded-[2rem] sm:rounded-[3rem] bg-slate-900 p-8 sm:p-10 md:p-12 relative overflow-hidden text-center">
                <div class="absolute top-0 right-0 h-full w-1/3 bg-gradient-to-l from-emerald-500/10 to-transparent pointer-events-none"></div>
                <div class="relative z-10">
                    <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-white leading-tight">Siap untuk Berkontribusi?</h2>
                    <p class="mt-4 text-slate-400 text-sm sm:text-base max-w-xl mx-auto">
                        Klik pada salah satu program di atas untuk memulai perjalanan wakaf Anda melalui platform Satu Wakaf Indonesia.
                    </p>
                    <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="https://satuwakaf.id" target="_blank" class="w-full sm:w-auto rounded-xl sm:rounded-2xl bg-emerald-600 px-8 py-4 font-bold text-white shadow-lg transition hover:bg-emerald-700 hover:scale-105 active:scale-95 text-sm sm:text-base inline-block text-center">
                            Kunjungi Satu Wakaf
                        </a>
                        <a href="{{ route('home') }}" class="w-full sm:w-auto rounded-xl sm:rounded-2xl border border-slate-700 bg-slate-800 px-8 py-4 font-bold text-slate-300 transition hover:bg-slate-700 hover:text-white text-sm sm:text-base inline-block text-center">
                            Ke Beranda KDEKS Kaltim
                        </a>
                    </div>
                </div>
            </div>
        </main>

        {{-- Simple Footer --}}
        <footer class="py-10 sm:py-12 text-center text-slate-400 px-4 border-t border-slate-100 mt-auto">
            <p class="text-[10px] font-bold uppercase tracking-widest">© {{ date('Y') }} KDEKS Kalimantan Timur. All Rights Reserved.</p>
        </footer>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>