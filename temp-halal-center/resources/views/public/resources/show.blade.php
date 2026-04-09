@extends('layouts.app')

@section('title', $resource->title)

@section('content')
    <section class="mx-auto max-w-4xl px-6 pb-20 pt-28">
        <a href="{{ route('resources.index') }}" class="text-sm font-bold text-emerald-600">← Kembali ke pustaka</a>
        <h1 class="mt-6 font-heading text-4xl font-extrabold text-slate-900">{{ $resource->title }}</h1>
        <div class="prose prose-slate mt-8 max-w-none">
            {!! $resource->content ?: '<p>Konten dokumen belum tersedia.</p>' !!}
        </div>
        <div class="mt-8 flex flex-wrap gap-3">
            @if($resource->document_path)
                <a href="{{ route('documents.download', ['type' => 'resource', 'id' => $resource->id]) }}" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white">Unduh Dokumen</a>
            @endif
            @if($resource->external_url)
                <a href="{{ $resource->external_url }}" target="_blank" rel="noopener noreferrer" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700">Buka Sumber Eksternal</a>
            @endif
        </div>
    </section>
@endsection
