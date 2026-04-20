@extends('layouts.app')

@section('title', 'Data KDEKS Kalimantan Timur')

@section('content')

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<section class="mx-auto max-w-7xl px-4 pb-24 pt-28 sm:px-6">

    <!-- Header -->
    <div class="mb-8 flex flex-col gap-4 md:mb-10 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">Data KDEKS</p>
            <h1 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 sm:text-4xl">Kalimantan Timur</h1>
            <p class="mt-2 max-w-xl text-sm leading-7 text-slate-500">Kumpulan data statistik terkini seputar ekosistem syariah, industri halal, dan komitmen KDEKS di Provinsi Kalimantan Timur.</p>
        </div>
        <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-500 shadow-sm">
            <i data-lucide="calendar-days" class="h-3.5 w-3.5 text-emerald-500"></i>
            Periode Data: 2023 – 2025
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="mb-8 grid grid-cols-2 gap-3 md:mb-10 md:grid-cols-4 md:gap-4">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="file-badge" class="h-4 w-4"></i>
            </div>
            <p class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">12.847</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Sertifikat Halal Terbit</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="building-2" class="h-4 w-4"></i>
            </div>
            <p class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">384</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">Pelaku Usaha Halal</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="utensils-crossed" class="h-4 w-4"></i>
            </div>
            <p class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">1.253</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">UMKM Halal</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600 sm:h-9 sm:w-9 sm:rounded-xl">
                <i data-lucide="map-pin" class="h-4 w-4"></i>
            </div>
            <p class="mt-2.5 text-xl font-extrabold text-slate-900 sm:mt-3 sm:text-2xl">14</p>
            <p class="mt-0.5 text-[10px] font-medium leading-snug text-slate-400 sm:text-xs">RPH Halal Beroperasi</p>
        </div>
    </div>


    <!-- ============================================ -->
    <!-- 1. INDIKATOR EKONOMI ISLAM GLOBAL (SGIE)    -->
    <!-- ============================================ -->
    <div class="mb-8">
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                <i data-lucide="globe" class="h-4 w-4"></i>
            </div>
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Indikator Ekonomi Islam Global</h2>
                <p class="text-[10px] text-slate-400 sm:text-xs">SGIE 2025 | Skor per sektor (0–100)</p>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
            <div class="h-[280px] sm:h-[380px]">
                <canvas id="chartSGIE"></canvas>
            </div>
        </div>
    </div>


    <!-- ============================================ -->
    <!-- 2. INDUSTRI PRODUK HALAL                     -->
    <!-- ============================================ -->
    <div class="mb-8">
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                <i data-lucide="trending-up" class="h-4 w-4"></i>
            </div>
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Industri Produk Halal</h2>
                <p class="text-[10px] text-slate-400 sm:text-xs">Perkembangan sertifikasi & nilai ekspor produk halal Kaltim</p>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Jumlah Sertifikasi Halal Terbit</p>
                <div class="h-[220px] sm:h-[280px]">
                    <canvas id="chartSertifikasi"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Nilai Ekspor Produk Halal (Juta USD)</p>
                <div class="h-[220px] sm:h-[280px]">
                    <canvas id="chartEkspor"></canvas>
                </div>
            </div>
        </div>
    </div>


    <!-- ============================================ -->
    <!-- 3. PARIWISATA RAMAH MUSLIM                   -->
    <!-- ============================================ -->
    <div class="mb-8">
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                <i data-lucide="utensils-crossed" class="h-4 w-4"></i>
            </div>
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Pariwisata Ramah Muslim</h2>
                <p class="text-[10px] text-slate-400 sm:text-xs">UMKM bersertifikat halal di Kalimantan Timur</p>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Jumlah Sertifikat per Kabupaten/Kota</p>
                <div class="h-[320px]">
                    <canvas id="chartPariwisata"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Sebaran UMKM Halal per Provinsi</p>
                <div class="relative h-[320px] overflow-hidden rounded-xl bg-gradient-to-br from-slate-50 to-emerald-50/40">
                    <!-- Simplified Map Placeholder -->
                    <svg viewBox="0 0 500 400" class="absolute inset-0 h-full w-full opacity-10">
                        <path d="M180,80 Q220,40 280,60 Q320,50 350,80 Q380,100 370,150 Q360,200 340,240 Q310,280 280,300 Q240,320 200,300 Q170,280 160,240 Q140,200 150,150 Q155,110 180,80Z" fill="none" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <div class="relative z-10 flex h-full flex-col justify-center gap-2.5 px-4 py-4 sm:gap-3 sm:px-6">
                        <div class="flex items-center justify-between rounded-xl border border-slate-200/80 bg-white/80 px-3 py-2.5 backdrop-blur-sm sm:px-4 sm:py-3">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span class="text-[11px] font-semibold text-slate-700 sm:text-xs">Kaltim</span>
                            </div>
                            <span class="text-sm font-extrabold text-slate-900">1.253</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-slate-200/80 bg-white/80 px-3 py-2.5 backdrop-blur-sm sm:px-4 sm:py-3">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                <span class="text-[11px] font-semibold text-slate-700 sm:text-xs">Kalsel</span>
                            </div>
                            <span class="text-sm font-extrabold text-slate-900">987</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-slate-200/80 bg-white/80 px-3 py-2.5 backdrop-blur-sm sm:px-4 sm:py-3">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                <span class="text-[11px] font-semibold text-slate-700 sm:text-xs">Kaltara</span>
                            </div>
                            <span class="text-sm font-extrabold text-slate-900">324</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-slate-200/80 bg-white/80 px-3 py-2.5 backdrop-blur-sm sm:px-4 sm:py-3">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                                <span class="text-[11px] font-semibold text-slate-700 sm:text-xs">Kalbar</span>
                            </div>
                            <span class="text-sm font-extrabold text-slate-900">256</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-slate-200/80 bg-white/80 px-3 py-2.5 backdrop-blur-sm sm:px-4 sm:py-3">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                <span class="text-[11px] font-semibold text-slate-700 sm:text-xs">Kalteng</span>
                            </div>
                            <span class="text-sm font-extrabold text-slate-900">198</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ============================================ -->
    <!-- 4. LEMBAGA PEMERIKSA HALAL (LPH)             -->
    <!-- ============================================ -->
    <div class="mb-8">
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600">
                <i data-lucide="scan-search" class="h-4 w-4"></i>
            </div>
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Lembaga Pemeriksa Halal (LPH)</h2>
                <p class="text-[10px] text-slate-400 sm:text-xs">Jumlah LPH, auditor, perkembangan tahunan, & jenis lembaga</p>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Perkembangan LPH & Auditor (Year-over-Year)</p>
                <div class="h-[260px] sm:h-[300px]">
                    <canvas id="chartLPHLine"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Komposisi Jenis LPH</p>
                <div class="h-[260px] sm:h-[300px]">
                    <canvas id="chartLPHPie"></canvas>
                </div>
            </div>
        </div>
    </div>


    <!-- ============================================ -->
    <!-- 5. MODERNISASI RPH HALAL                     -->
    <!-- ============================================ -->
    <div class="mb-8">
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-600">
                <i data-lucide="warehouse" class="h-4 w-4"></i>
            </div>
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Modernisasi RPH Halal</h2>
                <p class="text-[10px] text-slate-400 sm:text-xs">Jumlah RPH halal berdasarkan pengelola & sebaran per provinsi</p>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Jumlah RPH Halal berdasarkan Pengelola</p>
                <div class="h-[260px] sm:h-[300px]">
                    <canvas id="chartRPHPie"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
                <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Sebaran RPH Halal per Provinsi</p>
                <div class="relative h-[260px] overflow-hidden rounded-xl bg-gradient-to-br from-slate-50 to-rose-50/40 sm:h-[300px]">
                    <svg viewBox="0 0 500 400" class="absolute inset-0 h-full w-full opacity-10">
                        <path d="M180,80 Q220,40 280,60 Q320,50 350,80 Q380,100 370,150 Q360,200 340,240 Q310,280 280,300 Q240,320 200,300 Q170,280 160,240 Q140,200 150,150 Q155,110 180,80Z" fill="none" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <div class="relative z-10 grid h-full grid-cols-2 gap-2.5 px-4 py-4 sm:gap-3 sm:px-5 sm:py-5">
                        <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/80 bg-white/80 p-2.5 backdrop-blur-sm sm:p-3">
                            <span class="text-xl font-extrabold text-slate-900 sm:text-2xl">14</span>
                            <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-emerald-600 sm:text-[10px]">Kaltim</span>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/80 bg-white/80 p-2.5 backdrop-blur-sm sm:p-3">
                            <span class="text-xl font-extrabold text-slate-900 sm:text-2xl">11</span>
                            <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-blue-600 sm:text-[10px]">Kalsel</span>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/80 bg-white/80 p-2.5 backdrop-blur-sm sm:p-3">
                            <span class="text-xl font-extrabold text-slate-900 sm:text-2xl">7</span>
                            <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-amber-600 sm:text-[10px]">Kaltara</span>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/80 bg-white/80 p-2.5 backdrop-blur-sm sm:p-3">
                            <span class="text-xl font-extrabold text-slate-900 sm:text-2xl">9</span>
                            <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-violet-600 sm:text-[10px]">Kalbar</span>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/80 bg-white/80 p-2.5 backdrop-blur-sm sm:p-3">
                            <span class="text-xl font-extrabold text-slate-900 sm:text-2xl">6</span>
                            <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-rose-600 sm:text-[10px]">Kalteng</span>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50/50 p-2.5 backdrop-blur-sm sm:p-3">
                            <span class="text-xl font-extrabold text-slate-400 sm:text-2xl">47</span>
                            <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-slate-400 sm:text-[10px]">Total Kalimantan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ============================================ -->
    <!-- 6. SERTIFIKASI HALAL UMK                     -->
    <!-- ============================================ -->
    <div class="mb-8">
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                <i data-lucide="badge-check" class="h-4 w-4"></i>
            </div>
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900 sm:text-lg">Sertifikasi Halal UMK</h2>
                <p class="text-[10px] text-slate-400 sm:text-xs">SH Terbit (Reguler/Self-Declare), jumlah pendamping & lembaga PPH</p>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6">
            <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-4 sm:text-xs">Perkembangan Sertifikasi Halal UMK per Tahun</p>
            <div class="h-[260px] sm:h-[340px]">
                <canvas id="chartUMK"></canvas>
            </div>
            <!-- Sub info -->
            <div class="mt-4 grid grid-cols-1 gap-3 border-t border-slate-100 pt-4 sm:mt-5 sm:grid-cols-3 sm:gap-3 sm:pt-5">
                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 sm:block sm:border-0 sm:bg-transparent sm:text-center">
                    <p class="text-xl font-extrabold text-slate-900 sm:text-2xl">87</p>
                    <p class="ml-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:ml-0 sm:mt-0.5">Pendamping PPH Aktif</p>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 sm:block sm:border-0 sm:bg-transparent sm:text-center">
                    <p class="text-xl font-extrabold text-slate-900 sm:text-2xl">12</p>
                    <p class="ml-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:ml-0 sm:mt-0.5">Lembaga PPH Terdaftar</p>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 sm:block sm:border-0 sm:bg-transparent sm:text-center">
                    <p class="text-xl font-extrabold text-emerald-600 sm:text-2xl">94,2%</p>
                    <p class="ml-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:ml-0 sm:mt-0.5">Tingkat Keberhasilan</p>
                </div>
            </div>
        </div>
    </div>


