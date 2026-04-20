@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <article class="mx-auto max-w-4xl px-6 pb-20 pt-28">
        <a href="{{ route('articles.index') }}" class="text-sm font-bold text-emerald-600">← Kembali ke artikel</a>
        <p class="mt-8 text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">{{ strtoupper($article->type) }}</p>
        <h1 class="mt-4 font-heading text-4xl font-extrabold text-slate-900">{{ $article->title }}</h1>
        <div class="mt-4 flex flex-wrap gap-4 text-sm font-medium text-slate-500">
            <span>{{ $article->author_name ?: 'Tim KDEKS' }}</span>
            <span>{{ optional($article->published_at)->translatedFormat('d F Y') }}</span>
        </div>

        @if($article->cover_image_path)
            <img src="{{ asset('storage/' . $article->cover_image_path) }}" alt="{{ $article->title }}"
                class="mt-8 h-auto w-full rounded-[2rem] border border-slate-200 object-cover shadow-sm">
        @endif

        <div class="prose prose-slate mt-10 max-w-none prose-headings:font-heading prose-a:text-emerald-600">
            {!! $article->body !!}
        </div>

        @if($article->document_path)
            <div class="mt-10">
                <a href="{{ route('documents.download', ['type' => 'article', 'id' => $article->id]) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white">
                    Unduh Dokumen Pendukung
                </a>
            </div>
        @endif

        @if($relatedArticles->isNotEmpty())
            <section class="mt-16 border-t border-slate-200 pt-10">
                <h2 class="font-heading text-2xl font-extrabold text-slate-900">Artikel Lainnya</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach($relatedArticles as $relatedArticle)
                        <a href="{{ route('articles.show', $relatedArticle->slug) }}"
                            class="rounded-2xl border border-slate-200 bg-white p-4 text-sm font-bold text-slate-700 transition hover:border-emerald-300 hover:text-emerald-600">
                            {{ $relatedArticle->title }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
@endsection