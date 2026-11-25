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
            ];
        } else {
            $cards = [
                ['label' => 'Total UMKM', 'value' => $stats['umkms'] ?? 0, 'icon' => 'store', 'bg' => 'bg-emerald-50', 'iconColor' => 'text-emerald-600', 'route' => route('admin.umkms.index')],
                ['label' => 'Sertifikasi', 'value' => $stats['sehati'] ?? 0, 'icon' => 'file-check-2', 'bg' => 'bg-blue-50', 'iconColor' => 'text-blue-600', 'route' => route('admin.sehati-registrations.index')],
                ['label' => 'Menunggu Verifikasi', 'value' => $stats['sehati_pending'] ?? 0, 'icon' => 'clock', 'bg' => 'bg-amber-50', 'iconColor' => 'text-amber-600', 'route' => route('admin.sehati-registrations.index', ['search' => 'baru'])],
                ['label' => 'Pesan Buku Tamu', 'value' => $stats['consultations'] ?? 0, 'icon' => 'message-square', 'bg' => 'bg-cyan-50', 'iconColor' => 'text-cyan-600', 'route' => route('admin.consultation-requests.index')],
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
        {{-- Table Sertifikasi --}}
        @if(!$isEditor)
        <div class="admin-card animate-fade-in-up stagger-3 overflow-hidden rounded-[1.75rem]">
            <div class="flex items-center justify-between border-b border-slate-100 p-6">
                <h3 class="font-heading text-lg font-bold text-slate-900">Sertifikasi Terbaru</h3>
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

        {{-- Table Buku Tamu with Detail Modal --}}
        <div x-data="{
            showDetailModal: false,
            activeItem: {},
            openDetail(data) {
                this.activeItem = data;
                this.showDetailModal = true;
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            },
            getWhatsappUrl(phone) {
                if (!phone) return '#';
                let clean = phone.replace(/[^0-9]/g, '');
                if (clean.startsWith('0')) {
                    clean = '62' + clean.slice(1);
                }
                return 'https://wa.me/' + clean;
            }
        }" class="admin-card animate-fade-in-up stagger-4 overflow-hidden rounded-[1.75rem]">
            <div class="flex items-center justify-between border-b border-slate-100 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">
                        <i data-lucide="messages-square" class="h-4 w-4"></i>
                    </div>
                    <h3 class="font-heading text-lg font-bold text-slate-900">Buku Tamu Terbaru</h3>
                </div>
                <a href="{{ route('admin.consultation-requests.index') }}" class="text-xs font-bold text-cyan-600 hover:text-cyan-700 transition">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="text-xs">
                        @forelse($latestConsultations as $msg)
                            @php
                                $detailPayload = [
                                    'id' => $msg->id,
                                    'name' => $msg->name,
                                    'email' => $msg->email,
                                    'phone' => $msg->phone,
                                    'organization' => $msg->organization_name ?: '-',
                                    'subject' => $msg->subject,
                                    'message' => $msg->message,
                                    'status' => $msg->status ?: 'Baru',
                                    'date' => optional($msg->created_at)->translatedFormat('d F Y, H:i') . ' (' . optional($msg->created_at)->diffForHumans() . ')',
                                    'edit_url' => auth()->user()->hasAnyRole(['developer', 'superadmin', 'admin']) ? route('admin.consultation-requests.edit', $msg->id) : null,
                                ];
                            @endphp
                            <tr class="group border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $msg->name }}</p>
                                    <p class="text-[10px] text-slate-500 truncate max-w-xs">{{ $msg->subject }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                    {{ $msg->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Tombol Detail (Memicu Modal Pop-up) -->
                                        <button type="button" @click="openDetail({{ json_encode($detailPayload) }})" class="inline-flex items-center gap-1.5 rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-1.5 text-xs font-bold text-cyan-700 transition hover:bg-cyan-100 hover:border-cyan-300 shadow-sm" title="Lihat Detail Pesan">
                                            <i data-lucide="eye" class="h-3.5 w-3.5 text-cyan-600"></i>
                                            <span>Detail</span>
                                        </button>
                                        @if(auth()->user()->hasAnyRole(['developer', 'superadmin', 'admin']))
                                            <a href="{{ route('admin.consultation-requests.edit', $msg->id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 transition-all hover:bg-cyan-500 hover:border-cyan-500 hover:text-white" title="Kelola / Edit Pesan">
                                                <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="py-10 text-center text-slate-400">Tidak ada pesan terbaru</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MODAL DETAIL BUKU TAMU -->
            <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
                <div @click.away="showDetailModal = false" class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl transition-all max-h-[90vh] flex flex-col">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600 shadow-inner">
                                <i data-lucide="messages-square" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Detail Pesan Buku Tamu</h3>
                                <p class="text-xs text-slate-500" x-text="activeItem.date"></p>
                            </div>
                        </div>
                        <button type="button" @click="showDetailModal = false" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>

                    <!-- Modal Body (Scrollable) -->
                    <div class="mt-4 flex-1 overflow-y-auto space-y-4 pr-1">
                        <!-- Sender & Contact Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Pengirim</span>
                                <p class="mt-1 text-xs font-bold text-slate-900" x-text="activeItem.name"></p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Instansi / Lembaga</span>
                                <p class="mt-1 text-xs font-semibold text-slate-800" x-text="activeItem.organization || '-'"></p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Email</span>
                                <p class="mt-1 text-xs font-medium text-slate-800">
                                    <template x-if="activeItem.email">
                                        <a :href="'mailto:' + activeItem.email" class="text-cyan-600 hover:underline inline-flex items-center gap-1 font-semibold">
                                            <span x-text="activeItem.email"></span>
                                            <i data-lucide="mail" class="h-3 w-3"></i>
                                        </a>
                                    </template>
                                    <template x-if="!activeItem.email">
                                        <span>-</span>
                                    </template>
                                </p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">No. Telepon / WhatsApp</span>
                                <p class="mt-1 text-xs font-medium text-slate-800">
                                    <template x-if="activeItem.phone">
                                        <a :href="getWhatsappUrl(activeItem.phone)" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline inline-flex items-center gap-1 font-bold">
                                            <span x-text="activeItem.phone"></span>
                                            <i data-lucide="message-square" class="h-3 w-3"></i>
                                        </a>
                                    </template>
                                    <template x-if="!activeItem.phone">
                                        <span>-</span>
                                    </template>
                                </p>
                            </div>
                        </div>

                        <!-- Subjek & Status -->
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Perihal / Subjek</span>
                                <span class="rounded-full bg-cyan-100 px-2.5 py-0.5 text-[10px] font-bold text-cyan-800 uppercase tracking-wider" x-text="activeItem.status || 'Baru'"></span>
                            </div>
                            <p class="mt-1.5 text-xs font-bold text-slate-900" x-text="activeItem.subject"></p>
                        </div>

                        <!-- Pesan / Isi Buku Tamu -->
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Isi Pesan</span>
                            <div class="mt-2 text-xs leading-relaxed text-slate-700 whitespace-pre-line bg-slate-50 rounded-xl p-3 border border-slate-100 font-medium" x-text="activeItem.message || 'Tidak ada pesan tertulis.'"></div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-4">
                        <div class="flex items-center gap-2">
                            <template x-if="activeItem.phone">
                                <a :href="getWhatsappUrl(activeItem.phone)" target="_blank" rel="noopener noreferrer" class="h-9 px-3.5 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm inline-flex items-center gap-1.5 transition">
                                    <i data-lucide="message-square" class="h-3.5 w-3.5"></i>
                                    <span>Chat WhatsApp</span>
                                </a>
                            </template>
                            <template x-if="activeItem.email">
                                <a :href="'mailto:' + activeItem.email" class="h-9 px-3.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 shadow-sm inline-flex items-center gap-1.5 transition">
                                    <i data-lucide="mail" class="h-3.5 w-3.5 text-cyan-600"></i>
                                    <span>Balas Email</span>
                                </a>
                            </template>
                        </div>

                        <div class="flex items-center gap-2">
                            <template x-if="activeItem.edit_url">
                                <a :href="activeItem.edit_url" class="h-9 px-3.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition inline-flex items-center gap-1.5">
                                    <i data-lucide="pencil" class="h-3.5 w-3.5 text-slate-500"></i>
                                    <span>Kelola Status</span>
                                </a>
                            </template>
                            <button type="button" @click="showDetailModal = false" class="h-9 px-4 text-xs font-bold rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
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
