@extends('layouts.app')

@section('title', 'Artikel & Publikasi')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pb-20 pt-28">
        <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">Publikasi</p>
                <h1 class="mt-2 font-heading text-4xl font-extrabold text-slate-900">Artikel & Publikasi</h1>
                <p class="mt-3 max-w-2xl text-sm font-medium leading-7 text-slate-500">Semua berita, riset, dan publikasi terbaru KDEKS Kaltim tersedia dalam satu alur yang bisa dibaca penuh.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach(['' => 'Semua', 'news' => 'Berita', 'publication' => 'Publikasi', 'research' => 'Riset'] as $type => $label)
                    <a href="{{ $type ? route('articles.index', ['type' => $type]) : route('articles.index') }}" class="rounded-full border px-4 py-2 text-xs font-bold {{ $selectedType === $type ? 'border-emerald-500 bg-emerald-50 text-emerald-600' : 'border-slate-200 bg-white text-slate-500' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($articles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    @if($article->cover_image_path)
                        <img src="{{ asset('storage/'.$article->cover_image_path) }}" alt="{{ $article->title }}" class="h-56 w-full object-cover">
                    @endif
                    <div class="p-6">
                        <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-emerald-600">{{ strtoupper($article->type) }}</p>
                        <h2 class="mt-3 text-lg font-extrabold text-slate-900 transition group-hover:text-emerald-600">{{ $article->title }}</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-500">{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->body), 140) }}</p>
                        <div class="mt-5 flex items-center justify-between text-xs font-semibold text-slate-400">
                            <span>{{ $article->author_name ?: 'Tim KDEKS' }}</span>
                            <span>{{ optional($article->published_at)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $articles->links() }}</div>
    </section>
@endsection
