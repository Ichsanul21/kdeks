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
            <div class="mt-12 rounded-[2rem] border border-slate-200 bg-slate-50 p-8">
                <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <h3 class="font-heading text-xl font-bold text-slate-900">Dokumen Pendukung</h3>
                        <p class="mt-1 text-sm text-slate-500">Lihat atau unduh dokumen pendukung untuk artikel ini.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('documents.download', ['type' => 'article', 'id' => $article->id]) }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-emerald-600/20 transition hover:bg-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Unduh Dokumen
                        </a>
                        <a href="{{ route('documents.view', ['type' => 'article', 'id' => $article->id]) }}" target="_blank"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                            Buka Fullscreen
                        </a>
                    </div>
                </div>
                
                @if(strtolower(pathinfo($article->document_path, PATHINFO_EXTENSION)) === 'pdf')
                    <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <iframe src="{{ route('documents.view', ['type' => 'article', 'id' => $article->id]) }}#toolbar=0" class="h-[600px] w-full" frameborder="0"></iframe>
                    </div>
                @else
                    <div class="mt-8 flex items-center gap-4 rounded-2xl border border-dashed border-slate-300 bg-white p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ basename($article->document_path) }}</p>
                            <p class="text-xs text-slate-500">Klik tombol unduh untuk melihat dokumen ini.</p>
                        </div>
                    </div>
                @endif
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