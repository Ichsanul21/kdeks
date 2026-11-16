@extends('layouts.admin')

@section('content')
<div x-data="{
    showDeleteModal: false,
    deleteActionUrl: '',
    deleteItemName: '',
    openDeleteModal(url, name) {
        this.deleteActionUrl = url;
        this.deleteItemName = name;
        this.showDeleteModal = true;
    }
}" class="space-y-6">

    <!-- Header Section -->
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">
                Data Direktorat &bull; {{ $currentDirectorate->title ?? 'Semua Direktorat' }}
            </p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">Sub-Modul Dinamis</h2>
            <p class="mt-1 text-xs text-slate-500">
                Kelola sub-modul kustom khusus untuk {{ $currentDirectorate->title ?? 'direktorat Anda' }}. Setiap sub-modul memiliki form input dan template Excel mandiri.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if(auth()->user()->hasAnyRole(['developer', 'superadmin']) && $allDirectorates->count() > 1)
                <!-- Filter Direktorat untuk Superadmin/Developer -->
                <form method="GET" action="{{ route('admin.dynamic-sub-modules.index') }}" class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-600">Direktorat:</label>
                    <select name="sector_id" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 shadow-sm outline-none focus:border-emerald-500">
                        @foreach($allDirectorates as $dir)
                            <option value="{{ $dir->id }}" {{ optional($currentDirectorate)->id === $dir->id ? 'selected' : '' }}>
                                {{ $dir->title }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif

            <a href="{{ route('admin.dynamic-sub-modules.create') }}" class="h-10 px-4 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/20 inline-flex items-center gap-2 transition-all">
                <i data-lucide="plus" class="h-4 w-4"></i>
                <span>Buat Sub-Modul Baru</span>
            </a>
        </div>
    </div>

    <!-- Alert / Status Message -->
    @if(session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-medium text-emerald-800 shadow-sm flex items-center gap-3">
            <i data-lucide="check-circle" class="h-5 w-5 text-emerald-600 shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Content: List of Dynamic Sub-Modules -->
    @if($subModules->count() > 0)
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($subModules as $sub)
                <div class="admin-card rounded-[1.75rem] p-6 flex flex-col justify-between border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 shadow-inner">
                                <i data-lucide="layout-grid" class="h-5 w-5"></i>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600">
                                {{ $sub->rows_count ?? 0 }} Data
                            </span>
                        </div>

                        <h3 class="mt-4 font-heading text-lg font-bold text-slate-900 line-clamp-1" title="{{ $sub->name }}">
                            {{ $sub->name }}
                        </h3>

                        <p class="mt-1 text-xs text-slate-500 line-clamp-2">
                            {{ $sub->description ?: 'Tidak ada deskripsi tambahan.' }}
                        </p>

                        <!-- Fields Preview -->
                        <div class="mt-4 border-t border-slate-100 pt-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                                Kolom Field ({{ count($sub->columns) }}):
                            </p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($sub->fields as $f)
                                    <span class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-700 border border-slate-200" title="Tipe: {{ $f['type'] ?? 'text' }}">
                                        {{ $f['name'] }}
                                        <span class="text-[9px] text-slate-400">({{ $f['type'] ?? 'text' }})</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.dynamic-sub-modules.edit', $sub->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600 transition" title="Edit Form Builder">
                                <i data-lucide="settings-2" class="h-4 w-4"></i>
                            </a>

                            <button type="button" @click="openDeleteModal('{{ route('admin.dynamic-sub-modules.destroy', $sub->id) }}', '{{ addslashes($sub->name) }}')" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-rose-200 bg-white text-rose-500 hover:bg-rose-50 hover:text-rose-600 transition" title="Hapus Sub-Modul">
                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                            </button>
                        </div>

                        <a href="{{ route('admin.dynamic-sub-modules.show', $sub->id) }}" class="h-9 px-4 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm inline-flex items-center gap-1.5 transition">
                            <span>Buka Data</span>
                            <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State (Awalnya sub-modul tiap direktorat kosong) -->
        <div class="admin-card rounded-[2rem] p-12 text-center border border-slate-100 shadow-sm">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-emerald-50 text-emerald-600 shadow-inner">
                <i data-lucide="folder-plus" class="h-10 w-10"></i>
            </div>
            <h3 class="mt-5 font-heading text-xl font-bold text-slate-900">Belum Ada Sub-Modul Dinamis</h3>
            <p class="mx-auto mt-2 max-w-md text-xs text-slate-500 leading-relaxed">
                Direktorat <strong>{{ $currentDirectorate->title ?? '' }}</strong> belum memiliki sub-modul data dinamis. Anda dapat membuat sub-modul baru dengan menentukan nama dan definisi field kolom sesuai kebutuhan.
            </p>
            <div class="mt-6">
                <a href="{{ route('admin.dynamic-sub-modules.create') }}" class="h-11 px-6 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/20 inline-flex items-center gap-2 transition-all">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    <span>Buat Sub-Modul Pertama</span>
                </a>
            </div>
        </div>
    @endif

    <!-- MODAL KONFIRMASI HAPUS SUB-MODUL -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showDeleteModal = false" class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                    <i data-lucide="trash-2" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Sub-Modul</h3>
                    <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus sub-modul <strong class="text-slate-900" x-text="deleteItemName"></strong> beserta seluruh data di dalamnya?
                </p>
            </div>

            <form :action="deleteActionUrl" method="POST" class="mt-6 flex justify-end gap-2.5 border-t border-slate-100 pt-4">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="h-10 px-5 text-xs font-bold rounded-xl bg-rose-600 text-white shadow-md shadow-rose-600/20 hover:bg-rose-700 transition">
                    Ya, Hapus Sub-Modul
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
