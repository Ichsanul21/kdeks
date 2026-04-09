<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'Admin Dashboard' }}</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script>
        // Prevent FOUC for sidebar mini state
        if (localStorage.getItem('sidebarMini') === 'true') {
            document.documentElement.classList.add('sidebar-mini');
        }
    </script>
    <style>
        .sidebar-mini .lg\:\[\.sidebar-mini_\&\]\:block { display: block !important; }
        .sidebar-mini .lg\:\[\.sidebar-mini_\&\]\:hidden { display: none !important; }
        .sidebar-mini .lg\:\[\.sidebar-mini_\&\]\:w-20 { width: 5rem !important; }
        .sidebar-mini .lg\:\[\.sidebar-mini_\&\]\:px-3 { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
        .sidebar-mini .lg\:\[\.sidebar-mini_\&\]\:px-0 { padding-left: 0 !important; padding-right: 0 !important; }
        .sidebar-mini .lg\:\[\.sidebar-mini_\&\]\:justify-center { justify-content: center !important; }
        .sidebar-mini .lg\:\[\.sidebar-mini_\&\]\:mx-auto { margin-left: auto !important; margin-right: auto !important; }
        .sidebar-mini .lg\:\[\.sidebar-mini_\&\]\:rotate-180 { transform: rotate(180deg) !important; }
    </style>
</head>
<body class="admin-body antialiased selection:bg-emerald-500 selection:text-white">
    @include('components.watermark-overlay', ['setting' => $setting ?? null])
    @php
        $adminNavigation = [
            'admin.dashboard' => ['label' => 'Dashboard', 'icon' => 'layout-dashboard'],
            'admin.sehati-registrations.index' => ['label' => 'Pengajuan SEHATI', 'icon' => 'file-check-2'],
            'admin.halal-products.index' => ['label' => 'Direktori Produk', 'icon' => 'package'],
            'admin.umkms.index' => ['label' => 'Manajemen UMKM', 'icon' => 'map-pin'],
            'admin.knowledge-resources.index' => ['label' => 'Pustaka Dokumen', 'icon' => 'folder-open'],
            'admin.articles.index' => ['label' => 'Berita & Publikasi', 'icon' => 'newspaper'],
            'admin.events.index' => ['label' => 'Agenda', 'icon' => 'calendar-days'],
            'admin.gallery-items.index' => ['label' => 'Galeri', 'icon' => 'images'],
            'admin.lph-partners.index' => ['label' => 'LPH / LP3H', 'icon' => 'building-2'],
            'admin.mentors.index' => ['label' => 'Pendamping', 'icon' => 'users'],
            'admin.regions.index' => ['label' => 'Wilayah', 'icon' => 'map'],
            'admin.potential-items.index' => ['label' => 'Potensi', 'icon' => 'sparkles'],
            'admin.sector-items.index' => ['label' => 'Sektor', 'icon' => 'briefcase-business'],
            'admin.regulations.index' => ['label' => 'Regulasi', 'icon' => 'scale'],
            'admin.site-settings.index' => ['label' => 'Pengaturan Web', 'icon' => 'settings'],
            'admin.consultation-requests.index' => ['label' => 'Konsultasi', 'icon' => 'messages-square'],
        ];

        if (auth()->user()?->hasRole('developer')) {
            $adminNavigation['admin.watermark-settings.edit'] = ['label' => 'Watermark', 'icon' => 'shield-alert'];
        }
    @endphp

    @if(!request()->has('is_iframe'))
        <div id="sidebarBackdrop" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden" onclick="toggleSidebar()"></div>
    @endif

    <div class="flex h-screen overflow-hidden">
        @if(!request()->has('is_iframe'))
        <aside id="sidebar" class="admin-sidebar fixed inset-y-0 left-0 z-50 flex w-64 lg:[.sidebar-mini_&]:w-20 -translate-x-full transform flex-col shadow-2xl transition-[width,transform] duration-300 lg:relative lg:translate-x-0 lg:shadow-none group">
            <button type="button" onclick="toggleDesktopSidebar()" class="absolute -right-4 top-1/2 z-[60] hidden h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-emerald-200 hover:text-emerald-600 lg:flex">
                <i data-lucide="chevron-left" class="h-4 w-4 transition-transform duration-300 lg:[.sidebar-mini_&]:rotate-180"></i>
            </button>
            <div class="flex h-20 shrink-0 items-center gap-3 border-b border-slate-100 px-6 lg:[.sidebar-mini_&]:px-3 lg:[.sidebar-mini_&]:justify-center">
                <img src="{{ asset('storage/branding/logo.png') }}" alt="KDEKS Kaltim" class="h-8 w-auto object-contain sm:h-9 lg:[.sidebar-mini_&]:hidden">
                <img src="{{ asset('storage/branding/logo.png') }}" alt="KDEKS" class="hidden h-8 w-auto object-contain lg:[.sidebar-mini_&]:block">
                <button class="ml-auto text-slate-400 transition hover:text-slate-900 lg:hidden" onclick="toggleSidebar()">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-6 lg:[.sidebar-mini_&]:px-2">
                <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400 lg:[.sidebar-mini_&]:hidden">Menu Utama</p>

                <div class="space-y-1">
                    @foreach($adminNavigation as $route => $item)
                        @php
                            $active = request()->routeIs($route) || ($route !== 'admin.dashboard' && str_starts_with(optional(request()->route())->getName(), str_replace('.index', '', $route)));
                        @endphp
                        <a href="{{ route($route) }}" class="admin-nav-link group relative lg:[.sidebar-mini_&]:justify-center lg:[.sidebar-mini_&]:px-0 {{ $active ? 'admin-nav-link-active' : '' }}">
                            <span class="flex items-center gap-3 lg:[.sidebar-mini_&]:gap-0">
                                <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5 lg:[.sidebar-mini_&]:mx-auto"></i>
                                <span class="text-sm lg:[.sidebar-mini_&]:hidden">{{ $item['label'] }}</span>
                                @if($route === 'admin.sehati-registrations.index' && ($adminNewSehatiCount ?? 0) > 0)
                                    <span class="ml-auto rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-600 lg:[.sidebar-mini_&]:hidden">{{ $adminNewSehatiCount }}</span>
                                @endif
                            </span>

                            {{-- Custom Tooltip --}}
                            <div class="pointer-events-none absolute left-full top-1/2 z-[100] ml-2 -translate-y-1/2 whitespace-nowrap rounded-lg bg-white px-3 py-2 text-xs font-bold text-emerald-600 opacity-0 shadow-[0_4px_20px_rgba(0,0,0,0.1)] ring-1 ring-slate-100 transition-all duration-200 group-hover:ml-5 group-hover:opacity-100 hidden lg:[.sidebar-mini_&]:block">
                                {{ $item['label'] }}
                                <div class="absolute left-0 top-1/2 -ml-1 -translate-y-1/2 border-y-4 border-r-4 border-y-transparent border-r-white"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="shrink-0 border-t border-slate-100 p-4 lg:[.sidebar-mini_&]:p-2">
                <form method="POST" action="{{ route('logout') }}" class="group relative">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl p-2 text-left transition hover:bg-slate-50 lg:[.sidebar-mini_&]:justify-center">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-cyan-500 text-sm font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1 lg:[.sidebar-mini_&]:hidden">
                            <p class="truncate text-sm font-bold text-slate-900">{{ Auth::user()->name ?? 'Admin KDEKS' }}</p>
                            <p class="truncate text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">{{ Auth::user()?->getRoleNames()->first() ?? 'Administrator' }}</p>
                        </div>
                        <i data-lucide="log-out" class="h-4 w-4 shrink-0 text-slate-400 transition hover:text-red-500 lg:[.sidebar-mini_&]:hidden"></i>
                    </button>
                    {{-- Custom Tooltip Logout --}}
                    <div class="pointer-events-none absolute left-full top-1/2 z-[100] ml-2 -translate-y-1/2 whitespace-nowrap rounded-lg bg-white px-3 py-2 text-xs font-bold text-emerald-600 opacity-0 shadow-[0_4px_20px_rgba(0,0,0,0.1)] ring-1 ring-slate-100 transition-all duration-200 group-hover:ml-5 group-hover:opacity-100 hidden lg:[.sidebar-mini_&]:block">
                        Keluar dari Sistem
                        <div class="absolute left-0 top-1/2 -ml-1 -translate-y-1/2 border-y-4 border-r-4 border-y-transparent border-r-white"></div>
                    </div>
                </form>
            </div>
        </aside>
        @endif

        <div class="flex h-screen flex-1 flex-col overflow-hidden bg-slate-50/50">
            @if(!request()->has('is_iframe'))
            <header class="admin-topbar flex h-20 shrink-0 items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <!-- Mobile Hamburger -->
                    <button class="p-1 text-slate-500 transition hover:text-slate-900 lg:hidden" onclick="toggleSidebar()">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </button>

                    <form method="GET" action="{{ url()->current() }}" class="hidden w-80 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 transition-all focus-within:border-emerald-500/50 focus-within:ring-2 focus-within:ring-emerald-500/20 md:flex">
                        <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama UMKM, sertifikat, dokumen..." class="w-full border-none bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400">
                    </form>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 border-r border-slate-200 pr-4">
                        <button class="relative rounded-lg p-2 text-slate-400 transition hover:bg-slate-50 hover:text-slate-900">
                            <i data-lucide="bell" class="h-5 w-5"></i>
                            @if(($adminNewSehatiCount ?? 0) > 0)
                                <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full border-2 border-white bg-red-500 px-1 text-[10px] font-bold leading-none text-white">
                                    {{ $adminNewSehatiCount }}
                                </span>
                            @endif
                        </button>
                    </div>

                    <div class="hidden text-right sm:block">
                        <p class="text-xs font-bold text-slate-900">{{ now()->translatedFormat('d F Y') }}</p>
                        <p class="text-[10px] font-semibold text-slate-500">{{ now()->timezone(config('app.timezone'))->format('H:i') }} WITA</p>
                    </div>
                </div>
            </header>
            @endif

            <main class="flex-1 overflow-y-auto p-6 md:p-8">
                @if(session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')

                @if(!request()->has('is_iframe'))
                <div class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-slate-200 pt-6 md:flex-row">
                    <p class="text-xs font-bold text-slate-400">© {{ now()->year }} Sistem Informasi KDEKS Kaltim.</p>
                    <div class="flex gap-4 text-xs font-bold text-slate-400">
                        <span>Bantuan Support</span>
                        <span>Log Aktivitas</span>
                    </div>
                </div>
                @endif
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        document.querySelectorAll('[data-richtext]').forEach((element) => {
            const input = document.getElementById(element.dataset.input);
            const quill = new Quill(element, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ header: [1, 2, 3, false] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'blockquote'],
                        ['clean']
                    ]
                }
            });

            quill.root.innerHTML = input.value || '';
            quill.on('text-change', () => input.value = quill.root.innerHTML);
        });
    </script>
    <script>
        function toggleDesktopSidebar() {
            const html = document.documentElement;
            html.classList.toggle('sidebar-mini');
            localStorage.setItem('sidebarMini', html.classList.contains('sidebar-mini'));
        }
    </script>
</body>
</html>
