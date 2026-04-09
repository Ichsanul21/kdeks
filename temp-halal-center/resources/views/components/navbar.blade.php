<nav id="navbar" class="fixed inset-x-0 top-0 z-40 border-b-0 bg-white/70 py-4 backdrop-blur-xl transition-all duration-300">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6">
        <a href="#hero" class="flex items-center">
            <img src="{{ asset('storage/'.data_get($setting, 'logo_path', 'branding/logo.png')) }}" alt="{{ data_get($setting, 'institution_name', 'KDEKS Kalimantan Timur') }}" class="h-8 w-auto object-contain drop-shadow-sm sm:h-9">
        </a>

        <div class="hidden items-center space-x-8 lg:flex">
            @foreach ([
                route('home').'#webgis' => 'Pemetaan',
                route('articles.index') => 'Artikel',
                route('gallery.index') => 'Galeri',
                route('products.index') => 'Direktori',
                route('resources.index') => 'Dokumen',
            ] as $href => $label)
                <a href="{{ $href }}" class="text-sm font-semibold text-slate-500 transition-colors hover:text-emerald-600">{{ $label }}</a>
            @endforeach
        </div>

        <div class="hidden items-center space-x-4 lg:flex">
            <button aria-label="Search" class="text-slate-500 transition-colors hover:text-emerald-600" onclick="openModal('searchModal')">
                <i data-lucide="search" class="h-5 w-5"></i>
            </button>
            <div class="h-4 w-px bg-slate-300"></div>
            <button onclick="openModal('sehatiModal')" class="rounded-full bg-emerald-500 px-5 py-2 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition-all hover:bg-emerald-400">
                Daftar SEHATI
            </button>
        </div>

        <button class="-mr-2 p-2 text-slate-800 lg:hidden" aria-label="Menu" onclick="toggleMobileMenu()">
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
    <div class="flex flex-col gap-6 overflow-y-auto p-6">
        @foreach ([
            route('home').'#webgis' => 'Pemetaan',
            route('articles.index') => 'Artikel',
            route('gallery.index') => 'Galeri',
            route('products.index') => 'Direktori',
            route('resources.index') => 'Dokumen',
        ] as $href => $label)
            <a href="{{ $href }}" onclick="toggleMobileMenu()" class="border-b border-slate-50 pb-4 text-base font-bold text-slate-800">{{ $label }}</a>
        @endforeach
        <button onclick="openModal('searchModal'); toggleMobileMenu()" class="flex items-center justify-between border-b border-slate-50 pb-4 text-left text-base font-bold text-slate-800">
            Pencarian Terpadu
            <i data-lucide="search" class="h-5 w-5"></i>
        </button>
        <button onclick="openModal('sehatiModal'); toggleMobileMenu()" class="mt-2 w-full rounded-xl bg-emerald-500 py-4 font-extrabold text-white shadow-sm transition hover:bg-emerald-400">
            Daftar SEHATI
        </button>
    </div>
</div>
