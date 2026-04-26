@extends('layouts.app')

@section('title', 'Data Statistik - KDEKS Kalimantan Timur')

@section('content')

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <!-- Leaflet CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        /* ── Tab Button States ── */
        .tab-btn {
            border: 2px solid transparent;
            background-color: transparent;
        }

        .tab-btn .tab-icon-wrap {
            background-color: #f1f5f9;
            color: #94a3b8;
            transition: all 0.3s ease;
        }

        .tab-btn .tab-label {
            color: #94a3b8;
            transition: color 0.3s ease;
        }

        .tab-btn .tab-divider {
            background-color: #e2e8f0;
            transition: background-color 0.3s ease;
        }

        .tab-btn .tab-sublabel {
            color: #cbd5e1;
            transition: color 0.3s ease;
        }

        .tab-btn:hover .tab-icon-wrap {
            background-color: #e2e8f0;
            color: #64748b;
        }

        .tab-btn:hover .tab-label {
            color: #64748b;
        }

        .tab-btn.is-active {
            border-color: #a7f3d0;
            background-color: white;
            box-shadow: 0 8px 24px -4px rgba(16, 185, 129, 0.13), 0 2px 6px -2px rgba(16, 185, 129, 0.06);
        }

        .tab-btn.is-active .tab-icon-wrap {
            background-color: #ecfdf5;
            color: #059669;
        }

        .tab-btn.is-active .tab-label {
            color: #047857;
        }

        .tab-btn.is-active .tab-divider {
            background-color: #d1fae5;
        }

        .tab-btn.is-active .tab-sublabel {
            color: #10b981;
        }

        /* ── Nas Accordion States ── */
        .nas-nav-btn.is-active {
            background-color: #059669;
            color: white;
            box-shadow: 0 4px 12px -2px rgba(16, 185, 129, 0.3);
        }

        .nas-nav-btn.is-active i {
            color: white !important;
        }

        .nas-sub-nav-btn.is-active {
            color: #059669 !important;
            font-weight: 700;
            background-color: #f0fdf4;
        }

        .nas-accordion-content {
            transition: all 0.3s ease-in-out;
            max-height: 0;
            overflow: hidden;
        }

        .nas-accordion-item.is-open .nas-accordion-content {
            max-height: 1000px;
            padding-bottom: 0.5rem;
        }

        .nas-accordion-item.is-open .nas-accordion-trigger i[data-lucide="chevron-down"] {
            transform: rotate(180deg);
        }

        .nas-accordion-trigger:hover {
            background-color: #f8fafc;
        }

        .nas-sub-nav-btn:hover {
            background-color: #f1f5f9;
            color: #059669;
        }

        /* ── KDEKS Sidebar Nav ── */
        .kdeks-nav-btn {
            transition: all 0.2s ease;
        }

        .kdeks-nav-btn.is-active {
            background-color: #059669;
            color: white;
            box-shadow: 0 4px 12px -2px rgba(16, 185, 129, 0.3);
        }

        .kdeks-nav-btn.is-active i {
            color: white !important;
        }

        .kdeks-sub-btn {
            transition: all 0.2s ease;
            color: #94a3b8;
        }

        .kdeks-sub-btn:hover {
            background-color: #f1f5f9;
            color: #059669;
        }

        /* ── Dev Modal ── */
        #devModal {
            animation: fadeInModal 0.25s ease;
        }

        @keyframes fadeInModal {
            from { opacity: 0; transform: scale(0.96); }
            to   { opacity: 1; transform: scale(1); }
        }

        /* ── Sticky Sidebar (Desktop Only) ── */
        @media (min-width: 1024px) {
            .sidebar-sticky {
                position: -webkit-sticky;
                position: sticky;
                top: 90px; /* Di bawah navbar */
                align-self: flex-start;
                z-index: 30;
                will-change: transform, top;
                transform: translate3d(0,0,0);
            }
        }

        /* Mobile sidebar reset */
        .sidebar-mobile { display: none; }

        /* Parent flex alignment */
        #contentDaerah > .flex-data,
        #contentNasional > .flex {
            align-items: flex-start;
        }
    </style>

    <section class="mx-auto max-w-7xl px-4 pb-24 pt-28 sm:px-6">

        {{-- ============================================ --}}
        {{-- TAB: Nasional / Daerah (Full Width, Slim) --}}
        {{-- ============================================ --}}
        <div
            class="mb-8 grid w-full grid-cols-2 gap-2 rounded-2xl border-2 border-slate-200/80 bg-slate-50/50 p-1 md:gap-2.5 md:p-1.5">
            <button data-tab="nasional"
                class="tab-btn flex items-center justify-center gap-2 rounded-xl px-3 py-2 transition-all duration-300 md:gap-2.5 md:px-4 md:py-2.5">
                <div class="tab-icon-wrap flex h-7 w-7 shrink-0 items-center justify-center rounded-lg md:h-8 md:w-8">
                    <i data-lucide="landmark" class="h-3 w-3 md:h-3.5 md:w-3.5"></i>
                </div>
                <p class="tab-label text-[13px] font-bold leading-none md:text-sm">KNEKS</p>
                <div class="tab-divider mx-0.5 h-4 w-px shrink-0 md:mx-1 md:h-5"></div>
                <p class="tab-sublabel text-[8px] font-bold uppercase tracking-widest leading-none md:text-[9px]">Nasional</p>
            </button>
            <button data-tab="daerah"
                class="tab-btn is-active flex items-center justify-center gap-2 rounded-xl px-3 py-2 transition-all duration-300 md:gap-2.5 md:px-4 md:py-2.5">
                <div class="tab-icon-wrap flex h-7 w-7 shrink-0 items-center justify-center rounded-lg md:h-8 md:w-8">
                    <i data-lucide="map-pin" class="h-3 w-3 md:h-3.5 md:w-3.5"></i>
                </div>
                <p class="tab-label text-[13px] font-bold leading-none md:text-sm">KDEKS</p>
                <div class="tab-divider mx-0.5 h-4 w-px shrink-0 md:mx-1 md:h-5"></div>
                <p class="tab-sublabel text-[8px] font-bold uppercase tracking-widest leading-none md:text-[9px]">Kalimantan TImur</p>
            </button>
        </div>

        {{-- ============================================ --}}
        {{-- Header + Period Selector --}}
        {{-- ============================================ --}}
        <div class="mb-8 flex flex-col gap-4 md:mb-10 md:flex-row md:items-end md:justify-between">
            <div>
                <p id="sectionLabel" class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">Data KDEKS</p>
                <h1 id="sectionTitle" class="mt-1 font-heading text-2xl font-extrabold text-slate-900 sm:text-3xl">
                    Kalimantan Timur</h1>
                <p id="sectionDesc" class="mt-2 max-w-xl text-sm leading-7 text-slate-500">Kumpulan data statistik terkini
                    seputar ekosistem syariah, industri halal, dan komitmen KDEKS di Provinsi Kalimantan Timur.</p>
            </div>

        </div>

        {{-- ============================================ --}}
        {{-- Quick Stats Cards --}}
        {{-- ============================================ --}}
        {{-- ============================================ --}}
        {{-- Quick Stats Cards --}}
        {{-- ============================================ --}}
        <div id="statsDaerah" class="w-full mb-8 grid grid-cols-1 gap-4 xs:grid-cols-2 md:mb-10 md:grid-cols-4 md:gap-4">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 sm:h-9 sm:w-9 sm:rounded-xl">
                    <i data-lucide="file-badge" class="h-4 w-4"></i>
                </div>
                <p id="stat0" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">0</p>
                <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Sertifikat Halal Terbit</p>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 sm:h-9 sm:w-9 sm:rounded-xl">
                    <i data-lucide="building-2" class="h-4 w-4"></i>
                </div>
                <p id="stat1" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">0</p>
                <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Pelaku Usaha Halal</p>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 sm:h-9 sm:w-9 sm:rounded-xl">
                    <i data-lucide="utensils-crossed" class="h-4 w-4"></i>
                </div>
                <p id="stat2" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">0</p>
                <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">UMKM Halal</p>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600 sm:h-9 sm:w-9 sm:rounded-xl">
                    <i data-lucide="map-pin" class="h-4 w-4"></i>
                </div>
                <p id="stat3" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">0</p>
                <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">RPH Halal Beroperasi</p>
            </div>
        </div>

        <div id="statsNasional" class="w-full mb-8 hidden grid grid-cols-1 gap-4 xs:grid-cols-2 md:mb-10 md:grid-cols-4 md:gap-4">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 sm:h-9 sm:w-9 sm:rounded-xl">
                    <i data-lucide="file-check" class="h-4 w-4"></i>
                </div>
                <p id="nStat0" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">2.31M</p>
                <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Total Sertifikasi Halal</p>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 sm:h-9 sm:w-9 sm:rounded-xl">
                    <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                </div>
                <p id="nStat1" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">23.8K T</p>
                <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">PDB ADHB 2025</p>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 sm:h-9 sm:w-9 sm:rounded-xl">
                    <i data-lucide="landmark" class="h-4 w-4"></i>
                </div>
                <p id="nStat2" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">1.03K T</p>
                <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Aset Perbankan Syariah</p>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600 sm:h-9 sm:w-9 sm:rounded-xl">
                    <i data-lucide="lightbulb" class="h-4 w-4"></i>
                </div>
                <p id="nStat3" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">50.18</p>
                <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Indeks Literasi (2025)</p>
            </div>
        </div>



        {{-- ============================================ --}}
        {{-- Dev In Progress Modal --}}
        {{-- ============================================ --}}
        <div id="devModalOverlay" class="fixed inset-0 z-[300] hidden items-center justify-center px-4" style="display:none!important;">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDevModal()"></div>
            <div id="devModal" class="relative z-10 w-full max-w-sm rounded-3xl bg-white p-8 shadow-2xl text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50">
                    <i data-lucide="construction" class="h-7 w-7 text-amber-500"></i>
                </div>
                <h3 class="font-heading text-lg font-extrabold text-slate-900">Sedang Dikembangkan</h3>
                <p class="mt-2 text-sm leading-6 text-slate-500">Data untuk direktorat ini masih dalam proses pengembangan dan akan segera tersedia. Kami terus berupaya menyajikan data yang akurat dan komprehensif.</p>
                <button onclick="closeDevModal()" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">
                    <i data-lucide="check" class="h-4 w-4"></i>
                    Mengerti
                </button>
            </div>
        </div>

        <div id="contentDaerah" class="w-full overflow-visible">
            <div class="flex-data flex flex-col gap-6 lg:flex-row w-full overflow-visible">
                {{-- ===== SIDEBAR KDEKS ===== --}}
                <aside class="w-full lg:w-80 shrink-0 sidebar-sticky">
                    <div class="space-y-1.5">

                        {{-- Dashboard (semua data) --}}
                        <button data-kdeks-view="dashboard"
                            class="kdeks-nav-btn is-active w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-slate-700 hover:bg-slate-50">
                            <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100/50 text-emerald-600">
                                <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i>
                            </div>
                            <span class="text-[13px] font-bold">Dashboard</span>
                            <span class="ml-auto text-[9px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-600 rounded-full px-2 py-0.5">Semua Data</span>
                        </button>

                        {{-- Direktorat Items --}}
                        <div class="pt-1">
                            <p class="px-4 pb-1.5 text-[9px] font-bold uppercase tracking-[0.2em] text-slate-400">Direktorat</p>

                            <button data-kdeks-sub="industri-produk-halal" onclick="openDevModal(this)"
                                class="kdeks-sub-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500">
                                    <i data-lucide="package" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[12px] font-semibold">Industri Produk Halal</span>
                                <i data-lucide="lock" class="ml-auto h-3 w-3 text-slate-300"></i>
                            </button>

                            <button data-kdeks-sub="jasa-keuangan-syariah" onclick="openDevModal(this)"
                                class="kdeks-sub-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-500">
                                    <i data-lucide="landmark" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[12px] font-semibold">Jasa Keuangan Syariah</span>
                                <i data-lucide="lock" class="ml-auto h-3 w-3 text-slate-300"></i>
                            </button>

                            <button data-kdeks-sub="keuangan-sosial-syariah" onclick="openDevModal(this)"
                                class="kdeks-sub-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-rose-50 text-rose-500">
                                    <i data-lucide="heart-handshake" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[12px] font-semibold">Keuangan Sosial Syariah</span>
                                <i data-lucide="lock" class="ml-auto h-3 w-3 text-slate-300"></i>
                            </button>

                            <button data-kdeks-sub="bisnis-kewirausahaan-syariah" onclick="openDevModal(this)"
                                class="kdeks-sub-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-500">
                                    <i data-lucide="briefcase" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[12px] font-semibold">Bisnis & Kewirausahaan Syariah</span>
                                <i data-lucide="lock" class="ml-auto h-3 w-3 text-slate-300"></i>
                            </button>

                            <button data-kdeks-sub="infrastruktur-ekosistem-syariah" onclick="openDevModal(this)"
                                class="kdeks-sub-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-teal-50 text-teal-500">
                                    <i data-lucide="layers" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[12px] font-semibold">Infrastruktur Ekosistem Syariah</span>
                                <i data-lucide="lock" class="ml-auto h-3 w-3 text-slate-300"></i>
                            </button>
                        </div>
                    </div>
                </aside>

                {{-- ===== Main KDEKS Content ===== --}}
                <div class="flex-1 min-w-0 w-full">
            {{-- ============================================ --}}
            {{-- 1. SGIE --}}
            {{-- ============================================ --}}
            <div class="mb-8 w-full">
                <div class="mb-4 flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                        <i data-lucide="globe" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Indikator Ekonomi Islam
                            Global</h2>
                        <p class="text-[10px] text-slate-400 sm:text-xs">SGIE 2025 | Skor per sektor (0–100)</p>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <div class="h-[280px] sm:h-[380px]"><canvas id="chartSGIE"></canvas></div>
                </div>
            </div>


            {{-- ============================================ --}}
            {{-- 2. INDUSTRI PRODUK HALAL --}}
            {{-- ============================================ --}}
            <div class="mb-8">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600"><i
                            data-lucide="trending-up" class="h-4 w-4"></i></div>
                    <div>
                        <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Industri Produk Halal</h2>
                        <p id="descIndustri" class="text-[10px] text-slate-400 sm:text-xs">Perkembangan sertifikasi & nilai
                            ekspor produk halal <span class="font-semibold text-slate-600">Kalimantan Timur</span></p>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">
                            Jumlah Sertifikasi Halal Terbit</p>
                        <div class="h-[220px] sm:h-[280px]"><canvas id="chartSertifikasi"></canvas></div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">
                            Nilai Ekspor Produk Halal (Juta USD)</p>
                        <div class="h-[220px] sm:h-[280px]"><canvas id="chartEkspor"></canvas></div>
                    </div>
                </div>
            </div>


            {{-- ============================================ --}}
            {{-- 3. PARIWISATA RAMAH MUSLIM --}}
            {{-- ============================================ --}}
            <div class="mb-8">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600"><i
                            data-lucide="utensils-crossed" class="h-4 w-4"></i></div>
                    <div>
                        <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Pariwisata Ramah Muslim</h2>
                        <p id="descPariwisata" class="text-[10px] text-slate-400 sm:text-xs">UMKM bersertifikat halal di
                            <span class="font-semibold text-slate-600">Kalimantan Timur</span>
                        </p>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                        <p id="titlePariwisataBar"
                            class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">
                            Jumlah Sertifikat per Kabupaten/Kota</p>
                        <div class="h-[320px]"><canvas id="chartPariwisata"></canvas></div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">
                            Sebaran UMKM Halal per Provinsi</p>
                        <div id="panelPariwisata"
                            class="relative h-[320px] overflow-hidden rounded-xl bg-gradient-to-br from-slate-50 to-emerald-50/40">
                            <svg viewBox="0 0 500 400" class="absolute inset-0 h-full w-full opacity-10">
                                <path
                                    d="M180,80 Q220,40 280,60 Q320,50 350,80 Q380,100 370,150 Q360,200 340,240 Q310,280 280,300 Q240,320 200,300 Q170,280 160,240 Q140,200 150,150 Q155,110 180,80Z"
                                    fill="none" stroke="currentColor" stroke-width="2" />
                            </svg>
                            <div
                                class="relative z-10 flex h-full flex-col justify-center gap-2.5 px-4 py-4 sm:gap-3 sm:px-6">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ============================================ --}}
            {{-- 4. LPH --}}
            {{-- ============================================ --}}
            <div class="mb-8">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600">
                        <i data-lucide="scan-search" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Lembaga Pemeriksa Halal (LPH)
                        </h2>
                        <p class="text-[10px] text-slate-400 sm:text-xs">Jumlah LPH, auditor, perkembangan tahunan, & jenis
                            lembaga</p>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">
                            Perkembangan LPH & Auditor (Year-over-Year)</p>
                        <div class="h-[260px] sm:h-[300px]"><canvas id="chartLPHLine"></canvas></div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">
                            Komposisi Jenis LPH</p>
                        <div class="h-[260px] sm:h-[300px]"><canvas id="chartLPHPie"></canvas></div>
                    </div>
                </div>
            </div>


            {{-- ============================================ --}}
            {{-- 5. MODERNISASI RPH HALAL --}}
            {{-- ============================================ --}}
            <div class="mb-8">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-600"><i
                            data-lucide="warehouse" class="h-4 w-4"></i></div>
                    <div>
                        <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Modernisasi RPH Halal</h2>
                        <p class="text-[10px] text-slate-400 sm:text-xs">Jumlah RPH halal berdasarkan pengelola & sebaran
                            per provinsi</p>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">
                            Jumlah RPH Halal berdasarkan Pengelola</p>
                        <div class="h-[260px] sm:h-[300px]"><canvas id="chartRPHPie"></canvas></div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">
                            Sebaran RPH Halal per Provinsi</p>
                        <div id="panelRPH"
                            class="relative h-[260px] overflow-hidden rounded-xl bg-gradient-to-br from-slate-50 to-rose-50/40 sm:h-[300px]">
                            <svg viewBox="0 0 500 400" class="absolute inset-0 h-full w-full opacity-10">
                                <path
                                    d="M180,80 Q220,40 280,60 Q320,50 350,80 Q380,100 370,150 Q360,200 340,240 Q310,280 280,300 Q240,320 200,300 Q170,280 160,240 Q140,200 150,150 Q155,110 180,80Z"
                                    fill="none" stroke="currentColor" stroke-width="2" />
                            </svg>
                            <div class="relative z-10 grid h-full grid-cols-2 gap-2.5 px-4 py-4 sm:gap-3 sm:px-5 sm:py-5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ============================================ --}}
            {{-- 6. SERTIFIKASI HALAL UMK --}}
            {{-- ============================================ --}}
            <div class="mb-8">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-teal-100 text-teal-600"><i
                            data-lucide="badge-check" class="h-4 w-4"></i></div>
                    <div>
                        <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Sertifikasi Halal UMK</h2>
                        <p class="text-[10px] text-slate-400 sm:text-xs">SH Terbit (Reguler/Self-Declare), jumlah pendamping
                            & lembaga PPH</p>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">
                        Perkembangan Sertifikasi Halal UMK per Tahun</p>
                    <div class="h-[260px] sm:h-[340px]"><canvas id="chartUMK"></canvas></div>
                    <div
                        class="mt-4 grid grid-cols-1 gap-3 border-t border-slate-100 pt-4 sm:mt-5 sm:grid-cols-3 sm:gap-3 sm:pt-5">
                        <div
                            class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 sm:block sm:border-0 sm:bg-transparent sm:text-center">
                            <p id="umkInfo0" class="text-xl font-extrabold text-slate-900 sm:text-2xl">0</p>
                            <p class="ml-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:ml-0 sm:mt-0.5">
                                Pendamping PPH Aktif</p>
                        </div>
                        <div
                            class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 sm:block sm:border-0 sm:bg-transparent sm:text-center">
                            <p id="umkInfo1" class="text-xl font-extrabold text-slate-900 sm:text-2xl">0</p>
                            <p class="ml-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:ml-0 sm:mt-0.5">
                                Lembaga PPH Terdaftar</p>
                        </div>
                        <div
                            class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 sm:block sm:border-0 sm:bg-transparent sm:text-center">
                            <p id="umkInfo2" class="text-xl font-extrabold text-emerald-600 sm:text-2xl">0%</p>
                            <p class="ml-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:ml-0 sm:mt-0.5">
                                Tingkat Keberhasilan</p>
                        </div>
                    </div>
                </div> {{-- /.rounded-2xl Sertifikasi UMK --}}
            </div> {{-- /.mb-8 section UMK --}}

                </div> {{-- /.flex-1 min-w-0 main KDEKS content --}}
            </div> {{-- /.flex flex-col gap-6 KDEKS flex row --}}
        </div> {{-- /#contentDaerah --}}

        <div id="contentNasional" class="hidden w-full overflow-visible">
            <div class="flex flex-col gap-6 lg:flex-row w-full overflow-visible">
                {{-- Sidebar Accordion --}}
                <aside class="w-full lg:w-80 shrink-0 sidebar-sticky" id="nasSidebarDesktop">
                    <div class="space-y-2">
                        {{-- 1. Dashboard Eksekutif --}}
                        <button data-nas-view="eksekutif"
                            class="nas-nav-btn is-active w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-slate-700 hover:bg-slate-50 transition-all duration-300">
                            <div
                                class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100/50 text-emerald-600">
                                <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i>
                            </div>
                            <span class="text-[13px] font-bold">Dashboard Eksekutif</span>
                        </button>

                        {{-- 2. Penguatan Industri --}}
                        <div
                            class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                            <button
                                class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-50 text-blue-500">
                                        <i data-lucide="package" class="h-3.5 w-3.5"></i>
                                    </div>
                                    <span class="text-[13px] font-bold">Industri & UMKM Halal</span>
                                </div>
                                <i data-lucide="chevron-down"
                                    class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                            </button>
                            <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                                <button data-nas-view="daya-saing"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Daya
                                    Saing Industri</button>
                                <button data-nas-view="pariwisata"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pariwisata
                                    Ramah Muslim</button>
                                <button data-nas-view="kih"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Kawasan
                                    Industri Halal (KIH)</button>
                                <button data-nas-view="lph"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Lembaga
                                    Pemeriksa Halal (LPH)</button>
                                <button data-nas-view="kodifikasi"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Kodifikasi
                                    Data Industri</button>
                                <button data-nas-view="kesehatan"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Industri
                                    Kesehatan Syariah</button>
                                <button data-nas-view="rph"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Modernisasi
                                    RPH Halal</button>
                                <button data-nas-view="hvc"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Halal
                                    Value Chain (HVC)</button>
                                <button data-nas-view="khas"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Zona
                                    Kuliner Halal (KHAS)</button>
                                <button data-nas-view="modul-umkm"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Modul
                                    UMKM IH</button>
                                <button data-nas-view="cold-storage"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">RPHR,
                                    RPHU, Cold Storage</button>
                            </div>
                        </div>

                        {{-- 3. PDB --}}
                        <div
                            class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                            <button
                                class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-50 text-amber-500">
                                        <i data-lucide="bar-chart-3" class="h-3.5 w-3.5"></i>
                                    </div>
                                    <span class="text-[13px] font-bold">Aktivitas Usaha / PDB</span>
                                </div>
                                <i data-lucide="chevron-down"
                                    class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                            </button>
                            <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                                <button data-nas-view="indikator-aktivitas"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Indikator
                                    Aktivitas Usaha</button>
                            </div>
                        </div>

                        {{-- 4. Ekspor --}}
                        <div
                            class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                            <button
                                class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-sky-50 text-sky-500">
                                        <i data-lucide="ship" class="h-3.5 w-3.5"></i>
                                    </div>
                                    <span class="text-[13px] font-bold">Ekspor & Internasional</span>
                                </div>
                                <i data-lucide="chevron-down"
                                    class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                            </button>
                            <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                                <button data-nas-view="nilai-ekspor"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Nilai
                                    Ekspor/PDB</button>
                                <button data-nas-view="logistik"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Logistik
                                    Halal</button>
                                <button data-nas-view="percepatan-ekspor"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Percepatan
                                    Ekspor</button>
                            </div>
                        </div>

                        {{-- 5. Keuangan Syariah --}}
                        <div
                            class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                            <button
                                class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded-lg bg-violet-50 text-violet-500">
                                        <i data-lucide="landmark" class="h-3.5 w-3.5"></i>
                                    </div>
                                    <span class="text-[13px] font-bold">Keuangan Syariah</span>
                                </div>
                                <i data-lucide="chevron-down"
                                    class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                            </button>
                            <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                                <button data-nas-view="aset-keuangan"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Aset
                                    Keuangan Syariah</button>
                                <button data-nas-view="pembiayaan-umkm"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pembiayaan
                                    UMKM</button>
                                <button data-nas-view="perbankan-syariah"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Perbankan
                                    Syariah</button>
                                <button data-nas-view="payroll-asn"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Payroll
                                    ASN</button>
                                <button data-nas-view="jaminan-sosial"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Jaminan
                                    Sosial</button>
                                <button data-nas-view="pembiayaan-bprs"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pembiayaan
                                    Syariah Kepada IKMS</button>
                                <button data-nas-view="kpbu-syariah"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">KPBU
                                    Syariah</button>
                                <button data-nas-view="pasar-modal"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pasar
                                    Modal Syariah</button>
                                <button data-nas-view="tren-pasar-modal"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Tren
                                    Pasar Modal Syariah</button>
                                <button data-nas-view="inovasi-keuangan"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Inovasi
                                    Keuangan Syariah</button>
                                <button data-nas-view="asuransi-syariah"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Penguatan
                                    Asuransi Syariah</button>
                                <button data-nas-view="dapen-syariah"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pengembangan
                                    Dapen Syariah</button>
                                <button data-nas-view="sektor-ekonomi"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pembiayaan
                                    Sektor Ekonomi</button>
                                <button data-nas-view="iknb-syariah"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">IKNB
                                    Syariah</button>
                                <button data-nas-view="kinerja-perbankan"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Kinerja
                                    Perbankan Syariah</button>
                                <button data-nas-view="perkembangan-aset-keuangan"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Perkembangan
                                    Aset
                                    Keuangan Syariah</button>
                                <button data-nas-view="syariah-daerah"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Keuangan
                                    Syariah Daerah</button>
                                <button data-nas-view="kinerja-bus-uus"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Kinerja
                                    BUS - UUS</button>
                            </div>
                        </div>

                        {{-- 6. Dana Sosial --}}
                        <div
                            class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                            <button
                                class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded-lg bg-rose-50 text-rose-500">
                                        <i data-lucide="heart-handshake" class="h-3.5 w-3.5"></i>
                                    </div>
                                    <span class="text-[13px] font-bold">Dana Sosial Syariah</span>
                                </div>
                                <i data-lucide="chevron-down"
                                    class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                            </button>
                            <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                                <button data-nas-view="zis-pdb"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">ZIS
                                    / PDB</button>
                                <button data-nas-view="transformasi-wakaf"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Transformasi
                                    Wakaf</button>
                                <button data-nas-view="wakaf-uang"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Aset
                                    Wakaf Uang/PDB</button>
                                <button data-nas-view="pendanaan-umkm-sosial"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pendanaan
                                    UMKM Sosial</button>
                                <button data-nas-view="zis-nasional"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">ZIS
                                    Nasional</button>
                            </div>
                        </div>

                        {{-- 7. Ekosistem Pendukung --}}
                        <div
                            class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                            <button
                                class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded-lg bg-teal-50 text-teal-500">
                                        <i data-lucide="layers" class="h-3.5 w-3.5"></i>
                                    </div>
                                    <span class="text-[13px] font-bold">Ekosistem Pendukung</span>
                                </div>
                                <i data-lucide="chevron-down"
                                    class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                            </button>
                            <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                                <button data-nas-view="sertifikasi-umk-nas"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Sertifikasi
                                    Halal UMK</button>
                                <button data-nas-view="literasi-ekonomi"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Literasi
                                    Ekonomi Syariah</button>
                                <button data-nas-view="layanan-komunitas"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Layanan
                                    Komunitas</button>
                                <button data-nas-view="sdm-pendidikan"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">SDM
                                    Ekonomi Syariah</button>
                                <button data-nas-view="sosialisasi-brand"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Sosialisasi Brand</button>
                                <button data-nas-view="kdeks"
                                    class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">KDEKS</button>

                        </div>
                    </div>
                </aside>

                {{-- Main Content Area --}}
                <div id="nasMainContent" class="flex-1 min-w-0 w-full">
                    {{-- 1. Dashboard Eksekutif View --}}
                    <div id="nasView-eksekutif" class="nas-view-content space-y-10">
                        {{-- SGIE & Global Ranking --}}
                        <div>
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                    <i data-lucide="globe" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">SGIE 2025 &
                                        Global Ranking</h2>
                                    <p class="text-[10px] text-slate-400 sm:text-xs">Global Islamic Economy Indicator | Skor
                                        Top 15 Negara</p>
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-12">
                                <div
                                    class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-8">
                                    <div class="mb-4 flex items-center justify-between">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                            Skor Ekonomi Islam Global (Top 15)</p>
                                        <select id="sgieSelector"
                                            class="rounded-lg border-slate-200 bg-slate-50 px-3 py-1 text-[10px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-emerald-500/20">
                                            <option value="overall">Overall Indicator</option>
                                            <option value="food">Halal Food</option>
                                            <option value="finance">Islamic Finance</option>
                                            <option value="travel">Muslim-Friendly Travel</option>
                                            <option value="media">Media & Recreation</option>
                                            <option value="pharma">Pharma & Cosmetics</option>
                                            <option value="fashion">Modest Fashion</option>
                                        </select>
                                    </div>
                                    <div class="h-[320px] sm:h-[420px]"><canvas id="chartNasSGIE"></canvas></div>
                                </div>
                                <div
                                    class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-4">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Global Muslim Travel Index 2024</p>
                                    <div id="panelGMTI" class="space-y-2 overflow-y-auto pr-1" style="max-height: 420px;">
                                        <!-- List GMTI rendered via JS -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Makroekonomi & PDB Nasional --}}
                        <div>
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                    <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Makroekonomi &
                                        PDB Nasional</h2>
                                    <p class="text-[10px] text-slate-400 sm:text-xs">Produk Domestik Bruto Atas Dasar Harga
                                        Konstan & Berlaku</p>
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Tren PDB Tahunan (Triliun Rupiah)</p>
                                    <div class="h-[280px]"><canvas id="chartNasPDB"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Kontribusi PDB per Sektor (2025)</p>
                                    <div class="h-[280px]"><canvas id="chartNasPDBSektor"></canvas></div>
                                </div>
                            </div>
                        </div>

                        {{-- Industri Halal & Ekspor --}}
                        <div>
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                    <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Industri Halal &
                                        Ekspor</h2>
                                    <p class="text-[10px] text-slate-400 sm:text-xs">Sertifikasi Halal & Nilai Ekspor Produk
                                        Halal Indonesia</p>
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-3">
                                <div
                                    class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-2">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Perkembangan Sertifikasi Halal (Nasional)</p>
                                    <div class="h-[280px]"><canvas id="chartNasSertifikasi"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Kontribusi Ekspor per Sektor</p>
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="relative h-[220px] w-full shrink-0">
                                            <canvas id="chartNasEksporSektor"></canvas>
                                        </div>
                                        <div id="eksporSectorLegend" class="grid grid-cols-2 gap-x-4 gap-y-2 w-full"></div>
                                    </div>
                                </div>
                                <div
                                    class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-2">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Nilai Ekspor Produk Halal (2019 - 2025)</p>
                                    <div class="h-[280px]"><canvas id="chartNasEkspor"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Top 5 Negara Tujuan Ekspor (Jan 2025)</p>
                                    <div id="panelEksporNegara" class="space-y-3">
                                        <!-- List Top Export countries -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Keuangan Syariah & Bisnis --}}
                        <div>
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600">
                                    <i data-lucide="landmark" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Keuangan Syariah
                                        & Bisnis</h2>
                                    <p class="text-[10px] text-slate-400 sm:text-xs">Indikator Aktivitas Usaha & Aset
                                        Keuangan Syariah</p>
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Indikator Aktivitas Usaha Syariah</p>
                                    <div class="h-[300px]"><canvas id="chartNasAktivitas"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Aset Keuangan Islam Teratas (Global 2022)</p>
                                    <div class="h-[300px]"><canvas id="chartNasAsetGlobal"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Perkembangan Aset IKNB Syariah</p>
                                    <div class="h-[300px]"><canvas id="chartNasIKNB"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Pasar Konsumen Halal (Top Markets)</p>
                                    <div class="mb-4 flex items-center gap-2">
                                        <button data-market="food"
                                            class="market-tab-btn is-active rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500 transition-all hover:bg-slate-200">Food</button>
                                        <button data-market="fashion"
                                            class="market-tab-btn rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500 transition-all hover:bg-slate-200">Fashion</button>
                                        <button data-market="travel"
                                            class="market-tab-btn rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500 transition-all hover:bg-slate-200">Travel</button>
                                        <button data-market="pharma"
                                            class="market-tab-btn rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500 transition-all hover:bg-slate-200">Pharma</button>
                                    </div>
                                    <div class="h-[220px]"><canvas id="chartNasMarket"></canvas></div>
                                </div>
                            </div>
                        </div>

                        {{-- Keuangan Sosial & Literasi --}}
                        <div class="pb-10">
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                                    <i data-lucide="heart-handshake" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Keuangan Sosial &
                                        Literasi</h2>
                                    <p class="text-[10px] text-slate-400 sm:text-xs">Zakat, Wakaf, & Indeks Literasi Ekonomi
                                        Syariah</p>
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-3">
                                <div
                                    class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-2">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Indeks Literasi & Awareness (2019 - 2025)</p>
                                    <div class="h-[280px]"><canvas id="chartNasLiterasi"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                    <p
                                        class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                        Ringkasan Keuangan Sosial</p>
                                    <div class="space-y-4">
                                        <div class="rounded-xl bg-emerald-50/50 p-4">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Zakat
                                                & Infak</p>
                                            <div class="mt-2 flex items-center justify-between">
                                                <div>
                                                    <p class="text-[9px] text-slate-400">Pengumpulan</p>
                                                    <p class="text-sm font-bold text-slate-800">10.3 T</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-[9px] text-slate-400">Penyaluran</p>
                                                    <p class="text-sm font-bold text-slate-800">9.2 T</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rounded-xl bg-blue-50/50 p-4">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Wakaf
                                                Uang</p>
                                            <div class="mt-2 flex items-center justify-between">
                                                <div>
                                                    <p class="text-[9px] text-slate-400">Nazhir</p>
                                                    <p class="text-sm font-bold text-slate-800">432</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-[9px] text-slate-400">LKSPWU</p>
                                                    <p class="text-sm font-bold text-slate-800">61</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2.1 Daya Saing Industri View --}}
                    <div id="nasView-daya-saing" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="trending-up" class="h-3 w-3"></i>
                                <span>Penguatan Industri</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Daya Saing Industri Halal
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">Persentase Peningkatan Daya Saing Industri yang
                                Memproduksi Produk Halal (%)</p>
                        </div>

                        {{-- Stats Grid + Filter --}}
                        <div class="flex flex-col gap-6 md:flex-row md:items-start">
                            <div class="grid flex-1 grid-cols-1 gap-4 sm:grid-cols-2">
                                {{-- Target RPJMN Card --}}
                                <div
                                    class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:shadow-md">
                                    <div
                                        class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-[#A7D07C]/5 transition-transform group-hover:scale-110">
                                    </div>
                                    <div class="relative z-10">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#A7D07C]/10 text-[#7ca34f]">
                                                <i data-lucide="target" class="h-4.5 w-4.5"></i>
                                            </div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Target
                                                RPJMN</p>
                                        </div>
                                        <div class="flex items-baseline gap-1">
                                            <span id="dsTargetVal"
                                                class="text-4xl font-extrabold text-slate-900 tracking-tight">4.46</span>
                                            <span class="text-lg font-bold text-slate-400">%</span>
                                        </div>
                                        <p class="mt-2 text-[10px] leading-relaxed text-slate-400">Target Rencana
                                            Pembangunan Jangka Menengah Nasional</p>
                                    </div>
                                </div>

                                {{-- Realisasi Card --}}
                                <div
                                    class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:shadow-md">
                                    <div
                                        class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-[#F2A86F]/5 transition-transform group-hover:scale-110">
                                    </div>
                                    <div class="relative z-10">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F2A86F]/10 text-[#d68a4d]">
                                                <i data-lucide="bar-chart-3" class="h-4.5 w-4.5"></i>
                                            </div>
                                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Realisasi
                                                Capaian</p>
                                        </div>
                                        <div class="flex items-baseline gap-1">
                                            <span id="dsRealisasiVal"
                                                class="text-4xl font-extrabold text-slate-900 tracking-tight">4.55</span>
                                            <span class="text-lg font-bold text-slate-400">%</span>
                                        </div>
                                        <p class="mt-2 text-[10px] leading-relaxed text-slate-400">Realisasi pencapaian
                                            peningkatan daya saing industri</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Filter Tahun --}}
                            <div
                                class="w-full md:w-72 shrink-0 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <div class="flex items-center gap-2 mb-4">
                                    <i data-lucide="calendar" class="h-3.5 w-3.5 text-emerald-500"></i>
                                    <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Periode
                                        Tahun</label>
                                </div>
                                <select id="dsYearSelector"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-sm transition-all hover:bg-slate-100">
                                    <!-- Year options rendered via JS -->
                                </select>
                            </div>
                        </div>

                        {{-- Main Chart --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="font-heading text-lg font-bold text-slate-800">Tren Peningkatan Daya Saing
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Perbandingan Target RPJMN vs Realisasi
                                        Pencapaian</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                    <div class="flex items-center gap-2.5">
                                        <span class="h-3 w-3 rounded-full bg-[#A7D07C]"></span>
                                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Target
                                            RPJMN</span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <span class="h-3 w-3 rounded-full bg-[#F2A86F]"></span>
                                        <span
                                            class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Realisasi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[350px] sm:h-[450px]">
                                <canvas id="chartDayaSaing"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 2.2 Pariwisata Ramah Muslim View --}}
                    <div id="nasView-pariwisata" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="palmtree" class="h-3 w-3"></i>
                                <span>Pariwisata Ramah Muslim</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Restoran & Hotel
                                Bersertifikat Halal</h2>
                            <p class="text-sm text-slate-500 mt-1">Data sebaran restoran dan akomodasi yang telah
                                tersertifikasi halal/syariah di Indonesia.</p>
                        </div>

                        {{-- Stats Cards --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                        <i data-lucide="file-check" class="h-4.5 w-4.5"></i>
                                    </div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total
                                        Sertifikat Halal</p>
                                </div>
                                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">1.322</p>
                                <p class="mt-1 text-[10px] text-slate-400">Restoran & Rumah Makan</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                        <i data-lucide="users" class="h-4.5 w-4.5"></i>
                                    </div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Jumlah Pelaku
                                        Usaha</p>
                                </div>
                                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">1.211</p>
                                <p class="mt-1 text-[10px] text-slate-400">Pemilik Usaha Kuliner</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                        <i data-lucide="hotel" class="h-4.5 w-4.5"></i>
                                    </div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Hotel Syariah
                                    </p>
                                </div>
                                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">7</p>
                                <p class="mt-1 text-[10px] text-slate-400">Akomodasi Bersertifikat</p>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Chart Sebaran Restoran --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <div class="mb-6">
                                    <h3 class="font-heading text-lg font-bold text-slate-800">Provinsi Terbanyak (Restoran
                                        Halal)</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">5 Provinsi dengan jumlah sertifikasi restoran
                                        tertinggi</p>
                                </div>
                                <div class="h-[300px]">
                                    <canvas id="chartNasPariwisataProv"></canvas>
                                </div>
                            </div>

                            {{-- Table Daftar Hotel --}}
                            <div
                                class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h3 class="font-heading text-lg font-bold text-slate-800">Daftar Hotel Bersertifikat
                                        Halal</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Status dan masa berlaku sertifikasi akomodasi
                                    </p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="bg-slate-50/50">
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Lembaga / Nama Hotel</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Masa Berlaku</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasHotel" class="divide-y divide-slate-100">
                                            <!-- Row rendered via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- 2.2 Pariwisata Ramah Muslim View --}}
                    <!-- ... existing pariwisata view ... -->

                    {{-- 2.3 Kawasan Industri Halal (KIH) View --}}
                    <div id="nasView-kih" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="factory" class="h-3 w-3"></i>
                                <span>Kawasan Industri Halal</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Perkembangan KIH di
                                Indonesia</h2>
                            <p class="text-sm text-slate-500 mt-1">Data status perencanaan, pendampingan, dan operasional
                                Kawasan Industri Halal secara nasional.</p>
                        </div>

                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Perkembangan KIH (Pie Chart) --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <div class="mb-8">
                                    <h3 class="font-heading text-lg font-bold text-slate-800">Status Perkembangan KIH</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Persentase status Kawasan Industri Halal dari
                                        total 12 lokasi</p>
                                </div>
                                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                                    {{-- Left Legend --}}
                                    <div id="kihLegendLeft" class="flex-1 space-y-4 w-full"></div>

                                    {{-- Pie Chart --}}
                                    <div class="relative h-[300px] w-[300px] shrink-0">
                                        <canvas id="chartNasKIHStatus"></canvas>
                                    </div>

                                    {{-- Right Legend --}}
                                    <div id="kihLegendRight" class="flex-1 space-y-4 w-full"></div>
                                </div>
                            </div>

                            {{-- Sebaran KIH (Map) --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <div class="mb-6 flex items-center justify-between">
                                    <div>
                                        <h3 class="font-heading text-lg font-bold text-slate-800">Sebaran KIH Nasional</h3>
                                        <p class="text-xs text-slate-400 mt-0.5">Peta lokasi Kawasan Industri Halal di
                                            berbagai provinsi</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                        <span class="text-[10px] font-bold text-slate-500 uppercase">Telah Mendapatkan
                                            SK</span>
                                    </div>
                                </div>
                                <div
                                    class="relative h-[450px] w-full rounded-xl overflow-hidden bg-slate-50 border border-slate-100">
                                    <div id="mapNasKIH" class="h-full w-full"></div>
                                    {{-- Overlay fallback if Leaflet not initialized --}}
                                    <div id="mapNasKIHFallback"
                                        class="absolute inset-0 flex items-center justify-center bg-slate-50 hidden">
                                        <p class="text-xs font-medium text-slate-400">Memuat Peta Sebaran...</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Table Daftar KIH --}}
                            <div
                                class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h3 class="font-heading text-lg font-bold text-slate-800">Daftar Kawasan Industri Halal
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5">List lengkap lokasi dan status operasional KIH
                                    </p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="bg-slate-50/50">
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Nama Kawasan</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Provinsi</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasKIH" class="divide-y divide-slate-100">
                                            <!-- Row rendered via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2.4 Lembaga Pemeriksa Halal (LPH) View --}}
                    <div id="nasView-lph" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="microscope" class="h-3 w-3"></i>
                                <span>Lembaga Pemeriksa Halal</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Ekosistem Pemeriksa Halal
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">Data sebaran lembaga pemeriksa dan ketersediaan auditor
                                halal profesional di Indonesia.</p>
                        </div>

                        {{-- Stats Cards --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div
                                class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                                <div
                                    class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-emerald-50 transition-transform group-hover:scale-110">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 shadow-sm">
                                            <i data-lucide="building-2" class="h-5 w-5"></i>
                                        </div>
                                        <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400">Jumlah
                                            LPH</p>
                                    </div>
                                    <p class="text-4xl font-black text-slate-900">124</p>
                                    <p class="mt-2 text-xs font-medium text-slate-500">Lembaga aktif yang terdaftar di BPJPH
                                    </p>
                                </div>
                            </div>
                            <div
                                class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                                <div
                                    class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-blue-50 transition-transform group-hover:scale-110">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600 shadow-sm">
                                            <i data-lucide="users-round" class="h-5 w-5"></i>
                                        </div>
                                        <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400">Total
                                            Auditor Halal</p>
                                    </div>
                                    <p class="text-4xl font-black text-slate-900">1.730</p>
                                    <p class="mt-2 text-xs font-medium text-slate-500">Tenaga ahli pemeriksa kehalalan
                                        produk</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-12">
                            <div class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <div class="mb-8 flex items-center justify-between">
                                    <div>
                                        <h3 class="font-heading text-lg font-bold text-slate-800">Tren Pertumbuhan LPH</h3>
                                        <p class="text-xs text-slate-400 mt-0.5">Pertumbuhan jumlah lembaga dari 2019 - 2026
                                        </p>
                                    </div>
                                </div>
                                <div class="h-[320px]">
                                    <canvas id="chartNasLPHGrowth"></canvas>
                                </div>
                            </div>

                            <div class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <div class="mb-8">
                                    <h3 class="font-heading text-lg font-bold text-slate-800">Jenis LPH</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Komposisi LPH Pratama & Utama</p>
                                </div>
                                <div class="relative h-[220px]">
                                    <canvas id="chartNasLPHType"></canvas>
                                </div>
                                <div id="lphTypeLegend" class="mt-8 space-y-3"></div>
                            </div>

                            <div
                                class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h3 class="font-heading text-lg font-bold text-slate-800">Latar Belakang Pendidikan
                                        Auditor</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Jurusan auditor halal terbanyak secara nasional
                                    </p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="bg-slate-50/50">
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Program Studi / Jurusan</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-right">
                                                    Jumlah Auditor</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasLPH" class="divide-y divide-slate-100">
                                            <!-- Row rendered via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2.5 Kodifikasi Data Industri View --}}
                    <div id="nasView-kodifikasi" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="binary" class="h-3 w-3"></i>
                                <span>Kodifikasi Data Industri</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Data Kodifikasi Produk
                                Halal</h2>
                            <p class="text-sm text-slate-500 mt-1">Integrasi data sertifikasi dan logistik produk halal
                                nasional.</p>
                        </div>

                        {{-- Stats Cards --}}
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Sertifikat</p>
                                <p class="text-2xl font-black text-slate-900">850</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Jumlah PEB</p>
                                <p class="text-2xl font-black text-slate-900">131.052</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Tonase</p>
                                <p class="text-2xl font-black text-slate-900">28.5M</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Perusahaan</p>
                                <p class="text-2xl font-black text-slate-900">290</p>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Scale Pie Chart --}}
                            <div class="md:col-span-5 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Skala Usaha Perusahaan</h3>
                                <div class="h-[220px]">
                                    <canvas id="chartNasKodifScale"></canvas>
                                </div>
                                <div id="kodifScaleLegend" class="mt-6 grid grid-cols-2 gap-3"></div>
                            </div>

                            {{-- Top 10 Komoditas --}}
                            <div class="md:col-span-7 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Top 10 Komoditas</h3>
                                <div class="h-[320px]">
                                    <canvas id="chartNasKodifCommodity"></canvas>
                                </div>
                            </div>

                            {{-- Top 10 Perusahaan - Full Width --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Top 10 Perusahaan</h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasKodifCompany"></canvas>
                                </div>
                            </div>

                            {{-- Ports --}}
                            <div class="md:col-span-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Top 10 Pelabuhan Muat</h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasKodifPortOrigin"></canvas>
                                </div>
                            </div>
                            <div class="md:col-span-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Top 10 Pelabuhan Tujuan
                                </h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasKodifPortDest"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2.6 Industri Kesehatan Syariah View --}}
                    <div id="nasView-kesehatan" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="heart-pulse" class="h-3 w-3"></i>
                                <span>Industri Kesehatan Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Ekosistem Kesehatan
                                Syariah</h2>
                            <p class="text-sm text-slate-500 mt-1">Data sebaran produk farmasi dan layanan kesehatan
                                bersertifikat syariah.</p>
                        </div>

                        {{-- Stats Cards --}}
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Produk Farmasi
                                </p>
                                <p class="text-2xl font-black text-slate-900">{{ number_format($dashboard_data['farmasi_total'] ?? 44634, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Anggota MUKISI
                                </p>
                                <p class="text-2xl font-black text-slate-900">550</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">RS Syariah</p>
                                <p class="text-2xl font-black text-slate-900">{{ number_format($dashboard_data['rs_syariah_total'] ?? 38, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Klinik</p>
                                <p class="text-2xl font-black text-slate-900">{{ number_format($dashboard_data['klinik_total'] ?? 1, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Lab Medis</p>
                                <p class="text-2xl font-black text-slate-900">{{ number_format($dashboard_data['lab_medis_total'] ?? 1, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Farmasi Chart --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-lg font-bold text-slate-800 mb-6">Jenis Produk Farmasi Halal
                                </h3>
                                <div class="h-[400px]">
                                    <canvas id="chartNasFarmasiHalal"></canvas>
                                </div>
                            </div>

                            {{-- Table --}}
                            <div
                                class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h3 class="font-heading text-lg font-bold text-slate-800">Lembaga Kesehatan
                                        Bersertifikat Syariah</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Daftar RS, Laboratorium, dan Klinik aktif</p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="bg-slate-50/50">
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Nama Lembaga</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Jenis Layanan</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Berlaku S/D</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasRS" class="divide-y divide-slate-100">
                                            <!-- Row rendered via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2.7 RPH Halal View --}}
                    <div id="nasView-rph" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="utensils" class="h-3 w-3"></i>
                                <span>Rumah Potong Hewan (RPH) Halal</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Jejaring RPH Halal
                                Nasional</h2>
                            <p class="text-sm text-slate-500 mt-1">Data sebaran dan kepemilikan fasilitas pemotongan hewan
                                bersertifikat halal.</p>
                        </div>

                        {{-- Stats Cards --}}
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-2">
                            <div
                                class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                                <div
                                    class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-orange-50 transition-transform group-hover:scale-110">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-orange-600 shadow-sm">
                                            <i data-lucide="warehouse" class="h-5 w-5"></i>
                                        </div>
                                        <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400">Jumlah
                                            RPH Halal</p>
                                    </div>
                                    <p class="text-4xl font-black text-slate-900">1.078</p>
                                    <p class="mt-2 text-xs font-medium text-slate-500">Unit fasilitas pemotongan aktif</p>
                                </div>
                            </div>
                            <div
                                class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                                <div
                                    class="absolute top-0 right-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-blue-50 transition-transform group-hover:scale-110">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600 shadow-sm">
                                            <i data-lucide="map-pin" class="h-5 w-5"></i>
                                        </div>
                                        <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400">Sebaran
                                            Provinsi</p>
                                    </div>
                                    <p class="text-4xl font-black text-slate-900">28</p>
                                    <p class="mt-2 text-xs font-medium text-slate-500">Wilayah dengan fasilitas RPH Halal
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Distribution Pie --}}
                            <div class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Status Kepemilikan</h3>
                                <div class="h-[220px]">
                                    <canvas id="chartNasRPHOwner"></canvas>
                                </div>
                                <div id="rphOwnerLegend" class="mt-8 space-y-3"></div>
                            </div>

                            {{-- Top Provinces Table --}}
                            <div
                                class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                                <div class="p-6 border-b border-slate-100">
                                    <h3 class="font-heading text-base font-bold text-slate-800">Provinsi Terbanyak</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Wilayah dengan konsentrasi RPH Halal tertinggi
                                    </p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="bg-slate-50/50">
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Wilayah</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-right">
                                                    Total RPH</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasRPHTop" class="divide-y divide-slate-100">
                                            <!-- Row rendered via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Map --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Peta Sebaran RPH Halal</h3>
                                <div id="mapNasRPH" class="h-[500px] rounded-xl z-10 shadow-inner"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 2.8 Halal Value Chain (HVC) View --}}
                    <div id="nasView-hvc" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="trending-up" class="h-3 w-3"></i>
                                <span>Halal Value Chain (HVC)</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Analisis Sektor Unggulan
                                HVC</h2>
                            <p class="text-sm text-slate-500 mt-1">Perkembangan pertumbuhan, kontribusi, dan pangsa pasar
                                sektor ekonomi halal.</p>
                        </div>

                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Growth Chart --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Pertumbuhan Sektor Unggulan
                                    HVC (%)</h3>
                                <div class="h-[400px]">
                                    <canvas id="chartNasHVCGrowth"></canvas>
                                </div>
                            </div>

                            {{-- Contribution Chart --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Kontribusi Pertumbuhan
                                    Sektor HVC</h3>
                                <div class="h-[400px]">
                                    <canvas id="chartNasHVCContrib"></canvas>
                                </div>
                            </div>

                            {{-- PDB Share --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Pangsa Sektor HVC terhadap
                                    PDB (%)</h3>
                                <div class="h-[450px]">
                                    <canvas id="chartNasHVCPangsa"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2.9 Zona KHAS View --}}
                    <div id="nasView-khas" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="utensils-crossioned" class="h-3 w-3"></i>
                                <span>Zona KHAS (Kuliner Halal Aman & Sehat)</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Pengembangan Zona KHAS
                                Nasional</h2>
                            <p class="text-sm text-slate-500 mt-1">Data peresmian dan sebaran zona kuliner halal di seluruh
                                Indonesia.</p>
                        </div>

                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Peresmian Table --}}
                            <div
                                class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                                    <div>
                                        <h3 class="font-heading text-base font-bold text-slate-800">Daftar Peresmian Zona
                                            KHAS</h3>
                                        <p class="text-xs text-slate-400 mt-0.5">Data peresmian periode 2022 - 2025</p>
                                    </div>
                                </div>
                                <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                                    <table class="w-full text-left">
                                        <thead class="sticky top-0 z-10 bg-slate-50">
                                            <tr>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Nama Zona KHAS</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Wilayah</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Peresmian</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-center">
                                                    Tenant</th>
                                                <th
                                                    class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Diresmikan Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasKhas" class="divide-y divide-slate-100">
                                            <!-- Row rendered via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Sebaran Map --}}
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Peta Sebaran Zona KHAS</h3>
                                <div id="mapNasKhas" class="h-[500px] rounded-xl z-10 shadow-inner"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 2.10 UMKM IH View --}}
                    <div id="nasView-modul-umkm" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="store" class="h-3 w-3"></i>
                                <span>UMKM Industri Halal</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Implementasi UMKM Industri
                                Halal</h2>
                            <p class="text-sm text-slate-500 mt-1">Tren pertumbuhan implementasi UMKM halal periode 2023 -
                                2025.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-8 shadow-sm">
                            <h3 class="font-heading text-lg font-bold text-slate-800 mb-8 text-center">Tren Implementasi
                                UMKM Industri Halal</h3>
                            <div class="h-[450px]">
                                <canvas id="chartNasUmkmIh"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 2.11 Industri Halal (RPHR, RPHU, etc) View --}}
                    <div id="nasView-cold-storage" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="factory" class="h-3 w-3"></i>
                                <span>Industri Halal (Daging & Cold Storage)</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Ekosistem Industri Daging
                                Halal</h2>
                            <p class="text-sm text-slate-500 mt-1">Data teknis RPHR, RPHU, Cold Storage, Kios, dan Usaha
                                Daging Nasional.</p>
                        </div>

                        {{-- RPHR & RPHU Stats Grid --}}
                        <div class="grid gap-6 md:grid-cols-2">
                            {{-- RPHR Card --}}
                            <div class="rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                                <div class="bg-slate-900 p-6 text-white">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="font-heading text-lg font-bold">RPH Ruminansia (RPHR)</h3>
                                        <span class="px-2 py-1 rounded bg-white/20 text-[10px] font-bold uppercase">Januari
                                            2026</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase text-slate-400">Total Usaha</p>
                                            <p class="text-3xl font-black" id="statRPHRTotal">{{ number_format($dashboard_data['rphr_total'] ?? 594, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase text-slate-400">Operasional</p>
                                            <p class="text-3xl font-black text-emerald-400" id="statRPHROps">511</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="p-6 grid grid-cols-3 gap-4 border-t border-slate-100 bg-slate-50/30 text-center">
                                    <div>
                                        <p class="text-lg font-black text-slate-800" id="statRPHRNkv">221</p>
                                        <p class="text-[9px] font-bold uppercase text-slate-400">Memiliki NKV</p>
                                    </div>
                                    <div>
                                        <p class="text-lg font-black text-slate-800" id="statRPHRHalal">311</p>
                                        <p class="text-[9px] font-bold uppercase text-slate-400">Sertifikat Halal</p>
                                    </div>
                                    <div class="bg-emerald-50 rounded-lg py-1">
                                        <p class="text-lg font-black text-emerald-600" id="statRPHRBoth">174</p>
                                        <p class="text-[9px] font-bold uppercase text-emerald-500">NKV & Halal</p>
                                    </div>
                                </div>
                            </div>

                            {{-- RPHU Card --}}
                            <div class="rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                                <div class="bg-blue-900 p-6 text-white">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="font-heading text-lg font-bold">RPH Unggas (RPHU)</h3>
                                        <span class="px-2 py-1 rounded bg-white/20 text-[10px] font-bold uppercase">Januari
                                            2026</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase text-slate-400">Total Usaha</p>
                                            <p class="text-3xl font-black" id="statRPHUTotal">{{ number_format($dashboard_data['rphu_total'] ?? 362, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase text-slate-400">Operasional</p>
                                            <p class="text-3xl font-black text-emerald-400" id="statRPHUOps">328</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="p-6 grid grid-cols-3 gap-4 border-t border-slate-100 bg-slate-50/30 text-center">
                                    <div>
                                        <p class="text-lg font-black text-slate-800" id="statRPHUNkv">263</p>
                                        <p class="text-[9px] font-bold uppercase text-slate-400">Memiliki NKV</p>
                                    </div>
                                    <div>
                                        <p class="text-lg font-black text-slate-800" id="statRPHUHalal">269</p>
                                        <p class="text-[9px] font-bold uppercase text-slate-400">Sertifikat Halal</p>
                                    </div>
                                    <div class="bg-emerald-50 rounded-lg py-1">
                                        <p class="text-lg font-black text-emerald-600" id="statRPHUBoth">230</p>
                                        <p class="text-[9px] font-bold uppercase text-emerald-500">NKV & Halal</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pie Charts Row --}}
                        <div class="grid gap-6 md:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-sm font-bold text-slate-800 mb-6 text-center">Cold Storage
                                    Halal</h3>
                                <div class="h-[180px]">
                                    <canvas id="chartNasColdStorage"></canvas>
                                </div>
                                <div id="coldStorageLegend" class="mt-6 space-y-2"></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-sm font-bold text-slate-800 mb-6 text-center">Kios Industri
                                    Halal</h3>
                                <div class="h-[180px]">
                                    <canvas id="chartNasKios"></canvas>
                                </div>
                                <div id="kiosLegend" class="mt-6 space-y-2"></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-sm font-bold text-slate-800 mb-6 text-center">Usaha Daging
                                    Halal</h3>
                                <div class="h-[180px]">
                                    <canvas id="chartNasUsahaDaging"></canvas>
                                </div>
                                <div id="usahaDagingLegend" class="mt-6 space-y-2"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 3.1 Indikator Aktivitas Usaha View --}}
                    <div id="nasView-indikator-aktivitas" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="bar-chart-3" class="h-3 w-3"></i>
                                <span>Indikator Aktivitas Usaha Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Aktivitas Usaha Syariah
                                Nasional</h2>
                            <p class="text-sm text-slate-500 mt-1">Tren nilai pembiayaan dan pangsa aktivitas usaha syariah.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-8 shadow-sm">
                            <h3 class="font-heading text-lg font-bold text-slate-800 mb-8 text-center">Tren Nilai Pembiayaan
                                Syariah</h3>
                            <div class="h-[450px]">
                                <canvas id="chartNasAktivitasNilai"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 4.1 Nilai Ekspor / PDB View --}}
                    <div id="nasView-nilai-ekspor" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-50 text-sky-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="line-chart" class="h-3 w-3"></i>
                                <span>Rasio Ekspor Halal / PDB</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Analisis Kontribusi Ekspor
                                Halal</h2>
                            <p class="text-sm text-slate-500 mt-1">Rasio nilai ekspor produk halal terhadap PDB Nasional.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-8 shadow-sm">
                            <h3 class="font-heading text-lg font-bold text-slate-800 mb-8 text-center">Rasio Ekspor Produk
                                Halal / PDB (%)</h3>
                            <div class="h-[450px]">
                                <canvas id="chartNasEksporPdbRatio"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 4.2 Logistic Halal View --}}
                    <div id="nasView-logistik" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 text-teal-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="truck" class="h-3 w-3"></i>
                                <span>Logistik Halal Nasional</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Infrastruktur Logistik
                                Halal</h2>
                            <p class="text-sm text-slate-500 mt-1">Data sertifikasi halal untuk jasa logistik dan
                                penyimpanan.</p>
                        </div>

                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Total Card --}}
                            <div
                                class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-8 shadow-sm flex flex-col items-center justify-center text-center">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-4">Total
                                    Sertifikasi Logistik</p>
                                <p class="text-6xl font-black text-slate-900 mb-2">1.979</p>
                                <p class="text-xs text-slate-500 font-medium">Sertifikat aktif secara nasional</p>
                            </div>

                            {{-- Chart Card --}}
                            <div class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <div class="flex flex-col lg:flex-row items-center gap-8">
                                    <div class="relative h-[220px] w-[220px] shrink-0">
                                        <canvas id="chartNasLogistikType"></canvas>
                                    </div>
                                    <div id="logistikTypeLegend" class="flex-1 grid grid-cols-1 gap-2 w-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4.3 Percepatan Ekspor View --}}
                    <div id="nasView-percepatan-ekspor" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="trending-up" class="h-3 w-3"></i>
                                <span>Percepatan Ekspor Industri Halal</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Akselerasi Perdagangan
                                Halal</h2>
                            <p class="text-sm text-slate-500 mt-1">Data tren ekspor, negara tujuan, dan performa provinsi.
                            </p>
                        </div>

                        {{-- Trend & Contrib Row --}}
                        <div class="grid gap-6 md:grid-cols-12">
                            <div class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Nilai Ekspor Produk Halal
                                    (USD Ribu)</h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasEksporTrend"></canvas>
                                </div>
                            </div>
                            <div class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Kontribusi Per Sektor</h3>
                                <div class="relative h-[220px]">
                                    <canvas id="chartNasEksporSector"></canvas>
                                </div>
                                <div id="percepatanEksporSectorLegend" class="mt-6 space-y-2"></div>
                            </div>
                        </div>

                        {{-- Countries & PEB Row --}}
                        <div class="grid gap-6 md:grid-cols-12">
                            <div class="md:col-span-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6 text-center">Top 5 Negara
                                    Tujuan (Juli 2025)</h3>
                                <div class="h-[280px]">
                                    <canvas id="chartNasEksporCountries"></canvas>
                                </div>
                            </div>
                            <div class="md:col-span-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6 text-center">PEB 952 Halal
                                    (Jan-Mar 2026)</h3>
                                <div class="h-[280px]">
                                    <canvas id="chartNasEksporPEB"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- YoY Comparison Chart --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Perbandingan Ekspor 2024 vs
                                2025 per Provinsi</h3>
                            <div class="h-[400px]">
                                <canvas id="chartNasEksporYoy"></canvas>
                            </div>
                        </div>

                        {{-- Provincial Comparison --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <div>
                                    <h3 class="font-heading text-base font-bold text-slate-800">Tabel Nilai Ekspor Produk
                                        Halal Provinsi 2021 - 2025 (USD Ribu)</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Perbandingan Nilai 2024 vs 2025</p>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                Provinsi</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-right">
                                                2021</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-right">
                                                2022</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-right">
                                                2023</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-right">
                                                2024</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-right">
                                                2025</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-center">
                                                Share (%)</th>
                                            <th
                                                class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-right">
                                                Trend (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableNasEksporProv" class="divide-y divide-slate-100"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- 5.1 Aset Keuangan Syariah View --}}
                    <div id="nasView-aset-keuangan" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-50 text-violet-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="landmark" class="h-3 w-3"></i>
                                <span>Aset Keuangan Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Aset Keuangan Syariah &
                                PDB</h2>
                            <p class="text-sm text-slate-500 mt-1">Perbandingan nilai PDB Nasional, Total Aset JKS, dan
                                Rasio Aset terhadap PDB.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-8 shadow-sm">
                            <h3 class="font-heading text-lg font-bold text-slate-800 mb-8 text-center">Aset Keuangan Syariah
                                / PDB (%)</h3>
                            <div class="h-[450px]">
                                <canvas id="chartNasAsetKeuanganCombo"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 5.2 Pembiayaan UMKM View --}}
                    <div id="nasView-pembiayaan-umkm" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="store" class="h-3 w-3"></i>
                                <span>Pembiayaan UMKM</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Pembiayaan UMKM Syariah
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">Konsolidasi Outstanding Pembiayaan/Pendanaan Syariah
                                Terhadap UMKM (Periode 31 Desember 2025)</p>
                        </div>

                        {{-- Konsolidasi UMKM --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div
                                class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col justify-center items-center text-center">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Total Nilai
                                    Konsolidasi (Rp, Miliar)</p>
                                <p class="text-4xl font-black text-slate-900">165.532,86</p>
                            </div>
                            <div class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6 text-center md:text-left">
                                    Komposisi Outstanding Lembaga Pembiayaan</h3>
                                <div class="flex flex-col md:flex-row items-center gap-6">
                                    <div class="h-[200px] w-full md:w-[250px] shrink-0">
                                        <canvas id="chartNasUmkmKomposisi"></canvas>
                                    </div>
                                    <div id="umkmKomposisiLegend" class="flex-1 grid grid-cols-1 gap-3 w-full"></div>
                                </div>
                            </div>
                            <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-4">Kontribusi Outstanding
                                    Pembiayaan (Rp, Miliar)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasUmkmKontribusi"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Perbankan Syariah --}}
                        <div class="mt-12">
                            <h3 class="font-heading text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-6">1.
                                Pembiayaan Perbankan Syariah (BUS, UUS & BPRS)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <div class="md:col-span-4 space-y-6">
                                    <div
                                        class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col justify-center items-center text-center h-full">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Total
                                            Outstanding (Rp, Miliar)</p>
                                        <p class="text-3xl font-black text-slate-900">114.814,31</p>
                                        <div
                                            class="mt-4 px-4 py-1.5 bg-rose-50 text-rose-600 rounded-full text-xs font-bold">
                                            Pertumbuhan (YoY): -0,15%
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                                    <h4 class="font-heading text-sm font-bold text-slate-800 mb-4 text-center">Rasio
                                        Outstanding</h4>
                                    <div class="h-[180px] shrink-0">
                                        <canvas id="chartNasUmkmBankRasio"></canvas>
                                    </div>
                                    <div id="umkmBankRasioLegend" class="mt-6 grid grid-cols-1 gap-2 w-full"></div>
                                </div>
                                <div
                                    class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                                    <h4 class="font-heading text-sm font-bold text-slate-800 mb-4 text-center">Jumlah
                                        Komposisi Lembaga</h4>
                                    <div class="h-[180px] shrink-0">
                                        <canvas id="chartNasUmkmBankLembaga"></canvas>
                                    </div>
                                    <div id="umkmBankLembagaLegend" class="mt-6 grid grid-cols-1 gap-2 w-full"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mt-6">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        Outstanding BUS</p>
                                    <p class="text-xl font-black text-slate-900">82.233,54 <span
                                            class="text-xs font-normal text-slate-500">Miliar</span></p>
                                    <p class="text-xs text-emerald-600 font-bold mt-1">Rasio UMKM: 15.88%</p>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        Outstanding UUS</p>
                                    <p class="text-xl font-black text-slate-900">21.455,88 <span
                                            class="text-xs font-normal text-slate-500">Miliar</span></p>
                                    <p class="text-xs text-emerald-600 font-bold mt-1">Rasio UMKM: 12.83%</p>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        Outstanding BPRS</p>
                                    <p class="text-xl font-black text-slate-900">11.124,89 <span
                                            class="text-xs font-normal text-slate-500">Miliar</span></p>
                                    <p class="text-xs text-emerald-600 font-bold mt-1">Rasio UMKM: 54.79%</p>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm mt-6">
                                <h4 class="font-heading text-base font-bold text-slate-800 mb-6">Tren Nilai Outstanding
                                    Pembiayaan Bank Syariah</h4>
                                <div class="h-[300px]">
                                    <canvas id="chartNasUmkmBankTren"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Non-Perbankan Syariah --}}
                        <div class="mt-12">
                            <h3 class="font-heading text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-6">2.
                                Pembiayaan Non-Perbankan Syariah</h3>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <div
                                    class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col justify-center items-center text-center">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Total
                                        Outstanding (Rp, Miliar)</p>
                                    <p class="text-3xl font-black text-slate-900">48.707,72</p>
                                </div>
                                <div
                                    class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                                    <h4 class="font-heading text-sm font-bold text-slate-800 mb-4 text-center">Rasio
                                        Outstanding</h4>
                                    <div class="h-[180px] shrink-0">
                                        <canvas id="chartNasUmkmNonBankRasio"></canvas>
                                    </div>
                                    <div id="umkmNonBankRasioLegend" class="mt-6 grid grid-cols-1 gap-2 w-full"></div>
                                </div>
                                <div
                                    class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                                    <h4 class="font-heading text-sm font-bold text-slate-800 mb-4 text-center">Jumlah
                                        Komposisi Lembaga</h4>
                                    <div class="h-[180px] shrink-0">
                                        <canvas id="chartNasUmkmNonBankLembaga"></canvas>
                                    </div>
                                    <div id="umkmNonBankLembagaLegend" class="mt-6 grid grid-cols-1 gap-2 w-full"></div>
                                </div>
                                <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <h4 class="font-heading text-base font-bold text-slate-800 mb-4">Kontribusi Outstanding
                                        Pembiayaan Non-Perbankan (Rp, Miliar)</h4>
                                    <div class="h-[250px]">
                                        <canvas id="chartNasUmkmNonBankKontribusi"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SCF Syariah --}}
                        <div class="mt-12">
                            <h3 class="font-heading text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-6">3.
                                Securities Crowdfunding (SCF) Syariah</h3>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <div class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <h4 class="font-heading text-base font-bold text-slate-800 mb-4">Perkembangan SCF
                                        Syariah</h4>
                                    <div class="h-[300px]">
                                        <canvas id="chartNasUmkmScfPerkembangan"></canvas>
                                    </div>
                                </div>
                                <div
                                    class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                                    <h4 class="font-heading text-base font-bold text-slate-800 mb-4 text-center">Marketshare
                                        SCF Syariah</h4>
                                    <div class="h-[250px] shrink-0">
                                        <canvas id="chartNasUmkmScfMarketshare"></canvas>
                                    </div>
                                    <div id="umkmScfMarketshareLegend" class="mt-4 grid grid-cols-1 gap-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5.3 Perbankan Syariah View --}}
                    <div id="nasView-perbankan-syariah" class="hidden nas-view-content space-y-10">
                        <div class="mb-4 flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600">
                                <i data-lucide="landmark" class="h-4 w-4"></i>
                            </div>
                            <div>
                                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Perkembangan
                                    Perbankan Syariah</h2>
                                <p class="text-[10px] text-slate-400 sm:text-xs">Data Aset, Market Share, dan Rekening
                                    Perbankan Syariah Nasional</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            {{-- Summary Stats & Market Share --}}
                            <div class="md:col-span-4 space-y-6">
                                <div
                                    class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col items-center text-center">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Total Aset
                                        (Rp, Triliun)</p>
                                    <p class="text-3xl font-black text-slate-900">1.035,05</p>
                                    <p class="mt-2 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                                        +7.14% vs Dec 2024</p>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <h4 class="font-heading text-sm font-bold text-slate-800 mb-4 text-center">Market Share
                                        Perbankan</h4>
                                    <div class="h-[200px]">
                                        <canvas id="chartNasPerbankanMarket"></canvas>
                                    </div>
                                </div>
                            </div>

                            {{-- Perkembangan Table --}}
                            <div
                                class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-hidden">
                                <h4 class="font-heading text-base font-bold text-slate-800 mb-4">Tren Perkembangan Perbankan
                                    Syariah</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="border-b border-slate-100">
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">Periode
                                                </th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">Total
                                                    Aset (T)</th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">Market
                                                    Share</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasPerbankanAset" class="divide-y divide-slate-50">
                                            <!-- Data via JS -->
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    class="mt-4 flex items-center justify-between px-4 py-3 bg-slate-50 rounded-xl border border-slate-100">
                                    <p id="tableNasPerbankanAsetRange" class="text-xs font-bold text-slate-500">1 - 8</p>
                                    <div class="flex items-center gap-2">
                                        <button id="btnPrevPerbankanAset"
                                            class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 hover:border-emerald-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                        </button>
                                        <button id="btnNextPerbankanAset"
                                            class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 hover:border-emerald-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rekening --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h4 class="font-heading text-base font-bold text-slate-800 mb-4">Tren Jumlah Rekening
                                    Nasional (Juta)</h4>
                                <div class="h-[300px]">
                                    <canvas id="chartNasPerbankanRekening"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-hidden">
                                <h4 class="font-heading text-base font-bold text-slate-800 mb-4">Data Rekening Nasional</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="border-b border-slate-100">
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">Bulan
                                                </th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">
                                                    Simpanan (Juta)</th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">
                                                    Pembiayaan (Juta)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasPerbankanRekening" class="divide-y divide-slate-50"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Total Aset BUS, UUS, BPRS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h4 class="font-heading text-base font-bold text-slate-800 mb-4">Total Aset per Lembaga
                                    (Miliar)</h4>
                                <div class="h-[300px]">
                                    <canvas id="chartNasPerbankanLembaga"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-hidden">
                                <h4 class="font-heading text-base font-bold text-slate-800 mb-4">Data Aset per Lembaga</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="border-b border-slate-100">
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">
                                                    Bulan-Tahun</th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">BUS
                                                </th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">UUS
                                                </th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">BPRS
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasPerbankanLembaga" class="divide-y divide-slate-50"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Marketshare Tren --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h4 class="font-heading text-base font-bold text-slate-800 mb-4">Tren Marketshare (Aset,
                                    PYD, DPK)</h4>
                                <div class="h-[300px]">
                                    <canvas id="chartNasPerbankanMarketTren"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-hidden">
                                <h4 class="font-heading text-base font-bold text-slate-800 mb-4">Data Marketshare</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="border-b border-slate-100">
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">
                                                    Bulan-Tahun</th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">Aset
                                                </th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">PYD
                                                </th>
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">DPK
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasPerbankanMarketTren" class="divide-y divide-slate-50"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Tabel Sebaran - Kiri DPK, Kanan Pembiayaan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Tabel DPK --}}
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-hidden">
                                <h4 class="font-heading text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                                    <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Tabel Marketshare DPK per Provinsi
                                </h4>
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50">
                                            <th class="px-4 py-2.5 text-[10px] font-bold uppercase text-slate-400">Provinsi
                                            </th>
                                            <th
                                                class="px-4 py-2.5 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                Marketshare DPK</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableNasPerbankanDpkProv" class="divide-y divide-slate-50"></tbody>
                                </table>
                                <div
                                    class="mt-3 flex items-center justify-between px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                    <p id="tableNasPerbankanDpkRange" class="text-xs font-bold text-slate-500">1 - 8 dari 38
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <button id="btnPrevPerbankanDpk"
                                            class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 hover:border-emerald-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                            <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                        </button>
                                        <button id="btnNextPerbankanDpk"
                                            class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 hover:border-emerald-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                            <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Tabel Pembiayaan --}}
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-hidden">
                                <h4 class="font-heading text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                                    <span class="inline-block h-2 w-2 rounded-full bg-blue-500"></span>
                                    Tabel Marketshare Pembiayaan per Provinsi
                                </h4>
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50">
                                            <th class="px-4 py-2.5 text-[10px] font-bold uppercase text-slate-400">Provinsi
                                            </th>
                                            <th
                                                class="px-4 py-2.5 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                Marketshare PYD</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableNasPerbankanPydProv" class="divide-y divide-slate-50"></tbody>
                                </table>
                                <div
                                    class="mt-3 flex items-center justify-between px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                    <p id="tableNasPerbankanPydRange" class="text-xs font-bold text-slate-500">1 - 8 dari 38
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <button id="btnPrevPerbankanPyd"
                                            class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                            <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                        </button>
                                        <button id="btnNextPerbankanPyd"
                                            class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                            <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Peta DPK (row tersendiri) --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h4 class="font-heading text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                Peta Sebaran Marketshare DPK per Provinsi
                            </h4>
                            <div class="h-[480px] bg-slate-50 rounded-xl overflow-hidden">
                                <div id="mapNasMarketDpk" class="h-full w-full"></div>
                            </div>
                        </div>

                        {{-- Peta Pembiayaan (row tersendiri) --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h4 class="font-heading text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                                Peta Sebaran Marketshare Pembiayaan per Provinsi
                            </h4>
                            <div class="h-[480px] bg-slate-50 rounded-xl overflow-hidden">
                                <div id="mapNasMarketPyd" class="h-full w-full"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 5.4 Payroll ASN View --}}
                    <div id="nasView-payroll-asn" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-50 text-violet-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="wallet" class="h-3 w-3"></i>
                                <span>Payroll ASN</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Penyaluran Gaji ASN
                                Melalui Bank Syariah</h2>
                            <p class="text-sm text-slate-500 mt-1">Tren nominal penyaluran gaji, komposisi, dan sebaran per
                                provinsi di Indonesia.</p>
                        </div>

                        {{-- Charts Row 1 (Bar) --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Penyaluran Gaji ASN Melalui
                                Bank Syariah (Rp Miliar)</h3>
                            <div class="h-[350px]">
                                <canvas id="chartNasPayrollBar"></canvas>
                            </div>
                        </div>

                        {{-- Charts Row 2 (Trend) --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Payroll ASN Bank Syariah
                                (Trend)</h3>
                            <div class="h-[350px]">
                                <canvas id="chartNasPayrollTrend"></canvas>
                            </div>
                        </div>

                        {{-- Charts Row 2 --}}
                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Komposisi Nominal (Pie Chart) --}}
                            <div
                                class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Penyaluran Gaji ASN
                                    Berdasarkan Nominal</h3>
                                <div class="relative h-[250px] flex-1 flex items-center justify-center">
                                    <canvas id="chartNasPayrollPie"></canvas>
                                </div>
                                <div id="payrollPieLegend" class="mt-6 flex flex-col gap-3"></div>
                            </div>

                            {{-- Tabel Provinsi --}}
                            <div
                                class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-4">Tabel Payroll Berdasarkan
                                    Provinsi</h3>
                                <div class="flex-1 overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50">
                                                <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">
                                                    Provinsi</th>
                                                <th
                                                    class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    Total Gaji (Rp)</th>
                                                <th
                                                    class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    Sudah Memakai (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasPayrollProv" class="divide-y divide-slate-50"></tbody>
                                    </table>
                                </div>
                                <div
                                    class="mt-4 flex items-center justify-between px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                    <p id="tableNasPayrollRange" class="text-xs font-bold text-slate-500">1 - 8 dari 38</p>
                                    <div class="flex items-center gap-2">
                                        <button id="btnPrevPayroll"
                                            class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-violet-600 hover:border-violet-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                            <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                        </button>
                                        <button id="btnNextPayroll"
                                            class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-violet-600 hover:border-violet-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                            <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Peta Sebaran --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h4 class="font-heading text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                                Persentase Sebaran Penggunaan Bank Syariah Oleh Satker Berdasarkan Provinsi
                            </h4>
                            <div class="h-[500px] bg-slate-50 rounded-xl overflow-hidden relative">
                                <div id="mapNasPayroll" class="h-full w-full"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 5.5 Jaminan Sosial View --}}
                    <div id="nasView-jaminan-sosial" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-50 text-cyan-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="shield-check" class="h-3 w-3"></i>
                                <span>Jaminan Sosial</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Layanan Syariah Jaminan
                                Sosial Ketenagakerjaan</h2>
                            <p class="text-sm text-slate-500 mt-1">Perkembangan kepesertaan, investasi, dan portofolio
                                jaminan sosial ketenagakerjaan.</p>
                        </div>

                        {{-- Charts Row 1 --}}
                        <div class="grid gap-6 md:grid-cols-12">
                            {{-- Aceh Combo Chart --}}
                            <div class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Perkembangan Kepesertaan
                                    dan Investasi Jamsosnaker Provinsi Aceh</h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasJamsosAceh"></canvas>
                                </div>
                            </div>

                            {{-- Portofolio Pie Chart --}}
                            <div
                                class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Portofolio BPJS
                                    Ketenagakerjaan (Triliun Rupiah)</h3>
                                <div class="relative h-[250px] flex-1 flex items-center justify-center">
                                    <canvas id="chartNasJamsosPie"></canvas>
                                </div>
                                <div id="jamsosPieLegend" class="mt-6 flex flex-col gap-3"></div>
                            </div>
                        </div>

                        {{-- Charts Row 2 --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Pertumbuhan Investasi BPJS
                                Ketenagakerjaan</h3>
                            <div class="h-[400px]">
                                <canvas id="chartNasJamsosArea"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 5.6 Pembiayaan Syariah --}}
                    <div id="nasView-pembiayaan-bprs" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="hand-coins" class="h-3 w-3"></i>
                                <span>Pembiayaan Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Pembiayaan Syariah (BPRS,
                                LPDB dan PIP) Kepada IKMS</h2>
                            <p class="text-sm text-slate-500 mt-1">Data pembiayaan BPRS, LPDB Koperasi, dan Pusat Investasi
                                Pemerintah.</p>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            {{-- LPDB Koperasi --}}
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                    LPDB Koperasi
                                </h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div
                                        class="rounded-xl bg-slate-50 p-4 border border-slate-100 text-center flex flex-col justify-center">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                            Provinsi</p>
                                        <p class="text-xl font-black text-slate-900" id="bprsLpdbProv">-</p>
                                    </div>
                                    <div
                                        class="rounded-xl bg-slate-50 p-4 border border-slate-100 text-center flex flex-col justify-center">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Mitra
                                        </p>
                                        <p class="text-xl font-black text-slate-900" id="bprsLpdbMitra">-</p>
                                    </div>
                                    <div
                                        class="rounded-xl bg-slate-50 p-4 border border-slate-100 text-center flex flex-col justify-center">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Total
                                            Dana</p>
                                        <p class="text-xl font-black text-emerald-600" id="bprsLpdbDana">-</p>
                                        <p class="text-[9px] text-slate-400 font-medium">Miliar</p>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-4 font-medium italic">Sumber: LPDB Koperasi -
                                    September 2025</p>
                            </div>

                            {{-- Pusat Investasi Pemerintah --}}
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                                    Pusat Investasi Pemerintah (PIP)
                                </h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div
                                        class="rounded-xl bg-slate-50 p-4 border border-slate-100 text-center flex flex-col justify-center">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                            Provinsi</p>
                                        <p class="text-xl font-black text-slate-900" id="bprsPipProv">-</p>
                                    </div>
                                    <div
                                        class="rounded-xl bg-slate-50 p-4 border border-slate-100 text-center flex flex-col justify-center">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Mitra
                                        </p>
                                        <p class="text-xl font-black text-slate-900" id="bprsPipMitra">-</p>
                                    </div>
                                    <div
                                        class="rounded-xl bg-slate-50 p-4 border border-slate-100 text-center flex flex-col justify-center">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Total
                                            Dana</p>
                                        <p class="text-xl font-black text-blue-600" id="bprsPipDana">-</p>
                                        <p class="text-[9px] text-slate-400 font-medium">Triliun</p>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-4 font-medium italic">Sumber: Pusat Investasi
                                    Pemerintah (PIP) - September 2025</p>
                            </div>
                        </div>

                        {{-- BPRS Line Chart --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Data Pembiayaan BPRS untuk UMKM
                            </h3>
                            <div class="h-[350px]">
                                <canvas id="chartNasBprsUmkm"></canvas>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-4 font-medium italic">Sumber: OJK - Statistik Perbankan
                                Syariah - Juni 2025</p>
                        </div>
                    </div>

                    {{-- 5.7 KPBU Syariah View --}}
                    <div id="nasView-kpbu-syariah" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="building-2" class="h-3 w-3"></i>
                                <span>KPBU Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Kerjasama Pemerintah dan
                                Badan Usaha Syariah</h2>
                            <p class="text-sm text-slate-500 mt-1">Daftar dan nilai proyek KPBU yang dibiayai oleh Lembaga
                                Keuangan Syariah.</p>
                        </div>

                        {{-- Summary Cards --}}
                        <div class="grid gap-6 md:grid-cols-2">
                            <div
                                class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Total
                                        Pembiayaan Syariah</p>
                                    <p class="text-3xl font-black text-slate-900" id="kpbuTotalPembiayaan">-</p>
                                    <p class="text-xs text-slate-500 font-medium mt-1">Triliun Rupiah</p>
                                </div>
                                <div
                                    class="h-14 w-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                                    <i data-lucide="coins" class="h-7 w-7"></i>
                                </div>
                            </div>
                            <div
                                class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Total Proyek
                                    </p>
                                    <p class="text-3xl font-black text-slate-900" id="kpbuTotalProyek">-</p>
                                    <p class="text-xs text-slate-500 font-medium mt-1">Proyek Terdaftar</p>
                                </div>
                                <div
                                    class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                                    <i data-lucide="kanban-square" class="h-7 w-7"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Horizontal Bar Chart --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Daftar Proyek KPBU (Rp Miliar)
                            </h3>
                            <div class="h-[600px]">
                                <canvas id="chartNasKpbuBar"></canvas>
                            </div>
                        </div>

                        {{-- Tabel KPBU --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-4">Tabel Kontribusi LKS pada
                                Proyek KPBU</h3>
                            <div class="flex-1 overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50">
                                            <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">Proyek KPBU
                                            </th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400 text-center">
                                                Tahun</th>
                                            <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                Nilai Pembiayaan (Rp Miliar)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableNasKpbu" class="divide-y divide-slate-50"></tbody>
                                </table>
                            </div>
                            <div
                                class="mt-4 flex items-center justify-between px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                <p id="tableNasKpbuRange" class="text-xs font-bold text-slate-500">1 - 8</p>
                                <div class="flex items-center gap-2">
                                    <button id="btnPrevKpbu"
                                        class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                        <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                    </button>
                                    <button id="btnNextKpbu"
                                        class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                        <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5.8 Pasar Modal Syariah View --}}
                    <div id="nasView-pasar-modal" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="trending-up" class="h-3 w-3"></i>
                                <span>Pasar Modal Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Pertumbuhan Pasar Modal
                                Syariah</h2>
                            <p class="text-sm text-slate-500 mt-1">Data dan statistik perkembangan aset, saham, reksadana,
                                dan sukuk.</p>
                        </div>

                        {{-- Summary & Pie --}}
                        <div class="grid gap-6 md:grid-cols-12">
                            <div
                                class="md:col-span-8 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col justify-center text-center">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Total Aset Pasar
                                    Modal Syariah (Triliun Rupiah)</p>
                                <p class="text-4xl sm:text-5xl font-black text-slate-900" id="pasarModalTotalAset">-</p>
                            </div>
                            <div
                                class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col items-center">
                                <h3 class="font-heading text-sm font-bold text-slate-800 w-full text-left mb-2">Market Share
                                    Perbankan Syariah</h3>
                                <div class="relative h-[200px] w-full flex items-center justify-center">
                                    <canvas id="chartNasPasarModalPie"></canvas>
                                </div>
                                <div id="pasarModalPieLegend" class="mt-4 flex gap-4 w-full justify-center"></div>
                            </div>
                        </div>

                        {{-- Tabel Perbankan Syariah --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm flex flex-col">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-4">Tabel Perbankan Syariah</h3>
                            <div class="flex-1 overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50">
                                            <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400">Periode
                                            </th>
                                            <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                Total Aset (Rp Triliun)</th>
                                            <th class="px-4 py-3 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                Market Share</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableNasPasarModal" class="divide-y divide-slate-50"></tbody>
                                </table>
                            </div>
                            <div
                                class="mt-4 flex items-center justify-between px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                <p id="tableNasPasarModalRange" class="text-xs font-bold text-slate-500">1 - 6</p>
                                <div class="flex items-center gap-2">
                                    <button id="btnPrevPasarModal"
                                        class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                        <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                    </button>
                                    <button id="btnNextPasarModal"
                                        class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                        <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Pertumbuhan Saham & Reksadana --}}
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-4">Pertumbuhan Saham Syariah
                                </h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50">
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-center">
                                                    Tahun</th>
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    Jumlah</th>
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    Kapitalisasi (Triliun)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasSaham" class="divide-y divide-slate-50"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-4">Pertumbuhan Reksadana
                                    Syariah</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50">
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-center">
                                                    Tahun</th>
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    Jumlah</th>
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    NAB (Triliun)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasReksadana" class="divide-y divide-slate-50"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-4">Pertumbuhan Sukuk Korporasi
                                </h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50">
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-center">
                                                    Tahun</th>
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    Jumlah</th>
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    Nilai (Triliun)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasSukukKorporasi" class="divide-y divide-slate-50"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-4">Pertumbuhan Sukuk Negara
                                </h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50">
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-center">
                                                    Tahun</th>
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    Jumlah</th>
                                                <th
                                                    class="px-4 py-2 text-[10px] font-bold uppercase text-slate-400 text-right">
                                                    Nilai (Triliun)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasSukukNegara" class="divide-y divide-slate-50"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5.9 Tren Pasar Modal Syariah View --}}
                    <div id="nasView-tren-pasar-modal" class="hidden nas-view-content space-y-8">
                        {{-- Header --}}
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="bar-chart" class="h-3 w-3"></i>
                                <span>Tren Pasar Modal Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Perkembangan Pasar Modal
                                Syariah</h2>
                            <p class="text-sm text-slate-500 mt-1">Analisis perkembangan sukuk, daftar efek syariah, dan
                                kapitalisasi saham.</p>
                        </div>

                        {{-- Perkembangan Sukuk Negara (Combo) --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Perkembangan Sukuk Negara</h3>
                            <div class="h-[350px]">
                                <canvas id="chartNasSukukNegaraCombo"></canvas>
                            </div>
                        </div>

                        {{-- Efek Syariah Periode I & II --}}
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Daftar Efek Syariah Periode
                                    I</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasEfek1"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Daftar Efek Syariah Periode
                                    II</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasEfek2"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Kapitalisasi Saham Syariah --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Kapitalisasi Saham Syariah
                                (Triliun Rupiah)</h3>
                            <div class="h-[350px]">
                                <canvas id="chartNasKapitalisasiSaham"></canvas>
                            </div>
                        </div>

                        {{-- Perkembangan Sukuk Korporasi Combo --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Perkembangan Sukuk Korporasi
                            </h3>
                            <div class="h-[400px]">
                                <canvas id="chartNasSukukKorporasiCombo"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 5.10 Inovasi Keuangan Syariah --}}
                    <div id="nasView-inovasi-keuangan" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-50 text-violet-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="lightbulb" class="h-3 w-3"></i>
                                <span>Inovasi Keuangan Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Inovasi Produk dan Layanan
                                Keuangan Syariah</h2>
                        </div>
                        <div class="grid gap-6 md:grid-cols-1">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Perkembangan Peserta dan
                                    Investasi Tapera Syariah</h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasTaperaCombo"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Perkembangan Marketshare
                                    Peserta Tapera Syariah</h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasTaperaMarketshare"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5.11 Penguatan Asuransi Syariah --}}
                    <div id="nasView-asuransi-syariah" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-50 text-cyan-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="shield" class="h-3 w-3"></i>
                                <span>Penguatan Asuransi Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Penguatan Industri
                                Asuransi Syariah</h2>
                        </div>
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Total Aset Asuransi Syariah
                                (Triliun Rupiah)</h3>
                            <div class="h-[400px]">
                                <canvas id="chartNasAsuransiSyariah"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 5.12 Pengembangan Dapen Syariah --}}
                    <div id="nasView-dapen-syariah" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="piggy-bank" class="h-3 w-3"></i>
                                <span>Pengembangan Dapen Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Pengembangan Dana Pensiun
                                Syariah</h2>
                        </div>
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Total Aset Dana Pensiun Syariah
                            </h3>
                            <div class="h-[450px]">
                                <canvas id="chartNasDapenSyariah"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 5.13 Pembiayaan Berdasarkan Sektor Ekonomi --}}
                    <div id="nasView-sektor-ekonomi" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="pie-chart" class="h-3 w-3"></i>
                                <span>Sektor Ekonomi</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Pembiayaan Berdasarkan
                                Sektor Ekonomi</h2>
                        </div>
                        <div class="grid gap-6 md:grid-cols-1">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Pembiayaan BUS-UUS
                                    Berdasarkan Sektor Ekonomi (Miliar Rp)</h3>
                                <div class="h-[400px]">
                                    <canvas id="chartNasSektorPembiayaan"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Pertumbuhan Pembiayaan
                                    BUS-UUS (%)</h3>
                                <div class="h-[400px]">
                                    <canvas id="chartNasSektorPertumbuhan"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5.14 Perkembangan IKNB Syariah --}}
                    <div id="nasView-iknb-syariah" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="layers" class="h-3 w-3"></i>
                                <span>IKNB Syariah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Perkembangan Industri
                                Keuangan Non-Bank (IKNB) Syariah</h2>
                        </div>

                        <div
                            class="rounded-3xl bg-gradient-to-br from-emerald-600 to-teal-700 p-8 text-white shadow-lg shadow-emerald-200/50 text-center">
                            <p class="text-sm font-bold uppercase tracking-[0.2em] opacity-80 mb-2">Total Aset IKNB Syariah
                            </p>
                            <h3 class="text-5xl font-black">185.25 <span class="text-2xl opacity-60">Triliun</span></h3>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Market Share IKNB Syariah
                                </h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasIknbMarketShare"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Jumlah Pelaku IKNB Syariah
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">LKM Syariah</p>
                                        <p class="text-xl font-black text-slate-800">80</p>
                                    </div>
                                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-center">
                                        <p class="text-[10px] font-bold text-emerald-600 uppercase mb-1">Asuransi Syariah
                                        </p>
                                        <p class="text-xl font-black text-emerald-700">59</p>
                                    </div>
                                    <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 text-center">
                                        <p class="text-[10px] font-bold text-blue-600 uppercase mb-1">Pembiayaan Syariah</p>
                                        <p class="text-xl font-black text-blue-700">42</p>
                                    </div>
                                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-100 text-center">
                                        <p class="text-[10px] font-bold text-amber-600 uppercase mb-1">IKNB Lainnya</p>
                                        <p class="text-xl font-black text-amber-700">12</p>
                                    </div>
                                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-center">
                                        <p class="text-[10px] font-bold text-rose-600 uppercase mb-1">Penjaminan</p>
                                        <p class="text-xl font-black text-rose-700">9</p>
                                    </div>
                                    <div class="p-4 rounded-xl bg-violet-50 border border-violet-100 text-center">
                                        <p class="text-[10px] font-bold text-violet-600 uppercase mb-1">Fintech</p>
                                        <p class="text-xl font-black text-violet-700">7</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5.15 Kinerja Perbankan Syariah --}}
                    <div id="nasView-kinerja-perbankan" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="trending-up" class="h-3 w-3"></i>
                                <span>Kinerja Perbankan</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Kinerja Perbankan Syariah
                            </h2>
                        </div>

                        <div class="grid gap-6 md:grid-cols-1">
                            <div class="grid gap-4 grid-cols-1 sm:grid-cols-3">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                    <p class="text-xl font-black text-slate-800">948,17 <span
                                            class="text-sm font-bold text-slate-400">Triliun</span></p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total Aset</p>
                                    <p class="text-[10px] font-bold text-rose-500 mt-1"><i data-lucide="trending-down"
                                            class="inline h-3 w-3"></i> -3.28% YTD</p>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                    <p class="text-xl font-black text-slate-800">737,35 <span
                                            class="text-sm font-bold text-slate-400">Triliun</span></p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total DPK</p>
                                    <p class="text-[10px] font-bold text-rose-500 mt-1"><i data-lucide="trending-down"
                                            class="inline h-3 w-3"></i> -2.16% YTD</p>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                    <p class="text-xl font-black text-slate-800">639,06 <span
                                            class="text-sm font-bold text-slate-400">Triliun</span></p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total PYD</p>
                                    <p class="text-[10px] font-bold text-rose-500 mt-1"><i data-lucide="trending-down"
                                            class="inline h-3 w-3"></i> -0.70% YTD</p>
                                </div>
                            </div>
                            <div class="grid gap-4 grid-cols-1 sm:grid-cols-3">
                                <div
                                    class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm flex flex-col items-center justify-center text-center">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">YoY Growth Aset</p>
                                    <p class="text-xl font-black text-emerald-600">+9.16%</p>
                                </div>
                                <div
                                    class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm flex flex-col items-center justify-center text-center">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">YoY Growth DPK</p>
                                    <p class="text-xl font-black text-emerald-600">+9.85%</p>
                                </div>
                                <div
                                    class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm flex flex-col items-center justify-center text-center">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">YoY Growth PYD</p>
                                    <p class="text-xl font-black text-emerald-600">+9.77%</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div
                                class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm flex flex-col justify-center">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Jumlah Rekening</p>
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Juta</span>
                                </div>
                                <div class="flex gap-4">
                                    <div class="flex-1">
                                        <p class="text-[10px] font-bold text-slate-500 uppercase">DPK</p>
                                        <p class="text-lg font-black text-emerald-600">64.26</p>
                                        <p class="text-[10px] font-bold text-emerald-500">+7.12% YoY</p>
                                    </div>
                                    <div class="flex-1 border-l border-slate-100 pl-4">
                                        <p class="text-[10px] font-bold text-slate-500 uppercase">PYD</p>
                                        <p class="text-lg font-black text-blue-600">7.62</p>
                                        <p class="text-[10px] font-bold text-rose-500">-0.44% YoY</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm flex flex-col justify-center text-center">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Lembaga</p>
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Total:
                                        207</span>
                                </div>
                                <div class="flex gap-2">
                                    <div class="flex-1 text-center p-2 rounded-xl bg-emerald-50">
                                        <p class="text-[10px] font-bold text-emerald-600 uppercase mb-1">BUS</p>
                                        <p class="text-lg font-black text-emerald-700">14</p>
                                    </div>
                                    <div class="flex-1 text-center p-2 rounded-xl bg-blue-50">
                                        <p class="text-[10px] font-bold text-blue-600 uppercase mb-1">UUS</p>
                                        <p class="text-lg font-black text-blue-700">19</p>
                                    </div>
                                    <div class="flex-1 text-center p-2 rounded-xl bg-amber-50">
                                        <p class="text-[10px] font-bold text-amber-600 uppercase mb-1">BPRS</p>
                                        <p class="text-lg font-black text-amber-700">174</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Market Share Perbankan
                                    Syariah (Aset)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasPerbankanMarketShareSektor"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Pangsa Aset Berdasarkan
                                    Lembaga</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasPerbankanMarketShareLembaga"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Tren Pertumbuhan Perbankan
                                Syariah (YoY %)</h3>
                            <div class="h-[400px]">
                                <canvas id="chartNasPerbankanTrendGrowth"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 5.16 Perkembangan Aset Keuangan Syariah --}}
                    <div id="nasView-perkembangan-aset-keuangan" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="bar-chart-3" class="h-3 w-3"></i>
                                <span>Aset Keuangan</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Perkembangan Aset Keuangan
                                Syariah</h2>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Perkembangan Aset Keuangan
                                Syariah (Rp Triliun)</h3>
                            <div class="h-[450px]">
                                <canvas id="chartNasPerkembanganAsetCombo"></canvas>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-3">
                            <div class="md:col-span-1 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Pangsa Keuangan Syariah
                                </h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasPerkembanganAsetMarketShare"></canvas>
                                </div>
                            </div>
                            <div
                                class="md:col-span-2 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-hidden">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Perkembangan Aset Keuangan
                                    Syariah (Rp Triliun)</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50 border-y border-slate-200">
                                                <th
                                                    class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                                    Periode</th>
                                                <th
                                                    class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-emerald-600">
                                                    Perbankan</th>
                                                <th
                                                    class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-blue-600">
                                                    IKNB</th>
                                                <th
                                                    class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-amber-600">
                                                    Pasar Modal</th>
                                                <th
                                                    class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-rose-600">
                                                    Growth</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasPerkembanganAsetBody">
                                            <!-- To be populated by JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-hidden">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Total Aset Keuangan Syariah
                                Indonesia (Rp Triliun)</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 border-y border-slate-200">
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                                Periode</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">
                                                Perbankan</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">
                                                Asuransi</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">
                                                Pembiayaan</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-blue-600">
                                                IKNB Lain</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-amber-600">
                                                Sukuk Korp</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-amber-600">
                                                Reksa</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-amber-600">
                                                Sukuk Neg</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-amber-600">
                                                Saham</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableNasTotalAsetBreakdownBody">
                                        <!-- To be populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm overflow-hidden">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Tabel Perkembangan Aset IKNB
                                Syariah (Rp Triliun)</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 border-y border-slate-200">
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                                Periode</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-blue-600">
                                                Asuransi</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-blue-600">
                                                Pembiayaan</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-blue-600">
                                                IKNB Lain</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right text-rose-600">
                                                Growth</th>
                                            <th
                                                class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right font-black">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableNasIknbBreakdownBody">
                                        <!-- To be populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- 5.17 Keuangan Syariah Daerah --}}
                    <div id="nasView-syariah-daerah" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="map-pin" class="h-3 w-3"></i>
                                <span>Syariah Daerah</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Keuangan Syariah Daerah
                            </h2>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Rasio Keuangan Syariah Terhadap
                                PDRB 2024 (%)</h3>
                            <div class="h-[600px]">
                                <canvas id="chartNasDaerahRasioPdrb"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 5.18 Kinerja BUS - UUS --}}
                    <div id="nasView-kinerja-bus-uus" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="trending-up" class="h-3 w-3"></i>
                                <span>BUS - UUS</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Kinerja Bank Umum Syariah
                                (BUS) dan Unit Usaha Syariah (UUS)</h2>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Aset (Miliar Rp)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasBusAset"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Dana Pihak Ketiga (DPK)
                                    (Miliar Rp)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasBusDpk"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">PYD (Miliar Rp)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasBusPyd"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Non-Performing Financing
                                    (NPF) (%)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasBusNpf"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Capital Adequacy Ratio
                                    (CAR) (%)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasBusCar"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Financing to Deposit Ratio
                                    (FDR) (%)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasBusFdr"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">BOPO (%)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasBusBopo"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Return on Asset (ROA) (%)
                                </h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasBusRoa"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-2">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Net Operating Margin (NOM)
                                    (%)</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasBusNom"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 6.1 ZIS dan PDB --}}
                    <div id="nasView-zis-pdb" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="heart-handshake" class="h-3 w-3"></i>
                                <span>ZIS dan PDB</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Zakat, Infak, Sedekah, dan
                                PDB</h2>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Tren ZIS, DSKL, dan PDB
                                Nasional (Triliun Rp)</h3>
                            <div class="h-[450px]">
                                <canvas id="chartNasZisPdbCombo"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 6.2 Transformasi Wakaf --}}
                    <div id="nasView-transformasi-wakaf" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="refresh-cw" class="h-3 w-3"></i>
                                <span>Transformasi Wakaf</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Transformasi Pengelolaan
                                Wakaf Nasional</h2>
                        </div>

                        <div class="grid gap-6 md:grid-cols-4">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Nazhir Wakaf Uang</p>
                                <p class="text-2xl font-black text-slate-800">432</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Jumlah LKSPWU</p>
                                <p class="text-2xl font-black text-slate-800">61</p>
                            </div>
                            <div class="md:col-span-2 rounded-2xl border border-emerald-100 bg-emerald-50/50 p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-emerald-600 uppercase mb-1">Total Nilai CWLD</p>
                                <p class="text-2xl font-black text-emerald-700">Rp 11,79 <span
                                        class="text-sm font-bold opacity-60">Miliar</span></p>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Jenis LKS Penerima Wakaf
                                    Uang</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasWakafLembaga"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Implementasi CWLD oleh Bank
                                    Syariah</h3>
                                <div class="h-[300px]">
                                    <canvas id="chartNasWakafCwld"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                <h3 class="font-heading text-base font-bold text-slate-800">Sebaran Nazhir Wakaf Uang</h3>
                                <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-wider">
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        <span>Lokasi Nazhir</span>
                                    </div>
                                </div>
                            </div>
                            <div id="nasWakafMap" class="h-[450px] rounded-xl overflow-hidden bg-slate-50 border border-slate-100"></div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-6">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Sertifikasi Tanah Wakaf</h3>
                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Sudah Sertifikat</p>
                                            <p class="text-xl font-black text-slate-800">252.937 <span class="text-[10px] opacity-40">Lokasi</span></p>
                                        </div>
                                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Belum Sertifikat</p>
                                            <p class="text-xl font-black text-slate-800">187.575 <span class="text-[10px] opacity-40">Lokasi</span></p>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-[10px] font-bold text-slate-500 uppercase">Progres Lokasi</span>
                                                <span class="text-[10px] font-black text-emerald-600">57.28%</span>
                                            </div>
                                            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-emerald-500 rounded-full" style="width: 57.28%"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-[10px] font-bold text-slate-500 uppercase">Progres Luas</span>
                                                <span class="text-[10px] font-black text-blue-600">46.41%</span>
                                            </div>
                                            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500 rounded-full" style="width: 46.41%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Total Aset Tanah Wakaf</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                                            <p class="text-[10px] font-bold text-emerald-600 uppercase mb-1">Total Lokasi</p>
                                            <p class="text-2xl font-black text-emerald-700">440.512</p>
                                        </div>
                                        <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                                            <p class="text-[10px] font-bold text-blue-600 uppercase mb-1">Total Luas (Ha)</p>
                                            <p class="text-2xl font-black text-blue-700">57.263</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Penggunaan Tanah Wakaf</h3>
                                <div class="h-[400px]">
                                    <canvas id="chartNasWakafPenggunaan"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 6.3 Aset Wakaf Uang / PDB --}}
                    <div id="nasView-wakaf-uang" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="line-chart" class="h-3 w-3"></i>
                                <span>Wakaf Uang / PDB</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Aset Wakaf Uang terhadap PDB Nasional</h2>
                            <p class="text-sm text-slate-500 mt-1">Perkembangan nilai wakaf uang dan rasionya terhadap Produk Domestik Bruto.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Tren Aset Wakaf Uang & PDB (Triliun Rp)</h3>
                            <div class="h-[450px]">
                                <canvas id="chartNasWakafUangPdbCombo"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 6.4 Pendanaan UMKM Sosial --}}
                    <div id="nasView-pendanaan-umkm-sosial" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="heart-handshake" class="h-3 w-3"></i>
                                <span>Pendanaan UMKM Sosial</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Pendanaan Sosial Syariah bagi UMKM</h2>
                            <p class="text-sm text-slate-500 mt-1">Kontribusi dana sosial (BZM/BWM) dalam mendukung pengembangan UMKM.</p>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Jaringan BZM & BWM</p>
                                <p id="nasPendanaanSosialJaringan" class="text-2xl font-black text-slate-800">0</p>
                                <p class="text-[10px] text-slate-400 mt-1">Titik Jaringan Nasional</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total Pendanaan</p>
                                <p id="nasPendanaanSosialTotal" class="text-2xl font-black text-emerald-600">0</p>
                                <p class="text-[10px] text-slate-400 mt-1">Miliar Rupiah</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Pertumbuhan</p>
                                <p id="nasPendanaanSosialGrowth" class="text-2xl font-black text-rose-500">0%</p>
                                <p class="text-[10px] text-slate-400 mt-1">Pertumbuhan Kuartalan</p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Nilai Pendanaan bagi UMKM (Miliar Rp)</h3>
                            <div class="h-[400px]">
                                <canvas id="chartNasPendanaanUmkmSosial"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 6.5 ZIS Nasional --}}
                    <div id="nasView-zis-nasional" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="heart" class="h-3 w-3"></i>
                                <span>ZIS Nasional</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Transformasi Pengelolaan Zakat Nasional</h2>
                            <p class="text-sm text-slate-500 mt-1">Capaian pengumpulan, penyaluran, dan dampak zakat secara nasional.</p>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Pengumpulan</p>
                                <p id="nasZisTotalPengumpulan" class="text-xl font-black text-slate-800">0</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Penyaluran</p>
                                <p id="nasZisTotalPenyaluran" class="text-xl font-black text-slate-800">0</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Operasional</p>
                                <p id="nasZisTotalOperasional" class="text-xl font-black text-slate-800">0</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Mustahik</p>
                                <p id="nasZisTotalMustahik" class="text-xl font-black text-rose-600">0</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Muzaki</p>
                                <p id="nasZisTotalMuzaki" class="text-xl font-black text-emerald-600">0</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-xs font-bold text-slate-800 mb-4 text-center">Pengumpulan per Wilayah</h3>
                                <div class="h-[250px]">
                                    <canvas id="chartNasZisDonutPengumpulan"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-xs font-bold text-slate-800 mb-4 text-center">Penyaluran per Wilayah</h3>
                                <div class="h-[250px]">
                                    <canvas id="chartNasZisDonutPenyaluran"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-xs font-bold text-slate-800 mb-4 text-center">Operasional per Wilayah</h3>
                                <div class="h-[250px]">
                                    <canvas id="chartNasZisDonutOperasional"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-xs font-bold text-slate-800 mb-4 text-center">Mustahik per Wilayah</h3>
                                <div class="h-[250px]">
                                    <canvas id="chartNasZisDonutMustahik"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-xs font-bold text-slate-800 mb-4 text-center">Muzaki per Wilayah</h3>
                                <div class="h-[250px]">
                                    <canvas id="chartNasZisDonutMuzaki"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Distribusi Nilai (Triliun Rp)</h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasZisBarWilayah"></canvas>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Jumlah Muzaki & Mustahik (Juta Jiwa)</h3>
                                <div class="h-[350px]">
                                    <canvas id="chartNasZisBarOrang"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 7.1 Sertifikasi Halal UMK --}}
                    <div id="nasView-sertifikasi-umk-nas" class="hidden nas-view-content space-y-8">
                        <div>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="check-circle" class="h-3 w-3"></i>
                                <span>Sertifikasi Halal UMK</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Percepatan Implementasi Sertifikasi Halal UMK</h2>
                            <p class="text-sm text-slate-500 mt-1">Progress capaian sertifikasi halal bagi pelaku Usaha Mikro dan Kecil.</p>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total SH Terbit</p>
                                <p id="nasSertifTotal" class="text-xl font-black text-slate-800">0</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Reguler</p>
                                <p id="nasSertifReguler" class="text-xl font-black text-blue-600">0</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Self-Declare</p>
                                <p id="nasSertifSelf" class="text-xl font-black text-emerald-600">0</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Pendamping PPH</p>
                                <p id="nasSertifPendamping" class="text-xl font-black text-slate-800">0</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Lembaga PPH</p>
                                <p id="nasSertifLembaga" class="text-xl font-black text-slate-800">0</p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <h3 class="font-heading text-base font-bold text-slate-800 mb-6">Tren Perkembangan Sertifikasi Halal</h3>
                            <div class="h-[450px]">
                                <canvas id="chartNasSertifTrend"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 7.2 Literasi Ekonomi Syariah View --}}
                    <div id="nasView-literasi-ekonomi" class="hidden nas-view-content space-y-8">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="book-open" class="h-3 w-3"></i>
                                <span>Literasi & Brand</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Peningkatan Literasi Ekonomi dan Keuangan Syariah</h2>
                            <p class="text-sm text-slate-500 mt-1">Indeks Literasi Ekonomi Syariah (BI)</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                            <p class="mb-6 text-[10px] font-bold uppercase tracking-wider text-slate-400">Tren Indeks Literasi Ekonomi Syariah</p>
                            <div class="h-[400px]">
                                <canvas id="chartNasLiterasiIndeks"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 7.3 Layanan Komunitas View --}}
                    <div id="nasView-layanan-komunitas" class="hidden nas-view-content space-y-10">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 text-teal-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <i data-lucide="users" class="h-3 w-3"></i>
                                <span>Layanan Komunitas</span>
                            </div>
                            <h2 class="font-heading text-xl font-bold text-slate-900 sm:text-2xl">Koordinasi Pengembangan Layanan Keuangan Syariah Berbasis Pesantren dan Komunitas</h2>
                        </div>

                        {{-- Section: Agen Ponpes --}}
                        <div class="space-y-6">
                            <h3 class="font-heading text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Agen Ponpes/Sekolah Islam/BWM
                            </h3>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Perkembangan Jumlah Agen</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenPonpesTrend"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Penyebaran Agen (Peta)</p>
                                    <div id="nasAgenPonpesMap" class="h-[300px] rounded-xl overflow-hidden border border-slate-100"></div>
                                </div>
                            </div>
                            <div class="grid gap-6 md:grid-cols-12">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-5">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Top 5 Provinsi (Tren)</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenPonpesTop5"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-7">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Data Agen per Provinsi</p>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-[11px]">
                                            <thead>
                                                <tr class="border-b border-slate-100">
                                                    <th class="pb-3 font-bold text-slate-500">PROVINSI</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q1 2025</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q2 2025</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q3 2025</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q4 2025</th>
                                                </tr>
                                            </thead>
                                            <tbody id="nasAgenPonpesTableBody"></tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <p class="text-[10px] text-slate-400">Menampilkan <span id="agenPonpesStart">1</span>-<span id="agenPonpesEnd">8</span> dari <span id="agenPonpesTotal">38</span></p>
                                        <div class="flex gap-2">
                                            <button data-agen-type="ponpes" class="btn-prev-agen p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-50"><i data-lucide="chevron-left" class="h-3.5 w-3.5"></i></button>
                                            <button data-agen-type="ponpes" class="btn-next-agen p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-50"><i data-lucide="chevron-right" class="h-3.5 w-3.5"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Agen Bumdes --}}
                        <div class="space-y-6">
                            <h3 class="font-heading text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                Agen Bumdes
                            </h3>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Perkembangan Jumlah Agen Bumdes</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenBumdesTrend"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Penyebaran Agen Bumdes (Peta)</p>
                                    <div id="nasAgenBumdesMap" class="h-[300px] rounded-xl overflow-hidden border border-slate-100"></div>
                                </div>
                            </div>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Perkembangan Top 5 Provinsi</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenBumdesTop5"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Perbandingan per Kuartal (Top 5)</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenBumdesBar"></canvas></div>
                                </div>
                            </div>
                            <div class="grid gap-6 md:grid-cols-12">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-7">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Tabel Jumlah Agen per Provinsi</p>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-[11px]">
                                            <thead>
                                                <tr class="border-b border-slate-100">
                                                    <th class="pb-3 font-bold text-slate-500">PROVINSI</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q1 2025</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q2 2025</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q3 2025</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q4 2025</th>
                                                </tr>
                                            </thead>
                                            <tbody id="nasAgenBumdesTableBody"></tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <p class="text-[10px] text-slate-400">Menampilkan <span id="agenBumdesStart">1</span>-<span id="agenBumdesEnd">8</span> dari <span id="agenBumdesTotal">38</span></p>
                                        <div class="flex gap-2">
                                            <button data-agen-type="bumdes" class="btn-prev-agen p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-50"><i data-lucide="chevron-left" class="h-3.5 w-3.5"></i></button>
                                            <button data-agen-type="bumdes" class="btn-next-agen p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-50"><i data-lucide="chevron-right" class="h-3.5 w-3.5"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-5">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Perkembangan Transaksi</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenBumdesTrans"></canvas></div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Agen Masjid --}}
                        <div class="space-y-6">
                            <h3 class="font-heading text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                Agen Masjid & KBIH
                            </h3>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Perkembangan Jumlah Agen Masjid & KBIH</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenMasjidTrend"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Penyebaran Agen Masjid & KBIH (Peta)</p>
                                    <div id="nasAgenMasjidMap" class="h-[300px] rounded-xl overflow-hidden border border-slate-100"></div>
                                </div>
                            </div>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Perkembangan Top 5 Provinsi</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenMasjidTop5"></canvas></div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Perbandingan per Kuartal (Top 5)</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenMasjidBar"></canvas></div>
                                </div>
                            </div>
                            <div class="grid gap-6 md:grid-cols-12">
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-7">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Tabel Jumlah Agen per Provinsi</p>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-[11px]">
                                            <thead>
                                                <tr class="border-b border-slate-100">
                                                    <th class="pb-3 font-bold text-slate-500">PROVINSI</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q1 2025</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q2 2025</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q3 2025</th>
                                                    <th class="pb-3 text-center font-bold text-slate-500">Q4 2025</th>
                                                </tr>
                                            </thead>
                                            <tbody id="nasAgenMasjidTableBody"></tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <p class="text-[10px] text-slate-400">Menampilkan <span id="agenMasjidStart">1</span>-<span id="agenMasjidEnd">8</span> dari <span id="agenMasjidTotal">38</span></p>
                                        <div class="flex gap-2">
                                            <button data-agen-type="masjid" class="btn-prev-agen p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-50"><i data-lucide="chevron-left" class="h-3.5 w-3.5"></i></button>
                                            <button data-agen-type="masjid" class="btn-next-agen p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-50"><i data-lucide="chevron-right" class="h-3.5 w-3.5"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-5">
                                    <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Perkembangan Transaksi</p>
                                    <div class="h-[300px]"><canvas id="chartNasAgenMasjidTrans"></canvas></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 7.4 SDM Ekonomi Syariah --}}
                    <div id="nasView-sdm-pendidikan" class="hidden nas-view-content space-y-10">
                        {{-- Header --}}
                        <div>
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                    <i data-lucide="book-open" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">SDM Unggul Sektor Ekonomi dan Keuangan Syariah</h2>
                                    <p class="text-[10px] text-slate-400 sm:text-xs">Data Sekolah, Kurikulum Industri Halal, PKS/MoU, dan Akreditasi</p>
                                </div>
                            </div>
                        </div>

                        {{-- Sekolah Pelopor --}}
                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-12">
                                <h3 class="mb-6 font-heading text-sm font-bold text-slate-800">Sekolah Pelopor Ekonomi Syariah</h3>
                                <div class="grid grid-cols-3 gap-4 mb-6">
                                    <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Sekolah</p>
                                        <p id="sdmTotalSekolah" class="mt-1 text-2xl font-extrabold text-emerald-600">0</p>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total SMA</p>
                                        <p id="sdmTotalSma" class="mt-1 text-2xl font-extrabold text-blue-600">0</p>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total SMK</p>
                                        <p id="sdmTotalSmk" class="mt-1 text-2xl font-extrabold text-amber-600">0</p>
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Sebaran Provinsi (SMA & SMK)</p>
                                        <div class="h-[280px]"><canvas id="chartSdmSekolahProv"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Pertumbuhan Sekolah</p>
                                        <div class="h-[280px]"><canvas id="chartSdmSekolahTrend"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kampus Kurikulum Industri Halal --}}
                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-12">
                                <h3 class="mb-6 font-heading text-sm font-bold text-slate-800">Kampus Dengan Implementasi Kurikulum Industri Halal</h3>
                                <div class="grid gap-4 md:grid-cols-3">
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center">Instansi PKS per Tahun</p>
                                        <div class="h-[220px]"><canvas id="chartSdmKihPksTrend"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center">Direktorat Bekerja Sama (PKS)</p>
                                        <div class="h-[220px]"><canvas id="chartSdmKihPksDir"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center">Kategori Instansi (PKS)</p>
                                        <div class="h-[220px]"><canvas id="chartSdmKihPksKat"></canvas></div>
                                    </div>
                                </div>
                                <hr class="my-6 border-slate-100">
                                <div class="grid gap-4 md:grid-cols-3">
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center">Instansi MoU per Tahun</p>
                                        <div class="h-[220px]"><canvas id="chartSdmKihMouTrend"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center">Direktorat Bekerja Sama (MoU)</p>
                                        <div class="h-[220px]"><canvas id="chartSdmKihMouDir"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center">Kategori Instansi (MoU)</p>
                                        <div class="h-[220px]"><canvas id="chartSdmKihMouKat"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PKS dan MoU Perguruan Tinggi --}}
                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-12">
                                <h3 class="mb-6 font-heading text-sm font-bold text-slate-800">PKS Perguruan Tinggi</h3>
                                <div class="grid gap-4 md:grid-cols-2 mb-6">
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Jumlah PKS (2019-2025)</p>
                                        <div class="h-[240px]"><canvas id="chartSdmPksTahun"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Instansi dengan PKS Terbanyak</p>
                                        <div class="h-[240px]"><canvas id="chartSdmPksInstansi"></canvas></div>
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">PKS berdasarkan Direktorat</p>
                                        <div class="h-[240px]"><canvas id="chartSdmPksDirAll"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">PKS berdasarkan Kategori</p>
                                        <div class="h-[240px]"><canvas id="chartSdmPksKatAll"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-12">
                                <h3 class="mb-6 font-heading text-sm font-bold text-slate-800">MoU Perguruan Tinggi</h3>
                                <div class="grid gap-4 md:grid-cols-2 mb-6">
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Jumlah MoU (2020-2025)</p>
                                        <div class="h-[240px]"><canvas id="chartSdmMouTahun"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Instansi dengan MoU Terbanyak</p>
                                        <div class="h-[240px]"><canvas id="chartSdmMouInstansi"></canvas></div>
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">MoU berdasarkan Direktorat</p>
                                        <div class="h-[240px]"><canvas id="chartSdmMouDirAll"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">MoU berdasarkan Kategori</p>
                                        <div class="h-[240px]"><canvas id="chartSdmMouKatAll"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Data Akreditasi PT --}}
                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-12">
                                <h3 class="mb-6 font-heading text-sm font-bold text-slate-800">Data Akreditasi Perguruan Tinggi</h3>
                                <div class="grid gap-4 md:grid-cols-3 mb-6">
                                    <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-100 flex flex-col justify-center items-center">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 mb-2">Total Prodi</p>
                                        <p id="sdmTotalProdi" class="text-4xl font-extrabold text-emerald-700">0</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center">Distribusi Akreditasi</p>
                                        <div class="h-[180px]"><canvas id="chartSdmAkreDist"></canvas></div>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center">Akreditasi Dosen (%)</p>
                                        <div class="h-[180px]"><canvas id="chartSdmAkreDosen"></canvas></div>
                                    </div>
                                </div>
                                <div class="grid gap-4 md:grid-cols-3">
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Instansi Pembina</p>
                                        <div class="h-[280px]"><canvas id="chartSdmAkrePembina"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">PTKIN vs NON PTKIN</p>
                                        <div class="h-[280px]"><canvas id="chartSdmAkrePtkin"></canvas></div>
                                    </div>
                                    <div>
                                        <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Akreditasi per Jenjang</p>
                                        <div class="h-[280px]"><canvas id="chartSdmAkreJenjang"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 7.5 Sosialisasi Brand --}}
                    <div id="nasView-sosialisasi-brand" class="hidden nas-view-content space-y-10">
                        {{-- Header --}}
                        <div>
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                    <i data-lucide="award" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Sosialisasi Lanjutan Brand Ekonomi Syariah</h2>
                                    <p class="text-[10px] text-slate-400 sm:text-xs">Awareness Brand Ekonomi Syariah dan Anugerah Adinata Syariah</p>
                                </div>
                            </div>
                        </div>

                        {{-- Awareness Brand --}}
                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-12">
                                <h3 class="mb-6 font-heading text-sm font-bold text-slate-800">Awareness Brand Ekonomi Syariah</h3>
                                <div class="h-[300px]"><canvas id="chartNasSosialisasiAwareness"></canvas></div>
                            </div>
                        </div>

                        {{-- Anugerah Adinata Syariah --}}
                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-12">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="font-heading text-sm font-bold text-slate-800">Juara Anugerah Adinata Syariah</h3>
                                    <div class="flex items-center gap-2 rounded-lg bg-emerald-50 px-4 py-2 border border-emerald-100">
                                        <i data-lucide="trophy" class="h-4 w-4 text-emerald-600"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Juara Umum: <span id="sosialisasiJuaraUmum" class="text-emerald-900 ml-1"></span></span>
                                    </div>
                                </div>
                                <div class="overflow-x-auto rounded-xl border border-slate-200">
                                    <table class="w-full text-left text-sm text-slate-500">
                                        <thead class="bg-slate-50 text-[10px] font-bold uppercase text-slate-700">
                                            <tr>
                                                <th class="px-4 py-3">Kategori</th>
                                                <th class="px-4 py-3">Juara 1</th>
                                                <th class="px-4 py-3">Juara 2</th>
                                                <th class="px-4 py-3">Juara 3</th>
                                                <th class="px-4 py-3">Juara 4</th>
                                                <th class="px-4 py-3">Juara 5</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasSosialisasiAdinata" class="divide-y divide-slate-100 bg-white">
                                            <!-- Data disisipkan via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 7.6 KDEKS --}}
                    <div id="nasView-kdeks" class="hidden nas-view-content space-y-10">
                        {{-- Header --}}
                        <div>
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                    <i data-lucide="map-pin" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Kelembagaan Ekonomi dan Keuangan Syariah Daerah</h2>
                                    <p class="text-[10px] text-slate-400 sm:text-xs">Data dan Sebaran Komite Daerah Ekonomi dan Keuangan Syariah</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-4 flex flex-col justify-center">
                                <h3 class="mb-2 font-heading text-sm font-bold text-slate-800 text-center">Jumlah KDEKS</h3>
                                <p id="kdeksTotalCount" class="text-6xl font-extrabold text-emerald-600 text-center">0</p>
                                <p class="text-center text-xs text-slate-400 mt-2 font-medium uppercase tracking-wide">Tersebar di Indonesia</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-8">
                                <h3 class="mb-6 font-heading text-sm font-bold text-slate-800">Pertumbuhan KDEKS</h3>
                                <div class="h-[220px]"><canvas id="chartNasKdeksTrend"></canvas></div>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-12">
                                <h3 class="mb-6 font-heading text-sm font-bold text-slate-800">Sebaran KDEKS di Indonesia</h3>
                                <div class="h-[400px] overflow-hidden rounded-xl border border-slate-200 relative">
                                    <div id="nasMapKdeksFallback" class="hidden absolute inset-0 flex items-center justify-center bg-slate-50">
                                        <p class="text-sm text-slate-500 font-medium">Peta tidak dapat dimuat (Leaflet JS hilang)</p>
                                    </div>
                                    <div id="nasKdeksMap" class="h-full w-full bg-slate-50 z-0"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:col-span-12">
                                <h3 class="mb-6 font-heading text-sm font-bold text-slate-800">Daftar KDEKS</h3>
                                <div class="overflow-x-auto rounded-xl border border-slate-200">
                                    <table class="w-full text-left text-sm text-slate-500">
                                        <thead class="bg-slate-50 text-[10px] font-bold uppercase text-slate-700">
                                            <tr>
                                                <th class="px-4 py-3">Provinsi</th>
                                                <th class="px-4 py-3">Nomor SK</th>
                                                <th class="px-4 py-3">Tanggal SK</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableNasKdeks" class="divide-y divide-slate-100 bg-white">
                                            <!-- Data disisipkan via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Placeholder for other views --}}
                    <div id="nasView-placeholder" class="hidden nas-view-content py-20 text-center">
                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300">
                            <i data-lucide="layers" class="h-8 w-8"></i>
                        </div>
                        <h3 class="mt-4 text-base font-bold text-slate-900">Data Sedang Disiapkan</h3>
                        <p id="nasActiveCategoryName" class="mt-1 text-sm text-slate-400 font-medium">Kategori ini akan
                            segera tersedia dengan data statistik terbaru.</p>
                    </div>

                </div>
            </div>
        </div>

    </section>

    <script>
        window.SERVER_DATA = @json($dashboard_data ?? []);
    </script>
    @vite(['resources/js/data_statistik.js'])

    <script>
        // ===== KDEKS SIDEBAR =====
        (function () {
            const kdeksDashBtn = document.querySelector('[data-kdeks-view="dashboard"]');

            if (kdeksDashBtn) {
                kdeksDashBtn.addEventListener('click', function () {
                    document.querySelectorAll('.kdeks-sub-btn').forEach(b => b.classList.remove('is-active'));
                    document.querySelectorAll('.kdeks-nav-btn').forEach(b => b.classList.add('is-active'));
                });
            }
        })();

        // ===== DEV IN PROGRESS MODAL =====
        function openDevModal(triggerEl) {
            const overlay = document.getElementById('devModalOverlay');
            if (!overlay) return;
            document.querySelectorAll('.kdeks-nav-btn').forEach(b => b.classList.remove('is-active'));
            overlay.style.removeProperty('display');
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            if (window.lucide) window.lucide.createIcons();
        }

        function closeDevModal() {
            const overlay = document.getElementById('devModalOverlay');
            if (overlay) {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }
            document.querySelectorAll('.kdeks-nav-btn').forEach(b => b.classList.add('is-active'));
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDevModal();
        });

        // ===== JS STICKY SIDEBAR (Reverted to CSS) =====
        // CSS Sticky is used for better performance and reliability


        // ===== MOBILE KNEKS NAV (Legacy/Reverted) =====
        (function() {
            // Reverted to standard accordion behavior
        })();
    </script>

@endsection
