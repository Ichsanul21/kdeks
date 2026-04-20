@extends('layouts.app')

@section('title', $title . ' - ' . data_get($setting, 'meta_title', 'Halal Center Kaltim'))

@section('content')
<section class="relative min-h-[50vh] flex flex-col items-center justify-center pt-24 pb-12 bg-white">
    <div class="absolute inset-0 bg-slate-50/50 -z-10"></div>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-emerald-500/5 to-transparent -z-10"></div>
    
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight font-heading">{{ $title }}</h1>
        <p class="mt-4 text-slate-500 font-medium">Halaman masih dalam tahap pengembangan.</p>
        
        <div class="mt-8 flex justify-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</section>
@endsection
