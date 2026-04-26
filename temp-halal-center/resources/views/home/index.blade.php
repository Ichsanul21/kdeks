@extends('layouts.app')

@section('title', data_get($setting, 'meta_title', 'KDEKS Kalimantan Timur'))

@section('content')
    @if(session('status'))
        <div class="fixed left-1/2 top-24 z-[80] w-full max-w-md -translate-x-1/2 px-4">
            <div class="rounded-2xl border border-emerald-200 bg-white/95 px-5 py-4 text-sm font-semibold text-emerald-700 shadow-lg shadow-emerald-100 backdrop-blur">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @php
        $sehatiErrors = $errors->sehatiRegistration;
        $totalSertifikatTerbit = ($statistics['certificates_total'] ?? 0) + ($statistics['products_total'] ?? 0);
    @endphp

    {{-- ===== BANNER SLIDER — FIXED IMAGE, SCROLLABLE TEXT ===== --}}
    @if($banners->isEmpty())
        <div class="relative w-full bg-slate-900 flex items-center justify-center" style="height:665px;margin-top:64px;">
            <p class="text-white text-2xl font-bold">Banner belum tersedia</p>
        </div>
    @else
        <div id="bannerSlider" class="relative w-full" style="height:665px;margin-top:64px;" onmouseenter="window.bannerPause=true" onmouseleave="window.bannerPause=false">

            {{-- FIXED layer — gambar tetap nempel di viewport --}}
            <div id="bannerFixedBg" class="fixed left-0 right-0 overflow-hidden" style="top:64px;height:665px;z-index:1;">
                <div id="bannerTrack" class="flex h-full transition-transform duration-700 ease-in-out">
                    @foreach($banners as $index => $banner)
                        <div class="w-full flex-shrink-0 relative h-full bg-slate-900">
                            <img src="{{ asset('storage/'.$banner->image_path) }}" alt="{{ $banner->title }}" class="absolute inset-0 h-full w-full object-cover opacity-[70%]">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/30 to-slate-900/10"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SCROLLABLE layer — teks, arrows ikut scroll --}}
            <div class="relative flex h-full flex-col" style="z-index:2;">

                {{-- Parallax text --}}
                <div class="banner-parallax-text mx-auto flex flex-1 max-w-4xl flex-col items-center justify-center px-6 text-center lg:px-8">
                    <h2 class="banner-parallax-title font-heading text-3xl font-extrabold leading-tight text-white md:text-5xl md:leading-[1.15] lg:text-6xl lg:leading-[1.12]">
                        <span id="bannerTitle" class="banner-text-inner inline-block">{{ $banners->first()->title }}</span>
                    </h2>
                    <p class="banner-parallax-subtitle mt-5 max-w-2xl text-base font-medium leading-relaxed text-white/70 md:text-lg md:leading-relaxed">
                        <span id="bannerSubtitle" class="banner-text-inner inline-block">{{ $banners->first()->subtitle }}</span>
                    </p>
                </div>

                {{-- Bottom fade --}}
                <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-emerald-800/15 to-transparent pointer-events-none"></div>

                {{-- Navigation arrows --}}
                <button type="button" onclick="slideBanner(-1)" class="absolute left-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-black/20 text-white/70 backdrop-blur-sm transition hover:bg-black/40 hover:text-white lg:left-8">
                    <i data-lucide="chevron-left" class="h-5 w-5"></i>
                </button>
                <button type="button" onclick="slideBanner(1)" class="absolute right-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-black/20 text-white/70 backdrop-blur-sm transition hover:bg-black/40 hover:text-white lg:right-8">
                    <i data-lucide="chevron-right" class="h-5 w-5"></i>
                </button>

                {{-- Scroll Down Indicator --}}
                <button type="button" id="scrollDownBtn" class="scroll-down-btn absolute bottom-10 left-1/2 z-10 flex -translate-x-1/2 flex-col items-center gap-2 cursor-pointer group">
                    <span class="text-[11px] font-bold uppercase tracking-[0.25em] text-white/60 transition-colors group-hover:text-white">Scroll Down</span>
                    <span class="scroll-down-arrow flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-white/60 transition-all group-hover:border-white/50 group-hover:text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </button>

                {{-- Slide counter --}}
                <div class="absolute bottom-10 right-6 z-10 hidden items-center gap-2 text-sm font-bold text-white/50 lg:flex lg:right-10">
                    <span id="bannerCurrentNum" class="text-white">01</span>
                    <span class="w-8 h-px bg-white/30"></span>
                    <span>{{ str_pad($banners->count(), 2, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- ===== WRAPPER: semua konten di bawah banner ===== --}}
    <div id="pageContent" style="position:relative; z-index:5; background-color:#fff;">

        {{-- ===== HERO SECTION ===== --}}
        <section id="hero" class="relative flex flex-col overflow-hidden bg-white pb-8 pt-8 lg:pb-16">
            <div class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.7),transparent_40%)]"></div>
            <div class="pointer-events-none absolute inset-0 z-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdHRlcm4gaWQ9InNtYWxsR3JpZCIgd2lkdGg9IjEwIiBoZWlnaHQ9IjEwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNMTAgMEwwIDBMMCAxMCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDAsMCwwLDAuMDIpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ1cmwoI3NtYWxsR3JpZCkiLz48cGF0aCBkPSJNNDAgMEwwIDBMMCA0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDAsMCwwLDAuMDQpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-50"></div>

            <div class="relative z-10 mx-auto max-w-7xl px-6">
                <div class="grid items-center gap-10 lg:grid-cols-[7fr_3fr] lg:gap-14">
                    <div class="flex flex-col gap-6">
                        <h1 class="font-heading text-4xl font-extrabold leading-[1.15] tracking-tight text-slate-900 md:text-5xl md:leading-[1.15] lg:text-6xl lg:leading-[1.2] xl:text-7xl xl:leading-[1.2]">
                            Komite Daerah
                            <span class="text-gradient md:block">Keuangan dan Ekonomi</span>
                            Syariah Kaltim
                        </h1>

                        <div class="max-w-xl text-base font-medium leading-relaxed text-slate-500 lg:max-w-2xl xl:max-w-none sm:text-lg [&_p]:inline [&_p]:m-0">
                            {!! data_get($setting, 'short_description', 'Portal resmi KDEKS Kalimantan Timur untuk layanan sertifikasi halal, direktori produk, dokumen, dan pemetaan ekosistem syariah regional.') !!}
                        </div>

                        <div class="mt-2 flex flex-wrap gap-4">
                            <a href="{{ route('about') }}" class="flex items-center gap-2 rounded-xl bg-slate-900 px-8 py-4 font-bold text-white shadow-md transition-all hover:bg-slate-800">
                                Lihat Selengkapnya
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </a>
                            <button id="btnPenyebaranData" type="button" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-8 py-4 font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50">
                                <i data-lucide="map" class="h-4 w-4 text-slate-400"></i>
                                Penyebaran data KDEKS KalTim
                            </button>
                        </div>
                    </div>

                    <div class="relative hidden lg:block">
                        <div class="absolute -inset-6 rounded-[3rem] bg-gradient-to-tr from-emerald-500/10 to-cyan-500/10 blur-[80px]"></div>
                        <div class="relative overflow-hidden rounded-[2rem] shadow-2xl ring-1 ring-white/60">
                            <img
                                src="{{ asset('assets/img/hero.png') }}"
                                alt="KDEKS Kalimantan Timur"
                                class="block h-full min-h-[420px] w-full object-cover"
                            >
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== SEHATI CTA ===== --}}
            <div id="sehati" class="relative z-10 mt-10 flex flex-col items-center justify-center overflow-hidden bg-slate-900 px-6 py-20 md:mt-14 md:px-16 md:py-12">
                <div class="pointer-events-none absolute -left-20 top-0 h-80 w-80 rounded-full bg-emerald-500/10 blur-[120px]"></div>
                <div class="pointer-events-none absolute -right-20 bottom-0 h-80 w-80 rounded-full bg-cyan-500/10 blur-[120px]"></div>

                <div class="relative z-10 mx-auto max-w-5xl text-center">
                    <i data-lucide="quote" class="mx-auto mb-5 h-10 w-8 text-emerald-500/40 md:h-14 md:w-10"></i>

                    <blockquote class="font-heading text-2xl font-extrabold leading-snug tracking-tight text-white md:text-3xl lg:text-4xl lg:leading-tight">
                        <span class="text-emerald-400">Menjadikan Indonesia</span> yang Mandiri, Makmur & Madani
                        <br class="hidden sm:block">
                        dengan Menjadi
                        <span class="text-emerald-400">Pusat Ekonomi Syariah Terkemuka Dunia</span>
                    </blockquote>

                    <div class="mx-auto mt-10 h-px w-24 bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent"></div>
                </div>
            </div>

            <div class="relative z-10 mx-auto max-w-7xl px-6">
                {{-- ===== UMKM HALAL KALTIM STAT CARDS ===== --}}
                <div class="mt-4 grid grid-cols-2 gap-3 md:mt-8 md:grid-cols-4 md:gap-5">
                    <div class="group rounded-[1.75rem] border border-emerald-200 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition hover:shadow-lg hover:border-emerald-300 md:p-6">
                        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-500 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                            <i data-lucide="award" class="h-5 w-5"></i>
                        </div>
                        <h3 class="font-heading text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                            <span class="counter" data-target="{{ $statistics['certificates_total'] }}">0</span>
                        </h3>
                        <p class="mt-1 text-[11px] font-semibold leading-tight text-slate-500 md:text-xs">UMKM Halal Kaltim</p>
                    </div>

                    <div class="group rounded-[1.75rem] border border-emerald-200 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition hover:shadow-lg hover:border-emerald-300 md:p-6">
                        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-500 transition-colors group-hover:bg-blue-500 group-hover:text-white">
                            <i data-lucide="package" class="h-5 w-5"></i>
                        </div>
                        <h3 class="font-heading text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                            <span class="counter" data-target="{{ $statistics['products_total'] }}">0</span>
                        </h3>
                        <p class="mt-1 text-[11px] font-semibold leading-tight text-slate-500 md:text-xs">Produk Halal Terdaftar</p>
                    </div>

                    <div class="group rounded-[1.75rem] border border-emerald-200 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition hover:shadow-lg hover:border-emerald-300 md:p-6">
                        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-500 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                            <i data-lucide="badge-check" class="h-5 w-5"></i>
                        </div>
                        <h3 class="font-heading text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                            <span class="counter" data-target="{{ $totalSertifikatTerbit }}">0</span>
                        </h3>
                        <p class="mt-1 text-[11px] font-semibold leading-tight text-slate-500 md:text-xs">Sertifikat Halal Terbit</p>
                    </div>

                    <div class="group rounded-[1.75rem] border border-emerald-200 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition hover:shadow-lg hover:border-emerald-300 md:p-6">
                        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-cyan-50 text-cyan-500 transition-colors group-hover:bg-cyan-500 group-hover:text-white">
                            <i data-lucide="users" class="h-5 w-5"></i>
                        </div>
                        <h3 class="font-heading text-2xl font-extrabold tracking-tight text-cyan-500 md:text-3xl">
                            <span class="counter" data-target="{{ $statistics['assistants_total'] }}">0</span>
                        </h3>
                        <p class="mt-1 text-[11px] font-semibold leading-tight text-slate-500 md:text-xs">Lembaga Pendamping Aktif</p>
                    </div>
                </div>

                {{-- ===== PROGRAM UNGGULAN ===== --}}
                @if($slides->isNotEmpty())
                    <div class="mt-8 mb-4 md:mt-10">
                        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">Program Unggulan</h2>
                    </div>
                    <div class="grid max-w-2xl gap-3 sm:grid-cols-2 md:max-w-none md:grid-cols-4 items-start">
                        @foreach($slides->take(4) as $slide)
                            <button
                                type="button"
                                onclick="openProgramModal({{ $loop->index }})"
                                class="group text-left rounded-2xl border border-emerald-200 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition hover:border-emerald-300 hover:shadow-md overflow-hidden relative cursor-pointer flex flex-col"
                                style="height: 340px;"
                            >
                                @if($slide->image_path)
                                    <div class="h-36 w-full flex-shrink-0 overflow-hidden bg-slate-100">
                                        <img src="{{ asset('storage/'.$slide->image_path) }}" alt="{{ $slide->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    </div>
                                @else
                                    <div class="h-36 w-full flex-shrink-0 bg-gradient-to-br from-emerald-50 to-cyan-50 flex items-center justify-center">
                                        <i data-lucide="layout-grid" class="h-10 w-10 text-emerald-200"></i>
                                    </div>
                                @endif

                                <div class="p-4 flex flex-col flex-1 overflow-hidden">
                                    <div class="absolute top-3 right-3 flex h-7 w-7 items-center justify-center rounded-full bg-white/90 shadow-sm backdrop-blur-sm transition-colors duration-200">
                                        <i data-lucide="arrow-up-right" class="h-3.5 w-3.5 text-slate-400 transition-colors duration-200 group-hover:text-emerald-600"></i>
                                    </div>

                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.15em] text-emerald-600 sm:tracking-[0.24em] line-clamp-2 flex-shrink-0">{{ $slide->subtitle }}</p>

                                    <h3 class="mt-2 text-sm font-extrabold text-slate-900 line-clamp-2 flex-shrink-0">{{ $slide->title }}</h3>

                                    <p class="mt-2 text-xs font-medium leading-relaxed text-slate-500 line-clamp-5 flex-1">{{ strip_tags($slide->description) }}</p>

                                    <div class="mt-auto pt-2 flex items-center gap-1 text-[10px] font-bold text-slate-400 group-hover:text-emerald-600 transition-colors flex-shrink-0">
                                        <span>Lihat Detail</span>
                                        <i data-lucide="chevron-right" class="h-3 w-3"></i>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    {{-- Modal Detail Program Unggulan --}}
                    <div id="programModal" class="fixed inset-0 z-[200] flex items-center justify-center px-4 py-8" style="display:none!important;">
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeProgramModal()"></div>
                        <div class="relative z-10 w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
                            <div id="programModalImg" class="h-52 w-full bg-gradient-to-br from-emerald-50 to-cyan-50 flex-shrink-0 overflow-hidden"></div>

                            <div class="overflow-y-auto flex-1 px-7 py-6">
                                <p id="programModalSubtitle" class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-emerald-600 break-words"></p>
                                <h3 id="programModalTitle" class="mt-2 font-heading text-xl font-extrabold text-slate-900 break-words leading-snug"></h3>
                                <div id="programModalDesc" class="mt-4 text-sm leading-7 text-slate-600 break-words whitespace-pre-line max-w-full"></div>
                                @foreach($slides->take(4) as $slide)
                                    @if($slide->cta_url)
                                        <div class="mt-5 slide-cta-{{ $loop->index }}">
                                            <a href="{{ $slide->cta_url }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 transition">
                                                {{ $slide->cta_label ?: 'Selengkapnya' }}
                                                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <button onclick="closeProgramModal()" class="absolute top-4 right-4 flex h-9 w-9 items-center justify-center rounded-full bg-black/20 text-white backdrop-blur-sm hover:bg-black/40 transition">
                                <i data-lucide="x" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>

                    @php
                        $slidesJsonData = $slides->take(4)->map(function($s) {
                            return [
                                'subtitle'    => $s->subtitle,
                                'title'       => $s->title,
                                'description' => strip_tags($s->description),
                                'image_path'  => $s->image_path ? asset('storage/'.$s->image_path) : null,
                                'cta_url'     => $s->cta_url,
                                'cta_label'   => $s->cta_label,
                            ];
                        })->values()->toArray();
                    @endphp
                    <script>
                        const __programSlides = {!! json_encode($slidesJsonData) !!};

                        function openProgramModal(index) {
                            const slide = __programSlides[index];
                            if (!slide) return;

                            const modal = document.getElementById('programModal');
                            const imgBox = document.getElementById('programModalImg');

                            if (slide.image_path) {
                                imgBox.innerHTML = `<img src="${slide.image_path}" alt="${slide.title}" class="h-full w-full object-cover">`;
                            } else {
                                imgBox.innerHTML = '<div class="h-full w-full flex items-center justify-center"><svg class="h-14 w-14 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h8"/></svg></div>';
                            }

                            document.getElementById('programModalSubtitle').textContent = slide.subtitle || '';
                            document.getElementById('programModalTitle').textContent = slide.title || '';
                            document.getElementById('programModalDesc').textContent = slide.description || '';

                            document.querySelectorAll('[class*="slide-cta-"]').forEach(el => el.style.display = 'none');
                            const ctaEl = document.querySelector('.slide-cta-' + index);
                            if (ctaEl) ctaEl.style.display = 'block';

                            modal.style.removeProperty('display');
                            modal.style.display = 'flex';
                            document.body.style.overflow = 'hidden';

                            if (window.lucide) window.lucide.createIcons();
                        }

                        function closeProgramModal() {
                            const modal = document.getElementById('programModal');
                            modal.style.display = 'none';
                            document.body.style.overflow = '';
                        }

                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') closeProgramModal();
                        });
                    </script>
                @endif
            </div>
        </section>

        {{-- ===== SEKTOR EKONOMI SYARIAH ===== --}}
        <section id="sektor" class="bg-white py-24 border-b border-slate-100">
            <div class="mx-auto max-w-7xl px-6">
                <div>
                    <h2 class="text-center font-heading text-3xl font-extrabold tracking-tight text-slate-900">Sektor Ekonomi Syariah</h2>
                    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-5">
                        @foreach($sectorItems as $item)
                            <a href="{{ route('direktorat.show', $item->slug) }}" class="group relative block rounded-[1.75rem] border border-emerald-200 p-6 transition hover:shadow-md hover:border-emerald-400">
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                                    <i data-lucide="{{ $item->icon_key }}" class="h-5 w-5"></i>
                                </div>
                                <h4 class="mb-1 text-base font-bold text-slate-900 transition-colors group-hover:text-emerald-700">{{ $item->title }}</h4>
                                <p class="text-xs leading-relaxed text-slate-500">{{ $item->summary }}</p>
                                <span class="absolute top-4 right-4 flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-slate-50 transition-all duration-200 group-hover:border-emerald-300 group-hover:bg-emerald-50">
                                    <i data-lucide="arrow-up-right" class="h-3.5 w-3.5 text-slate-400 transition-colors duration-200 group-hover:text-emerald-600"></i>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== PETA SEBARAN HALAL ===== --}}
        <section id="webgis" class="relative z-10 bg-white py-24">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl">Peta Sebaran KDEKS KalTim</h2>
                        <p class="font-medium text-slate-500">Lokasi usaha dan layanan halal di Kalimantan Timur berdasarkan kategori, LP3H, dan kabupaten/kota.</p>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-x-0 -top-10 bottom-10 rounded-full bg-gradient-to-tr from-emerald-500/20 to-cyan-500/20 blur-[100px] opacity-80"></div>
                    <div class="relative flex min-h-[500px] w-full flex-col overflow-hidden rounded-[2.5rem] border border-white bg-white/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl md:h-[600px]">
                        <div class="pointer-events-none absolute inset-x-5 top-5 z-[500] flex flex-col gap-3 sm:left-8 sm:right-auto sm:top-8 sm:w-80">
                            <div class="pointer-events-auto flex w-full items-center gap-3 rounded-2xl border border-slate-100 bg-white/95 p-3.5 shadow-sm backdrop-blur-md">
                                <i data-lucide="search" class="ml-2 h-4 w-4 shrink-0 text-slate-400"></i>
                                <input id="mapSearchInput" type="text" placeholder="Cari kota atau usaha..." class="w-full border-none bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400">
                            </div>
                            <div class="pointer-events-auto flex gap-2">
                                <select id="mapCityFilter" class="w-full cursor-pointer rounded-xl border border-slate-100 bg-white/95 px-3 py-2 text-[10px] font-bold text-slate-700 shadow-sm outline-none backdrop-blur-md">
                                    <option value="">Semua Kota</option>
                                    @foreach($mapCities as $city)
                                        <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </select>
                                <select id="mapPartnerFilter" class="w-full cursor-pointer rounded-xl border border-slate-100 bg-white/95 px-3 py-2 text-[10px] font-bold text-slate-700 shadow-sm outline-none backdrop-blur-md">
                                    <option value="">LP3H / LPH</option>
                                    @foreach($lphPartners as $partner)
                                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="mapSubDistrictFilters" class="pointer-events-auto flex gap-2">
                                <select id="mapKecamatanFilter" disabled class="w-full cursor-pointer rounded-xl border border-slate-100 bg-white/95 px-3 py-2 text-[10px] font-bold text-slate-700 shadow-sm outline-none backdrop-blur-md disabled:opacity-50 disabled:cursor-not-allowed">
                                    <option value="">Kecamatan</option>
                                </select>
                                <select id="mapKelurahanFilter" disabled class="w-full cursor-pointer rounded-xl border border-slate-100 bg-white/95 px-3 py-2 text-[10px] font-bold text-slate-700 shadow-sm outline-none backdrop-blur-md disabled:opacity-50 disabled:cursor-not-allowed">
                                    <option value="">Kelurahan</option>
                                </select>
                            </div>
                        </div>

                        <div class="pointer-events-auto absolute right-5 top-5 z-[500] hidden flex-col overflow-hidden rounded-xl border border-slate-100 bg-white/95 shadow-sm backdrop-blur-md sm:flex sm:right-8 sm:top-8">
                            <button type="button" data-map-zoom="in" class="border-b border-slate-100 p-3 text-slate-500 transition hover:bg-slate-50">
                                <i data-lucide="plus" class="h-4 w-4"></i>
                            </button>
                            <button type="button" data-map-zoom="out" class="p-3 text-slate-500 transition hover:bg-slate-50">
                                <i data-lucide="minus" class="h-4 w-4"></i>
                            </button>
                        </div>

                        <div id="leafletKaltim" class="absolute inset-0 z-0" data-map-url="{{ url('/api/map') }}"></div>

                        <div class="pointer-events-none absolute bottom-5 left-5 right-5 z-[500] flex items-center justify-between gap-4 sm:bottom-8 sm:left-8 sm:right-8">
                            <div class="pointer-events-auto relative w-full sm:w-auto">
                                <select id="mapCategoryFilter" class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-200 bg-white/95 px-5 py-3.5 pr-12 text-[11px] font-bold text-slate-700 shadow-sm outline-none backdrop-blur-md sm:w-auto sm:text-xs">
                                    <option value="">Semua Kategori</option>
                                    @foreach($mapCategories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevrons-up-down" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            </div>

                            <div class="pointer-events-auto flex flex-col overflow-hidden rounded-xl border border-slate-100 bg-white/95 shadow-sm backdrop-blur-md sm:hidden">
                                <button type="button" data-map-zoom="in" class="border-b border-slate-100 p-3 text-slate-500 transition hover:bg-slate-50">
                                    <i data-lucide="plus" class="h-4 w-4"></i>
                                </button>
                                <button type="button" data-map-zoom="out" class="p-3 text-slate-500 transition hover:bg-slate-50">
                                    <i data-lucide="minus" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== DIREKTORI ===== --}}
        <section id="direktori" class="border-t border-slate-100 bg-white py-24">
            <div class="mx-auto max-w-7xl px-6">
                <div class="grid gap-16 lg:grid-cols-2">
                    <div class="flex h-full flex-col">
                        <div class="mb-8 flex items-end justify-between">
                            <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Data Produk</h2>
                            <a href="{{ route('products.index') }}" class="text-sm font-bold text-emerald-600">Lihat semua</a>
                        </div>
                        <div class="flex flex-1 flex-col gap-4">
                            @foreach($featuredProducts->take(4) as $product)
                                <a href="#" class="group flex flex-1 cursor-pointer items-center gap-4 rounded-2xl border border-emerald-200 p-5 transition hover:border-emerald-300 hover:shadow-sm">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-50">
                                        <i data-lucide="package" class="h-5 w-5 text-slate-400"></i>
                                    </div>
                                    <div class="flex flex-1 flex-col justify-center">
                                        <h4 class="text-sm font-bold text-slate-900 transition group-hover:text-emerald-600">{{ $product->nama_produk }}</h4>
                                        <p class="mt-1 text-[11px] font-medium text-slate-500">{{ $product->umkm?->nama_umkm ?? 'Produk UMKM' }}</p>
                                    </div>
                                    <span class="self-center rounded bg-emerald-50 px-2.5 py-1 text-[9px] font-bold uppercase text-emerald-600">Terverifikasi</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div id="data" class="flex h-full flex-col">
                        <div class="mb-8 flex items-end justify-between">
                            <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Dokumen & Regulasi</h2>
                            <div class="flex gap-4">
                                <a href="{{ route('resources.index') }}" class="text-sm font-bold text-emerald-600">Dokumen</a>
                                <a href="{{ route('regulations.index') }}" class="text-sm font-bold text-emerald-600">Regulasi</a>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col gap-4">
                            @foreach($resources->take(2) as $resource)
                                <a href="{{ route('resources.show', $resource->slug) }}" class="group flex flex-1 items-center gap-4 rounded-2xl border border-emerald-200 p-5 transition hover:border-emerald-300 hover:shadow-sm">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-500">
                                        <i data-lucide="file-text" class="h-5 w-5"></i>
                                    </div>
                                    <div class="flex flex-1 flex-col justify-center">
                                        <h4 class="text-sm font-bold text-slate-900 transition group-hover:text-emerald-600">{{ $resource->title }}</h4>
                                        <p class="mt-1 text-[11px] font-medium text-slate-500">Dokumen &bull; {{ optional($resource->published_at)->translatedFormat('M Y') }}</p>
                                    </div>
                                    <i data-lucide="download" class="h-4 w-4 shrink-0 self-center text-slate-400 transition group-hover:text-emerald-600"></i>
                                </a>
                            @endforeach

                            @foreach($regulations->take(2) as $regulation)
                                <a href="{{ route('regulations.show', $regulation->slug) }}" class="group flex flex-1 items-center gap-4 rounded-2xl border border-emerald-200 p-5 transition hover:border-emerald-300 hover:shadow-sm">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-500">
                                        <i data-lucide="scale" class="h-5 w-5"></i>
                                    </div>
                                    <div class="flex flex-1 flex-col justify-center">
                                        <h4 class="text-sm font-bold text-slate-900 transition group-hover:text-emerald-600">{{ $regulation->title }}</h4>
                                        <p class="mt-1 text-[11px] font-medium text-slate-500">{{ $regulation->regulation_number }}</p>
                                    </div>
                                    <i data-lucide="arrow-up-right" class="h-4 w-4 shrink-0 self-center text-slate-400 transition group-hover:text-emerald-600"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== BERITA & PUBLIKASI ===== --}}
        <section id="artikel" class="border-t border-slate-100 bg-slate-50 py-24">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-10 flex items-end justify-between">
                    <div>
                        <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Berita & Publikasi</h2>
                        <p class="mt-2 text-sm font-medium text-slate-500">Update berita, siaran pers, dan riset terbaru KDEKS Kaltim.</p>
                    </div>
                    <a href="{{ route('articles.index') }}" class="text-sm font-bold text-emerald-600">Lihat semua</a>
                </div>
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($featuredArticles->take(4) as $article)
                        <a href="{{ route('articles.show', $article->slug) }}" class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            @if($article->cover_image_path)
                                <img src="{{ asset('storage/'.$article->cover_image_path) }}" alt="{{ $article->title }}" class="h-48 w-full object-cover">
                            @endif
                            <div class="p-5">
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-600 sm:tracking-[0.24em]">{{ strtoupper($article->type) }}</p>
                                <h3 class="mt-3 text-base font-extrabold text-slate-900">{{ $article->title }}</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-500">{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->body), 110) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===== FAQ ===== --}}
        <section id="faq" class="border-t border-slate-100 bg-white py-24">
            <div class="mx-auto max-w-5xl px-6">
                <div class="mb-10 text-center">
                    <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Pertanyaan Umum</h2>
                    <p class="mt-2 text-sm font-medium text-slate-500">Jawaban singkat untuk alur layanan publik yang paling sering ditanyakan.</p>
                </div>
                <div class="space-y-4">
                    @foreach($faqs->take(6) as $faq)
                        <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-6">
                            <h3 class="text-base font-extrabold text-slate-900">{{ $faq->question }}</h3>
                            <div class="mt-3 text-sm leading-7 text-slate-500">{!! $faq->answer !!}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>{{-- end wrapper --}}

    {{-- ===== ANIMATION STYLES ===== --}}
    <style>
        @keyframes scrollDownBounce {
            0%, 100% { transform: translateY(0); opacity: 0.6; }
            50% { transform: translateY(6px); opacity: 1; }
        }

        .scroll-down-arrow {
            animation: scrollDownBounce 2s ease-in-out infinite;
        }

        .scroll-down-btn:hover .scroll-down-arrow {
            animation: scrollDownBounce 1.2s ease-in-out infinite;
        }

        .banner-text-inner {
            will-change: opacity, transform;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        .banner-parallax-title,
        .banner-parallax-subtitle {
            will-change: transform;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            transform: translate3d(0, 0, 0);
        }
    </style>

    {{-- ===== BANNER SLIDER + SMOOTH PARALLAX SCRIPT ===== --}}
    <script>
        (() => {
            window.bannerPause = false;
            let currentBannerSlide = 0;
            const totalBannerSlides = {{ $banners->count() }};
            const bannerTrack = document.getElementById('bannerTrack');
            const bannerCounter = document.getElementById('bannerCurrentNum');
            const bannerSlider = document.getElementById('bannerSlider');
            const bannerFixedBg = document.getElementById('bannerFixedBg');
            const bannerTitleEl = document.getElementById('bannerTitle');
            const bannerSubtitleEl = document.getElementById('bannerSubtitle');
            const parallaxTitles = document.querySelectorAll('.banner-parallax-title');
            const parallaxSubtitles = document.querySelectorAll('.banner-parallax-subtitle');

            const NAVBAR_HEIGHT = 64;
            const BANNER_HEIGHT = 665;

            const bannerTexts = @json($banners->map(fn($b) => [
                'title' => $b->title,
                'subtitle' => $b->subtitle,
            ]));

            let textAnimating = false;

            function pad(n) { return String(n + 1).padStart(2, '0'); }

            function updateBannerText() {
                if (!bannerTitleEl || !bannerSubtitleEl || textAnimating) return;
                textAnimating = true;

                const slide = bannerTexts[currentBannerSlide];

                // Fade OUT — cepat, 160ms
                var outT = 'opacity 0.16s ease-in, transform 0.16s ease-in';
                bannerTitleEl.style.transition = outT;
                bannerSubtitleEl.style.transition = outT;

                bannerTitleEl.style.opacity = '0';
                bannerTitleEl.style.transform = 'translateY(12px)';
                bannerSubtitleEl.style.opacity = '0';
                bannerSubtitleEl.style.transform = 'translateY(8px)';

                // Ganti teks tepat saat fade-out selesai, langsung fade-in tanpa jeda
                setTimeout(function() {
                    bannerTitleEl.textContent = slide.title;
                    bannerSubtitleEl.textContent = slide.subtitle;

                    // Posisikan di atas dulu (masih opacity 0, tidak kelihatan)
                    bannerTitleEl.style.transition = 'none';
                    bannerSubtitleEl.style.transition = 'none';
                    bannerTitleEl.style.transform = 'translateY(-12px)';
                    bannerSubtitleEl.style.transform = 'translateY(-8px)';

                    // Force reflow supaya posisi baru dihitung browser
                    void bannerTitleEl.offsetHeight;

                    // Fade IN — halus dengan deceleration, 360ms
                    var inT = 'opacity 0.36s ease-out, transform 0.36s cubic-bezier(0.22,1,0.36,1)';
                    bannerTitleEl.style.transition = inT;
                    bannerSubtitleEl.style.transition = inT;

                    bannerTitleEl.style.opacity = '1';
                    bannerTitleEl.style.transform = 'translateY(0)';
                    bannerSubtitleEl.style.opacity = '1';
                    bannerSubtitleEl.style.transform = 'translateY(0)';

                    setTimeout(function() { textAnimating = false; }, 380);
                }, 180);
            }

            function updateBannerSlider() {
                if (!bannerTrack) return;
                bannerTrack.style.transform = 'translateX(-' + (currentBannerSlide * 100) + '%)';
                if (bannerCounter) bannerCounter.textContent = pad(currentBannerSlide);
                updateBannerText();
            }

            window.slideBanner = function(direction) {
                currentBannerSlide = (currentBannerSlide + direction + totalBannerSlides) % totalBannerSlides;
                updateBannerSlider();
            };

            window.goToBannerSlide = function(index) {
                currentBannerSlide = index;
                updateBannerSlider();
            };

            setInterval(function() {
                if (!window.bannerPause) {
                    currentBannerSlide = (currentBannerSlide + 1) % totalBannerSlides;
                    updateBannerSlider();
                }
            }, 3000);

            // Touch / swipe support
            var touchStartX = 0;
            if (bannerSlider) {
                bannerSlider.addEventListener('touchstart', function(e) {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });
                bannerSlider.addEventListener('touchend', function(e) {
                    var diff = touchStartX - e.changedTouches[0].screenX;
                    if (Math.abs(diff) > 50) {
                        slideBanner(diff > 0 ? 1 : -1);
                    }
                }, { passive: true });
            }

            // ===== CUSTOM SMOOTH SCROLL =====
            function smoothScrollTo(targetY, duration) {
                var startY = window.pageYOffset || document.documentElement.scrollTop;
                var diff = targetY - startY;
                if (Math.abs(diff) < 2) return;

                var start = null;

                function step(timestamp) {
                    if (!start) start = timestamp;
                    var elapsed = timestamp - start;
                    var progress = Math.min(elapsed / duration, 1);

                    var ease = progress < 0.5
                        ? 8 * progress * progress * progress * progress
                        : 1 - Math.pow(-2 * progress + 2, 4) / 2;

                    window.scrollTo(0, startY + diff * ease);

                    if (progress < 1) {
                        requestAnimationFrame(step);
                    }
                }

                requestAnimationFrame(step);
            }

            // ===== SCROLL DOWN BUTTON =====
            var scrollDownBtn = document.getElementById('scrollDownBtn');
            if (scrollDownBtn) {
                scrollDownBtn.addEventListener('click', function() {
                    var heroSection = document.getElementById('hero');
                    if (heroSection) {
                        var rect = heroSection.getBoundingClientRect();
                        var targetY = window.pageYOffset + rect.top - NAVBAR_HEIGHT;
                        smoothScrollTo(targetY, 1000);
                    }
                });
            }

            // ===== PENYEBARAN DATA BUTTON =====
            var btnPenyebaran = document.getElementById('btnPenyebaranData');
            if (btnPenyebaran) {
                btnPenyebaran.addEventListener('click', function() {
                    var target = document.getElementById('webgis');
                    if (target) {
                        var rect = target.getBoundingClientRect();
                        var targetY = window.pageYOffset + rect.top - NAVBAR_HEIGHT - 16;
                        smoothScrollTo(targetY, 900);
                    }
                });
            }

            // ===== SMOOTH PARALLAX WITH LERP =====
            var bgHidden = false;
            var LERP_FACTOR = 0.08;
            var currentTitleY = 0;
            var currentSubtitleY = 0;
            var targetTitleY = 0;
            var targetSubtitleY = 0;

            function lerp(a, b, t) {
                return a + (b - a) * t;
            }

            function calcTargets() {
                if (!bannerSlider) return;

                var rect = bannerSlider.getBoundingClientRect();
                var sliderHeight = bannerSlider.offsetHeight || BANNER_HEIGHT;

                var scrollPast = Math.max(0, NAVBAR_HEIGHT - rect.top);
                var progress = Math.min(scrollPast / sliderHeight, 1);

                var eased = progress * progress * (3 - 2 * progress);

                targetTitleY = eased * sliderHeight * 0.35;
                targetSubtitleY = eased * sliderHeight * 0.2;
            }

            function parallaxLoop() {
                calcTargets();

                currentTitleY = lerp(currentTitleY, targetTitleY, LERP_FACTOR);
                currentSubtitleY = lerp(currentSubtitleY, targetSubtitleY, LERP_FACTOR);

                if (Math.abs(currentTitleY - targetTitleY) < 0.05) currentTitleY = targetTitleY;
                if (Math.abs(currentSubtitleY - targetSubtitleY) < 0.05) currentSubtitleY = targetSubtitleY;

                parallaxTitles.forEach(function(el) {
                    el.style.transform = 'translate3d(0,' + currentTitleY + 'px,0)';
                });
                parallaxSubtitles.forEach(function(el) {
                    el.style.transform = 'translate3d(0,' + currentSubtitleY + 'px,0)';
                });

                if (bannerFixedBg) {
                    var rect = bannerSlider.getBoundingClientRect();
                    if (rect.bottom <= 0 && !bgHidden) {
                        bannerFixedBg.style.display = 'none';
                        bgHidden = true;
                    } else if (rect.bottom > 0 && bgHidden) {
                        bannerFixedBg.style.display = 'block';
                        bgHidden = false;
                    }
                }

                requestAnimationFrame(parallaxLoop);
            }

            requestAnimationFrame(parallaxLoop);
        })();
    </script>

    {{-- ===== MAP SCRIPT ===== --}}
    <script>
        (() => {
            const MAP_FILTER_MODE = 'city-only';

            const escapeHtml = (value) => {
                if (value === null || value === undefined) return '';
                return String(value)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            };

            const publicDetailUrl = (slug) => slug ? `/locations/${slug}` : '#';

            const normalizeCollection = (value) => {
                if (Array.isArray(value)) return value;
                if (Array.isArray(value?.data)) return value.data;
                return [];
            };

            const bootLandingMap = () => {
                if (!window.L) return;

                const currentMapElement = document.getElementById('leafletKaltim');
                if (!currentMapElement) return;

                let mapElement = currentMapElement;
                if (mapElement.dataset.mapBooted === 'true') return;

                if (mapElement._leaflet_id) {
                    const replacement = mapElement.cloneNode(false);
                    replacement.dataset.mapUrl = mapElement.dataset.mapUrl;
                    mapElement.parentNode.replaceChild(replacement, mapElement);
                    mapElement = replacement;
                }

                mapElement.dataset.mapBooted = 'true';

                const map = L.map(mapElement, { zoomControl: false }).setView([0.706, 116.426], 7);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                }).addTo(map);

                const highlightKaltim = async () => {
                    try {
                        const response = await fetch('https://raw.githubusercontent.com/fahadh4ilyas/indonesia-geojson-archive/master/Indonesia_provinces.geojson');
                        const data = await response.json();
                        const kaltim = data.features.find(f => {
                            const name = f.properties.Propinsi || f.properties.NAME_1 || f.properties.name || f.properties.PROVINSI || '';
                            return name.toUpperCase() === 'KALIMANTAN TIMUR';
                        });
                        if (kaltim) {
                            L.geoJSON(kaltim, {
                                style: {
                                    fillColor: '#10b981',
                                    fillOpacity: 0.08,
                                    color: '#10b981',
                                    weight: 2,
                                    dashArray: '4, 4',
                                    lineCap: 'round',
                                }
                            }).addTo(map);
                        }
                    } catch (e) {}
                };
                highlightKaltim();

                const aggregateIcon = L.divIcon({
                    className: 'leaflet-custom-marker',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10],
                    popupAnchor: [0, -10],
                });

                const businessIcon = L.divIcon({
                    className: 'leaflet-business-marker',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6],
                    popupAnchor: [0, -8],
                });

                const cityFilter = document.getElementById('mapCityFilter');
                const partnerFilter = document.getElementById('mapPartnerFilter');
                const kecamatanFilter = document.getElementById('mapKecamatanFilter');
                const kelurahanFilter = document.getElementById('mapKelurahanFilter');
                const searchInput = document.getElementById('mapSearchInput');
                const categoryFilter = document.getElementById('mapCategoryFilter');
                const zoomButtons = document.querySelectorAll('[data-map-zoom]');
                const markersLayer = L.layerGroup().addTo(map);

                if (MAP_FILTER_MODE === 'city-only') {
                    const subFilters = document.getElementById('mapSubDistrictFilters');
                    if (subFilters) subFilters.style.display = 'none';
                }

                const state = {
                    category: '',
                    city: '',
                    lph_partner_id: '',
                    keyword: '',
                    kecamatan: '',
                    kelurahan: '',
                };

                let filterData = {};

                const updateFilters = (filters) => {
                    filterData = filters;
                };

                const renderMap = async () => {
                    const query = new URLSearchParams();
                    Object.entries(state).forEach(([key, value]) => {
                        if (value) query.set(key, value);
                    });

                    try {
                        const response = await fetch(`${mapElement.dataset.mapUrl}?${query.toString()}`);
                        const result = await response.json();

                        if (result?.data?.filters) {
                            updateFilters(result.data.filters);
                        }

                        const regions = normalizeCollection(result?.data?.regions);
                        const locations = normalizeCollection(result?.data?.locations)
                            .filter((location) => Number.isFinite(Number(location.latitude)) && Number.isFinite(Number(location.longitude)));

                        markersLayer.clearLayers();

                        regions.forEach((region) => {
                            if (!Number.isFinite(Number(region.latitude)) || !Number.isFinite(Number(region.longitude))) return;

                            const regionLocations = locations.filter((location) => location.region?.id === region.id);
                            if (!regionLocations.length) return;

                            const popupContent = `
                                <div class="text-left">
                                    <h4 class="font-heading mb-1 text-sm font-extrabold text-slate-900">${escapeHtml(region.name)}</h4>
                                    <p class="mb-2 border-b border-slate-100 pb-2 text-[10px] font-semibold text-slate-500">Sebaran usaha dan layanan halal</p>
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-[9px] font-bold uppercase text-slate-400">Titik Tersedia</p>
                                            <p class="text-sm font-extrabold text-emerald-600">${new Intl.NumberFormat('id-ID').format(regionLocations.length)}</p>
                                        </div>
                                        <a href="/products?keyword=${encodeURIComponent(region.name)}" class="rounded bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-600">Lihat Produk</a>
                                    </div>
                                </div>
                            `;

                            L.marker([Number(region.latitude), Number(region.longitude)], { icon: aggregateIcon })
                                .bindPopup(popupContent)
                                .addTo(markersLayer);
                        });

                        locations.forEach((location) => {
                            const fotoHtml = location.foto_url
                                ? `<div class="mb-2 h-28 w-full overflow-hidden rounded-lg bg-slate-100"><img src="${escapeHtml(location.foto_url)}" alt="Foto" class="h-full w-full object-cover"></div>`
                                : '';
                            const waNumber = location.nomor_wa ? location.nomor_wa.replace(/[^0-9]/g, '') : '';

                            const waHtml = waNumber
                                ? `<a href="https://wa.me/${waNumber.startsWith('0') ? '62' + waNumber.substring(1) : waNumber}?text=Halo%20${encodeURIComponent(escapeHtml(location.name))}" target="_blank" class="flex-1 flex items-center justify-center gap-1.5 rounded-lg bg-emerald-50 px-2 py-1.5 text-center text-[10px] font-bold text-emerald-600 transition hover:bg-emerald-100">WhatsApp</a>`
                                : '';

                            const navHtml = `<a href="https://www.google.com/maps/dir/?api=1&destination=${location.latitude},${location.longitude}" target="_blank" class="flex-1 flex items-center justify-center gap-1.5 rounded-lg bg-blue-50 px-2 py-1.5 text-center text-[10px] font-bold text-blue-600 transition hover:bg-blue-100">Rute Lokasi</a>`;

                            let descHtml = '';
                            if (location.deskripsi) {
                                descHtml = `<p class="mb-2 mt-1 text-[10px] font-medium leading-relaxed text-slate-500 line-clamp-2">${escapeHtml(location.deskripsi)}</p>`;
                            }

                            const popupContent = `
                                <div class="min-w-[220px] max-w-[260px] text-left">
                                    ${fotoHtml}
                                    <div class="mb-1.5 flex items-start justify-between gap-2">
                                        <span class="inline-block rounded border border-cyan-100 bg-cyan-50 px-2 py-0.5 text-[8px] font-bold uppercase text-cyan-600">${escapeHtml(location.category ?? 'UMKM')}</span>
                                    </div>
                                    <h4 class="font-heading mb-0.5 text-sm font-extrabold leading-tight text-slate-900">${escapeHtml(location.name)}</h4>
                                    <p class="mb-0 text-[10px] font-medium text-slate-500">Pemilik: ${escapeHtml(location.nama_pemilik && location.nama_pemilik !== '-' ? location.nama_pemilik : 'Tidak diketahui')}</p>
                                    ${descHtml}
                                    <div class="mt-2 mb-2 border-l-2 border-slate-200 pl-2">
                                        <p class="text-[9px] font-bold text-slate-600">${escapeHtml(location.address ?? location.city_name ?? '')}</p>
                                        <p class="mt-0.5 text-[9px] font-medium text-slate-400">Mitra: ${escapeHtml(location.lph_partner?.name ?? '-')}</p>
                                    </div>
                                    <div class="mt-3 flex gap-2">
                                        ${waHtml}
                                        ${navHtml}
                                    </div>
                                </div>
                            `;

                            L.marker([Number(location.latitude), Number(location.longitude)], { icon: businessIcon })
                                .bindPopup(popupContent)
                                .addTo(markersLayer);
                        });

                        if (locations.length && (state.city || state.keyword || state.lph_partner_id || state.category)) {
                            const bounds = L.latLngBounds(locations.map((location) => [Number(location.latitude), Number(location.longitude)]));
                            map.fitBounds(bounds.pad(0.12));
                        } else {
                            map.setView([0.706, 116.426], 7);
                        }
                    } catch (error) {
                        markersLayer.clearLayers();
                    }
                };

                cityFilter?.addEventListener('change', () => {
                    state.city = cityFilter.value;
                    state.kecamatan = '';
                    state.kelurahan = '';
                    kecamatanFilter.innerHTML = '<option value="">Kecamatan</option>';
                    kelurahanFilter.innerHTML = '<option value="">Kelurahan</option>';
                    kelurahanFilter.disabled = true;

                    if (MAP_FILTER_MODE === 'full') {
                        if (state.city && filterData[state.city]) {
                            kecamatanFilter.disabled = false;
                            Object.keys(filterData[state.city]).sort().forEach(kec => {
                                const opt = document.createElement('option');
                                opt.value = kec;
                                opt.textContent = kec;
                                kecamatanFilter.appendChild(opt);
                            });
                        } else {
                            kecamatanFilter.disabled = true;
                        }
                    }

                    renderMap();
                });

                partnerFilter?.addEventListener('change', () => {
                    state.lph_partner_id = partnerFilter.value;
                    renderMap();
                });

                if (MAP_FILTER_MODE === 'full') {
                    kecamatanFilter?.addEventListener('change', () => {
                        state.kecamatan = kecamatanFilter.value;
                        state.kelurahan = '';
                        kelurahanFilter.innerHTML = '<option value="">Kelurahan</option>';

                        if (state.city && state.kecamatan && filterData[state.city]?.[state.kecamatan]) {
                            kelurahanFilter.disabled = false;
                            filterData[state.city][state.kecamatan].forEach(kel => {
                                const opt = document.createElement('option');
                                opt.value = kel;
                                opt.textContent = kel;
                                kelurahanFilter.appendChild(opt);
                            });
                        } else {
                            kelurahanFilter.disabled = true;
                        }
                        renderMap();
                    });

                    kelurahanFilter?.addEventListener('change', () => {
                        state.kelurahan = kelurahanFilter.value;
                        renderMap();
                    });
                }

                let searchTimer = null;
                searchInput?.addEventListener('input', () => {
                    if (searchTimer) window.clearTimeout(searchTimer);
                    searchTimer = window.setTimeout(() => {
                        state.keyword = searchInput.value.trim();
                        renderMap();
                    }, 250);
                });

                categoryFilter?.addEventListener('change', () => {
                    state.category = categoryFilter.value;
                    renderMap();
                });

                zoomButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        if (button.dataset.mapZoom === 'in') map.zoomIn();
                        if (button.dataset.mapZoom === 'out') map.zoomOut();
                    });
                });

                window.setTimeout(() => map.invalidateSize(), 120);
                renderMap();
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', bootLandingMap);
            } else {
                bootLandingMap();
            }

            window.setTimeout(bootLandingMap, 400);
        })();
    </script>
@endsection
