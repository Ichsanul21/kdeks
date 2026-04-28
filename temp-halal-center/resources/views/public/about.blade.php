@extends('layouts.app')

@section('title', 'Tentang Kami - ' . data_get($setting, 'meta_title', 'KDEKS Kalimantan Timur'))

@section('content')
    <section class="relative min-h-[45vh] overflow-hidden pt-32 pb-16">
        <div
            class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.05),transparent_40%)]">
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-6">
            <div class="max-w-4xl">
                <h1 class="font-heading text-4xl font-extrabold leading-tight text-slate-900 md:text-5xl lg:text-6xl">
                    Membangun <span class="text-gradient">Ekonomi Syariah</span><br>
                    di Kalimantan Timur
                </h1>
                <p class="mt-6 text-lg font-medium leading-relaxed text-slate-500">
                    Mengenal lebih dekat Komite Daerah Ekonomi dan Keuangan Syariah (KDEKS) dan komitmen kami bagi kemajuan
                    daerah.
                </p>
            </div>
        </div>
    </section>

    <section class="relative pb-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-16 lg:grid-cols-12">
                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <div
                        class="group relative overflow-hidden rounded-[2.5rem] border border-white bg-white/80 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl md:p-12">
                        <div
                            class="absolute right-0 top-0 h-48 w-48 translate-x-16 -translate-y-16 rounded-full bg-emerald-500/5 blur-3xl transition-all group-hover:bg-emerald-500/10">
                        </div>

                        <div class="relative">
                            <div class="mb-10 flex items-center gap-4">
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                                    <i data-lucide="award" class="h-8 w-8"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-2xl font-bold text-slate-900">Deskripsi Umum</h2>
                                    <p class="text-sm font-medium text-slate-400 uppercase tracking-widest">Tentang KDEKS
                                    </p>
                                </div>
                            </div>

                            <div
                                class="prose prose-emerald prose-lg max-w-none prose-p:text-slate-600 prose-p:leading-relaxed prose-strong:text-slate-900">
                                {!! $aboutUs->description ?? '<p>Komite Daerah Ekonomi dan Keuangan Syariah (KDEKS) merupakan bagian dari upaya pemerintah Indonesia untuk memperkuat ekonomi syariah di tingkat nasional dan daerah.</p>' !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Our Mission -->
                    <div class="rounded-[2.5rem] bg-emerald-600 p-8 text-white shadow-xl shadow-emerald-600/20">
                        <h3 class="mb-6 font-heading text-xl font-bold">Fokus Utama KDEKS</h3>
                        <ul class="space-y-4">
                            @if($aboutUs && $aboutUs->focus)
                                @foreach(explode("\n", str_replace("\r", "", $aboutUs->focus)) as $focusItem)
                                    @if(trim($focusItem))
                                        <li class="flex items-start gap-3">
                                            <i data-lucide="check-circle" class="h-5 w-5 shrink-0 text-emerald-200"></i>
                                            <span class="text-sm font-medium leading-relaxed">{{ $focusItem }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            @else
                                <li class="flex items-start gap-3">
                                    <i data-lucide="check-circle" class="h-5 w-5 shrink-0 text-emerald-200"></i>
                                    <span class="text-sm font-medium leading-relaxed">Penguatan koordinasi antar pemangku kepentingan.</span>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Call Tracking -->
                    <div class="rounded-[2.5rem] border border-slate-100 bg-white p-8 shadow-sm">
                        <h3 class="mb-4 font-heading text-lg font-bold text-slate-900">Informasi Publik</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">Akses dokumen kebijakan dan regulasi terbaru
                            mengenai ekonomi syariah.</p>
                        <a href="{{ route('regulations.index') }}"
                            class="flex items-center justify-center gap-2 rounded-xl bg-slate-50 py-3 text-sm font-bold text-slate-900 transition hover:bg-slate-100">
                            Lihat Regulasi
                            <i data-lucide="arrow-right" class="h-4 w-4 text-emerald-600"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== ROADMAP / MILESTONE ===== --}}
    <section class="relative pb-24 overflow-hidden">
        {{-- Background decoration --}}
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(16,185,129,0.04),transparent_50%)]"></div>

        <div class="mx-auto max-w-5xl px-6">
            <div class="mb-14 text-center">
                <span class="inline-block rounded-full bg-emerald-50 px-4 py-1.5 text-[11px] font-extrabold uppercase tracking-[0.25em] text-emerald-600 border border-emerald-100">Sejarah & Perjalanan</span>
                <h2 class="mt-4 font-heading text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl">Tonggak Perjalanan</h2>
                <p class="mt-3 text-sm font-medium leading-relaxed text-slate-500">Dari KNKS hingga KDEKS Kalimantan Timur | rekam jejak kebijakan yang membentuk ekosistem ekonomi syariah Indonesia.</p>
            </div>

            {{-- Timeline --}}
            <div class="relative">
                {{-- Vertical line (desktop) --}}
                <div class="absolute left-1/2 top-0 hidden h-full w-px -translate-x-1/2 bg-gradient-to-b from-emerald-200 via-emerald-300 to-transparent md:block"></div>

                <div class="space-y-12 md:space-y-16">


                    @php
                    $colorMap = [
                        'emerald' => ['bg' => 'bg-emerald-500', 'ring' => 'ring-emerald-200', 'badge_bg' => 'bg-emerald-50', 'badge_text' => 'text-emerald-700', 'badge_border' => 'border-emerald-200', 'card_border' => 'border-emerald-100', 'dot' => 'bg-emerald-400'],
                        'cyan'    => ['bg' => 'bg-cyan-500',    'ring' => 'ring-cyan-200',    'badge_bg' => 'bg-cyan-50',    'badge_text' => 'text-cyan-700',    'badge_border' => 'border-cyan-200',    'card_border' => 'border-cyan-100',    'dot' => 'bg-cyan-400'],
                        'blue'    => ['bg' => 'bg-blue-500',    'ring' => 'ring-blue-200',    'badge_bg' => 'bg-blue-50',    'badge_text' => 'text-blue-700',    'badge_border' => 'border-blue-200',    'card_border' => 'border-blue-100',    'dot' => 'bg-blue-400'],
                        'violet'  => ['bg' => 'bg-violet-500',  'ring' => 'ring-violet-200',  'badge_bg' => 'bg-violet-50',  'badge_text' => 'text-violet-700',  'badge_border' => 'border-violet-200',  'card_border' => 'border-violet-100',  'dot' => 'bg-violet-400'],
                        'amber'   => ['bg' => 'bg-amber-500',   'ring' => 'ring-amber-200',   'badge_bg' => 'bg-amber-50',   'badge_text' => 'text-amber-700',   'badge_border' => 'border-amber-200',   'card_border' => 'border-amber-100',   'dot' => 'bg-amber-400'],
                        'rose'    => ['bg' => 'bg-rose-500',    'ring' => 'ring-rose-200',    'badge_bg' => 'bg-rose-50',    'badge_text' => 'text-rose-700',    'badge_border' => 'border-rose-200',    'card_border' => 'border-rose-100',    'dot' => 'bg-rose-400'],
                    ];
                    @endphp

                    @foreach($milestones as $i => $m)
                        @php
                            $colorKey = $m->color ?: 'emerald';
                            $c = $colorMap[$colorKey] ?? $colorMap['emerald'];
                            $isRight = $i % 2 === 0;
                        @endphp

                        <div class="relative flex flex-col md:flex-row md:items-start {{ $isRight ? 'md:flex-row' : 'md:flex-row-reverse' }} gap-6 md:gap-0">

                            {{-- ─── CENTER NODE (desktop) ─── --}}
                            <div class="hidden md:flex absolute left-1/2 top-5 -translate-x-1/2 z-10 flex-col items-center">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full {{ $c['bg'] }} text-white shadow-lg ring-4 {{ $c['ring'] }}">
                                    <i data-lucide="{{ $m->icon }}" class="h-5 w-5"></i>
                                </div>
                            </div>

                            {{-- ─── YEAR BADGE side ─── --}}
                            <div class="md:w-[calc(50%-2.5rem)] {{ $isRight ? 'md:pr-16 md:text-right' : 'md:pl-16 md:text-left' }} flex items-start {{ $isRight ? 'md:justify-end' : 'md:justify-start' }}">
                                <div class="flex items-center gap-2 md:flex-col md:items-{{ $isRight ? 'end' : 'start' }} md:gap-1 pt-4">
                                    {{-- Mobile node --}}
                                    <div class="flex md:hidden h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $c['bg'] }} text-white shadow ring-4 {{ $c['ring'] }}">
                                        <i data-lucide="{{ $m->icon }}" class="h-4 w-4"></i>
                                    </div>
                                    <div>
                                        <span class="inline-block rounded-full {{ $c['badge_bg'] }} border {{ $c['badge_border'] }} px-3 py-1 text-xs font-extrabold {{ $c['badge_text'] }}">{{ $m->year }}</span>
                                        @if($m->sub_title)
                                            <p class="mt-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $m->sub_title }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ─── CARD ─── --}}
                            <div class="md:w-[calc(50%-2.5rem)] {{ $isRight ? 'md:pl-16' : 'md:pr-16' }}">
                                <div class="rounded-2xl border {{ $c['card_border'] }} bg-white shadow-sm transition hover:shadow-md overflow-hidden">

                                    {{-- Placeholder foto — hanya muncul jika milestone punya key 'image' --}}
                                    @if($m->image_path)
                                        <div class="h-44 w-full flex-shrink-0 overflow-hidden">
                                            <img src="{{ asset('storage/'.$m->image_path) }}" alt="{{ $m->title }}" class="h-full w-full object-cover">
                                        </div>
                                    @endif

                                    <div class="p-5">
                                        <h3 class="font-heading text-base font-extrabold text-slate-900">{{ $m->title }}</h3>
                                        @if(!empty($m->items))
                                            <ul class="mt-3 space-y-2">
                                                @foreach($m->items as $item)
                                                    <li class="flex items-start gap-2.5 text-sm leading-relaxed text-slate-600">
                                                        <span class="mt-2 h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $c['dot'] }}"></span>
                                                        <span>{{ $item }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>

@endsection
