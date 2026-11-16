@extends('layouts.app')

@section('title', $sector->title . ' - KDEKS Kalimantan Timur')

@section('content')
    <section class="relative min-h-[40vh] overflow-hidden pt-32 pb-16">
        <div
            class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.05),transparent_40%)]">
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-6 pt-4">
            <!-- <nav class="mb-8 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                    <a href="{{ route('home') }}" class="transition hover:text-emerald-600">Beranda</a>
                    <i data-lucide="chevron-right" class="h-3 w-3"></i>
                    <span class="text-slate-500">Direktorat</span>
                    <i data-lucide="chevron-right" class="h-3 w-3"></i>
                    <span class="text-emerald-600">{{ $sector->title }}</span>
                </nav> -->

            <div class="max-w-4xl">
                <div
                    class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                    <i data-lucide="{{ $sector->icon_key }}" class="h-8 w-8"></i>
                </div>
                <h1 class="font-heading text-4xl font-extrabold leading-tight text-slate-900 md:text-5xl lg:text-6xl">
                    Direktorat <br>
                    <span class="text-gradient">{{ $sector->title }}</span>
                </h1>
                <p class="mt-6 text-lg font-medium leading-relaxed text-slate-500">
                    {{ $sector->summary }}
                </p>
            </div>
        </div>
    </section>

    <section class="relative pb-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-12 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div
                        class="group relative overflow-hidden rounded-[2.5rem] border border-white bg-white/80 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl md:p-12 transition-all hover:shadow-[0_8px_40px_rgb(0,0,0,0.06)]">
                        <div
                            class="absolute right-0 top-0 h-48 w-48 translate-x-16 -translate-y-16 rounded-full bg-emerald-500/5 blur-3xl transition-all group-hover:bg-emerald-500/10">
                        </div>

                        <div class="relative">
                            <div class="prose prose-emerald prose-lg max-w-none
                                    prose-headings:font-heading prose-headings:font-bold prose-headings:text-slate-900
                                    prose-p:text-slate-600 prose-p:leading-relaxed
                                    prose-li:text-slate-600 prose-li:leading-relaxed
                                    prose-strong:text-slate-900 prose-strong:font-bold
                                    prose-ol:list-decimal prose-ul:list-disc
                                    prose-li:marker:text-emerald-500">
                                {!! $sector->content !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Other Directorates -->
                    <div class="rounded-[2.5rem] border border-slate-100 bg-white p-8 shadow-sm">
                        <h3 class="mb-6 font-heading text-xl font-bold text-slate-900">Direktorat Lainnya</h3>
                        <div class="space-y-4">
                            @foreach($otherSectors as $other)
                                <a href="{{ route('direktorat.show', $other->slug) }}"
                                    class="group flex items-center gap-4 rounded-2xl border border-slate-50 bg-slate-50/50 p-4 transition-all hover:border-emerald-200 hover:bg-emerald-50">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                                        <i data-lucide="{{ $other->icon_key }}" class="h-5 w-5"></i>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-slate-700 transition-colors group-hover:text-emerald-700">{{ $other->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- CTA -->
                    <div
                        class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 text-white transition-all hover:scale-[1.02]">
                        <div class="absolute -right-4 -top-4 h-32 w-32 rounded-full bg-emerald-500/20 blur-2xl"></div>
                        <div class="relative z-10">
                            <h4 class="mb-2 text-lg font-bold">Konsultasi Terpadu</h4>
                            <p class="mb-6 text-sm leading-relaxed text-slate-400">Hubungi tim ahli kami untuk informasi
                                lebih lanjut mengenai program direktoral.</p>
                            <a href="{{ route('contact') }}"
                                class="flex items-center justify-center gap-2 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white transition hover:bg-emerald-400">
                                Hubungi Kami
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($galleryItems->count() > 0)
    <section class="relative bg-slate-50 py-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 flex items-end justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-emerald-600">Dokumentasi</p>
                    <h2 class="mt-4 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">Galeri Kegiatan</h2>
                    <p class="mt-4 text-sm font-medium text-slate-500">Kumpulan dokumentasi kegiatan dari Direktorat {{ $sector->title }}.</p>
                </div>
                <a href="{{ route('gallery.index') }}" class="group flex items-center gap-2 text-sm font-bold text-emerald-600 transition hover:text-emerald-500">
                    Lihat Semua
                    <i data-lucide="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($galleryItems as $item)
                    @php
                        $photos = $item->all_media_paths;
                        $photoCount = count($photos);
                        $primaryUrl = $item->media_type === 'image' && !empty($photos) ? \Illuminate\Support\Facades\Storage::url($photos[0]) : $item->external_video_url;
                        $allUrls = $item->all_media_urls;
                    @endphp
                    <article 
                        class="group cursor-pointer overflow-hidden rounded-[2rem] border border-white bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/10"
                        onclick="showGalleryDetail({{ json_encode([
                            'title' => $item->title,
                            'caption' => $item->caption,
                            'media_type' => $item->media_type,
                            'media_url' => $primaryUrl,
                            'media_urls' => $allUrls,
                            'photo_count' => $photoCount,
                            'recorded_at' => optional($item->recorded_at)->translatedFormat('d F Y'),
                            'sector' => $sector->title
                        ]) }})"
                    >
                        <div class="relative aspect-video overflow-hidden bg-slate-100">
                            @if($item->media_type === 'image' && !empty($photos))
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($photos[0]) }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @elseif($item->media_type === 'video' && $item->external_video_url)
                                <div class="flex h-full w-full items-center justify-center bg-slate-900">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 backdrop-blur-md">
                                        <i data-lucide="play" class="h-5 w-5 fill-white text-white"></i>
                                    </div>
                                </div>
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-slate-50 text-slate-300">
                                    <i data-lucide="image" class="h-12 w-12"></i>
                                </div>
                            @endif

                            @if($photoCount > 1)
                                <div class="absolute right-4 top-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-900/80 px-2.5 py-1 text-[10px] font-bold text-white shadow-sm backdrop-blur-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        {{ $photoCount }} Foto
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Multiple photo thumbnail preview bar inside the card --}}
                        @if($photoCount > 1)
                            <div class="grid grid-cols-5 gap-1.5 p-3 bg-slate-50/80 border-b border-slate-100">
                                @foreach($photos as $pIdx => $pPath)
                                    <div class="relative aspect-square overflow-hidden rounded-lg border border-slate-200 bg-white">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($pPath) }}" alt="Foto {{ $pIdx + 1 }}" class="h-full w-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="font-heading text-lg font-bold text-slate-900 line-clamp-1 group-hover:text-emerald-600 transition-colors">{{ $item->title }}</h3>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">{{ optional($item->recorded_at)->translatedFormat('d F Y') }}</span>
                                </div>
                                <span class="text-xs font-bold text-emerald-600 group-hover:underline">
                                    {{ $photoCount > 1 ? $photoCount . ' Foto' : 'Detail' }}
                                </span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        let activeGalleryUrls = [];
        let currentGalleryIndex = 0;

        function setGalleryIndex(index) {
            if (activeGalleryUrls.length === 0) return;
            currentGalleryIndex = (index + activeGalleryUrls.length) % activeGalleryUrls.length;
            const mainImg = document.getElementById('galleryActiveMainImg');
            if (mainImg) {
                mainImg.src = activeGalleryUrls[currentGalleryIndex];
            }
            const counter = document.getElementById('galleryModalCounter');
            if (counter) {
                counter.innerText = `${currentGalleryIndex + 1} / ${activeGalleryUrls.length}`;
            }
            document.querySelectorAll('[data-gallery-thumb]').forEach((thumb, idx) => {
                if (idx === currentGalleryIndex) {
                    thumb.classList.add('ring-2', 'ring-emerald-500', 'opacity-100');
                    thumb.classList.remove('opacity-60');
                } else {
                    thumb.classList.remove('ring-2', 'ring-emerald-500', 'opacity-100');
                    thumb.classList.add('opacity-60');
                }
            });
        }

        function showGalleryDetail(data) {
            const modal = document.getElementById('galleryModal');
            const title = document.getElementById('galleryModalTitle');
            const caption = document.getElementById('galleryModalCaption');
            const date = document.getElementById('galleryModalDate');
            const sector = document.getElementById('galleryModalSector');
            const mediaContainer = document.getElementById('galleryModalMedia');

            if(!modal || !title || !caption || !date || !sector || !mediaContainer) return;

            title.innerText = data.title;
            caption.innerText = data.caption || 'Tidak ada deskripsi.';
            date.innerText = data.recorded_at;
            sector.innerText = data.sector;

            mediaContainer.innerHTML = '';
            activeGalleryUrls = data.media_urls && data.media_urls.length > 0 ? data.media_urls : (data.media_url ? [data.media_url] : []);
            currentGalleryIndex = 0;

            if (data.media_type === 'image' && activeGalleryUrls.length > 0) {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative flex flex-col items-center';

                let controlsHtml = '';
                if (activeGalleryUrls.length > 1) {
                    controlsHtml = `
                        <button type="button" onclick="setGalleryIndex(currentGalleryIndex - 1)" class="absolute left-3 top-1/2 -translate-y-1/2 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-slate-900/60 text-white shadow-md backdrop-blur-sm transition hover:bg-slate-900 hover:scale-105 active:scale-95" title="Foto Sebelumnya">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        </button>
                        <button type="button" onclick="setGalleryIndex(currentGalleryIndex + 1)" class="absolute right-3 top-1/2 -translate-y-1/2 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-slate-900/60 text-white shadow-md backdrop-blur-sm transition hover:bg-slate-900 hover:scale-105 active:scale-95" title="Foto Selanjutnya">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </button>
                        <div class="absolute bottom-3 right-3 z-20 rounded-full bg-slate-900/75 px-3 py-1 text-xs font-bold text-white shadow-sm backdrop-blur-md" id="galleryModalCounter">
                            1 / ${activeGalleryUrls.length}
                        </div>
                    `;
                }

                let thumbsHtml = '';
                if (activeGalleryUrls.length > 1) {
                    thumbsHtml = `<div class="mt-3 flex w-full gap-2 overflow-x-auto pb-1 justify-center">`;
                    activeGalleryUrls.forEach((url, idx) => {
                        thumbsHtml += `
                            <button type="button" onclick="setGalleryIndex(${idx})" data-gallery-thumb="${idx}" class="relative h-14 w-14 shrink-0 overflow-hidden rounded-xl border border-slate-200 transition ${idx === 0 ? 'ring-2 ring-emerald-500 opacity-100' : 'opacity-60 hover:opacity-100'}">
                                <img src="${url}" class="h-full w-full object-cover">
                            </button>
                        `;
                    });
                    thumbsHtml += `</div>`;
                }

                wrapper.innerHTML = `
                    <div class="relative w-full overflow-hidden rounded-2xl bg-black">
                        <img id="galleryActiveMainImg" src="${activeGalleryUrls[0]}" class="w-full max-h-[50vh] object-contain mx-auto transition-opacity duration-300">
                        ${controlsHtml}
                    </div>
                    ${thumbsHtml}
                `;

                mediaContainer.appendChild(wrapper);
            } else if (data.media_type === 'video') {
                if (data.media_url.includes('youtube.com') || data.media_url.includes('youtu.be')) {
                    let videoId = '';
                    if (data.media_url.includes('youtube.com')) {
                        videoId = new URL(data.media_url).searchParams.get('v');
                    } else {
                        videoId = data.media_url.split('/').pop();
                    }
                    mediaContainer.innerHTML = `
                        <div class="relative aspect-video overflow-hidden rounded-2xl shadow-lg">
                            <iframe src="https://www.youtube.com/embed/${videoId}" class="absolute inset-0 h-full w-full border-0" allowfullscreen></iframe>
                        </div>
                    `;
                } else {
                    mediaContainer.innerHTML = `
                        <div class="flex flex-col items-center justify-center rounded-2xl bg-slate-900 p-12 text-center text-white">
                            <i data-lucide="external-link" class="mb-4 h-12 w-12 text-emerald-400"></i>
                            <h4 class="text-lg font-bold">Video Eksternal</h4>
                            <p class="mt-2 text-sm text-slate-400">Video ini dihosting di platform eksternal.</p>
                            <a href="${data.media_url}" target="_blank" class="mt-6 rounded-xl bg-emerald-500 px-6 py-3 text-sm font-bold transition hover:bg-emerald-400">Buka Video</a>
                        </div>
                    `;
                    if (window.lucide) window.lucide.createIcons();
                }
            }

            if (typeof window.openModal === 'function') {
                window.openModal('galleryModal');
            }
        }
    </script>
    @endif
@endsection
