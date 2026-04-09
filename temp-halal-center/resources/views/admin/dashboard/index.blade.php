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

    <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach([
            ['label' => 'Total Sertifikat Terbit', 'value' => $stats['locations'] ?? 0, 'icon' => 'file-check-2', 'bg' => 'bg-emerald-50', 'iconColor' => 'text-emerald-600', 'accent' => '8%'],
            ['label' => 'UMKM Terdaftar', 'value' => $stats['products'] ?? 0, 'icon' => 'store', 'bg' => 'bg-blue-50', 'iconColor' => 'text-blue-600', 'accent' => '12%'],
            ['label' => 'Menunggu Verifikasi', 'value' => $stats['sehati'] ?? 0, 'icon' => 'clock', 'bg' => 'bg-yellow-50', 'iconColor' => 'text-yellow-600', 'accent' => null],
            ['label' => 'Pendamping Aktif', 'value' => $stats['mentors'] ?? 0, 'icon' => 'users', 'bg' => 'bg-cyan-50', 'iconColor' => 'text-cyan-600', 'accent' => '-'],
        ] as $card)
            <div class="admin-card relative overflow-hidden rounded-2xl p-6 transition hover:shadow-md">
                @if($card['label'] === 'Menunggu Verifikasi')
                    <div class="absolute right-0 top-0 h-full w-2 bg-yellow-400"></div>
                @endif
                <div class="mb-4 flex items-start justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border {{ $card['bg'] }} {{ $card['iconColor'] }}">
                        <i data-lucide="{{ $card['icon'] }}" class="h-6 w-6"></i>
                    </div>
                    @if($card['accent'])
                        <span class="rounded-md {{ $card['accent'] === '-' ? 'bg-slate-50 text-slate-400' : 'bg-emerald-50 text-emerald-600' }} px-2 py-1 text-[10px] font-bold">{{ $card['accent'] }}</span>
                    @endif
                </div>
                <p class="mb-1 text-xs font-bold uppercase tracking-[0.18em] text-slate-500">{{ $card['label'] }}</p>
                <h3 class="font-heading text-3xl font-extrabold text-slate-900">{{ number_format((int) $card['value'], 0, ',', '.') }}</h3>
            </div>
        @endforeach
    </div>

    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="admin-card rounded-[1.75rem] p-6 lg:col-span-2">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="font-heading text-lg font-bold text-slate-900">Statistik Pengajuan SEHATI</h3>
                    <p class="text-xs font-medium text-slate-500">Ringkasan operasional saat ini berdasarkan data dashboard.</p>
                </div>
                <span class="rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-700">Tahun {{ now()->year }}</span>
            </div>

            @php
                $chartValues = [
                    ['label' => 'SEHATI', 'value' => max(1, $stats['sehati'] ?? 0), 'color' => 'bg-emerald-400'],
                    ['label' => 'Produk', 'value' => max(1, $stats['products'] ?? 0), 'color' => 'bg-emerald-500'],
                    ['label' => 'Lokasi', 'value' => max(1, $stats['locations'] ?? 0), 'color' => 'bg-cyan-400'],
                    ['label' => 'Mitra', 'value' => max(1, $stats['lph_partners'] ?? 0), 'color' => 'bg-blue-400'],
                    ['label' => 'Dokumen', 'value' => max(1, $stats['resources'] ?? 0), 'color' => 'bg-slate-400'],
                ];
                $chartMax = collect($chartValues)->max('value') ?: 1;
            @endphp

            <div class="flex min-h-[220px] items-end gap-3 pt-4 md:gap-6">
                @foreach($chartValues as $bar)
                    @php
                        $height = max(18, (int) round(($bar['value'] / $chartMax) * 100));
                    @endphp
                    <div class="group flex flex-1 flex-col items-center gap-2">
                        <div class="relative flex h-full w-full items-end justify-center">
                            <div class="bar-grow {{ $bar['color'] }} flex w-full justify-center rounded-t-lg pb-2 md:w-12" style="height: {{ $height }}%">
                                <span class="absolute -mt-6 rounded-full bg-white px-2 py-0.5 text-[10px] font-bold text-slate-700 shadow-sm opacity-0 transition group-hover:opacity-100">{{ number_format($bar['value'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-500">{{ $bar['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="admin-card rounded-[1.75rem] p-6">
            <div class="mb-6">
                <h3 class="font-heading text-lg font-bold text-slate-900">Distribusi Pendaftar</h3>
                <p class="text-xs font-medium text-slate-500">Berdasarkan Lembaga Pendamping</p>
            </div>

            @php
                $totalRegistrations = max(1, $latestSehatiRegistrations->count());
                $partnerDistribution = $latestSehatiRegistrations->groupBy(fn ($item) => $item->lphPartner?->name ?? 'Belum Dipilih')->map->count();
                $partnerColors = ['bg-emerald-500', 'bg-cyan-500', 'bg-blue-400', 'bg-slate-300'];
            @endphp

            <div class="space-y-5">
                @forelse($partnerDistribution as $partnerName => $count)
                    @php
                        $percent = (int) round(($count / $totalRegistrations) * 100);
                    @endphp
                    <div>
                        <div class="mb-1.5 flex justify-between text-xs font-bold">
                            <span class="text-slate-700">{{ $partnerName }}</span>
                            <span class="text-slate-900">{{ $percent }}%</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100">
                            <div class="h-2 rounded-full {{ $partnerColors[$loop->index % count($partnerColors)] }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm font-medium text-slate-500">Belum ada distribusi pendaftar untuk ditampilkan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="admin-card overflow-hidden rounded-[1.75rem]">
        <div class="flex flex-col items-start justify-between gap-4 border-b border-slate-100 p-6 sm:flex-row sm:items-center">
            <div>
                <h3 class="font-heading text-lg font-bold text-slate-900">Pendaftar SEHATI Terbaru</h3>
                <p class="mt-1 text-xs font-medium text-slate-500">Daftar pengajuan sertifikasi yang butuh verifikasi segera.</p>
            </div>
            <a href="{{ route('admin.sehati-registrations.index') }}" class="flex items-center gap-1 text-sm font-bold text-emerald-600 transition hover:text-emerald-700">
                Lihat Semua
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table min-w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60">
                        <th class="px-6 py-4">Nama UMKM & Produk</th>
                        <th class="px-6 py-4">Pemilik</th>
                        <th class="px-6 py-4">LP3H</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($latestSehatiRegistrations as $registration)
                        @php
                            $statusClasses = match($registration->status) {
                                'processed' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                'reviewed' => 'bg-blue-50 text-blue-600 border-blue-200',
                                'closed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                default => 'bg-yellow-50 text-yellow-600 border-yellow-200',
                            };
                        @endphp
                        <tr class="border-b border-slate-50 transition hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $registration->business_name }}</p>
                                <p class="text-[11px] font-medium text-slate-500">{{ $registration->product_name }}</p>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600">{{ $registration->owner_name }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-md border border-slate-200 bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-600">{{ $registration->lphPartner?->name ?? 'Belum Dipilih' }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ $registration->created_at->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[10px] font-bold {{ $statusClasses }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ strtoupper($registration->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.sehati-registrations.edit', $registration->id) }}" class="inline-flex rounded-lg border border-slate-200 bg-white p-2 text-slate-400 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm font-medium text-slate-500">Belum ada pendaftar SEHATI.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
