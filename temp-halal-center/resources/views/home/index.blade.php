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
    @endphp

    <section id="hero" class="relative flex min-h-screen items-center overflow-hidden pb-12 pt-24">
        <div class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.7),transparent_40%)]"></div>
        <div class="pointer-events-none absolute inset-0 z-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdHRlcm4gaWQ9InNtYWxsR3JpZCIgd2lkdGg9IjEwIiBoZWlnaHQ9IjEwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNMTAgMEwwIDBMMCAxMCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDAsMCwwLDAuMDIpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ1cmwoI3NtYWxsR3JpZCkiLz48cGF0aCBkPSJNNDAgMEwwIDBMMCA0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDAsMCwwLDAuMDQpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-50"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-6">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                <div class="flex max-w-2xl flex-col gap-6">
                    <h1 class="font-heading text-4xl font-extrabold leading-[1.05] tracking-tight text-slate-900 md:text-5xl lg:text-7xl">
                        Komite Daerah <br>
                        <span class="text-gradient">Keuangan dan Ekonomi</span> <br>
                        Syariah Kaltim
                    </h1>

                    <div class="text-base font-medium leading-relaxed text-slate-500 sm:text-lg [&_p]:inline [&_p]:m-0">
                        {!! data_get($setting, 'short_description', 'Portal resmi KDEKS Kalimantan Timur untuk layanan sertifikasi halal, direktori produk, dokumen, dan pemetaan ekosistem syariah regional.') !!}
                    </div>

                    <div class="mt-2 flex flex-wrap gap-4">
                        <button onclick="openModal('sehatiModal')" class="flex items-center gap-2 rounded-xl bg-slate-900 px-8 py-4 font-bold text-white shadow-md transition-all hover:bg-slate-800">
                            Daftar Sertifikasi Gratis
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </button>
                        <a href="#webgis" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-8 py-4 font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50">
                            <i data-lucide="map" class="h-4 w-4 text-slate-400"></i>
                            Jelajahi Peta
                        </a>
                    </div>
                </div>

                <div class="relative flex aspect-square w-full items-center justify-center md:aspect-video lg:aspect-square">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-emerald-500/10 to-cyan-500/10 blur-[80px]"></div>
                    <div class="relative flex w-full max-w-md flex-col gap-5 animate-float">
                        <div class="rounded-[1.75rem] border border-white bg-white/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl">
                            <div class="mb-2 flex items-center justify-between">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-500">
                                    <i data-lucide="award" class="h-5 w-5"></i>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600">Tumbuh Pesat</span>
                            </div>
                            <h3 class="font-heading text-4xl font-extrabold tracking-tight text-slate-900"><span class="counter" data-target="{{ $statistics['certificates_total'] }}">0</span></h3>
                            <p class="mt-1 text-sm font-semibold text-slate-500">UMKM Halal Kaltim</p>
                        </div>

                        <div class="flex flex-col gap-5 sm:flex-row">
                            <div class="flex-1 rounded-[1.75rem] border border-white bg-white/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl">
                                <h3 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900"><span class="counter" data-target="{{ $statistics['products_total'] }}">0</span></h3>
                                <p class="mt-1 text-xs font-semibold leading-tight text-slate-500">Produk Terdaftar</p>
                            </div>
                            <div class="flex-1 rounded-[1.75rem] border border-white bg-white/80 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl">
                                <h3 class="font-heading text-3xl font-extrabold tracking-tight text-cyan-500"><span class="counter" data-target="{{ $statistics['assistants_total'] }}">0</span></h3>
                                <p class="mt-1 text-xs font-semibold leading-tight text-slate-500">Lembaga Pendamping Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($slides->isNotEmpty())
                <div class="mt-12 grid max-w-2xl gap-3 sm:grid-cols-2">
                    @foreach($slides->take(2) as $slide)
                        <div class="rounded-2xl border border-white/80 bg-white/75 p-4 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.28em] text-emerald-600">{{ $slide->subtitle }}</p>
                            <h3 class="mt-2 text-sm font-extrabold text-slate-900">{{ $slide->title }}</h3>
                            <p class="mt-2 text-xs font-medium leading-relaxed text-slate-500">{{ strip_tags($slide->description) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section id="webgis" class="relative z-10 py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl">Peta Sebaran Halal</h2>
                    <p class="font-medium text-slate-500">Lokasi usaha dan layanan halal di Kalimantan Timur berdasarkan kategori, LP3H, dan kabupaten/kota.</p>
                </div>
            </div>

            <div class="relative flex h-[500px] w-full flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-100 shadow-sm md:h-[550px]">
                <div class="pointer-events-none absolute left-5 right-16 top-5 z-[500] flex flex-col gap-3 sm:right-auto">
                    <div class="pointer-events-auto flex w-full items-center gap-3 rounded-xl border border-slate-100 bg-white/95 p-2 shadow-sm backdrop-blur sm:w-80">
                        <i data-lucide="search" class="ml-2 h-4 w-4 shrink-0 text-slate-400"></i>
                        <input id="mapSearchInput" type="text" placeholder="Cari kota atau usaha halal..." class="w-full border-none bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400">
                    </div>
                    <div class="pointer-events-auto flex flex-wrap gap-2 sm:flex-nowrap">
                        <select id="mapCityFilter" class="cursor-pointer rounded-lg border border-slate-100 bg-white/95 px-3 py-2 text-xs font-bold text-slate-700 shadow-sm outline-none backdrop-blur">
                            <option value="">Semua Kota</option>
                            @foreach($mapCities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                        <select id="mapPartnerFilter" class="cursor-pointer rounded-lg border border-slate-100 bg-white/95 px-3 py-2 text-xs font-bold text-slate-700 shadow-sm outline-none backdrop-blur">
                            <option value="">Semua LPH/LP3H</option>
                            @foreach($lphPartners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pointer-events-auto absolute right-5 top-5 z-[500] flex flex-col overflow-hidden rounded-xl border border-slate-100 bg-white/95 shadow-sm backdrop-blur">
                    <button type="button" data-map-zoom="in" class="border-b border-slate-100 p-2.5 text-slate-500 transition hover:bg-slate-50">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                    </button>
                    <button type="button" data-map-zoom="out" class="p-2.5 text-slate-500 transition hover:bg-slate-50">
                        <i data-lucide="minus" class="h-4 w-4"></i>
                    </button>
                </div>

                <div id="leafletKaltim" class="absolute inset-0 z-0" data-map-url="{{ url('/api/map') }}"></div>

                <div class="pointer-events-none absolute bottom-5 left-0 right-0 z-[500] px-5">
                    <div class="pointer-events-auto flex justify-start">
                        <div class="relative">
                            <select id="mapCategoryFilter" class="cursor-pointer appearance-none rounded-xl border border-slate-200 bg-white/95 px-4 py-2.5 pr-10 text-xs font-bold text-slate-700 shadow-sm outline-none backdrop-blur">
                                <option value="">Semua Kategori</option>
                                @foreach($mapCategories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="chevrons-up-down" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="potensi" class="border-y border-slate-100 bg-white py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-20">
                <h2 class="text-center font-heading text-3xl font-extrabold tracking-tight text-slate-900">Data Potensi Pengembangan Ekonomi Syariah Kalimantan Timur</h2>
                <div class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-5">
                    @foreach($potentialItems as $item)
                        <div class="group cursor-pointer rounded-2xl border border-slate-100 bg-slate-50 p-5 text-center transition hover:bg-emerald-50">
                            <i data-lucide="{{ $item->icon_key }}" class="mx-auto mb-3 h-6 w-6 text-slate-400 transition group-hover:text-emerald-500"></i>
                            <h4 class="text-xs font-bold text-slate-800">{{ $item->title }}</h4>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h2 class="text-center font-heading text-3xl font-extrabold tracking-tight text-slate-900">Sektor Ekonomi Syariah</h2>
                <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-4">
                    @foreach($sectorItems as $item)
                        <div class="rounded-[1.75rem] border border-slate-100 p-6 transition hover:shadow-md">
                            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50">
                                <i data-lucide="{{ $item->icon_key }}" class="h-5 w-5 text-slate-700"></i>
                            </div>
                            <h4 class="mb-1 text-base font-bold text-slate-900">{{ $item->title }}</h4>
                            <p class="text-xs leading-relaxed text-slate-500">{{ $item->summary }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="sehati" class="relative z-10 py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="relative flex flex-col items-center justify-between gap-10 overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 md:flex-row md:p-16">
                <div class="pointer-events-none absolute right-0 top-0 h-96 w-96 rounded-full bg-emerald-500/20 blur-[100px]"></div>
                <div class="relative z-10 max-w-xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.28em] text-white">
                        100% Subsidi Pemerintah
                    </div>
                    <h2 class="mb-4 font-heading text-3xl font-extrabold tracking-tight text-white md:text-5xl">Program Sertifikasi <span class="text-emerald-400">SEHATI</span></h2>
                    <p class="mb-6 font-medium leading-relaxed text-slate-300">
                        Fasilitasi pembiayaan sertifikasi halal gratis untuk pelaku usaha mikro dan kecil melalui alur yang lebih ringan dan pendampingan yang terarah.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <span class="flex items-center gap-2 text-xs font-bold text-white"><i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i> Proses Mudah</span>
                        <span class="flex items-center gap-2 text-xs font-bold text-white"><i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i> Didampingi LP3H</span>
                    </div>
                </div>

                <div class="relative z-10 shrink-0">
                    <button onclick="openModal('sehatiModal')" class="flex items-center gap-2 rounded-2xl bg-emerald-500 px-8 py-4 font-extrabold text-slate-900 shadow-[0_0_20px_rgba(16,185,129,0.3)] transition-all hover:-translate-y-1 hover:bg-emerald-400">
                        Mulai Pendaftaran
                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section id="direktori" class="border-t border-slate-100 bg-white py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-16 lg:grid-cols-2">
                <div>
                    <div class="mb-8 flex items-end justify-between">
                        <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Direktori Produk</h2>
                        <a href="{{ route('products.index') }}" class="text-sm font-bold text-emerald-600">Lihat semua</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($featuredProducts->take(4) as $product)
                            <a href="#" class="group flex cursor-pointer items-center gap-4 rounded-2xl border border-slate-100 p-4 transition hover:border-slate-200">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-50">
                                    <i data-lucide="package" class="h-5 w-5 text-slate-400"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-slate-900 transition group-hover:text-emerald-600">{{ $product->nama_produk }}</h4>
                                    <p class="text-[11px] font-medium text-slate-500">{{ $product->umkm?->nama_umkm ?? 'Produk UMKM' }}</p>
                                </div>
                                <span class="rounded bg-emerald-50 px-2 py-1 text-[9px] font-bold uppercase text-emerald-600">Terverifikasi</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div id="data">
                    <div class="mb-8 flex items-end justify-between">
                        <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Dokumen & Regulasi</h2>
                        <div class="flex gap-4">
                            <a href="{{ route('resources.index') }}" class="text-sm font-bold text-emerald-600">Dokumen</a>
                            <a href="{{ route('regulations.index') }}" class="text-sm font-bold text-emerald-600">Regulasi</a>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @foreach($resources->take(2) as $resource)
                            <a href="{{ route('resources.show', $resource->slug) }}" class="group flex items-center justify-between rounded-2xl border border-slate-100 p-4 transition hover:border-slate-200">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-500">
                                        <i data-lucide="file-text" class="h-4 w-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 transition group-hover:text-emerald-600">{{ $resource->title }}</h4>
                                        <p class="text-[11px] font-medium text-slate-500">Dokumen • {{ optional($resource->published_at)->translatedFormat('M Y') }}</p>
                                    </div>
                                </div>
                                <i data-lucide="download" class="h-4 w-4 text-slate-400 transition group-hover:text-emerald-600"></i>
                            </a>
                        @endforeach

                        @foreach($regulations->take(2) as $regulation)
                            <a href="{{ route('regulations.show', $regulation->slug) }}" class="group flex items-center justify-between rounded-2xl border border-slate-100 p-4 transition hover:border-slate-200">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-500">
                                        <i data-lucide="scale" class="h-4 w-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 transition group-hover:text-emerald-600">{{ $regulation->title }}</h4>
                                        <p class="text-[11px] font-medium text-slate-500">{{ $regulation->regulation_number }}</p>
                                    </div>
                                </div>
                                <i data-lucide="arrow-up-right" class="h-4 w-4 text-slate-400 transition group-hover:text-emerald-600"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="artikel" class="border-t border-slate-100 bg-slate-50 py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Artikel & Publikasi</h2>
                    <p class="mt-2 text-sm font-medium text-slate-500">Update berita, publikasi, dan riset terbaru KDEKS Kaltim.</p>
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
                            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-emerald-600">{{ strtoupper($article->type) }}</p>
                            <h3 class="mt-3 text-base font-extrabold text-slate-900">{{ $article->title }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-500">{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->body), 110) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="agenda" class="border-t border-slate-100 bg-white py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Agenda Kegiatan</h2>
                    <p class="mt-2 text-sm font-medium text-slate-500">Kegiatan dan agenda resmi yang bisa diikuti masyarakat serta pelaku usaha.</p>
                </div>
                <a href="{{ route('events.index') }}" class="text-sm font-bold text-emerald-600">Lihat semua</a>
            </div>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @foreach($events->take(4) as $event)
                    <a href="{{ route('events.show', $event->slug) }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-emerald-600">{{ optional($event->starts_at)->translatedFormat('d M Y') }}</p>
                        <h3 class="mt-3 text-base font-extrabold text-slate-900">{{ $event->title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">{{ $event->summary }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="gallery" class="border-t border-slate-100 bg-slate-50 py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Galeri Dokumentasi</h2>
                    <p class="mt-2 text-sm font-medium text-slate-500">Dokumentasi visual kegiatan, workshop, dan publikasi KDEKS Kaltim.</p>
                </div>
                <a href="{{ route('gallery.index') }}" class="text-sm font-bold text-emerald-600">Lihat semua</a>
            </div>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @foreach($galleryItems->take(4) as $item)
                    <a href="{{ route('gallery.index') }}" class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        @if($item->media_type === 'image' && $item->media_path)
                            <img src="{{ asset('storage/'.$item->media_path) }}" alt="{{ $item->title }}" class="h-56 w-full object-cover">
                        @else
                            <div class="flex h-56 items-center justify-center bg-slate-900 text-sm font-bold text-white">Media Video</div>
                        @endif
                        <div class="p-5">
                            <h3 class="text-base font-extrabold text-slate-900">{{ $item->title }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-500">{{ $item->caption ?: 'Dokumentasi resmi KDEKS Kaltim.' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

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

    <div id="sehatiModal" class="fixed inset-0 z-[100] hidden" @if($sehatiErrors->any()) data-open-on-load="true" @endif>
        <div id="sehatiBackdrop" class="absolute inset-0 bg-slate-900/40 opacity-0 backdrop-blur-sm transition-opacity" onclick="closeModal('sehatiModal')"></div>
        <div class="pointer-events-none absolute inset-0 flex items-start justify-center overflow-y-auto px-4 pb-10 pt-20">
            <div id="sehatiContent" class="pointer-events-auto w-full max-w-2xl scale-95 rounded-3xl bg-white opacity-0 shadow-2xl transition-all">
                <div class="flex items-center justify-between rounded-t-3xl border-b border-slate-100 bg-slate-50 p-6">
                    <div>
                        <h3 class="font-heading text-xl font-extrabold tracking-tight text-slate-900">Formulir SEHATI</h3>
                        <p class="text-xs font-medium text-slate-500">Pengajuan Sertifikasi Halal Gratis</p>
                    </div>
                    <button onclick="closeModal('sehatiModal')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <div class="p-6 md:p-8">
                    <form method="POST" action="{{ route('sehati.store') }}" class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Lembaga Pendamping (LP3H)</label>
                            <select name="lph_partner_id" class="w-full cursor-pointer rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white">
                                <option value="">-- Pilih Lembaga Pendamping --</option>
                                @foreach($lphPartners as $partner)
                                    @if($partner->partner_type === 'lp3h')
                                        <option value="{{ $partner->id }}" @selected(old('lph_partner_id') == $partner->id)>{{ $partner->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if($sehatiErrors->has('lph_partner_id'))
                                <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('lph_partner_id') }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Nama Pemilik UMKM</label>
                            <input type="text" name="owner_name" value="{{ old('owner_name') }}" placeholder="Sesuai KTP" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white" required>
                            @if($sehatiErrors->has('owner_name'))
                                <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('owner_name') }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Nama UMKM</label>
                            <input type="text" name="business_name" value="{{ old('business_name') }}" placeholder="Contoh: Kedai Mubarok" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white" required>
                            @if($sehatiErrors->has('business_name'))
                                <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('business_name') }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Nama Produk yang Diajukan</label>
                            <input type="text" name="product_name" value="{{ old('product_name') }}" placeholder="Jenis/Nama Produk" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white" required>
                            @if($sehatiErrors->has('product_name'))
                                <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('product_name') }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Nomor HP / WA</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white" required>
                            @if($sehatiErrors->has('phone'))
                                <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('phone') }}</p>
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Deskripsi Singkat Usaha</label>
                            <textarea name="description" rows="3" placeholder="Jelaskan secara singkat..." class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white">{{ old('description') }}</textarea>
                            @if($sehatiErrors->has('description'))
                                <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('description') }}</p>
                            @endif
                        </div>
                        <div class="md:col-span-2 pt-2">
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 py-3.5 text-sm font-extrabold text-white shadow-md transition-colors hover:bg-emerald-400">
                                Ajukan Pendaftaran
                                <i data-lucide="send" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="searchModal" class="fixed inset-0 z-[100] hidden">
        <div id="searchBackdrop" class="absolute inset-0 bg-white/95 opacity-0 backdrop-blur-md transition-opacity" onclick="closeModal('searchModal')"></div>
        <div id="searchContent" class="absolute left-1/2 top-20 w-full max-w-2xl -translate-x-1/2 scale-95 px-4 opacity-0 transition-all sm:top-24">
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400 sm:left-5 sm:h-6 sm:w-6"></i>
                <input id="globalSearchInput" type="text" placeholder="Ketik kata kunci..." class="w-full rounded-2xl border border-slate-200 bg-white py-4 pl-12 pr-16 text-base font-bold text-slate-900 shadow-xl focus:border-emerald-500/50 focus:outline-none sm:py-5 sm:pl-16 sm:text-lg">
                <button onclick="closeModal('searchModal')" class="absolute right-3 top-1/2 -translate-y-1/2 rounded bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-400 hover:text-slate-900 sm:right-5 sm:text-xs">ESC</button>
            </div>
            <div class="mt-4 flex flex-wrap justify-center gap-2">
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">Alur Sertifikasi</span>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">LPH Samarinda</span>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">Produk Halal</span>
            </div>
            <div id="searchResults" class="mt-5 grid gap-3 sm:grid-cols-2"></div>
        </div>
    </div>

    <script>
        (() => {
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

                const map = L.map(mapElement, { zoomControl: false }).setView([0.706, 116.426], 8);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                }).addTo(map);

                const highlightKaltim = async () => {
                    try {
                        // Using a more up-to-date GeoJSON source that separates Kaltara from Kaltim
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
                    } catch (e) {
                        // Silent fail
                    }
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
                const searchInput = document.getElementById('mapSearchInput');
                const categoryFilter = document.getElementById('mapCategoryFilter');
                const zoomButtons = document.querySelectorAll('[data-map-zoom]');
                const markersLayer = L.layerGroup().addTo(map);

                const state = {
                    category: '',
                    city: '',
                    lph_partner_id: '',
                    keyword: '',
                };

                const renderMap = async () => {
                    const query = new URLSearchParams();
                    Object.entries(state).forEach(([key, value]) => {
                        if (value) query.set(key, value);
                    });

                    try {
                        const response = await fetch(`${mapElement.dataset.mapUrl}?${query.toString()}`);
                        const result = await response.json();
                        const regions = normalizeCollection(result?.data?.regions);
                        const locations = normalizeCollection(result?.data?.locations)
                            .filter((location) => Number.isFinite(Number(location.latitude)) && Number.isFinite(Number(location.longitude)));

                        markersLayer.clearLayers();

                        regions.forEach((region) => {
                            if (!Number.isFinite(Number(region.latitude)) || !Number.isFinite(Number(region.longitude))) {
                                return;
                            }

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
                            map.setView([0.706, 116.426], 8);
                        }
                    } catch (error) {
                        markersLayer.clearLayers();
                    }
                };

                cityFilter?.addEventListener('change', () => {
                    state.city = cityFilter.value;
                    renderMap();
                });

                partnerFilter?.addEventListener('change', () => {
                    state.lph_partner_id = partnerFilter.value;
                    renderMap();
                });

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
