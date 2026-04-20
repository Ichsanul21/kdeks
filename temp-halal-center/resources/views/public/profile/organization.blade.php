@extends('layouts.app')

@section('title', 'Struktur Organisasi - KDEKS Kalimantan Timur')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#f8fafb] pb-36 pt-28">
    {{-- Background --}}
    <div class="pointer-events-none absolute inset-0 z-0">
        <div class="absolute -top-32 left-[15%] h-[500px] w-[500px] rounded-full bg-emerald-200/20 blur-[120px]"></div>
        <div class="absolute top-[40%] -right-24 h-[600px] w-[600px] rounded-full bg-cyan-200/10 blur-[140px]"></div>
        <div class="absolute -bottom-32 left-[30%] h-[400px] w-[400px] rounded-full bg-amber-100/10 blur-[120px]"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-6xl px-6">

        {{-- ===== HEADER ===== --}}
        <div class="mb-24 text-center">
            <h1 class="font-heading text-4xl font-extrabold tracking-tight text-slate-900 md:text-5xl">
                Struktur <span class="text-gradient">Organisasi</span>
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-slate-500">
                Susunan kepengurusan Komite Daerah Ekonomi dan Keuangan Syariah Kalimantan Timur
            </p>
        </div>

        {{-- =========================================== --}}
        {{--  SECTION 1 : MANAJEMEN EKSEKUTIF            --}}
        {{-- =========================================== --}}
        <div class="org-section mb-28">
            <div class="mb-12 mt-8 text-center">
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Manajemen Eksekutif</h2>
            </div>

            <div class="org-tree relative">
                {{-- SVG Connector Overlay --}}
                <div class="org-connector absolute inset-0 pointer-events-none z-0"
                     data-line="#e2e8f0" data-dot="#94a3b8">
                    <svg class="w-full h-full"></svg>
                </div>

                {{-- Dirut --}}
                <div class="relative z-10 flex justify-center anim-up" style="animation-delay:.1s">
                    <a href="{{ route('profile.member', 'dirut') }}" class="parent-link org-parent-card group block">
                        <div class="relative flex flex-col items-center rounded-2xl border border-slate-100 bg-white px-4 py-5 shadow-sm transition-all duration-400 hover:-translate-y-1.5 hover:shadow-md hover:shadow-emerald-100/40 hover:border-emerald-200/80">
                            <div class="absolute -top-px left-8 right-8 h-px overflow-hidden rounded-full">
                                <div class="h-full w-full bg-gradient-to-r from-transparent via-emerald-400 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-60"></div>
                            </div>
                            {{-- Badge lencana kiri atas --}}
                            <div class="absolute -top-2.5 -left-2.5 z-10 flex h-9 w-9 items-center justify-center rounded-xl border-[2.5px] border-white bg-emerald-50 shadow-lg shadow-slate-200/50 transition-transform duration-400 group-hover:rotate-[-6deg]">
                                <i data-lucide="shield-check" class="h-4 w-4 text-emerald-500"></i>
                            </div>
                            {{-- Placeholder foto profil --}}
                            <div class="mt-1 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100/80 ring-4 ring-emerald-50/80 transition-all duration-400 group-hover:ring-emerald-100/80">
                                <i data-lucide="user" class="h-7 w-7 text-slate-300 transition-transform duration-300 group-hover:scale-110"></i>
                            </div>
                            <div class="mt-3 text-center">
                                <h3 class="text-sm font-bold text-slate-800">Nama Lengkap</h3>
                                <p class="mt-1 text-[8px] font-bold uppercase tracking-[0.15em] text-slate-400">Direktur Utama</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Directors Grid --}}
                <div class="org-grid mt-14 anim-fade relative z-10" style="animation-delay:.3s">
                    @php
                        $directors = [
                            ['title' => 'Industri Produk Halal',   'icon' => 'package',          'color' => 'emerald'],
                            ['title' => 'Jasa Keuangan Syariah',   'icon' => 'landmark',         'color' => 'blue'],
                            ['title' => 'Keuangan Sosial Syariah', 'icon' => 'heart-handshake',  'color' => 'rose'],
                            ['title' => 'Bisnis & Kewirausahaan Syariah',  'icon' => 'briefcase',        'color' => 'amber'],
                            ['title' => 'Infrastruktur Ekosistem Syariah', 'icon' => 'server',           'color' => 'indigo'],
                        ];
                    @endphp
                    @foreach($directors as $i => $d)
                        <div class="anim-up" style="animation-delay:{{ ($i+4)*.08 }}s">
                            {{-- Director Card --}}
                            <a href="{{ route('profile.member', 'dir-'.$i) }}" class="child-card group block">
                                <div class="relative flex flex-col items-center rounded-2xl border border-slate-100 bg-white px-4 py-5 shadow-sm transition-all duration-400 hover:-translate-y-1.5 hover:shadow-md hover:shadow-{{ $d['color'] }}-100/40 hover:border-{{ $d['color'] }}-200/80">
                                    <div class="absolute -top-px left-8 right-8 h-px overflow-hidden rounded-full">
                                        <div class="h-full w-full bg-gradient-to-r from-transparent via-{{ $d['color'] }}-400 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-60"></div>
                                    </div>
                                    {{-- Badge lencana kiri atas --}}
                                    <div class="absolute -top-2.5 -left-2.5 z-10 flex h-9 w-9 items-center justify-center rounded-xl border-[2.5px] border-white bg-{{ $d['color'] }}-50 shadow-lg shadow-slate-200/50 transition-transform duration-400 group-hover:rotate-[-6deg]">
                                        <i data-lucide="{{ $d['icon'] }}" class="h-4 w-4 text-{{ $d['color'] }}-500"></i>
                                    </div>
                                    {{-- Placeholder foto profil --}}
                                    <div class="mt-1 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100/80 ring-4 ring-{{ $d['color'] }}-50/80 transition-all duration-400 group-hover:ring-{{ $d['color'] }}-100/80">
                                        <i data-lucide="user" class="h-7 w-7 text-slate-300 transition-transform duration-300 group-hover:scale-110"></i>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <h3 class="text-sm font-bold text-slate-800">Nama Lengkap</h3>
                                        <p class="mt-1 text-[8px] font-bold uppercase tracking-[0.15em] text-slate-400">Direktur</p>
                                        <p class="mt-2 line-clamp-2 text-[10px] font-semibold leading-snug text-{{ $d['color'] }}-500 uppercase tracking-tight">{{ $d['title'] }}</p>
                                    </div>
                                </div>
                            </a>

                            {{-- Connector: Director → Deputy --}}
                            <div class="relative flex justify-center h-5">
                                <div class="w-px h-full bg-{{ $d['color'] }}-100"></div>
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-1.5 w-1.5 rounded-full bg-{{ $d['color'] }}-200 ring-1 ring-white"></div>
                            </div>

                            {{-- Deputy Card --}}
                            <a href="{{ route('profile.member', 'wakil-dir-'.$i) }}" class="group block">
                                <div class="flex flex-col items-center rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-sm hover:border-{{ $d['color'] }}-200/60">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white ring-1 ring-{{ $d['color'] }}-100/50">
                                        <i data-lucide="user" class="h-3.5 w-3.5 text-{{ $d['color'] }}-300"></i>
                                    </div>
                                    <div class="mt-1.5 text-center">
                                        <h3 class="text-[10px] font-semibold text-slate-600">Nama Lengkap</h3>
                                        <p class="mt-0.5 text-[7px] font-bold uppercase tracking-[0.12em] text-slate-400">Wakil Direktur</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- =========================================== --}}
        {{--  SECTION 2 : SEKRETARIAT                     --}}
        {{-- =========================================== --}}
        <div class="org-section">
            <div class="mb-12 mt-8 text-center">
                <h2 class="text-lg font-bold tracking-tight text-slate-900">Sekretariat</h2>
            </div>

            <div class="org-tree relative">
                {{-- SVG Connector Overlay --}}
                <div class="org-connector absolute inset-0 pointer-events-none z-0"
                     data-line="#e2e8f0" data-dot="#94a3b8">
                    <svg class="w-full h-full"></svg>
                </div>

                {{-- Ketua Sekretariat --}}
                <div class="relative z-10 flex justify-center anim-up" style="animation-delay:.7s">
                    <a href="{{ route('profile.member', 'sekre-ketua') }}" class="parent-link org-parent-card group block">
                        <div class="relative flex flex-col items-center rounded-2xl border border-slate-100 bg-white px-4 py-5 shadow-sm transition-all duration-400 hover:-translate-y-1.5 hover:shadow-md hover:shadow-cyan-100/40 hover:border-cyan-200/80">
                            <div class="absolute -top-px left-8 right-8 h-px overflow-hidden rounded-full">
                                <div class="h-full w-full bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-60"></div>
                            </div>
                            {{-- Badge lencana kiri atas --}}
                            <div class="absolute -top-2.5 -left-2.5 z-10 flex h-9 w-9 items-center justify-center rounded-xl border-[2.5px] border-white bg-cyan-50 shadow-lg shadow-slate-200/50 transition-transform duration-400 group-hover:rotate-[-6deg]">
                                <i data-lucide="stamp" class="h-4 w-4 text-cyan-500"></i>
                            </div>
                            {{-- Placeholder foto profil --}}
                            <div class="mt-1 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100/80 ring-4 ring-cyan-50/80 transition-all duration-400 group-hover:ring-cyan-100/80">
                                <i data-lucide="user" class="h-7 w-7 text-slate-300 transition-transform duration-300 group-hover:scale-110"></i>
                            </div>
                            <div class="mt-3 text-center">
                                <h3 class="text-sm font-bold text-slate-800">Nama Lengkap</h3>
                                <p class="mt-1 text-[8px] font-bold uppercase tracking-[0.15em] text-slate-400">Ketua Sekretariat</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Sub-units Grid --}}
                <div class="org-grid mt-14 anim-fade relative z-10" style="animation-delay:.9s">
                    @php
                        $subUnits = [
                            ['title' => 'Tata Usaha & Administrasi', 'icon' => 'file-text',    'color' => 'cyan'],
                            ['title' => 'Keuangan & Perencanaan',     'icon' => 'calculator',   'color' => 'teal'],
                            ['title' => 'Sumber Daya Manusia',        'icon' => 'users-round',  'color' => 'sky'],
                        ];
                    @endphp
                    @foreach($subUnits as $i => $s)
                        <div class="anim-up" style="animation-delay:{{ ($i+10)*.08 }}s">
                            {{-- Kepala Bidang Card --}}
                            <a href="{{ route('profile.member', 'sekre-'.$i) }}" class="child-card group block">
                                <div class="relative flex flex-col items-center rounded-2xl border border-slate-100 bg-white px-4 py-5 shadow-sm transition-all duration-400 hover:-translate-y-1.5 hover:shadow-md hover:shadow-{{ $s['color'] }}-100/40 hover:border-{{ $s['color'] }}-200/80">
                                    <div class="absolute -top-px left-8 right-8 h-px overflow-hidden rounded-full">
                                        <div class="h-full w-full bg-gradient-to-r from-transparent via-{{ $s['color'] }}-300 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-60"></div>
                                    </div>
                                    {{-- Badge lencana kiri atas --}}
                                    <div class="absolute -top-2.5 -left-2.5 z-10 flex h-9 w-9 items-center justify-center rounded-xl border-[2.5px] border-white bg-{{ $s['color'] }}-50 shadow-lg shadow-slate-200/50 transition-transform duration-400 group-hover:rotate-[-6deg]">
                                        <i data-lucide="{{ $s['icon'] }}" class="h-4 w-4 text-{{ $s['color'] }}-500"></i>
                                    </div>
                                    {{-- Placeholder foto profil --}}
                                    <div class="mt-1 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100/80 ring-4 ring-{{ $s['color'] }}-50/80 transition-all duration-400 group-hover:ring-{{ $s['color'] }}-100/80">
                                        <i data-lucide="user" class="h-7 w-7 text-slate-300 transition-transform duration-300 group-hover:scale-110"></i>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <h3 class="text-sm font-bold text-slate-800">Nama Lengkap</h3>
                                        <p class="mt-1 text-[8px] font-bold uppercase tracking-[0.15em] text-slate-400">Kepala Bidang</p>
                                        <p class="mt-2 line-clamp-2 text-[10px] font-semibold leading-snug text-{{ $s['color'] }}-500 uppercase tracking-tight">{{ $s['title'] }}</p>
                                    </div>
                                </div>
                            </a>

                            {{-- Connector: Kepala Bidang → Wakil --}}
                            <div class="relative flex justify-center h-5">
                                <div class="w-px h-full bg-{{ $s['color'] }}-100"></div>
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-1.5 w-1.5 rounded-full bg-{{ $s['color'] }}-200 ring-1 ring-white"></div>
                            </div>

                            {{-- Wakil Kepala Bidang Card --}}
                            <a href="{{ route('profile.member', 'wakil-sekre-'.$i) }}" class="group block">
                                <div class="flex flex-col items-center rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-sm hover:border-{{ $s['color'] }}-200/60">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white ring-1 ring-{{ $s['color'] }}-100/50">
                                        <i data-lucide="user" class="h-3.5 w-3.5 text-{{ $s['color'] }}-300"></i>
                                    </div>
                                    <div class="mt-1.5 text-center">
                                        <h3 class="text-[10px] font-semibold text-slate-600">Nama Lengkap</h3>
                                        <p class="mt-0.5 text-[7px] font-bold uppercase tracking-[0.12em] text-slate-400">Wakil Kepala Bidang</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ===== LEGEND ===== --}}
        <div class="mt-16 flex flex-col items-center gap-3">
            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-1.5">
                <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-slate-300">Eksekutif</span>
                @foreach($directors as $d)
                    <div class="flex items-center gap-1.5">
                        <div class="h-1.5 w-1.5 rounded-full bg-{{ $d['color'] }}-400"></div>
                        <span class="text-[9px] font-medium text-slate-400">{{ $d['title'] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-1.5">
                <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-slate-300">Sekretariat</span>
                @foreach($subUnits as $s)
                    <div class="flex items-center gap-1.5">
                        <div class="h-1.5 w-1.5 rounded-full bg-{{ $s['color'] }}-300"></div>
                        <span class="text-[9px] font-medium text-slate-400">{{ $s['title'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="mt-8 text-center text-[10px] font-medium tracking-wide text-slate-300">
            Dokumen ini bersifat resmi dan diterbitkan oleh KDEKS Kalimantan Timur
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    drawConnectors();
    setTimeout(drawConnectors, 1500);
    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(drawConnectors, 120);
    });
});

function drawConnectors() {
    document.querySelectorAll('.org-tree').forEach(function (tree) {
        var connector = tree.querySelector('.org-connector');
        var parent    = tree.querySelector('.parent-link');
        var grid      = tree.querySelector('.org-grid');
        var cards     = grid.querySelectorAll('.child-card');

        if (!parent || !cards.length) return;

        var svg       = connector.querySelector('svg');
        var treeR     = tree.getBoundingClientRect();
        var parentR   = parent.getBoundingClientRect();
        var px        = parentR.left + parentR.width / 2 - treeR.left;
        var parentBot = parentR.bottom - treeR.top;
        var lineClr   = connector.dataset.line || '#e2e8f0';
        var dotClr    = connector.dataset.dot  || '#94a3b8';

        var positions = [];
        cards.forEach(function (c) {
            var r = c.getBoundingClientRect();
            positions.push({
                cx:  r.left + r.width / 2 - treeR.left,
                top: r.top - treeR.top
            });
        });

        var rows = [];
        var cur  = [positions[0]];
        for (var i = 1; i < positions.length; i++) {
            if (Math.abs(positions[i].top - cur[0].top) < 10) {
                cur.push(positions[i]);
            } else {
                rows.push(cur);
                cur = [positions[i]];
            }
        }
        rows.push(cur);

        var html = '';

        html += '<circle cx="' + px + '" cy="' + parentBot + '" r="3.5" fill="' + dotClr + '" stroke="#fff" stroke-width="2"/>';

        var primaryBarY = rows[0][0].top - 32;
        html += '<line x1="' + px + '" y1="' + parentBot + '" x2="' + px + '" y2="' + primaryBarY + '" stroke="' + lineClr + '" stroke-width="1.5"/>';

        var allX = positions.map(function (p) { return p.cx; });
        var minX = Math.min.apply(null, allX);
        var maxX = Math.max.apply(null, allX);
        html += '<line x1="' + minX + '" y1="' + primaryBarY + '" x2="' + maxX + '" y2="' + primaryBarY + '" stroke="' + lineClr + '" stroke-width="1.5"/>';

        positions.forEach(function (pos) {
            html += '<line x1="' + pos.cx + '" y1="' + primaryBarY + '" x2="' + pos.cx + '" y2="' + pos.top + '" stroke="' + lineClr + '" stroke-width="1.5"/>';
            html += '<circle cx="' + pos.cx + '" cy="' + primaryBarY + '" r="2.5" fill="' + dotClr + '" stroke="#fff" stroke-width="1.5"/>';
            html += '<circle cx="' + pos.cx + '" cy="' + pos.top + '" r="2" fill="' + dotClr + '"/>';
        });

        svg.innerHTML = html;
    });
}
</script>

<style>
    .text-gradient {
        @apply bg-gradient-to-r from-emerald-600 via-teal-500 to-cyan-600 bg-clip-text text-transparent;
    }

    /* Ukuran card — digunakan bersama oleh parent card & grid items */
    .org-parent-card,
    .org-grid > * {
        width: calc(33.333% - 1rem);
        min-width: 190px;
    }

    .org-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem;
    }

    @media (max-width: 767px) {
        .org-parent-card,
        .org-grid > * { width: calc(50% - 0.75rem); }
    }
    @media (max-width: 480px) {
        .org-parent-card,
        .org-grid > * { width: 100%; }
    }

    .anim-up   { animation: slideUp .7s cubic-bezier(.16,1,.3,1) both; }
    .anim-fade { animation: fadeIn  .5s ease both; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    @media (prefers-reduced-motion: reduce) {
        .anim-up, .anim-fade { animation: none !important; opacity: 1 !important; }
        .parent-link > div,
        .org-grid a > div    { transition: none !important; }
    }
</style>
@endsection