</section>


{{-- ========================================= --}}
{{-- CHART.JS INITIALIZATION                    --}}
{{-- ========================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fontColor = '#94a3b8';
    const gridColor = 'rgba(226, 232, 240, 0.6)';
    const isMobile = window.innerWidth < 640;

    Chart.defaults.font.family = "'Inter', 'Satoshi', system-ui, sans-serif";
    Chart.defaults.font.size = isMobile ? 10 : 11;
    Chart.defaults.color = fontColor;

    // ── 1. SGIE 2025 (Horizontal Bar) ──
    new Chart(document.getElementById('chartSGIE'), {
        type: 'bar',
        data: {
            labels: isMobile
                ? ['Halal Food', 'Islamic Finance', 'Muslim Travel', 'Media', 'Pharma & Cosmetics', 'Modest Fashion', 'Global Avg']
                : [
                    'Halal Food',
                    'Islamic Finance',
                    'Muslim-Friendly Travel',
                    'Media & Recreation',
                    'Halal Pharma & Cosmetics',
                    'Modest Fashion',
                    'Global (Rata-rata)'
                ],
            datasets: [{
                label: 'Skor SGIE 2025',
                data: [72, 58, 64, 41, 53, 48, 56],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(16, 185, 129, 0.6)',
                    'rgba(16, 185, 129, 0.48)',
                    'rgba(16, 185, 129, 0.38)',
                    'rgba(16, 185, 129, 0.28)',
                    'rgba(100, 116, 139, 0.25)',
                ],
                borderColor: 'transparent',
                borderRadius: 6,
                borderSkipped: false,
                barThickness: isMobile ? 18 : 28,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { weight: '700' },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => `Skor: ${ctx.parsed.x}/100`
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { callback: v => v }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { weight: '600', size: isMobile ? 9 : 11 }, color: '#475569' }
                }
            }
        }
    });


    // ── 2a. Sertifikasi Halal Terbit (Line) ──
    new Chart(document.getElementById('chartSertifikasi'), {
        type: 'line',
        data: {
            labels: ['2019', '2020', '2021', '2022', '2023', '2024', '2025*'],
            datasets: [{
                label: 'Sertifikat Terbit',
                data: [1240, 1890, 3420, 5680, 8150, 10840, 12847],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                fill: true,
                tension: 0.4,
                borderWidth: 2.5,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: isMobile ? 3 : 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: { label: ctx => `${ctx.parsed.y.toLocaleString('id-ID')} sertifikat` }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { callback: v => (v / 1000) + 'K' }
                }
            }
        }
    });


    // ── 2b. Nilai Ekspor (Line) ──
    new Chart(document.getElementById('chartEkspor'), {
        type: 'line',
        data: {
            labels: ['2019', '2020', '2021', '2022', '2023', '2024', '2025*'],
            datasets: [{
                label: 'Ekspor (Juta USD)',
                data: [42, 38, 67, 94, 128, 156, 183],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.08)',
                fill: true,
                tension: 0.4,
                borderWidth: 2.5,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: isMobile ? 3 : 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: { label: ctx => `$${ctx.parsed.y} Juta` }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { callback: v => '$' + v + 'M' }
                }
            }
        }
    });


    // ── 3. Pariwisata - UMKM Halal per Kab/Kota (Horizontal Bar) ──
    new Chart(document.getElementById('chartPariwisata'), {
        type: 'bar',
        data: {
            labels: isMobile
                ? ['Samarinda', 'Balikpapan', 'Bontang', 'Kukar', 'PPU', 'Kutim', 'Paser', 'Berau', 'Mahulu']
                : ['Kota Samarinda', 'Kota Balikpapan', 'Kota Bontang', 'Kutai Kartanegara', 'Penajam Paser Utara', 'Kutai Timur', 'Paser', 'Berau', 'Mahakam Ulu'],
            datasets: [{
                label: 'Sertifikat',
                data: [387, 324, 168, 112, 78, 64, 52, 41, 27],
                backgroundColor: 'rgba(245, 158, 11, 0.75)',
                borderColor: 'transparent',
                borderRadius: 5,
                borderSkipped: false,
                barThickness: isMobile ? 16 : 22,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: { label: ctx => `${ctx.parsed.x} sertifikat` }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { weight: '600', size: isMobile ? 9 : 10 }, color: '#475569' }
                }
            }
        }
    });


    // ── 4a. LPH & Auditor (Line YtoY) ──
    new Chart(document.getElementById('chartLPHLine'), {
        type: 'line',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024', '2025*'],
            datasets: [
                {
                    label: 'Jumlah LPH',
                    data: [3, 5, 7, 9, 11, 14],
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.06)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#8b5cf6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: isMobile ? 3 : 4,
                },
                {
                    label: 'Auditor',
                    data: [18, 34, 52, 73, 98, 124],
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6, 182, 212, 0.06)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: isMobile ? 3 : 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 10,
                        boxHeight: 10,
                        borderRadius: 3,
                        useBorderRadius: true,
                        padding: isMobile ? 10 : 16,
                        font: { weight: '600', size: isMobile ? 10 : 11 }
                    }
                },
                tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 10 }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: gridColor, drawBorder: false } }
            }
        }
    });


    // ── 4b. Jenis LPH (Doughnut) ──
    new Chart(document.getElementById('chartLPHPie'), {
        type: 'doughnut',
        data: {
            labels: ['LPH UMK', 'LPH Pemerintah', 'LPH Blended'],
            datasets: [{
                data: [8, 3, 3],
                backgroundColor: ['rgba(139, 92, 246, 0.8)', 'rgba(6, 182, 212, 0.8)', 'rgba(245, 158, 11, 0.8)'],
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        boxHeight: 10,
                        borderRadius: 3,
                        useBorderRadius: true,
                        padding: isMobile ? 12 : 20,
                        font: { weight: '600', size: isMobile ? 10 : 11 }
                    }
                },
                tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 10 }
            }
        }
    });


    // ── 5a. RPH Halal by Pengelola (Doughnut) ──
    new Chart(document.getElementById('chartRPHPie'), {
        type: 'doughnut',
        data: {
            labels: ['Pemerintah', 'Swasta', 'BUMD'],
            datasets: [{
                data: [5, 7, 2],
                backgroundColor: ['rgba(239, 68, 68, 0.8)', 'rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        boxHeight: 10,
                        borderRadius: 3,
                        useBorderRadius: true,
                        padding: isMobile ? 12 : 20,
                        font: { weight: '600', size: isMobile ? 10 : 11 }
                    }
                },
                tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 10 }
            }
        }
    });


    // ── 6. Sertifikasi Halal UMK (Multi-Line) ──
    new Chart(document.getElementById('chartUMK'), {
        type: 'line',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024', '2025*'],
            datasets: [
                {
                    label: 'SH Reguler',
                    data: [210, 680, 1420, 2890, 4350, 5640],
                    borderColor: '#14b8a6',
                    backgroundColor: 'rgba(20, 184, 166, 0.06)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#14b8a6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: isMobile ? 3 : 4,
                },
                {
                    label: 'Self-Declare',
                    data: [0, 0, 180, 620, 1840, 3200],
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.06)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: isMobile ? 3 : 4,
                },
                {
                    label: 'Total',
                    data: [210, 680, 1600, 3510, 6190, 8840],
                    borderColor: '#0f172a',
                    borderDash: [6, 4],
                    backgroundColor: 'transparent',
                    fill: false,
                    tension: 0.4,
                    borderWidth: 1.5,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 10,
                        boxHeight: 10,
                        borderRadius: 3,
                        useBorderRadius: true,
                        padding: isMobile ? 10 : 16,
                        font: { weight: '600', size: isMobile ? 10 : 11 }
                    }
                },
                tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 10 }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { callback: v => (v / 1000) + 'K' }
                }
            }
        }
    });

});
</script>

@endsection
