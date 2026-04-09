@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <section class="mx-auto max-w-4xl px-6 pb-20 pt-28">
        <a href="{{ route('events.index') }}" class="text-sm font-bold text-emerald-600">← Kembali ke agenda</a>
        <h1 class="mt-6 font-heading text-4xl font-extrabold text-slate-900">{{ $event->title }}</h1>
        <p class="mt-4 text-sm font-medium text-slate-500">{{ optional($event->starts_at)->translatedFormat('d F Y H:i') }} · {{ $event->location_name ?: 'Lokasi menyusul' }}</p>
        <div class="prose prose-slate mt-8 max-w-none">
            {!! $event->description ?: '<p>Deskripsi agenda belum tersedia.</p>' !!}
        </div>
        <div class="mt-8 flex flex-wrap gap-3">
            @if($event->registration_url)
                <a href="{{ $event->registration_url }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white">Daftar Kegiatan</a>
            @endif
            @if($event->meeting_url)
                <a href="{{ $event->meeting_url }}" target="_blank" rel="noopener noreferrer" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700">Buka Meeting</a>
            @endif
        </div>
    </section>
@endsection
