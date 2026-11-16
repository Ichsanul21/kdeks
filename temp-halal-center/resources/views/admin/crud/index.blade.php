@extends('layouts.admin')

@section('content')
<div x-data="{
    showDeleteModal: false,
    deleteActionUrl: '',
    deleteItemLabel: '',
    showDetailModal: false,
    detailItem: {},
    openDeleteModal(url, label) {
        this.deleteActionUrl = url;
        this.deleteItemLabel = label;
        this.showDeleteModal = true;
    },
    openDetailModal(data) {
        this.detailItem = data;
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
}">
    @php $isAdminDirektorat = $isAdminDirektorat ?? false; @endphp
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">Content Management</p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">{{ $pageTitle }}</h2>
        </div>
        <div class="flex flex-wrap gap-3">
            @if($publicIndexRoute)
                <a href="{{ route($publicIndexRoute) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <i data-lucide="external-link" class="h-4 w-4"></i>
                    Lihat Publik
                </a>
            @endif
            <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition hover:bg-emerald-400">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah Data
            </a>
        </div>
    </div>

    <div class="admin-card rounded-[1.75rem] p-6">
        <form method="GET" class="mb-6">
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 focus-within:border-emerald-500/50 focus-within:ring-2 focus-within:ring-emerald-500/20">
                <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data..." class="w-full border-none bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400">
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="admin-table min-w-full text-left text-sm">
                <thead>
                    <tr>
                        @foreach($tableColumns as $column)
                            <th class="pb-4">{{ $column['label'] }}</th>
                        @endforeach
                        <th class="pb-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="border-t border-slate-100 transition hover:bg-slate-50">
                            @foreach($tableColumns as $column)
                                <td class="py-4">
                                    @php
                                        $val = data_get($item, $column['key']);
                                        $isIzinStatus = ($column['key'] === 'izin_status');
                                        if (isset($column['options']) && isset($column['options'][$val])) {
                                            $val = $column['options'][$val];
                                        }
                                    @endphp
                                    @if($isIzinStatus && filled($val))
                                        <span class="inline-flex items-center rounded-full @if(data_get($item, 'izin_status') === 'disetujui') bg-emerald-50 text-emerald-600 @else bg-rose-50 text-rose-600 @endif px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider">
                                            {{ $val }}
                                        </span>
                                    @elseif($column['key'] === 'is_featured')
                                        @if($val)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-600 ring-1 ring-amber-200">
                                                <i data-lucide="star" class="h-3 w-3 fill-amber-500"></i>
                                                Unggulan
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">-</span>
                                        @endif
                                    @elseif($column['key'] === 'status' && $routePrefix === 'admin.press-releases')
                                        @if($val === 'streaming')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-rose-600 ring-1 ring-rose-200">
                                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-rose-500"></span>
                                                Streaming
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-600">
                                                Arsip
                                            </span>
                                        @endif
                                    @elseif($column['key'] === 'video_url')
                                        <div class="max-w-[150px] truncate" title="{{ $val }}">
                                            <span class="font-medium text-slate-500">{{ $val }}</span>
                                        </div>
                                    @else
                                        <span class="font-medium text-slate-700">{{ $val }}</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($routePrefix === 'admin.sehati-registrations' && $item->status === 'diproses')
                                        <form method="POST" action="{{ route('admin.sehati-registrations.approve', $item->id) }}" style="display: contents;">
                                            @csrf
                                            <input type="hidden" name="decision" value="yes">
                                            <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white transition hover:bg-emerald-600" title="Setujui">
                                                <i data-lucide="check" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.sehati-registrations.approve', $item->id) }}" style="display: contents;">
                                            @csrf
                                            <input type="hidden" name="decision" value="no">
                                            <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-rose-500 text-white transition hover:bg-rose-600" title="Tolak">
                                                <i data-lucide="x" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($routePrefix === 'admin.press-releases' && $item->status === 'streaming')
                                        <form method="POST" action="{{ route('admin.press-releases.finish-stream', $item->id) }}" style="display: contents;">
                                            @csrf
                                            <button type="submit" class="inline-flex h-9 px-3 items-center justify-center gap-2 rounded-lg bg-rose-100 text-rose-600 font-bold text-[10px] uppercase tracking-wider transition hover:bg-rose-500 hover:text-white" title="Selesaikan Live Streaming">
                                                <i data-lucide="stop-circle" class="h-4 w-4"></i>
                                                Selesai
                                            </button>
                                        </form>
                                    @endif
                                    @if(
                                        $publicShowRoute
                                        && ($publicShowRouteKey === null || filled(data_get($item, $publicShowRouteKey)))
                                        && (! filled(data_get($item, 'status')) || data_get($item, 'status') === 'published')
                                    )
                                        <a
                                            href="{{ $publicShowRouteKey ? route($publicShowRoute, data_get($item, $publicShowRouteKey)) : route($publicShowRoute) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-600"
                                            title="Pratinjau"
                                        >
                                            <i data-lucide="external-link" class="h-4 w-4"></i>
                                        </a>
                                    @endif

                                    @if($routePrefix === 'admin.consultation-requests')
                                        @php
                                            $detailPayload = [
                                                'id' => $item->id,
                                                'name' => $item->name,
                                                'email' => $item->email,
                                                'phone' => $item->phone,
                                                'organization' => $item->organization_name ?? '-',
                                                'subject' => $item->subject,
                                                'message' => $item->message,
                                                'status' => $item->status ?? 'Terkirim',
                                                'date' => optional($item->created_at)->translatedFormat('d F Y, H:i') . ' (' . optional($item->created_at)->diffForHumans() . ')',
                                                'edit_url' => route($routePrefix.'.edit', $item->id),
                                            ];
                                            $phone = preg_replace('/[^0-9]/', '', $item->phone ?? '');
                                            if (str_starts_with($phone, '0')) {
                                                $phone = '62' . substr($phone, 1);
                                            }
                                        @endphp
                                        <button type="button" @click="openDetailModal({{ json_encode($detailPayload) }})" class="inline-flex items-center gap-1.5 rounded-lg border border-cyan-200 bg-cyan-50 px-2.5 py-1.5 text-xs font-bold text-cyan-700 transition hover:bg-cyan-100 hover:border-cyan-300 shadow-sm" title="Lihat Detail Pesan">
                                            <i data-lucide="eye" class="h-4 w-4 text-cyan-600"></i>
                                            <span>Detail</span>
                                        </button>
                                        @if(filled($item->phone))
                                            <a href="https://wa.me/{{ $phone }}" target="_blank" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white transition hover:bg-emerald-600" title="Hubungi WA">
                                                <i data-lucide="message-square" class="h-4 w-4"></i>
                                            </a>
                                        @endif
                                    @endif
                                    <a href="{{ route($routePrefix.'.edit', $item->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600" title="Edit">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>
                                    <button type="button" @click="openDeleteModal('{{ route($routePrefix.'.destroy', $item->id) }}', '{{ addslashes($item->title ?? $item->name ?? 'data ini') }}')" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-500 transition hover:bg-rose-600 hover:text-white" title="Hapus">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($tableColumns) + 1 }}" class="py-8 text-center text-slate-400">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $items->links() }}</div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS CRUD -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showDeleteModal = false" class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                    <i data-lucide="trash-2" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Data</h3>
                    <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus <strong class="text-slate-900" x-text="deleteItemLabel"></strong>? Data yang telah dihapus tidak dapat dipulihkan kembali.
                </p>
            </div>

            <form :action="deleteActionUrl" method="POST" class="mt-6 flex justify-end gap-2.5 border-t border-slate-100 pt-4">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="h-10 px-5 text-xs font-bold rounded-xl bg-rose-600 text-white shadow-md shadow-rose-600/20 hover:bg-rose-700 transition">
                    Ya, Hapus Data
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL BUKU TAMU / PESAN -->
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
                        <p class="text-xs text-slate-500" x-text="detailItem.date"></p>
                    </div>
                </div>
                <button type="button" @click="showDetailModal = false" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="mt-4 flex-1 overflow-y-auto space-y-4 pr-1">
                <!-- Grid Informasi Pengirim -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Pengirim</span>
                        <p class="mt-1 text-xs font-bold text-slate-900" x-text="detailItem.name"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Instansi / Lembaga</span>
                        <p class="mt-1 text-xs font-semibold text-slate-800" x-text="detailItem.organization || '-'"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Email</span>
                        <p class="mt-1 text-xs font-medium text-slate-800">
                            <template x-if="detailItem.email">
                                <a :href="'mailto:' + detailItem.email" class="text-cyan-600 hover:underline inline-flex items-center gap-1 font-semibold">
                                    <span x-text="detailItem.email"></span>
                                    <i data-lucide="mail" class="h-3 w-3"></i>
                                </a>
                            </template>
                            <template x-if="!detailItem.email">
                                <span>-</span>
                            </template>
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">No. Telepon / WhatsApp</span>
                        <p class="mt-1 text-xs font-medium text-slate-800">
                            <template x-if="detailItem.phone">
                                <a :href="getWhatsappUrl(detailItem.phone)" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline inline-flex items-center gap-1 font-bold">
                                    <span x-text="detailItem.phone"></span>
                                    <i data-lucide="message-square" class="h-3 w-3"></i>
                                </a>
                            </template>
                            <template x-if="!detailItem.phone">
                                <span>-</span>
                            </template>
                        </p>
                    </div>
                </div>

                <!-- Subjek & Status -->
                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Perihal / Subjek</span>
                        <span class="rounded-full bg-cyan-100 px-2.5 py-0.5 text-[10px] font-bold text-cyan-800 uppercase tracking-wider" x-text="detailItem.status || 'Terkirim'"></span>
                    </div>
                    <p class="mt-1.5 text-xs font-bold text-slate-900" x-text="detailItem.subject"></p>
                </div>

                <!-- Pesan / Isi Buku Tamu -->
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Isi Pesan</span>
                    <div class="mt-2 text-xs leading-relaxed text-slate-700 whitespace-pre-line bg-slate-50 rounded-xl p-3 border border-slate-100 font-medium" x-text="detailItem.message || 'Tidak ada pesan tertulis.'"></div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-4">
                <div class="flex items-center gap-2">
                    <template x-if="detailItem.phone">
                        <a :href="getWhatsappUrl(detailItem.phone)" target="_blank" rel="noopener noreferrer" class="h-9 px-3.5 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm inline-flex items-center gap-1.5 transition">
                            <i data-lucide="message-square" class="h-3.5 w-3.5"></i>
                            <span>Chat WhatsApp</span>
                        </a>
                    </template>
                    <template x-if="detailItem.email">
                        <a :href="'mailto:' + detailItem.email" class="h-9 px-3.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 shadow-sm inline-flex items-center gap-1.5 transition">
                            <i data-lucide="mail" class="h-3.5 w-3.5 text-cyan-600"></i>
                            <span>Balas Email</span>
                        </a>
                    </template>
                </div>

                <div class="flex items-center gap-2">
                    <template x-if="detailItem.edit_url">
                        <a :href="detailItem.edit_url" class="h-9 px-3.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition inline-flex items-center gap-1.5">
                            <i data-lucide="pencil" class="h-3.5 w-3.5 text-slate-500"></i>
                            <span>Edit Pesan</span>
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
@endsection
