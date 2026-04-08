<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'Admin Dashboard' }}</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
</head>
<body class="admin-body antialiased selection:bg-emerald-500 selection:text-white">
    @php
        $adminNavigation = [
            'admin.dashboard' => ['label' => 'Dashboard', 'icon' => 'layout-dashboard'],
            'admin.sehati-registrations.index' => ['label' => 'Pengajuan SEHATI', 'icon' => 'file-check-2'],
            'admin.halal-products.index' => ['label' => 'Direktori Produk', 'icon' => 'package'],
            'admin.halal-locations.index' => ['label' => 'Manajemen WebGIS', 'icon' => 'map-pin'],
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
    @endphp

    <div id="sidebarBackdrop" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden" onclick="toggleSidebar()"></div>

    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="admin-sidebar fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full transform flex-col shadow-2xl transition-transform duration-300 lg:static lg:translate-x-0 lg:shadow-none">
            <div class="flex h-20 shrink-0 items-center gap-3 border-b border-slate-100 px-6">
                <img src="{{ asset('storage/branding/logo.png') }}" alt="KDEKS Kaltim" class="h-8 w-auto object-contain sm:h-9">
                <button class="ml-auto text-slate-400 transition hover:text-slate-900 lg:hidden" onclick="toggleSidebar()">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-6">
                <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400">Menu Utama</p>

                <div class="space-y-1">
                    @foreach($adminNavigation as $route => $item)
                        @php
                            $active = request()->routeIs($route) || ($route !== 'admin.dashboard' && str_starts_with(optional(request()->route())->getName(), str_replace('.index', '', $route)));
                        @endphp
                        <a href="{{ route($route) }}" class="admin-nav-link {{ $active ? 'admin-nav-link-active' : '' }}">
                            <span class="flex items-center gap-3">
                                <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                                <span class="text-sm">{{ $item['label'] }}</span>
                                @if($route === 'admin.sehati-registrations.index')
                                    <span class="ml-auto rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-600">{{ \App\Models\SehatiRegistration::count() }}</span>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="shrink-0 border-t border-slate-100 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl p-2 text-left transition hover:bg-slate-50">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-cyan-500 text-sm font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-slate-900">{{ Auth::user()->name ?? 'Admin KDEKS' }}</p>
                            <p class="truncate text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">{{ Auth::user()?->getRoleNames()->first() ?? 'Administrator' }}</p>
                        </div>
                        <i data-lucide="log-out" class="h-4 w-4 text-slate-400 transition hover:text-red-500"></i>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex h-screen flex-1 flex-col overflow-hidden bg-slate-50/50">
            <header class="admin-topbar flex h-20 shrink-0 items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <button class="p-1 text-slate-500 transition hover:text-slate-900 lg:hidden" onclick="toggleSidebar()">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </button>

                    <div class="hidden w-80 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 transition-all focus-within:border-emerald-500/50 focus-within:ring-2 focus-within:ring-emerald-500/20 md:flex">
                        <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                        <input type="text" placeholder="Cari nama UMKM, sertifikat, dokumen..." class="w-full border-none bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 border-r border-slate-200 pr-4">
                        <button class="relative rounded-lg p-2 text-slate-400 transition hover:bg-slate-50 hover:text-slate-900">
                            <i data-lucide="bell" class="h-5 w-5"></i>
                            <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full border-2 border-white bg-red-500"></span>
                        </button>
                    </div>

                    <div class="hidden text-right sm:block">
                        <p class="text-xs font-bold text-slate-900">{{ now()->translatedFormat('d F Y') }}</p>
                        <p class="text-[10px] font-semibold text-slate-500">{{ now()->timezone(config('app.timezone'))->format('H:i') }} WITA</p>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-8">
                @if(session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')

                <div class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-slate-200 pt-6 md:flex-row">
                    <p class="text-xs font-bold text-slate-400">© {{ now()->year }} Sistem Informasi KDEKS Kaltim.</p>
                    <div class="flex gap-4 text-xs font-bold text-slate-400">
                        <span>Bantuan Support</span>
                        <span>Log Aktivitas</span>
                    </div>
                </div>
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
</body>
</html>
