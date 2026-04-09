@extends('layouts.app')

@section('title', $regulation->title)

@section('content')
    <section class="mx-auto max-w-4xl px-6 pb-20 pt-28">
        <a href="{{ route('regulations.index') }}" class="text-sm font-bold text-emerald-600">← Kembali ke regulasi</a>
        <h1 class="mt-6 font-heading text-4xl font-extrabold text-slate-900">{{ $regulation->title }}</h1>
        <p class="mt-4 text-sm font-medium text-slate-500">{{ $regulation->regulation_number }} · {{ optional($regulation->issued_at)->translatedFormat('d F Y') }}</p>
        <div class="prose prose-slate mt-8 max-w-none">
            <p>{{ $regulation->summary ?: 'Ringkasan regulasi belum tersedia.' }}</p>
        </div>
        <div class="mt-8 flex flex-wrap gap-3">
            @if($regulation->document_path)
                <a href="{{ route('documents.download', ['type' => 'regulation', 'id' => $regulation->id]) }}" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white">Unduh Dokumen</a>
            @endif
            @if($regulation->external_url)
                <a href="{{ $regulation->external_url }}" target="_blank" rel="noopener noreferrer" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700">Buka Sumber Eksternal</a>
            @endif
        </div>
    </section>
@endsection
