@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-heading text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">Ringkasan Sistem</h1>
            <p class="mt-1 text-sm font-medium text-slate-500">Pantau seluruh aktivitas ekosistem halal Kaltim hari ini.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <i data-lucide="external-link" class="h-4 w-4 text-slate-400"></i>
                Lihat Website
            </a>
            <a href="{{ route('admin.sehati-registrations.create') }}" class="flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition hover:bg-emerald-400">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah Data
            </a>
        </div>
    </div>

    <style>
        @keyframes growUp {
            from { height: 0; }
        }
        .animate-grow { animation: growUp 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    </style>

    @php
        $cards = [];
        if ($isEditor) {
            $cards = [
                ['label' => 'Berita & Publikasi', 'value' => $stats['articles'] ?? 0, 'icon' => 'newspaper', 'bg' => 'bg-emerald-50', 'iconColor' => 'text-emerald-600', 'route' => route('admin.articles.index')],
                ['label' => 'Pustaka Dokumen', 'value' => $stats['resources'] ?? 0, 'icon' => 'folder-open', 'bg' => 'bg-blue-50', 'iconColor' => 'text-blue-600', 'route' => route('admin.knowledge-resources.index')],
                ['label' => 'Buku Tamu', 'value' => $stats['consultations'] ?? 0, 'icon' => 'messages-square', 'bg' => 'bg-cyan-50', 'iconColor' => 'text-cyan-600', 'route' => route('admin.consultation-requests.index')],
                ['label' => 'Banner Beranda', 'value' => $stats['banners'] ?? 0, 'icon' => 'monitor', 'bg' => 'bg-amber-50', 'iconColor' => 'text-amber-600', 'route' => route('admin.banners.index')],
                ['label' => 'Siaran Pers', 'value' => $stats['press_releases'] ?? 0, 'icon' => 'video', 'bg' => 'bg-rose-50', 'iconColor' => 'text-rose-600', 'route' => route('admin.press-releases.index')],
            ];
        } else {
            $cards = [
                ['label' => 'Total UMKM', 'value' => $stats['umkms'] ?? 0, 'icon' => 'store', 'bg' => 'bg-emerald-50', 'iconColor' => 'text-emerald-600', 'route' => route('admin.umkms.index')],
                ['label' => 'Sertifikasi Halal', 'value' => $stats['sehati'] ?? 0, 'icon' => 'file-check-2', 'bg' => 'bg-blue-50', 'iconColor' => 'text-blue-600', 'route' => route('admin.sehati-registrations.index')],
                ['label' => 'Menunggu Verifikasi', 'value' => $stats['sehati_pending'] ?? 0, 'icon' => 'clock', 'bg' => 'bg-amber-50', 'iconColor' => 'text-amber-600', 'route' => route('admin.sehati-registrations.index', ['search' => 'baru'])],
                ['label' => 'Pesan Buku Tamu', 'value' => $stats['consultations'] ?? 0, 'icon' => 'message-square', 'bg' => 'bg-cyan-50', 'iconColor' => 'text-cyan-600', 'route' => route('admin.consultation-requests.index')],
                ['label' => 'Siaran Pers', 'value' => $stats['press_releases'] ?? 0, 'icon' => 'video', 'bg' => 'bg-rose-50', 'iconColor' => 'text-rose-600', 'route' => route('admin.press-releases.index')],
            ];
        }
    @endphp

    <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($cards as $card)
            <a href="{{ $card['route'] }}" class="admin-card group animate-fade-in-up stagger-{{ $loop->iteration }} relative overflow-hidden rounded-2xl p-6 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/50">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-slate-50 opacity-0 transition-opacity group-hover:opacity-100"></div>
                <div class="mb-4 flex items-start justify-between relative z-10">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border {{ $card['bg'] }} {{ $card['iconColor'] }} transition-transform group-hover:scale-110">
                        <i data-lucide="{{ $card['icon'] }}" class="h-6 w-6"></i>
                    </div>
                    <div class="opacity-0 transition-opacity group-hover:opacity-100">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-900 text-white shadow-lg">
                            <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                        </div>
                    </div>
                </div>
                <p class="mb-1 text-xs font-bold uppercase tracking-[0.18em] text-slate-500 relative z-10">{{ $card['label'] }}</p>
                <h3 class="font-heading text-3xl font-extrabold text-slate-900 relative z-10">{{ number_format((int) $card['value'], 0, ',', '.') }}</h3>
            </a>
        @endforeach
    </div>

    <div class="mb-8 grid grid-cols-1 gap-6 {{ $isEditor ? '' : 'lg:grid-cols-3' }}">
        <div class="admin-card animate-fade-in-up stagger-1 rounded-[1.75rem] p-6 {{ $isEditor ? '' : 'lg:col-span-2' }}">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h3 class="font-heading text-lg font-bold text-slate-900">{{ $isEditor ? 'Ringkasan Konten CMS' : 'Performa Ekosistem Halal' }}</h3>
                    <p class="text-xs font-medium text-slate-500">{{ $isEditor ? 'Jumlah data konten yang Anda kelola saat ini.' : 'Visualisasi sebaran data pendaftaran dan kemitraan.' }}</p>
                </div>
                <div class="flex gap-2">
                    <div class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-[10px] font-bold text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        {{ now()->year }}
                    </div>
                </div>
            </div>

            @php
                if ($isEditor) {
                    $chartValues = [
                        ['label' => 'Berita', 'value' => $stats['articles'] ?? 0, 'color' => 'bg-emerald-500 shadow-emerald-200'],
                        ['label' => 'Pustaka', 'value' => $stats['resources'] ?? 0, 'color' => 'bg-cyan-500 shadow-cyan-200'],
                        ['label' => 'Regulasi', 'value' => $stats['regulations'] ?? 0, 'color' => 'bg-blue-500 shadow-blue-200'],
                        ['label' => 'FAQ', 'value' => $stats['faqs'] ?? 0, 'color' => 'bg-indigo-500 shadow-indigo-200'],
                        ['label' => 'Kegiatan', 'value' => $stats['events'] ?? 0, 'color' => 'bg-slate-500 shadow-slate-200'],
                    ];
                } else {
                    $chartValues = [
                        ['label' => 'UMKM', 'value' => $stats['umkms'] ?? 0, 'color' => 'bg-emerald-500 shadow-emerald-200'],
                        ['label' => 'Produk', 'value' => $stats['products'] ?? 0, 'color' => 'bg-cyan-500 shadow-cyan-200'],
                        ['label' => 'Sertifikat', 'value' => $stats['sehati'] ?? 0, 'color' => 'bg-blue-500 shadow-blue-200'],
                        ['label' => 'Mitra', 'value' => $stats['lph_partners'] ?? 0, 'color' => 'bg-indigo-500 shadow-indigo-200'],
                        ['label' => 'Berita', 'value' => $stats['articles'] ?? 0, 'color' => 'bg-slate-500 shadow-slate-200'],
                    ];
                }
                $chartMax = collect($chartValues)->max('value') ?: 1;
            @endphp

            <div class="flex min-h-[240px] items-end justify-between gap-2 px-2 pt-10 md:gap-8 md:px-6 text-center">
                @foreach($chartValues as $bar)
                    @php
                        $height = max(15, (int) round(($bar['value'] / $chartMax) * 100));
                    @endphp
                    <div class="group relative flex flex-1 flex-col items-center">
                        <div class="mb-2 hidden group-hover:block absolute -top-12 z-20">
                            <div class="relative rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white shadow-xl">
                                {{ number_format($bar['value'], 0, ',', '.') }}
                                <div class="absolute -bottom-1 left-1/2 -ml-1 h-2 w-2 rotate-45 bg-slate-900"></div>
                            </div>
                        </div>
                        <div class="relative flex h-48 w-full items-end justify-center rounded-xl bg-slate-50/50 p-1">
                            <div class="animate-grow w-full rounded-lg {{ $bar['color'] }} shadow-lg transition-all duration-500 group-hover:brightness-110" style="height: {{ $height }}%"></div>
                        </div>
                        <span class="mt-4 text-[10px] font-bold uppercase tracking-wider text-slate-400 md:text-xs">{{ $bar['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        @if(!$isEditor)
        <div class="admin-card animate-fade-in-up stagger-2 rounded-[1.75rem] p-6">
            <div class="mb-6">
                <h3 class="font-heading text-lg font-bold text-slate-900">Sebaran Wilayah</h3>
                <p class="text-xs font-medium text-slate-500">Top 5 Kabupaten/Kota Terbanyak (UMKM)</p>
            </div>

            <div class="relative flex min-h-[300px] items-center justify-center">
                @if($topRegions->isNotEmpty())
                    <canvas id="regionPieChart" class="max-h-[280px] max-w-[280px]"></canvas>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <i data-lucide="map-pin" class="mb-2 h-8 w-8 text-slate-200"></i>
                        <p class="text-xs font-medium text-slate-400">Belum ada data wilayah</p>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6 {{ $isEditor ? '' : 'lg:grid-cols-2' }}">
        {{-- Table Sehati --}}
        @if(!$isEditor)
        <div class="admin-card animate-fade-in-up stagger-3 overflow-hidden rounded-[1.75rem]">
            <div class="flex items-center justify-between border-b border-slate-100 p-6">
                <h3 class="font-heading text-lg font-bold text-slate-900">Sertifikat Halal Terbaru</h3>
                <a href="{{ route('admin.sehati-registrations.index') }}" class="text-xs font-bold text-emerald-600">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="text-xs">
                        @forelse($latestSehatiRegistrations as $reg)
                            <tr class="group border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $reg->business_name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $reg->product_name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $label = match($reg->status) {
                                            'baru' => ['bg-amber-100 text-amber-700', 'Baru'],
                                            'ditinjau' => ['bg-blue-100 text-blue-700', 'Ditinjau'],
                                            'diproses' => ['bg-indigo-100 text-indigo-700', 'Diproses'],
                                            'selesai' => ['bg-emerald-100 text-emerald-700', 'Selesai'],
                                            default => ['bg-slate-100 text-slate-700', $reg->status]
                                        };
                                    @endphp
                                    <span class="rounded px-2 py-0.5 font-bold uppercase {{ $label[0] }}">{{ $label[1] }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.sehati-registrations.edit', $reg->id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 transition-all group-hover:bg-emerald-500 group-hover:text-white">
                                        <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="py-10 text-center text-slate-400">Tidak ada pengajuan terbaru</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Table Buku Tamu --}}
        <div class="admin-card animate-fade-in-up stagger-4 overflow-hidden rounded-[1.75rem]">
            <div class="flex items-center justify-between border-b border-slate-100 p-6">
                <h3 class="font-heading text-lg font-bold text-slate-900">Buku Tamu Terbaru</h3>
                <a href="{{ route('admin.consultation-requests.index') }}" class="text-xs font-bold text-cyan-600">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="text-xs">
                        @forelse($latestConsultations as $msg)
                            <tr class="group border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $msg->name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $msg->subject }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $msg->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.consultation-requests.edit', $msg->id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 transition-all group-hover:bg-cyan-500 group-hover:text-white">
                                        <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="py-10 text-center text-slate-400">Tidak ada pesan terbaru</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @if($topRegions->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('regionPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @json($topRegions->pluck('name')),
                    datasets: [{
                        data: @json($topRegions->pluck('halal_msmes_count')),
                        backgroundColor: [
                            '#10b981', // emerald-500
                            '#06b6d4', // cyan-500
                            '#3b82f6', // blue-500
                            '#6366f1', // indigo-500
                            '#8b5cf6'  // violet-500
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 20
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endif
@endsection
