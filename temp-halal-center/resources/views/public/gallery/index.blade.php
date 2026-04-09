@extends('layouts.app')

@section('title', 'Galeri Kegiatan')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pb-20 pt-28">
        <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">Dokumentasi</p>
        <h1 class="mt-2 font-heading text-4xl font-extrabold text-slate-900">Galeri Kegiatan</h1>
        <p class="mt-3 max-w-2xl text-sm font-medium leading-7 text-slate-500">Dokumentasi kegiatan, publikasi visual, dan rekaman aktivitas ekosistem halal KDEKS Kaltim.</p>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($galleryItems as $item)
                <article class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                    @if($item->media_type === 'image' && $item->media_path)
                        <img src="{{ asset('storage/'.$item->media_path) }}" alt="{{ $item->title }}" class="h-64 w-full object-cover">
                    @elseif($item->media_type === 'video' && $item->external_video_url)
                        <div class="flex h-64 items-center justify-center bg-slate-900 px-6 text-center text-sm font-semibold text-white">
                            <a href="{{ $item->external_video_url }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-white px-4 py-3 text-slate-900">Buka Video Eksternal</a>
                        </div>
                    @else
                        <div class="flex h-64 items-center justify-center bg-slate-100 text-sm font-semibold text-slate-400">Media belum tersedia</div>
                    @endif
                    <div class="p-6">
                        <h2 class="text-lg font-extrabold text-slate-900">{{ $item->title }}</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-500">{{ $item->caption ?: 'Dokumentasi resmi KDEKS Kaltim.' }}</p>
                        <p class="mt-4 text-xs font-semibold text-slate-400">{{ optional($item->recorded_at)->translatedFormat('d F Y') }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">{{ $galleryItems->links() }}</div>
    </section>
@endsection
