@extends('layouts.app')

@section('title', 'Siaran Pers - ' . data_get($setting, 'meta_title', 'Halal Center Kaltim'))

@section('content')
<section class="relative pt-24 pb-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight font-heading">Siaran Pers</h1>
            <p class="mt-2 text-slate-500 font-medium">Arsip video dan liputan kegiatan resmi KDEKS Kaltim.</p>
        </div>

        @if($featured)
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Player Area (Left) -->
            <div class="flex-1">
                <div class="aspect-video w-full bg-slate-900 rounded-2xl overflow-hidden shadow-2xl shadow-slate-200 group relative">
                    <iframe 
                        id="main-player"
                        class="w-full h-full"
                        src="{{ $featured->embed_url }}" 
                        title="{{ $featured->title }}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="mt-6">
                    <h2 class="text-2xl font-bold text-slate-900" id="current-title">{{ $featured->title }}</h2>
                    <p class="mt-2 text-slate-600 leading-relaxed" id="current-description">
                        {{ $featured->description ?? 'Tidak ada deskripsi untuk video ini.' }}
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-slate-400 text-sm">
                        <i data-lucide="calendar" class="h-4 w-4"></i>
                        <span>{{ $featured->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Playlist Area (Right) -->
            <div class="w-full lg:w-96 flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 text-lg">Video Lainnya</h3>
                    <span class="text-sm text-slate-500">{{ $releases->total() }} Video</span>
                </div>
                
                <div class="flex flex-col gap-4 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                    @php
                        // Check if current featured is the streaming one
                        $isCurrentFeaturedStreaming = ($streaming && $featured && $streaming->id === $featured->id);
                    @endphp

                    {{-- Always show streaming video if it's NOT the one currently in the player --}}
                    @if($streaming && (!$featured || $streaming->id !== $featured->id))
                    <a href="?v={{ $streaming->id }}" class="flex gap-4 p-2 rounded-xl bg-rose-50 ring-1 ring-rose-200 border-l-4 border-rose-500 transition-all group">
                        <div class="relative w-32 h-20 bg-slate-200 rounded-lg overflow-hidden shrink-0">
                            <img src="{{ $streaming->thumbnail_url }}" alt="{{ $streaming->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 flex items-center justify-center bg-rose-500/10">
                                <span class="bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full font-extrabold animate-pulse">LIVE NOW</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 text-sm line-clamp-2 group-hover:text-rose-600 transition-colors">{{ $streaming->title }}</h4>
                            <p class="text-[10px] text-rose-500 font-bold mt-1">Sedang Berlangsung</p>
                        </div>
                    </a>
                    @endif

                    {{-- Current Featured (If it's playing, show it in the playlist area too with "Playing" mark) --}}
                    @if($featured)
                    <div class="flex gap-4 p-2 rounded-xl bg-slate-50 ring-1 ring-slate-200 border-l-4 border-primary">
                        <div class="relative w-32 h-20 bg-slate-200 rounded-lg overflow-hidden shrink-0">
                            <img src="{{ $featured->thumbnail_url }}" alt="{{ $featured->title }}" class="w-full h-full object-cover opacity-60">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="bg-primary text-white text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider">
                                    {{ $isCurrentFeaturedStreaming ? 'PLAYING LIVE' : 'PLAYING' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 text-sm line-clamp-2">{{ $featured->title }}</h4>
                            <p class="text-xs text-slate-500 mt-1">{{ $featured->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif

                    @foreach ($releases as $release)
                    {{-- Skip if it's the featured one or the streaming one (already shown above) --}}
                    @continue(($featured && $release->id === $featured->id) || ($streaming && $release->id === $streaming->id))
                    
                    <a href="?v={{ $release->id }}" class="flex gap-4 p-2 rounded-xl hover:bg-slate-50 transition-all group">
                        <div class="relative w-32 h-20 bg-slate-200 rounded-lg overflow-hidden shrink-0">
                            <img src="{{ $release->thumbnail_url }}" alt="{{ $release->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 text-sm line-clamp-2 group-hover:text-primary transition-colors">{{ $release->title }}</h4>
                            <p class="text-xs text-slate-500 mt-1">{{ $release->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>

                @if($releases->hasPages())
                <div class="mt-4">
                    {{ $releases->links() }}
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="py-20 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 mb-4">
                <i data-lucide="video-off" class="h-10 w-10 text-slate-400"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Belum ada siaran pers</h3>
            <p class="text-slate-500 mt-2">Silakan kembali lagi nanti untuk update video terbaru.</p>
        </div>
        @endif
    </div>
</section>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection
