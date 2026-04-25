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

    <div class="relative z-10 mx-auto max-w-7xl px-6">

        {{-- ===== HEADER ===== --}}
        <div class="mb-12 text-center">
            <h1 class="font-heading text-4xl font-extrabold tracking-tight text-slate-900 md:text-5xl">
                Struktur <span class="text-gradient">Organisasi</span>
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-slate-500">
                Susunan kepengurusan Komite Nasional Ekonomi dan Keuangan Syariah serta Komite Daerah Ekonomi dan Keuangan Syariah Kalimantan Timur
            </p>
        </div>

        {{-- =========================================== --}}
        {{--  GAMBAR THUMBNAIL KESELURUHAN              --}}
        {{-- =========================================== --}}
        <div class="mb-14 anim-up" style="animation-delay:.15s">
            <div class="relative mx-auto max-w-4xl">
                <div class="pointer-events-none absolute -inset-3 z-0 rounded-3xl">
                    <div class="absolute left-0 top-0 h-8 w-8 rounded-tl-3xl border-l-2 border-t-2 border-emerald-200/60"></div>
                    <div class="absolute right-0 top-0 h-8 w-8 rounded-tr-3xl border-r-2 border-t-2 border-emerald-200/60"></div>
                    <div class="absolute bottom-0 left-0 h-8 w-8 rounded-bl-3xl border-b-2 border-l-2 border-emerald-200/60"></div>
                    <div class="absolute bottom-0 right-0 h-8 w-8 rounded-br-3xl border-b-2 border-r-2 border-emerald-200/60"></div>
                </div>
                <div class="relative z-10 overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-3 shadow-lg shadow-slate-200/40 md:p-5">
                    <div class="pointer-events-none absolute inset-x-0 top-0 h-20 bg-gradient-to-b from-emerald-50/40 to-transparent"></div>
                    @if($setting?->organization_structure_image_path)
                        <img
                            src="{{ asset('storage/'.$setting->organization_structure_image_path) }}"
                            alt="Struktur Organisasi KNEKS & KDEKS"
                            class="relative w-full rounded-lg object-contain"
                            loading="lazy"
                        >
                    @else
                        <div class="flex items-center justify-center py-20 bg-slate-50 rounded-lg border-2 border-dashed border-slate-200">
                            <p class="text-slate-400 font-bold uppercase tracking-widest">Gambar struktur belum tersedia</p>
                        </div>
                    @endif
                </div>
                <div class="mt-4 flex items-center justify-center gap-2">
                    <div class="h-px max-w-[60px] flex-1 bg-gradient-to-r from-transparent to-slate-200"></div>
                    <p class="text-[10px] font-medium uppercase tracking-[0.15em] text-slate-300">Gambaran Struktur Keseluruhan</p>
                    <div class="h-px max-w-[60px] flex-1 bg-gradient-to-l from-transparent to-slate-200"></div>
                </div>
            </div>
        </div>

        {{-- DIVIDER 1 --}}
        <div class="relative mb-14 flex items-center justify-center">
            <div class="h-px w-full max-w-xs bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
            <div class="absolute flex h-10 w-10 items-center justify-center rounded-full border border-slate-200/80 bg-white shadow-sm">
                <i data-lucide="chevrons-down" class="h-4 w-4 text-slate-300"></i>
            </div>
        </div>

        {{-- =========================================== --}}
        {{--  KNEKS NASIONAL — Struktur Kartu            --}}
        {{-- =========================================== --}}
        @if($kneksExecutive)
            <div class="org-section mb-14">
                <div class="mb-10 text-center">
                    <div class="mx-auto mb-4 flex w-fit items-center gap-2 rounded-full border border-emerald-200/60 bg-emerald-50/70 px-4 py-1.5">
                        <div class="flex h-5 w-5 items-center justify-center rounded-md bg-emerald-100">
                            <i data-lucide="landmark" class="h-3 w-3 text-emerald-600"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-700">Nasional</span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">Struktur Organisasi <span class="text-gradient">KNEKS</span></h2>
                    <p class="mx-auto mt-2 max-w-md text-xs leading-relaxed text-slate-400">Komite Nasional Ekonomi dan Keuangan Syariah</p>
                </div>

                <div class="org-tree relative">
                    <div class="org-connector absolute inset-0 pointer-events-none z-0"
                         data-line="#e2e8f0" data-dot="#94a3b8">
                        <svg class="w-full h-full"></svg>
                    </div>

                    {{-- Executive Card --}}
                    <div class="relative z-10 flex justify-center anim-up" style="animation-delay:.1s">
                        <a href="{{ route('profile.member', $kneksExecutive->id) }}" class="parent-link org-parent-card group block">
                            <div class="relative flex flex-col items-center rounded-2xl border border-slate-100 bg-white px-4 py-5 shadow-sm transition-all duration-400 hover:-translate-y-1.5 hover:shadow-md hover:shadow-emerald-100/40 hover:border-emerald-200/80">
                                <div class="absolute -top-px left-8 right-8 h-px overflow-hidden rounded-full">
                                    <div class="h-full w-full bg-gradient-to-r from-transparent via-emerald-400 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-60"></div>
                                </div>
                                <div class="absolute -top-2.5 -left-2.5 z-10 flex h-9 w-9 items-center justify-center rounded-xl border-[2.5px] border-white bg-emerald-50 shadow-lg shadow-slate-200/50 transition-transform duration-400 group-hover:rotate-[-6deg]">
                                    <i data-lucide="shield-check" class="h-4 w-4 text-emerald-500"></i>
                                </div>
                                <div class="mt-1 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100/80 transition-all duration-300 group-hover:scale-110">
                                    @if($kneksExecutive->photo_path)
                                        <img src="{{ asset('storage/'.$kneksExecutive->photo_path) }}" class="h-full w-full rounded-full object-cover">
                                    @else
                                        <i data-lucide="user" class="h-7 w-7 text-slate-300"></i>
                                    @endif
                                </div>
                                <div class="mt-3 text-center">
                                    <h3 class="text-sm font-bold text-slate-800">{{ $kneksExecutive->name }}</h3>
                                    <p class="mt-1 text-[8px] font-bold uppercase tracking-[0.15em] text-slate-400">{{ $kneksExecutive->role_title }}</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Children Grid --}}
                    <div class="org-grid org-grid-5 mt-14 anim-fade relative z-10" style="animation-delay:.3s">
                        @foreach($kneksExecutive->children as $i => $child)
                            @php
                                $colors = ['emerald', 'blue', 'rose', 'amber', 'indigo'];
                                $c = $colors[$i % 5];
                            @endphp
                            <div class="anim-up" style="animation-delay:{{ ($i+4)*.08 }}s">
                                {{-- Director Card --}}
                                @if($child->name === 'Belum Ditentukan')
                                    <div class="child-card relative flex flex-col items-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/30 px-3 py-5 opacity-50">
                                        <div class="mt-1 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100/60">
                                            <i data-lucide="user-x" class="h-6 w-6 text-slate-300"></i>
                                        </div>
                                        <div class="mt-2.5 text-center">
                                            <h3 class="text-xs font-semibold text-slate-400 leading-tight">Belum Ditentukan</h3>
                                            <p class="mt-0.5 text-[7px] font-bold uppercase tracking-[0.15em] text-slate-300">Direktur</p>
                                            <p class="mt-2 line-clamp-2 text-[9px] font-semibold leading-snug text-slate-400 uppercase tracking-tight">{{ str_replace('Direktur ', '', $child->role_title) }}</p>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('profile.member', $child->id) }}" class="child-card group block">
                                        <div class="relative flex flex-col items-center rounded-2xl border border-slate-100 bg-white px-3 py-5 shadow-sm transition-all duration-400 hover:-translate-y-1.5 hover:shadow-md hover:shadow-{{ $c }}-100/40 hover:border-{{ $c }}-200/80">
                                            <div class="absolute -top-px left-6 right-6 h-px overflow-hidden rounded-full">
                                                <div class="h-full w-full bg-gradient-to-r from-transparent via-{{ $c }}-400 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-60"></div>
                                            </div>
                                            <div class="absolute -top-2.5 -left-2.5 z-10 flex h-8 w-8 items-center justify-center rounded-xl border-[2.5px] border-white bg-{{ $c }}-50 shadow-lg shadow-slate-200/50 transition-transform duration-400 group-hover:rotate-[-6deg]">
                                                <i data-lucide="user" class="h-3.5 w-3.5 text-{{ $c }}-500"></i>
                                            </div>
                                            <div class="mt-1 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100/80 transition-all duration-300 group-hover:scale-110">
                                                @if($child->photo_path)
                                                    <img src="{{ asset('storage/'.$child->photo_path) }}" class="h-full w-full rounded-full object-cover">
                                                @else
                                                    <i data-lucide="user" class="h-6 w-6 text-slate-300"></i>
                                                @endif
                                            </div>
                                            <div class="mt-2.5 text-center">
                                                <h3 class="text-xs font-bold text-slate-800 leading-tight">{{ $child->name }}</h3>
                                                <p class="mt-0.5 text-[7px] font-bold uppercase tracking-[0.15em] text-slate-400">Direktur</p>
                                                <p class="mt-2 line-clamp-2 text-[9px] font-semibold leading-snug text-{{ $c }}-500 uppercase tracking-tight">{{ str_replace('Direktur ', '', $child->role_title) }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                {{-- Subs Timeline --}}
                                @if($child->children->isNotEmpty())
                                    <div class="relative mt-3 pl-4">
                                        <div class="absolute left-[5px] top-1 bottom-1 w-px bg-{{ $c }}-100"></div>
                                        @foreach($child->children as $sub)
                                            <div class="relative py-2">
                                                <div class="absolute -left-[11px] top-1/2 -translate-y-1/2 h-[5px] w-[5px] rounded-full border-2 border-white bg-{{ $c }}-300"></div>
                                                <a href="{{ route('profile.member', $sub->id) }}" class="group/sub flex items-center gap-3 rounded-xl border border-slate-200/60 bg-white/60 px-3 py-3 transition-all hover:border-{{ $c }}-200/60 hover:shadow-sm hover:bg-white">
                                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-{{ $c }}-50">
                                                        <i data-lucide="user" class="h-4 w-4 text-{{ $c }}-400"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[10px] font-semibold text-slate-700 leading-snug">{{ $sub->name }}</p>
                                                        <p class="mt-0.5 text-[8px] font-medium text-slate-400 leading-snug line-clamp-2">{{ $sub->role_title }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-center gap-2">
                    <div class="h-px max-w-[60px] flex-1 bg-gradient-to-r from-transparent to-slate-200"></div>
                    <p class="text-[10px] font-medium uppercase tracking-[0.15em] text-slate-300">Struktur Organisasi KNEKS</p>
                    <div class="h-px max-w-[60px] flex-1 bg-gradient-to-l from-transparent to-slate-200"></div>
                </div>
            </div>
        @endif

        {{-- DIVIDER 2 --}}
        <div class="relative mb-14 flex items-center justify-center">
            <div class="h-px w-full max-w-xs bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
            <div class="absolute flex h-10 w-10 items-center justify-center rounded-full border border-slate-200/80 bg-white shadow-sm">
                <i data-lucide="chevrons-down" class="h-4 w-4 text-slate-300"></i>
            </div>
        </div>

        {{-- =========================================== --}}
        {{--  KDEKS DAERAH — Struktur Kartu              --}}
        {{-- =========================================== --}}
        @if($kdeksExecutive)
            <div class="org-section mb-14">
                <div class="mb-10 text-center">
                    <div class="mx-auto mb-4 flex w-fit items-center gap-2 rounded-full border border-cyan-200/60 bg-cyan-50/70 px-4 py-1.5">
                        <div class="flex h-5 w-5 items-center justify-center rounded-md bg-cyan-100">
                            <i data-lucide="map-pin" class="h-3 w-3 text-cyan-600"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-700">Daerah</span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">Struktur Organisasi <span class="text-gradient">KDEKS</span></h2>
                    <p class="mx-auto mt-2 max-w-md text-xs leading-relaxed text-slate-400">Komite Daerah Ekonomi dan Keuangan Syariah Kalimantan Timur</p>
                </div>

                <div class="org-tree relative">
                    <div class="org-connector absolute inset-0 pointer-events-none z-0"
                         data-line="#e2e8f0" data-dot="#94a3b8">
                        <svg class="w-full h-full"></svg>
                    </div>

                    {{-- Executive Card --}}
                    <div class="relative z-10 flex justify-center anim-up" style="animation-delay:.1s">
                        <a href="{{ route('profile.member', $kdeksExecutive->id) }}" class="parent-link org-parent-card group block">
                            <div class="relative flex flex-col items-center rounded-2xl border border-slate-100 bg-white px-4 py-5 shadow-sm transition-all duration-400 hover:-translate-y-1.5 hover:shadow-md hover:shadow-emerald-100/40 hover:border-emerald-200/80">
                                <div class="absolute -top-px left-8 right-8 h-px overflow-hidden rounded-full">
                                    <div class="h-full w-full bg-gradient-to-r from-transparent via-emerald-400 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-60"></div>
                                </div>
                                <div class="absolute -top-2.5 -left-2.5 z-10 flex h-9 w-9 items-center justify-center rounded-xl border-[2.5px] border-white bg-emerald-50 shadow-lg shadow-slate-200/50 transition-transform duration-400 group-hover:rotate-[-6deg]">
                                    <i data-lucide="shield-check" class="h-4 w-4 text-emerald-500"></i>
                                </div>
                                <div class="mt-1 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100/80 transition-all duration-300 group-hover:scale-110">
                                    @if($kdeksExecutive->photo_path)
                                        <img src="{{ asset('storage/'.$kdeksExecutive->photo_path) }}" class="h-full w-full rounded-full object-cover">
                                    @else
                                        <i data-lucide="user" class="h-7 w-7 text-slate-300"></i>
                                    @endif
                                </div>
                                <div class="mt-3 text-center">
                                    <h3 class="text-sm font-bold text-slate-800">{{ $kdeksExecutive->name }}</h3>
                                    <p class="mt-1 text-[8px] font-bold uppercase tracking-[0.15em] text-slate-400">{{ $kdeksExecutive->role_title }}</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Children Grid --}}
                    <div class="org-grid org-grid-5 mt-14 anim-fade relative z-10" style="animation-delay:.3s">
                        @foreach($kdeksExecutive->children as $i => $child)
                            @php
                                $colors = ['emerald', 'blue', 'rose', 'amber', 'indigo'];
                                $c = $colors[$i % 5];
                            @endphp
                            <div class="anim-up" style="animation-delay:{{ ($i+4)*.08 }}s">
                                {{-- Director Card --}}
                                <a href="{{ route('profile.member', $child->id) }}" class="child-card group block">
                                    <div class="relative flex flex-col items-center rounded-2xl border border-slate-100 bg-white px-3 py-5 shadow-sm transition-all duration-400 hover:-translate-y-1.5 hover:shadow-md hover:shadow-{{ $c }}-100/40 hover:border-{{ $c }}-200/80">
                                        <div class="absolute -top-px left-6 right-6 h-px overflow-hidden rounded-full">
                                            <div class="h-full w-full bg-gradient-to-r from-transparent via-{{ $c }}-400 to-transparent opacity-0 transition-opacity duration-400 group-hover:opacity-60"></div>
                                        </div>
                                        <div class="absolute -top-2.5 -left-2.5 z-10 flex h-8 w-8 items-center justify-center rounded-xl border-[2.5px] border-white bg-{{ $c }}-50 shadow-lg shadow-slate-200/50 transition-transform duration-400 group-hover:rotate-[-6deg]">
                                            <i data-lucide="user" class="h-3.5 w-3.5 text-{{ $c }}-500"></i>
                                        </div>
                                        <div class="mt-1 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100/80 transition-all duration-300 group-hover:scale-110">
                                            @if($child->photo_path)
                                                <img src="{{ asset('storage/'.$child->photo_path) }}" class="h-full w-full rounded-full object-cover">
                                            @else
                                                <i data-lucide="user" class="h-6 w-6 text-slate-300"></i>
                                            @endif
                                        </div>
                                        <div class="mt-2.5 text-center">
                                            <h3 class="text-xs font-bold text-slate-800 leading-tight">{{ $child->name }}</h3>
                                            <p class="mt-0.5 text-[7px] font-bold uppercase tracking-[0.15em] text-slate-400">Direktur</p>
                                            <p class="mt-2 line-clamp-2 text-[9px] font-semibold leading-snug text-{{ $c }}-500 uppercase tracking-tight">{{ str_replace('Direktur ', '', $child->role_title) }}</p>
                                        </div>
                                    </div>
                                </a>

                                {{-- Subs Timeline --}}
                                @if($child->children->isNotEmpty())
                                    <div class="relative mt-3 pl-4">
                                        <div class="absolute left-[5px] top-1 bottom-1 w-px bg-{{ $c }}-100"></div>
                                        @foreach($child->children as $sub)
                                            <div class="relative py-2">
                                                <div class="absolute -left-[11px] top-1/2 -translate-y-1/2 h-[5px] w-[5px] rounded-full border-2 border-white bg-{{ $c }}-300"></div>
                                                <a href="{{ route('profile.member', $sub->id) }}" class="group/sub flex items-center gap-3 rounded-xl border border-slate-200/60 bg-white/60 px-3 py-3 transition-all hover:border-{{ $c }}-200/60 hover:shadow-sm hover:bg-white">
                                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-{{ $c }}-50">
                                                        <i data-lucide="user" class="h-4 w-4 text-{{ $c }}-400"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[10px] font-semibold text-slate-700 leading-snug">{{ $sub->name }}</p>
                                                        <p class="mt-0.5 text-[8px] font-medium text-slate-400 leading-snug line-clamp-2">{{ $sub->role_title }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-center gap-2">
                    <div class="h-px max-w-[60px] flex-1 bg-gradient-to-r from-transparent to-slate-200"></div>
                    <p class="text-[10px] font-medium uppercase tracking-[0.15em] text-slate-300">Struktur Organisasi KDEKS Kalimantan Timur</p>
                    <div class="h-px max-w-[60px] flex-1 bg-gradient-to-l from-transparent to-slate-200"></div>
                </div>
            </div>
        @endif</div>

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

    .org-parent-card {
        width: 220px;
    }

    .org-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem;
    }
    .org-grid > * {
        width: calc(33.333% - 1rem);
        min-width: 190px;
    }

    .org-grid-5 {
        gap: 0.75rem;
    }
    .org-grid-5 > * {
        width: calc(20% - 0.6rem);
        min-width: 160px;
    }

    @media (max-width: 1023px) {
        .org-grid-5 {
            gap: 1rem;
        }
        .org-grid-5 > * {
            width: calc(33.333% - 0.67rem);
            min-width: 170px;
        }
    }
    @media (max-width: 639px) {
        .org-parent-card {
            width: 180px;
        }
        .org-grid > *,
        .org-grid-5 > * {
            width: calc(50% - 0.75rem);
            min-width: 150px;
        }
    }
    @media (max-width: 374px) {
        .org-grid > *,
        .org-grid-5 > * {
            width: 100%;
            min-width: 0;
        }
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
