@extends('layouts.app')

@section('title', 'Siaran Pers - ' . data_get($setting, 'meta_title', 'Halal Center Kaltim'))

@section('content')
<section class="relative pt-24 pb-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight font-heading">Siaran Pers</h1>
            <p class="mt-2 text-slate-500 font-medium">Arsip video dan liputan kegiatan resmi KDEKS Kaltim.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Player Area (Left 3/4ish) -->
            <div class="flex-1">
                <div class="aspect-video w-full bg-slate-200 rounded-2xl animate-pulse flex items-center justify-center">
                    <i data-lucide="play-circle" class="h-16 w-16 text-slate-400"></i>
                </div>
                <div class="mt-4">
                    <div class="h-6 w-3/4 bg-slate-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 w-1/4 bg-slate-200 rounded animate-pulse mb-4"></div>
                </div>
            </div>

            <!-- Playlist Area (Right) -->
            <div class="w-full lg:w-80 flex flex-col gap-4">
                <h3 class="font-bold text-slate-900 text-lg">Video Lainnya</h3>
                @for ($i = 0; $i < 5; $i++)
                <div class="flex gap-3 items-start">
                    <div class="w-32 h-20 bg-slate-200 rounded-xl shrink-0 animate-pulse"></div>
                    <div class="flex-1">
                        <div class="h-4 w-full bg-slate-200 rounded animate-pulse mb-2"></div>
                        <div class="h-4 w-2/3 bg-slate-200 rounded animate-pulse mb-2"></div>
                        <div class="h-3 w-1/2 bg-slate-200 rounded animate-pulse"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</section>
@endsection
