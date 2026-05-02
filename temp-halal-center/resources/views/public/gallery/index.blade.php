@extends('layouts.app')

@section('title', 'Galeri Kegiatan - KDEKS Kalimantan Timur')

@section('content')
    <section class="relative min-h-[30vh] overflow-hidden pt-32 pb-16">
        <div class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.05),transparent_40%)]"></div>
        <div class="relative z-10 mx-auto max-w-7xl px-6">
            <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-emerald-600">Dokumentasi Visual</p>
            <h1 class="mt-4 font-heading text-4xl font-extrabold leading-tight text-slate-900 md:text-5xl lg:text-6xl">
                Galeri <span class="text-gradient">Kegiatan</span>
            </h1>
            <p class="mt-6 max-w-2xl text-lg font-medium leading-relaxed text-slate-500">
                Arsip visual dokumentasi kegiatan, publikasi, dan aktivitas strategis ekosistem ekonomi syariah di Kalimantan Timur.
            </p>
        </div>
    </section>

    <section class="pb-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($galleryItems as $item)
                    <article 
                        class="group cursor-pointer overflow-hidden rounded-[2rem] border border-slate-100 bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/10"
                        onclick="showGalleryDetail({{ json_encode([
                            'title' => $item->title,
                            'caption' => $item->caption,
                            'media_type' => $item->media_type,
                            'media_url' => $item->media_type === 'image' && $item->media_path ? \Illuminate\Support\Facades\Storage::url($item->media_path) : $item->external_video_url,
                            'recorded_at' => optional($item->recorded_at)->translatedFormat('d F Y'),
                            'sector' => $item->sectorItem ? $item->sectorItem->title : 'Umum'
                        ]) }})"
                    >
                        <div class="relative aspect-[4/3] overflow-hidden">
                            @if($item->media_type === 'image' && $item->media_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($item->media_path) }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @elseif($item->media_type === 'video' && $item->external_video_url)
                                <div class="flex h-full w-full items-center justify-center bg-slate-900">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-md transition-transform group-hover:scale-110">
                                        <i data-lucide="play" class="h-6 w-6 fill-white text-white"></i>
                                    </div>
                                </div>
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-slate-50 text-slate-300">
                                    <i data-lucide="image" class="h-12 w-12"></i>
                                </div>
                            @endif
                            
                            @if($item->sectorItem)
                                <div class="absolute left-4 top-4">
                                    <span class="rounded-full bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700 backdrop-blur-md shadow-sm">
                                        {{ $item->sectorItem->title }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="p-8">
                            <h2 class="font-heading text-xl font-bold text-slate-900 line-clamp-1 group-hover:text-emerald-600 transition-colors">{{ $item->title }}</h2>
                            <p class="mt-3 text-sm font-medium leading-relaxed text-slate-500 line-clamp-2">
                                {{ $item->caption ?: 'Tidak ada deskripsi.' }}
                            </p>
                            <div class="mt-6 flex items-center justify-between border-t border-slate-50 pt-6">
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i data-lucide="calendar" class="h-4 w-4"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider">{{ optional($item->recorded_at)->translatedFormat('d F Y') ?: 'Baru Saja' }}</span>
                                </div>
                                <span class="text-xs font-extrabold text-emerald-600 group-hover:underline">Lihat Detail</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-16">
                {{ $galleryItems->links() }}
            </div>
        </div>
    </section>

    <script>
        function showGalleryDetail(data) {
            const modal = document.getElementById('galleryModal');
            const title = document.getElementById('galleryModalTitle');
            const caption = document.getElementById('galleryModalCaption');
            const date = document.getElementById('galleryModalDate');
            const sector = document.getElementById('galleryModalSector');
            const mediaContainer = document.getElementById('galleryModalMedia');

            title.innerText = data.title;
            caption.innerText = data.caption || 'Tidak ada deskripsi.';
            date.innerText = data.recorded_at;
            sector.innerText = data.sector;

            mediaContainer.innerHTML = '';
            if (data.media_type === 'image') {
                const img = document.createElement('img');
                img.src = data.media_url;
                img.className = 'w-full max-h-[50vh] rounded-2xl object-cover shadow-lg';
                mediaContainer.appendChild(img);
            } else if (data.media_type === 'video') {
                // Check if youtube
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

            openModal('galleryModal');
        }
    </script>
@endsection

