<nav id="navbar" class="fixed inset-x-0 top-0 z-40 border-b-0 bg-white/70 py-4 backdrop-blur-xl transition-all duration-300">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6">
        <a href="{{ request()->routeIs('home') ? '#hero' : route('home') }}" class="flex items-center gap-3 transition-transform hover:scale-105 active:scale-95">
            <img src="{{ asset('storage/'.data_get($setting, 'logo_path', 'branding/logo.png')) }}" alt="{{ data_get($setting, 'institution_name', 'KDEKS Kalimantan Timur') }}" class="h-8 w-auto object-contain drop-shadow-sm sm:h-9">
            <div class="h-8 w-px bg-slate-300/70"></div>
            <img src="{{ asset('logo2.png') }}" alt="Logo 2" class="h-10 w-auto object-contain drop-shadow-sm sm:h-12">
        </a>

        <div class="hidden items-center space-x-7 lg:flex">
            <a href="{{ route('home') }}" class="group relative py-1 text-sm font-semibold {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-500' }} transition-colors hover:text-emerald-600">
                Beranda
                <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-emerald-600 transition-all duration-300 group-hover:w-full {{ request()->routeIs('home') ? 'w-full' : '' }}"></span>
            </a>

            <!-- Profil Dropdown -->
            <div class="group relative py-1">
                <button class="flex items-center gap-1 text-sm font-semibold text-slate-500 transition-colors hover:text-emerald-600">
                    Profil
                    <i data-lucide="chevron-down" class="h-3.5 w-3.5 transition-transform group-hover:rotate-180"></i>
                </button>
                <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-emerald-600 transition-all duration-300 group-hover:w-full"></span>
                <div class="absolute left-0 top-full pt-4 w-56 transform origin-top scale-y-0 opacity-0 transition-all duration-300 group-hover:scale-y-100 group-hover:opacity-100">
                    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white p-2 shadow-xl backdrop-blur-xl">
                        <a href="{{ route('about') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Tentang Kami</a>
                        <a href="{{ route('profile.organization') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Struktur Organisasi</a>
                    </div>
                </div>
            </div>

            <!-- Direktorat Dropdown -->
            <div class="group relative py-1">
                <button class="flex items-center gap-1 text-sm font-semibold text-slate-500 transition-colors hover:text-emerald-600">
                    Direktorat
                    <i data-lucide="chevron-down" class="h-3.5 w-3.5 transition-transform group-hover:rotate-180"></i>
                </button>
                <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-emerald-600 transition-all duration-300 group-hover:w-full"></span>
                <div class="absolute left-0 top-full pt-4 w-72 transform origin-top scale-y-0 opacity-0 transition-all duration-300 group-hover:scale-y-100 group-hover:opacity-100">
                    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white p-2 shadow-xl backdrop-blur-xl">
                        <a href="{{ route('direktorat.show', 'industri-produk-halal') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Industri Produk Halal</a>
                        <a href="{{ route('direktorat.show', 'jasa-keuangan-syariah') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Jasa Keuangan Syariah</a>
                        <a href="{{ route('direktorat.show', 'keuangan-sosial-syariah') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Keuangan Sosial Syariah</a>
                        <a href="{{ route('direktorat.show', 'bisnis-kewirausahaan-syariah') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Bisnis dan Kewirausahaan Syariah</a>
                        <a href="{{ route('direktorat.show', 'infrastruktur-ekosistem-syariah') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Infrastruktur Ekosistem Syariah</a>
                    </div>
                </div>
            </div>

            <!-- Berita Dropdown -->
            <div class="group relative py-1">
                <button class="flex items-center gap-1 text-sm font-semibold {{ request()->routeIs('articles.*') ? 'text-emerald-600' : 'text-slate-500' }} transition-colors hover:text-emerald-600">
                    Berita
                    <i data-lucide="chevron-down" class="h-3.5 w-3.5 transition-transform group-hover:rotate-180"></i>
                </button>
                <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-emerald-600 transition-all duration-300 group-hover:w-full {{ request()->routeIs('articles.*') ? 'w-full' : '' }}"></span>
                <div class="absolute left-0 top-full pt-4 w-56 transform origin-top scale-y-0 opacity-0 transition-all duration-300 group-hover:scale-y-100 group-hover:opacity-100">
                    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white p-2 shadow-xl backdrop-blur-xl">
                        <a href="{{ route('articles.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Berita Terkini</a>
                        <a href="{{ route('siaran-pers') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Siaran Pers</a>
                    </div>
                </div>
            </div>

            <!-- Dokumen Dropdown -->
            <div class="group relative py-1">
                <button class="flex items-center gap-1 text-sm font-semibold {{ request()->routeIs('resources.*') || request()->routeIs('regulations.*') ? 'text-emerald-600' : 'text-slate-500' }} transition-colors hover:text-emerald-600">
                    Dokumen
                    <i data-lucide="chevron-down" class="h-3.5 w-3.5 transition-transform group-hover:rotate-180"></i>
                </button>
                <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-emerald-600 transition-all duration-300 group-hover:w-full {{ request()->routeIs('resources.*') || request()->routeIs('regulations.*') ? 'w-full' : '' }}"></span>
                <div class="absolute left-0 top-full pt-4 w-56 transform origin-top scale-y-0 opacity-0 transition-all duration-300 group-hover:scale-y-100 group-hover:opacity-100">
                    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white p-2 shadow-xl backdrop-blur-xl">
                        <a href="{{ route('resources.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Dokumen</a>
                        <a href="{{ route('regulations.index') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Regulasi</a>
                    </div>
                </div>
            </div>

            @foreach ([
                route('home').'#webgis' => 'Pemetaan',
                route('data.index') => 'Data',
                route('contact') => 'Kontak',
            ] as $href => $label)
                <a href="{{ $href }}" class="group relative py-1 text-sm font-semibold {{ request()->fullUrl() === $href ? 'text-emerald-600' : 'text-slate-500' }} transition-colors hover:text-emerald-600">
                    {{ $label }}
                    <span class="absolute bottom-0 left-0 h-0.5 w-0 bg-emerald-600 transition-all duration-300 group-hover:w-full {{ request()->fullUrl() === $href ? 'w-full' : '' }}"></span>
                </a>
            @endforeach
        </div>

        <div class="hidden items-center space-x-4 lg:flex">
            <button aria-label="Search" class="group flex h-10 w-10 items-center justify-center rounded-full text-slate-500 transition-all hover:bg-emerald-50 hover:text-emerald-600" onclick="openModal('searchModal')">
                <i data-lucide="search" class="h-5 w-5 transition-transform group-hover:scale-110"></i>
            </button>
            {{-- <div class="h-4 w-px bg-slate-300"></div> --}}
            {{-- <button onclick="openModal('sehatiModal')" class="rounded-full bg-emerald-500 px-5 py-2 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition-all hover:bg-emerald-400 active:scale-95">
                Daftar Sertifikasi Halal
            </button> --}}
        </div>

        <button class="-mr-2 rounded-lg p-2 text-slate-800 transition hover:bg-slate-100 lg:hidden" aria-label="Menu" onclick="toggleMobileMenu()">
            <i data-lucide="menu" class="h-6 w-6"></i>
        </button>
    </div>
