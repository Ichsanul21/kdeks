@extends('layouts.app')

@section('title', 'Data Statistik - KDEKS Kalimantan Timur')

@section('content')

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

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
        box-shadow: 0 8px 24px -4px rgba(16,185,129,0.13), 0 2px 6px -2px rgba(16,185,129,0.06);
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

    /* ── Period Button States ── */
    .period-btn.is-active {
        background-color: white;
        color: #047857;
        box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);
    }

    /* ── Nas Accordion States ── */
    .nas-nav-btn.is-active {
        background-color: #059669;
        color: white;
        box-shadow: 0 4px 12px -2px rgba(16,185,129,0.3);
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
</style>

<section class="mx-auto max-w-7xl px-4 pb-24 pt-28 sm:px-6">

    {{-- ============================================ --}}
    {{-- TAB: Nasional / Daerah (Full Width, Slim) --}}
    {{-- ============================================ --}}
    <div class="mb-8 grid w-full grid-cols-2 gap-2 rounded-2xl border-2 border-slate-200/80 bg-slate-50/50 p-1 md:gap-2.5 md:p-1.5">
        <button data-tab="nasional" class="tab-btn flex items-center justify-center gap-2 rounded-xl px-3 py-2 transition-all duration-300 md:gap-2.5 md:px-4 md:py-2.5">
            <div class="tab-icon-wrap flex h-7 w-7 shrink-0 items-center justify-center rounded-lg md:h-8 md:w-8">
                <i data-lucide="landmark" class="h-3 w-3 md:h-3.5 md:w-3.5"></i>
            </div>
            <p class="tab-label text-[13px] font-bold leading-none md:text-sm">Nasional</p>
            <div class="tab-divider mx-0.5 h-4 w-px shrink-0 md:mx-1 md:h-5"></div>
            <p class="tab-sublabel text-[8px] font-bold uppercase tracking-widest leading-none md:text-[9px]">KNEKS</p>
        </button>
        <button data-tab="daerah" class="tab-btn is-active flex items-center justify-center gap-2 rounded-xl px-3 py-2 transition-all duration-300 md:gap-2.5 md:px-4 md:py-2.5">
            <div class="tab-icon-wrap flex h-7 w-7 shrink-0 items-center justify-center rounded-lg md:h-8 md:w-8">
                <i data-lucide="map-pin" class="h-3 w-3 md:h-3.5 md:w-3.5"></i>
            </div>
            <p class="tab-label text-[13px] font-bold leading-none md:text-sm">Kalimantan Timur</p>
            <div class="tab-divider mx-0.5 h-4 w-px shrink-0 md:mx-1 md:h-5"></div>
            <p class="tab-sublabel text-[8px] font-bold uppercase tracking-widest leading-none md:text-[9px]">KDEKS</p>
        </button>
    </div>

    {{-- ============================================ --}}
    {{-- Header + Period Selector                    --}}
    {{-- ============================================ --}}
    <div class="mb-8 flex flex-col gap-4 md:mb-10 md:flex-row md:items-end md:justify-between">
        <div>
            <p id="sectionLabel" class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">Data KDEKS</p>
            <h1 id="sectionTitle" class="mt-1 font-heading text-2xl font-extrabold text-slate-900 sm:text-3xl">Kalimantan Timur</h1>
            <p id="sectionDesc" class="mt-2 max-w-xl text-sm leading-7 text-slate-500">Kumpulan data statistik terkini seputar ekosistem syariah, industri halal, dan komitmen KDEKS di Provinsi Kalimantan Timur.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                <button data-period="1" class="period-btn rounded-lg px-4 py-2.5 text-xs font-bold text-slate-400 transition-all duration-200 hover:text-slate-600">1 Tahun</button>
                <button data-period="3" class="period-btn rounded-lg px-4 py-2.5 text-xs font-bold text-slate-400 transition-all duration-200 hover:text-slate-600">3 Tahun</button>
                <button data-period="5" class="period-btn is-active rounded-lg px-4 py-2.5 text-xs font-bold transition-all duration-200">5 Tahun</button>
            </div>
            <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-500 shadow-sm">
                <i data-lucide="calendar-days" class="h-3.5 w-3.5 text-emerald-500"></i>
                <span id="periodText">2021 – 2025</span>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- Quick Stats Cards                           --}}
    {{-- ============================================ --}}
    {{-- ============================================ --}}
    {{-- Quick Stats Cards                           --}}
    {{-- ============================================ --}}
    <div id="statsDaerah" class="mb-8 grid grid-cols-2 gap-3 md:mb-10 md:grid-cols-4 md:gap-4">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="file-badge" class="h-4 w-4"></i>
            </div>
            <p id="stat0" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">0</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Sertifikat Halal Terbit</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="building-2" class="h-4 w-4"></i>
            </div>
            <p id="stat1" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">0</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Pelaku Usaha Halal</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="utensils-crossed" class="h-4 w-4"></i>
            </div>
            <p id="stat2" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">0</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">UMKM Halal</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="map-pin" class="h-4 w-4"></i>
            </div>
            <p id="stat3" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">0</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">RPH Halal Beroperasi</p>
        </div>
    </div>

    <div id="statsNasional" class="mb-8 hidden grid grid-cols-2 gap-3 md:mb-10 md:grid-cols-4 md:gap-4">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="file-check" class="h-4 w-4"></i>
            </div>
            <p id="nStat0" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">2.31M</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Total Sertifikasi Halal</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
            </div>
            <p id="nStat1" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">23.8K T</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">PDB ADHB 2025</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="landmark" class="h-4 w-4"></i>
            </div>
            <p id="nStat2" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">1.03K T</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Aset Perbankan Syariah</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="lightbulb" class="h-4 w-4"></i>
            </div>
            <p id="nStat3" class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">50.1%</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Indeks Literasi (2025)</p>
        </div>
    </div>



    <div id="contentDaerah">
        {{-- ============================================ --}}
        {{-- 1. SGIE                                     --}}
        {{-- ============================================ --}}
        <div class="mb-8">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600"><i data-lucide="globe" class="h-4 w-4"></i></div>
                <div>
                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Indikator Ekonomi Islam Global</h2>
                    <p class="text-[10px] text-slate-400 sm:text-xs">SGIE 2025 | Skor per sektor (0–100)</p>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <div class="h-[280px] sm:h-[380px]"><canvas id="chartSGIE"></canvas></div>
            </div>
        </div>


        {{-- ============================================ --}}
        {{-- 2. INDUSTRI PRODUK HALAL                     --}}
        {{-- ============================================ --}}
        <div class="mb-8">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600"><i data-lucide="trending-up" class="h-4 w-4"></i></div>
                <div>
                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Industri Produk Halal</h2>
                    <p id="descIndustri" class="text-[10px] text-slate-400 sm:text-xs">Perkembangan sertifikasi & nilai ekspor produk halal <span class="font-semibold text-slate-600">Kalimantan Timur</span></p>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Jumlah Sertifikasi Halal Terbit</p>
                    <div class="h-[220px] sm:h-[280px]"><canvas id="chartSertifikasi"></canvas></div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Nilai Ekspor Produk Halal (Juta USD)</p>
                    <div class="h-[220px] sm:h-[280px]"><canvas id="chartEkspor"></canvas></div>
                </div>
            </div>
        </div>


        {{-- ============================================ --}}
        {{-- 3. PARIWISATA RAMAH MUSLIM                   --}}
        {{-- ============================================ --}}
        <div class="mb-8">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600"><i data-lucide="utensils-crossed" class="h-4 w-4"></i></div>
                <div>
                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Pariwisata Ramah Muslim</h2>
                    <p id="descPariwisata" class="text-[10px] text-slate-400 sm:text-xs">UMKM bersertifikat halal di <span class="font-semibold text-slate-600">Kalimantan Timur</span></p>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <p id="titlePariwisataBar" class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Jumlah Sertifikat per Kabupaten/Kota</p>
                    <div class="h-[320px]"><canvas id="chartPariwisata"></canvas></div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Sebaran UMKM Halal per Provinsi</p>
                    <div id="panelPariwisata" class="relative h-[320px] overflow-hidden rounded-xl bg-gradient-to-br from-slate-50 to-emerald-50/40">
                        <svg viewBox="0 0 500 400" class="absolute inset-0 h-full w-full opacity-10"><path d="M180,80 Q220,40 280,60 Q320,50 350,80 Q380,100 370,150 Q360,200 340,240 Q310,280 280,300 Q240,320 200,300 Q170,280 160,240 Q140,200 150,150 Q155,110 180,80Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                        <div class="relative z-10 flex h-full flex-col justify-center gap-2.5 px-4 py-4 sm:gap-3 sm:px-6"></div>
                    </div>
                </div>
            </div>
        </div>


        {{-- ============================================ --}}
        {{-- 4. LPH                                      --}}
        {{-- ============================================ --}}
        <div class="mb-8">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600"><i data-lucide="scan-search" class="h-4 w-4"></i></div>
                <div>
                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Lembaga Pemeriksa Halal (LPH)</h2>
                    <p class="text-[10px] text-slate-400 sm:text-xs">Jumlah LPH, auditor, perkembangan tahunan, & jenis lembaga</p>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Perkembangan LPH & Auditor (Year-over-Year)</p>
                    <div class="h-[260px] sm:h-[300px]"><canvas id="chartLPHLine"></canvas></div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Komposisi Jenis LPH</p>
                    <div class="h-[260px] sm:h-[300px]"><canvas id="chartLPHPie"></canvas></div>
                </div>
            </div>
        </div>


        {{-- ============================================ --}}
        {{-- 5. MODERNISASI RPH HALAL                     --}}
        {{-- ============================================ --}}
        <div class="mb-8">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-600"><i data-lucide="warehouse" class="h-4 w-4"></i></div>
                <div>
                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Modernisasi RPH Halal</h2>
                    <p class="text-[10px] text-slate-400 sm:text-xs">Jumlah RPH halal berdasarkan pengelola & sebaran per provinsi</p>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Jumlah RPH Halal berdasarkan Pengelola</p>
                    <div class="h-[260px] sm:h-[300px]"><canvas id="chartRPHPie"></canvas></div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Sebaran RPH Halal per Provinsi</p>
                    <div id="panelRPH" class="relative h-[260px] overflow-hidden rounded-xl bg-gradient-to-br from-slate-50 to-rose-50/40 sm:h-[300px]">
                        <svg viewBox="0 0 500 400" class="absolute inset-0 h-full w-full opacity-10"><path d="M180,80 Q220,40 280,60 Q320,50 350,80 Q380,100 370,150 Q360,200 340,240 Q310,280 280,300 Q240,320 200,300 Q170,280 160,240 Q140,200 150,150 Q155,110 180,80Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                        <div class="relative z-10 grid h-full grid-cols-2 gap-2.5 px-4 py-4 sm:gap-3 sm:px-5 sm:py-5"></div>
                    </div>
                </div>
            </div>
        </div>


        {{-- ============================================ --}}
        {{-- 6. SERTIFIKASI HALAL UMK                     --}}
        {{-- ============================================ --}}
        <div class="mb-8">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-teal-100 text-teal-600"><i data-lucide="badge-check" class="h-4 w-4"></i></div>
                <div>
                    <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Sertifikasi Halal UMK</h2>
                    <p class="text-[10px] text-slate-400 sm:text-xs">SH Terbit (Reguler/Self-Declare), jumlah pendamping & lembaga PPH</p>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Perkembangan Sertifikasi Halal UMK per Tahun</p>
                <div class="h-[260px] sm:h-[340px]"><canvas id="chartUMK"></canvas></div>
                <div class="mt-4 grid grid-cols-1 gap-3 border-t border-slate-100 pt-4 sm:mt-5 sm:grid-cols-3 sm:gap-3 sm:pt-5">
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 sm:block sm:border-0 sm:bg-transparent sm:text-center">
                        <p id="umkInfo0" class="text-xl font-extrabold text-slate-900 sm:text-2xl">0</p>
                        <p class="ml-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:ml-0 sm:mt-0.5">Pendamping PPH Aktif</p>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 sm:block sm:border-0 sm:bg-transparent sm:text-center">
                        <p id="umkInfo1" class="text-xl font-extrabold text-slate-900 sm:text-2xl">0</p>
                        <p class="ml-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:ml-0 sm:mt-0.5">Lembaga PPH Terdaftar</p>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 sm:block sm:border-0 sm:bg-transparent sm:text-center">
                        <p id="umkInfo2" class="text-xl font-extrabold text-emerald-600 sm:text-2xl">0%</p>
                        <p class="ml-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:ml-0 sm:mt-0.5">Tingkat Keberhasilan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="contentNasional" class="hidden">
        <div class="flex flex-col gap-6 lg:flex-row">
            {{-- Sidebar Accordion --}}
            <aside class="w-full lg:w-80 shrink-0">
                <div class="sticky top-24 space-y-2">
                    {{-- 1. Dashboard Eksekutif --}}
                    <button data-nas-view="eksekutif" class="nas-nav-btn is-active w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-slate-700 hover:bg-slate-50 transition-all duration-300">
                        <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100/50 text-emerald-600">
                            <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i>
                        </div>
                        <span class="text-[13px] font-bold">1. Dashboard Eksekutif</span>
                    </button>

                    {{-- 2. Penguatan Industri --}}
                    <div class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                        <button class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-50 text-blue-500">
                                    <i data-lucide="package" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[13px] font-bold">2. Industri & UMKM Halal</span>
                            </div>
                            <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                        </button>
                        <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                            <button data-nas-view="daya-saing" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Daya Saing Industri</button>
                            <button data-nas-view="pariwisata" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pariwisata Ramah Muslim</button>
                            <button data-nas-view="kih" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Kawasan Industri Halal (KIH)</button>
                            <button data-nas-view="lph" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Lembaga Pemeriksa Halal (LPH)</button>
                            <button data-nas-view="kodifikasi" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Kodifikasi Data Industri</button>
                            <button data-nas-view="kesehatan" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Industri Kesehatan Syariah</button>
                            <button data-nas-view="rph" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Modernisasi RPH Halal</button>
                            <button data-nas-view="hvc" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Halal Value Chain (HVC)</button>
                            <button data-nas-view="khas" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Zona Kuliner Halal (KHAS)</button>
                            <button data-nas-view="modul-umkm" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Modul UMKM IH</button>
                            <button data-nas-view="cold-storage" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">RPHR, RPHU, Cold Storage</button>
                        </div>
                    </div>

                    {{-- 3. PDB --}}
                    <div class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                        <button class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-50 text-amber-500">
                                    <i data-lucide="bar-chart-3" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[13px] font-bold">3. Aktivitas Usaha / PDB</span>
                            </div>
                            <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                        </button>
                        <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                            <button data-nas-view="indikator-aktivitas" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Indikator Aktivitas Usaha</button>
                        </div>
                    </div>

                    {{-- 4. Ekspor --}}
                    <div class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                        <button class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-sky-50 text-sky-500">
                                    <i data-lucide="ship" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[13px] font-bold">4. Ekspor & Internasional</span>
                            </div>
                            <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                        </button>
                        <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                            <button data-nas-view="nilai-ekspor" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Nilai Ekspor/PDB</button>
                            <button data-nas-view="logistik" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Logistik Halal</button>
                            <button data-nas-view="percepatan-ekspor" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Percepatan Ekspor</button>
                            <button data-nas-view="ekspor-provinsi" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Ekspor per Provinsi</button>
                        </div>
                    </div>

                    {{-- 5. Keuangan Syariah --}}
                    <div class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                        <button class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-violet-50 text-violet-500">
                                    <i data-lucide="landmark" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[13px] font-bold">5. Keuangan Syariah</span>
                            </div>
                            <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                        </button>
                        <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                            <button data-nas-view="aset-keuangan" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Aset Keuangan Syariah</button>
                            <button data-nas-view="pembiayaan-umkm" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pembiayaan UMKM</button>
                            <button data-nas-view="perbankan-syariah" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Perbankan Syariah</button>
                            <button data-nas-view="payroll-asn" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Payroll ASN</button>
                            <button data-nas-view="jaminan-sosial" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Jaminan Sosial</button>
                            <button data-nas-view="kpbu-syariah" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">KPBU Syariah</button>
                            <button data-nas-view="pasar-modal" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pasar Modal Syariah</button>
                            <button data-nas-view="iknb-asuransi" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">IKNB & Asuransi Syariah</button>
                            <button data-nas-view="kinerja-rasio" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Kinerja & Rasio</button>
                        </div>
                    </div>

                    {{-- 6. Dana Sosial --}}
                    <div class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                        <button class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-rose-50 text-rose-500">
                                    <i data-lucide="heart-handshake" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[13px] font-bold">6. Dana Sosial Syariah</span>
                            </div>
                            <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                        </button>
                        <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                            <button data-nas-view="zis-pdb" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">ZIS / PDB</button>
                            <button data-nas-view="transformasi-wakaf" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Transformasi Wakaf</button>
                            <button data-nas-view="wakaf-uang" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Aset Wakaf Uang/PDB</button>
                            <button data-nas-view="pendanaan-umkm-sosial" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Pendanaan UMKM Sosial</button>
                            <button data-nas-view="zis-national" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">ZIS National</button>
                        </div>
                    </div>

                    {{-- 7. Ekosistem Pendukung --}}
                    <div class="nas-accordion-item rounded-xl border border-slate-200 bg-white overflow-hidden transition-all duration-300">
                        <button class="nas-accordion-trigger w-full flex items-center justify-between px-4 py-3.5 text-slate-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-teal-50 text-teal-500">
                                    <i data-lucide="layers" class="h-3.5 w-3.5"></i>
                                </div>
                                <span class="text-[13px] font-bold">7. Ekosistem Pendukung</span>
                            </div>
                            <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300"></i>
                        </button>
                        <div class="nas-accordion-content bg-slate-50/50 border-t border-slate-100">
                            <button data-nas-view="sertifikasi-umk" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Sertifikasi Halal UMK</button>
                            <button data-nas-view="literasi-brand" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Literasi & Brand</button>
                            <button data-nas-view="layanan-komunitas" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Layanan Komunitas</button>
                            <button data-nas-view="sdm-pendidikan" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">SDM & Pendidikan</button>
                            <button data-nas-view="kelembagaan-daerah" class="nas-sub-nav-btn w-full text-left px-11 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 transition-colors hover:text-emerald-600">Kelembagaan Daerah</button>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Main Content Area --}}
            <div id="nasMainContent" class="flex-1 min-w-0">
                {{-- 1. Dashboard Eksekutif View --}}
                <div id="nasView-eksekutif" class="nas-view-content space-y-10">
                    {{-- SGIE & Global Ranking --}}
                    <div>
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600"><i data-lucide="globe" class="h-4 w-4"></i></div>
                            <div>
                                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">SGIE 2025 & Global Ranking</h2>
                                <p class="text-[10px] text-slate-400 sm:text-xs">Global Islamic Economy Indicator | Skor Top 15 Negara</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-12">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-8">
                                <div class="mb-4 flex items-center justify-between">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Skor Ekonomi Islam Global (Top 15)</p>
                                    <select id="sgieSelector" class="rounded-lg border-slate-200 bg-slate-50 px-3 py-1 text-[10px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-emerald-500/20">
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
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-4">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Global Muslim Travel Index 2024</p>
                                <div id="panelGMTI" class="space-y-2 overflow-y-auto pr-1" style="max-height: 420px;">
                                    <!-- List GMTI rendered via JS -->
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Makroekonomi & PDB Nasional --}}
                    <div>
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600"><i data-lucide="bar-chart-3" class="h-4 w-4"></i></div>
                            <div>
                                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Makroekonomi & PDB Nasional</h2>
                                <p class="text-[10px] text-slate-400 sm:text-xs">Produk Domestik Bruto Atas Dasar Harga Konstan & Berlaku</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Tren PDB Tahunan (Triliun Rupiah)</p>
                                <div class="h-[280px]"><canvas id="chartNasPDB"></canvas></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Kontribusi PDB per Sektor (2025)</p>
                                <div class="h-[280px]"><canvas id="chartNasPDBSektor"></canvas></div>
                            </div>
                        </div>
                    </div>

                    {{-- Industri Halal & Ekspor --}}
                    <div>
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600"><i data-lucide="shopping-bag" class="h-4 w-4"></i></div>
                            <div>
                                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Industri Halal & Ekspor</h2>
                                <p class="text-[10px] text-slate-400 sm:text-xs">Sertifikasi Halal & Nilai Ekspor Produk Halal Indonesia</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-2">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Perkembangan Sertifikasi Halal (Nasional)</p>
                                <div class="h-[280px]"><canvas id="chartNasSertifikasi"></canvas></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Kontribusi Ekspor per Sektor</p>
                                <div class="h-[280px]"><canvas id="chartNasEksporSektor"></canvas></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-2">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Nilai Ekspor Produk Halal (2019 - 2025)</p>
                                <div class="h-[280px]"><canvas id="chartNasEkspor"></canvas></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Top 5 Negara Tujuan Ekspor (Jan 2025)</p>
                                <div id="panelEksporNegara" class="space-y-3">
                                    <!-- List Top Export countries -->
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Keuangan Syariah & Bisnis --}}
                    <div>
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600"><i data-lucide="landmark" class="h-4 w-4"></i></div>
                            <div>
                                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Keuangan Syariah & Bisnis</h2>
                                <p class="text-[10px] text-slate-400 sm:text-xs">Indikator Aktivitas Usaha & Aset Keuangan Syariah</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Indikator Aktivitas Usaha Syariah</p>
                                <div class="h-[300px]"><canvas id="chartNasAktivitas"></canvas></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Aset Keuangan Islam Teratas (Global 2022)</p>
                                <div class="h-[300px]"><canvas id="chartNasAsetGlobal"></canvas></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Perkembangan Aset IKNB Syariah</p>
                                <div class="h-[300px]"><canvas id="chartNasIKNB"></canvas></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Pasar Konsumen Halal (Top Markets)</p>
                                <div class="mb-4 flex items-center gap-2">
                                    <button data-market="food" class="market-tab-btn is-active rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500 transition-all hover:bg-slate-200">Food</button>
                                    <button data-market="fashion" class="market-tab-btn rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500 transition-all hover:bg-slate-200">Fashion</button>
                                    <button data-market="travel" class="market-tab-btn rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500 transition-all hover:bg-slate-200">Travel</button>
                                    <button data-market="pharma" class="market-tab-btn rounded-lg bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500 transition-all hover:bg-slate-200">Pharma</button>
                                </div>
                                <div class="h-[220px]"><canvas id="chartNasMarket"></canvas></div>
                            </div>
                        </div>
                    </div>

                    {{-- Keuangan Sosial & Literasi --}}
                    <div class="pb-10">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-teal-100 text-teal-600"><i data-lucide="heart-handshake" class="h-4 w-4"></i></div>
                            <div>
                                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Keuangan Sosial & Literasi</h2>
                                <p class="text-[10px] text-slate-400 sm:text-xs">Zakat, Wakaf, & Indeks Literasi Ekonomi Syariah</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6 md:col-span-2">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Indeks Literasi & Awareness (2019 - 2025)</p>
                                <div class="h-[280px]"><canvas id="chartNasLiterasi"></canvas></div>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                                <p class="mb-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">Ringkasan Keuangan Sosial</p>
                                <div class="space-y-4">
                                    <div class="rounded-xl bg-emerald-50/50 p-4">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Zakat & Infak</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <div><p class="text-[9px] text-slate-400">Pengumpulan</p><p class="text-sm font-bold text-slate-800">10.3 T</p></div>
                                            <div class="text-right"><p class="text-[9px] text-slate-400">Penyaluran</p><p class="text-sm font-bold text-slate-800">9.2 T</p></div>
                                        </div>
                                    </div>
                                    <div class="rounded-xl bg-blue-50/50 p-4">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Wakaf Uang</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <div><p class="text-[9px] text-slate-400">Nazhir</p><p class="text-sm font-bold text-slate-800">432</p></div>
                                            <div class="text-right"><p class="text-[9px] text-slate-400">LKSPWU</p><p class="text-sm font-bold text-slate-800">61</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Placeholder for other views --}}
                <div id="nasView-placeholder" class="hidden nas-view-content">
                    <div class="flex flex-col items-center justify-center py-24 rounded-3xl border-2 border-dashed border-slate-200 bg-white/50 backdrop-blur-sm">
                        <div class="h-20 w-20 flex items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500 mb-6 shadow-sm">
                            <i data-lucide="bar-chart-big" class="h-10 w-10"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Statistik Belum Tersedia</h3>
                        <p class="text-sm text-slate-500 mt-2 text-center max-w-sm px-6">Data untuk kategori <span id="nasActiveCategoryName" class="font-bold text-emerald-600">ini</span> sedang dalam tahap integrasi data nasional.</p>
                        <button onclick="window.DataStatistik.resetNasView()" class="mt-8 px-6 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold transition-all hover:bg-slate-800">Kembali ke Dashboard Eksekutif</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

@vite(['resources/js/data_statistik.js'])

@endsection
