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
                <a href="{{ route('documents.download', ['type' => 'regulation', 'id' => $regulation->id]) }}" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800">Unduh Dokumen</a>
            @endif
            @if($regulation->external_url)
                <a href="{{ $regulation->external_url }}" target="_blank" rel="noopener noreferrer" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Buka Sumber Eksternal</a>
            @endif
        </div>

        {{-- PDF Preview Section --}}
        @if($regulation->document_path && \Illuminate\Support\Str::endsWith($regulation->document_path, '.pdf'))
            <div class="mt-12">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-heading text-lg font-bold text-slate-800">Pratinjau Regulasi</h2>
                    <span class="rounded-lg bg-emerald-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-600">PDF Viewer</span>
                </div>
                <div class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl transition-all hover:border-emerald-200">
                    <div class="absolute inset-0 z-10 pointer-events-none border-[12px] border-white/50 rounded-[inherit]"></div>
                    <iframe 
                        src="{{ route('documents.view', ['type' => 'regulation', 'id' => $regulation->id]) }}#toolbar=0" 
                        class="h-[800px] w-full border-none" 
                        title="PDF Preview">
                    </iframe>
                </div>
                <p class="mt-4 text-center text-xs font-medium text-slate-400">Gunakan tombol unduh di atas jika pratinjau tidak muncul atau ingin menyimpan dokumen.</p>
            </div>
        @endif
    </section>
@endsection