</nav>

<!-- Mobile Overlay -->
<div id="mobileOverlay" class="fixed inset-0 z-[59] hidden bg-black/40 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="toggleMobileMenu()"></div>

<!-- Mobile Sidebar -->
<div id="mobileMenu" class="fixed inset-y-0 right-0 z-[60] flex w-[85vw] max-w-sm flex-col bg-white shadow-2xl translate-x-full transition-transform duration-[400ms] ease-[cubic-bezier(0.32,0.72,0,1)]">

    <!-- Header -->
    <div class="relative flex items-center justify-between px-5 py-5">
        <div class="flex items-center gap-2.5">
            <img src="{{ asset('storage/'.data_get($setting, 'logo_path', 'branding/logo.png')) }}" alt="{{ data_get($setting, 'institution_name', 'KDEKS Kalimantan Timur') }}" class="h-7 w-auto object-contain">
            <div class="h-6 w-px bg-slate-200"></div>
            <img src="{{ asset('logo2.png') }}" alt="Logo 2" class="h-7 w-auto object-contain">
        </div>
        <button onclick="toggleMobileMenu()" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition-all hover:bg-slate-100 hover:text-slate-600 active:scale-90">
            <i data-lucide="x" class="h-5 w-5"></i>
        </button>
        <!-- Accent line -->
        <div class="absolute bottom-0 left-5 right-5 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    </div>

    <!-- Scrollable Content -->
    <div class="flex-1 overflow-y-auto overscroll-contain px-4 py-5">
        <div class="flex flex-col gap-1">

            <!-- Beranda -->
            <a href="{{ route('home') }}" onclick="toggleMobileMenu()" class="mobile-nav-link group flex items-center gap-3.5 rounded-2xl px-4 py-3.5 text-[15px] font-semibold text-slate-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 active:scale-[0.98]">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 transition-colors duration-200 group-hover:bg-emerald-100">
                    <i data-lucide="home" class="h-[18px] w-[18px] text-slate-500 transition-colors duration-200 group-hover:text-emerald-600"></i>
                </span>
                Beranda
            </a>

            <!-- Profil Accordion -->
            <div class="mobile-accordion">
                <button onclick="toggleAccordion(this)" class="mobile-nav-link group flex w-full items-center justify-between rounded-2xl px-4 py-3.5 text-[15px] font-semibold text-slate-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 active:scale-[0.98]">
                    <span class="flex items-center gap-3.5">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 transition-colors duration-200 group-hover:bg-emerald-100">
                            <i data-lucide="building-2" class="h-[18px] w-[18px] text-slate-500 transition-colors duration-200 group-hover:text-emerald-600"></i>
                        </span>
                        Profil
                    </span>
                    <i data-lucide="chevron-down" class="accordion-chevron h-4 w-4 text-slate-400 transition-transform duration-300"></i>
                </button>
                <div class="accordion-content overflow-hidden transition-all duration-300" style="max-height:0;opacity:0">
                    <div class="ml-[52px] mt-1 flex flex-col gap-0.5 pb-2">
                        <a href="{{ route('about') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Tentang Kami</a>
                        <a href="{{ route('profile.organization') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Struktur Organisasi</a>
                    </div>
                </div>
            </div>

            <!-- Direktorat Accordion -->
            <div class="mobile-accordion">
                <button onclick="toggleAccordion(this)" class="mobile-nav-link group flex w-full items-center justify-between rounded-2xl px-4 py-3.5 text-[15px] font-semibold text-slate-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 active:scale-[0.98]">
                    <span class="flex items-center gap-3.5">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 transition-colors duration-200 group-hover:bg-emerald-100">
                            <i data-lucide="layout-grid" class="h-[18px] w-[18px] text-slate-500 transition-colors duration-200 group-hover:text-emerald-600"></i>
                        </span>
                        Direktorat
                    </span>
                    <i data-lucide="chevron-down" class="accordion-chevron h-4 w-4 text-slate-400 transition-transform duration-300"></i>
                </button>
                <div class="accordion-content overflow-hidden transition-all duration-300" style="max-height:0;opacity:0">
                    <div class="ml-[52px] mt-1 flex flex-col gap-0.5 pb-2">
                        <a href="{{ route('direktorat.show', 'industri-produk-halal') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Industri Produk Halal</a>
                        <a href="{{ route('direktorat.show', 'jasa-keuangan-syariah') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Jasa Keuangan Syariah</a>
                        <a href="{{ route('direktorat.show', 'keuangan-sosial-syariah') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Keuangan Sosial Syariah</a>
                        <a href="{{ route('direktorat.show', 'bisnis-kewirausahaan-syariah') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Bisnis & Kewirausahaan Syariah</a>
                        <a href="{{ route('direktorat.show', 'infrastruktur-ekosistem-syariah') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Infrastruktur Ekosistem Syariah</a>
                    </div>
                </div>
            </div>

            <!-- Berita Accordion -->
            <div class="mobile-accordion">
                <button onclick="toggleAccordion(this)" class="mobile-nav-link group flex w-full items-center justify-between rounded-2xl px-4 py-3.5 text-[15px] font-semibold text-slate-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 active:scale-[0.98]">
                    <span class="flex items-center gap-3.5">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 transition-colors duration-200 group-hover:bg-emerald-100">
                            <i data-lucide="newspaper" class="h-[18px] w-[18px] text-slate-500 transition-colors duration-200 group-hover:text-emerald-600"></i>
                        </span>
                        Berita
                    </span>
                    <i data-lucide="chevron-down" class="accordion-chevron h-4 w-4 text-slate-400 transition-transform duration-300"></i>
                </button>
                <div class="accordion-content overflow-hidden transition-all duration-300" style="max-height:0;opacity:0">
                    <div class="ml-[52px] mt-1 flex flex-col gap-0.5 pb-2">
                        <a href="{{ route('articles.index') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Berita Terkini</a>
                        <a href="{{ route('siaran-pers') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Siaran Pers</a>
                    </div>
                </div>
            </div>

            <!-- Dokumen Accordion -->
            <div class="mobile-accordion">
                <button onclick="toggleAccordion(this)" class="mobile-nav-link group flex w-full items-center justify-between rounded-2xl px-4 py-3.5 text-[15px] font-semibold text-slate-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 active:scale-[0.98]">
                    <span class="flex items-center gap-3.5">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 transition-colors duration-200 group-hover:bg-emerald-100">
                            <i data-lucide="file-text" class="h-[18px] w-[18px] text-slate-500 transition-colors duration-200 group-hover:text-emerald-600"></i>
                        </span>
                        Dokumen
                    </span>
                    <i data-lucide="chevron-down" class="accordion-chevron h-4 w-4 text-slate-400 transition-transform duration-300"></i>
                </button>
                <div class="accordion-content overflow-hidden transition-all duration-300" style="max-height:0;opacity:0">
                    <div class="ml-[52px] mt-1 flex flex-col gap-0.5 pb-2">
                        <a href="{{ route('resources.index') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Dokumen Terkait</a>
                        <a href="{{ route('regulations.index') }}" onclick="toggleMobileMenu()" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-500 transition-all duration-200 hover:bg-emerald-50/70 hover:text-emerald-600 active:scale-[0.98]">Regulasi</a>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="my-2 h-px bg-gradient-to-r from-transparent via-slate-100 to-transparent"></div>

            <!-- Pemetaan -->
            <a href="{{ route('home') }}#webgis" onclick="toggleMobileMenu()" class="mobile-nav-link group flex items-center gap-3.5 rounded-2xl px-4 py-3.5 text-[15px] font-semibold text-slate-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 active:scale-[0.98]">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 transition-colors duration-200 group-hover:bg-emerald-100">
                    <i data-lucide="map-pin" class="h-[18px] w-[18px] text-slate-500 transition-colors duration-200 group-hover:text-emerald-600"></i>
                </span>
                Pemetaan
            </a>

            <!-- Data -->
            <a href="{{ route('data.index') }}" onclick="toggleMobileMenu()" class="mobile-nav-link group flex items-center gap-3.5 rounded-2xl px-4 py-3.5 text-[15px] font-semibold text-slate-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 active:scale-[0.98]">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 transition-colors duration-200 group-hover:bg-emerald-100">
                    <i data-lucide="bar-chart-3" class="h-[18px] w-[18px] text-slate-500 transition-colors duration-200 group-hover:text-emerald-600"></i>
                </span>
                Data
            </a>

            <!-- Kontak -->
            <a href="{{ route('contact') }}" onclick="toggleMobileMenu()" class="mobile-nav-link group flex items-center gap-3.5 rounded-2xl px-4 py-3.5 text-[15px] font-semibold text-slate-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 active:scale-[0.98]">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 transition-colors duration-200 group-hover:bg-emerald-100">
                    <i data-lucide="mail" class="h-[18px] w-[18px] text-slate-500 transition-colors duration-200 group-hover:text-emerald-600"></i>
                </span>
                Kontak
            </a>

        </div>
    </div>

    <!-- Sticky Bottom -->
    <div class="border-t border-slate-100 bg-white/90 backdrop-blur-lg px-4 py-4">
        <div class="flex flex-col gap-2.5">
            <button onclick="openModal('searchModal'); toggleMobileMenu()" class="group flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3.5 text-[15px] font-semibold text-slate-600 transition-all duration-200 hover:bg-slate-100 hover:text-slate-800 active:scale-[0.98]">
                <span class="flex items-center gap-3.5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-200/70 transition-colors duration-200 group-hover:bg-slate-200">
                        <i data-lucide="search" class="h-[18px] w-[18px] text-slate-500 group-hover:text-slate-700"></i>
                    </span>
                    Pencarian Terpadu
                </span>
                <i data-lucide="arrow-right" class="h-4 w-4 text-slate-400 transition-transform duration-200 group-hover:translate-x-0.5"></i>
            </button>
            {{-- <button onclick="openModal('sehatiModal'); toggleMobileMenu()" class="flex w-full items-center justify-center gap-2.5 rounded-2xl bg-emerald-500 py-4 text-[15px] font-bold text-white shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:bg-emerald-400 hover:shadow-emerald-400/30 active:scale-[0.98]">
                <i data-lucide="shield-check" class="h-[18px] w-[18px]"></i>
                Daftar Sertifikasi Halal
            </button> --}}
        </div>
    </div>
