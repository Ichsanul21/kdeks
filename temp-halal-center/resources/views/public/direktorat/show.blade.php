@extends('layouts.app')

@section('title', $sector->title . ' - KDEKS Kalimantan Timur')

@section('content')
    <section class="relative min-h-[40vh] overflow-hidden pt-32 pb-16">
        <div
            class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.05),transparent_40%)]">
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-6 pt-4">
            <!-- <nav class="mb-8 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                    <a href="{{ route('home') }}" class="transition hover:text-emerald-600">Beranda</a>
                    <i data-lucide="chevron-right" class="h-3 w-3"></i>
                    <span class="text-slate-500">Direktorat</span>
                    <i data-lucide="chevron-right" class="h-3 w-3"></i>
                    <span class="text-emerald-600">{{ $sector->title }}</span>
                </nav> -->

            <div class="max-w-4xl">
                <div
                    class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                    <i data-lucide="{{ $sector->icon_key }}" class="h-8 w-8"></i>
                </div>
                <h1 class="font-heading text-4xl font-extrabold leading-tight text-slate-900 md:text-5xl lg:text-6xl">
                    Direktorat <br>
                    <span class="text-gradient">{{ $sector->title }}</span>
                </h1>
                <p class="mt-6 text-lg font-medium leading-relaxed text-slate-500">
                    {{ $sector->summary }}
                </p>
            </div>
        </div>
    </section>

    <section class="relative pb-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-12 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div
                        class="group relative overflow-hidden rounded-[2.5rem] border border-white bg-white/80 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl md:p-12 transition-all hover:shadow-[0_8px_40px_rgb(0,0,0,0.06)]">
                        <div
                            class="absolute right-0 top-0 h-48 w-48 translate-x-16 -translate-y-16 rounded-full bg-emerald-500/5 blur-3xl transition-all group-hover:bg-emerald-500/10">
                        </div>

                        <div class="relative">
                            <div class="prose prose-emerald prose-lg max-w-none
                                    prose-headings:font-heading prose-headings:font-bold prose-headings:text-slate-900
                                    prose-p:text-slate-600 prose-p:leading-relaxed
                                    prose-li:text-slate-600 prose-li:leading-relaxed
                                    prose-strong:text-slate-900 prose-strong:font-bold
                                    prose-ol:list-decimal prose-ul:list-disc
                                    prose-li:marker:text-emerald-500">
                                {!! $sector->content !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Other Directorates -->
                    <div class="rounded-[2.5rem] border border-slate-100 bg-white p-8 shadow-sm">
                        <h3 class="mb-6 font-heading text-xl font-bold text-slate-900">Direktorat Lainnya</h3>
                        <div class="space-y-4">
                            @foreach($otherSectors as $other)
                                <a href="{{ route('direktorat.show', $other->slug) }}"
                                    class="group flex items-center gap-4 rounded-2xl border border-slate-50 bg-slate-50/50 p-4 transition-all hover:border-emerald-200 hover:bg-emerald-50">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                                        <i data-lucide="{{ $other->icon_key }}" class="h-5 w-5"></i>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-slate-700 transition-colors group-hover:text-emerald-700">{{ $other->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- CTA -->
                    <div
                        class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 text-white transition-all hover:scale-[1.02]">
                        <div class="absolute -right-4 -top-4 h-32 w-32 rounded-full bg-emerald-500/20 blur-2xl"></div>
                        <div class="relative z-10">
                            <h4 class="mb-2 text-lg font-bold">Konsultasi Terpadu</h4>
                            <p class="mb-6 text-sm leading-relaxed text-slate-400">Hubungi tim ahli kami untuk informasi
                                lebih lanjut mengenai program direktoral.</p>
                            <a href="{{ route('contact') }}"
                                class="flex items-center justify-center gap-2 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white transition hover:bg-emerald-400">
                                Hubungi Kami
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
