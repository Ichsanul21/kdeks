<nav id="navbar" class="fixed inset-x-0 top-0 z-40 border-b-0 bg-white/70 py-4 backdrop-blur-xl transition-all duration-300">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6">
        <a href="{{ request()->routeIs('home') ? '#hero' : route('home') }}" class="flex items-center transition-transform hover:scale-105 active:scale-95">
            <img src="{{ asset('storage/'.data_get($setting, 'logo_path', 'branding/logo.png')) }}" alt="{{ data_get($setting, 'institution_name', 'KDEKS Kalimantan Timur') }}" class="h-8 w-auto object-contain drop-shadow-sm sm:h-9">
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
                        <a href="{{ route('page.placeholder', 'struktur-organisasi') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition-all hover:bg-emerald-50 hover:text-emerald-600">Struktur Organisasi</a>
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
                route('products.index') => 'Direktori',
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
            <div class="h-4 w-px bg-slate-300"></div>
            <button onclick="openModal('sehatiModal')" class="rounded-full bg-emerald-500 px-5 py-2 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition-all hover:bg-emerald-400 active:scale-95">
                Daftar SEHATI
            </button>
        </div>

        <button class="-mr-2 rounded-lg p-2 text-slate-800 transition hover:bg-slate-100 lg:hidden" aria-label="Menu" onclick="toggleMobileMenu()">
            <i data-lucide="menu" class="h-6 w-6"></i>
        </button>
    </div>
</nav>

<div id="mobileMenu" class="fixed inset-0 z-[60] hidden translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300">
    <div class="flex items-center justify-between border-b border-slate-100 p-6">
        <div class="flex items-center">
            <img src="{{ asset('storage/'.data_get($setting, 'logo_path', 'branding/logo.png')) }}" alt="{{ data_get($setting, 'institution_name', 'KDEKS Kalimantan Timur') }}" class="h-7 w-auto object-contain">
        </div>
        <button onclick="toggleMobileMenu()" class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100">
            <i data-lucide="x" class="h-6 w-6"></i>
        </button>
    </div>
    <div class="flex flex-col gap-4 overflow-y-auto p-6">
        <a href="{{ route('home') }}" onclick="toggleMobileMenu()" class="text-base font-bold text-slate-800">Beranda</a>
        
        <div class="flex flex-col gap-2">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Profil</p>
            <a href="{{ route('about') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Tentang Kami</a>
            <a href="{{ route('page.placeholder', 'struktur-organisasi') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Struktur Organisasi</a>
        </div>

        <div class="flex flex-col gap-2">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Direktorat</p>
            <a href="{{ route('direktorat.show', 'industri-produk-halal') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Industri Produk Halal</a>
            <a href="{{ route('direktorat.show', 'jasa-keuangan-syariah') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Jasa Keuangan Syariah</a>
            <a href="{{ route('direktorat.show', 'keuangan-sosial-syariah') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Keuangan Sosial Syariah</a>
            <a href="{{ route('direktorat.show', 'bisnis-kewirausahaan-syariah') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Bisnis dan Kewirausahaan Syariah</a>
            <a href="{{ route('direktorat.show', 'infrastruktur-ekosistem-syariah') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Infrastruktur Ekosistem Syariah</a>
        </div>

        <div class="flex flex-col gap-2">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Berita</p>
            <a href="{{ route('articles.index') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Berita Terkini</a>
            <a href="{{ route('siaran-pers') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Siaran Pers</a>
        </div>

        <div class="flex flex-col gap-2">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Dokumen</p>
            <a href="{{ route('resources.index') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Dokumen Terkait</a>
            <a href="{{ route('regulations.index') }}" onclick="toggleMobileMenu()" class="pl-2 text-sm font-semibold text-slate-600">Regulasi</a>
        </div>

        @foreach ([
            route('home').'#webgis' => 'Pemetaan',
            route('products.index') => 'Direktori',
            route('contact') => 'Kontak',
        ] as $href => $label)
            <a href="{{ $href }}" onclick="toggleMobileMenu()" class="text-base font-bold text-slate-800">{{ $label }}</a>
        @endforeach
        <button onclick="openModal('searchModal'); toggleMobileMenu()" class="flex items-center justify-between border-t border-slate-50 pt-4 text-left text-base font-bold text-slate-800">
            Pencarian Terpadu
            <i data-lucide="search" class="h-5 w-5"></i>
        </button>
        <button onclick="openModal('sehatiModal'); toggleMobileMenu()" class="mt-2 w-full rounded-xl bg-emerald-500 py-4 font-extrabold text-white shadow-sm transition hover:bg-emerald-400">
            Daftar SEHATI
        </button>
    </div>
</div>