</div>

<script>
    let mobileMenuOpen = false;

    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileOverlay');
        mobileMenuOpen = !mobileMenuOpen;

        if (mobileMenuOpen) {
            // Show overlay
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                overlay.classList.remove('opacity-0');
                overlay.classList.add('opacity-100');
            });

            // Slide menu in
            menu.classList.remove('translate-x-full');
            menu.classList.add('translate-x-0');

            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        } else {
            // Hide overlay
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);

            // Slide menu out
            menu.classList.remove('translate-x-0');
            menu.classList.add('translate-x-full');

            // Close all accordions
            document.querySelectorAll('.mobile-accordion').forEach(acc => {
                const content = acc.querySelector('.accordion-content');
                const chevron = acc.querySelector('.accordion-chevron');
                if (content && chevron) {
                    content.style.maxHeight = '0';
                    content.style.opacity = '0';
                    chevron.style.transform = 'rotate(0deg)';
                }
            });

            // Restore body scroll
            document.body.style.overflow = '';
        }
    }

    function toggleAccordion(button) {
        const accordion = button.closest('.mobile-accordion');
        const content = accordion.querySelector('.accordion-content');
        const chevron = accordion.querySelector('.accordion-chevron');
        const isOpen = content.style.maxHeight !== '0px' && content.style.maxHeight !== '';

        // Close all other accordions first
        document.querySelectorAll('.mobile-accordion').forEach(other => {
            if (other !== accordion) {
                const otherContent = other.querySelector('.accordion-content');
                const otherChevron = other.querySelector('.accordion-chevron');
                if (otherContent && otherChevron) {
                    otherContent.style.maxHeight = '0';
                    otherContent.style.opacity = '0';
                    otherChevron.style.transform = 'rotate(0deg)';
                }
            }
        });

        if (isOpen) {
            content.style.maxHeight = '0';
            content.style.opacity = '0';
            chevron.style.transform = 'rotate(0deg)';
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            content.style.opacity = '1';
            chevron.style.transform = 'rotate(180deg)';
        }
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenuOpen) {
            toggleMobileMenu();
        }
    });

    // Close on resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024 && mobileMenuOpen) {
            toggleMobileMenu();
        }
    });
</script>
