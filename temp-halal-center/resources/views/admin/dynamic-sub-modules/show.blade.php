@extends('layouts.admin')

@section('content')
<div x-data="{
    searchQuery: '',
    showTambahModal: false,
    showEditModal: false,
    showChangeTemplateModal: false,
    showImportWarningModal: false,
    showImportFileModal: false,
    showClearConfirmModal: false,
    showDeleteRowModal: false,
    deleteRowUrl: '',
    randomCode: '',
    userInputCode: '',
    editRowId: null,
    editData: {},
    columns: {{ json_encode($columns) }},
    hasTemplate: {{ !empty($columns) ? 'true' : 'false' }},
    hasRows: {{ count($rows) > 0 ? 'true' : 'false' }},

    openDeleteRowModal(url) {
        this.deleteRowUrl = url;
        this.showDeleteRowModal = true;
    },
    generateCode() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        let res = '';
        for (let i = 0; i < 5; i++) {
            res += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        this.randomCode = res;
        this.userInputCode = '';
    },
    openClearModal() {
        this.generateCode();
        this.showClearConfirmModal = true;
    }
}" class="space-y-6">

    <!-- Header Section -->
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.dynamic-sub-modules.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition">
                    &larr; Daftar Sub-Modul
                </a>
            </div>
            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">
                Data Direktorat &bull; {{ $sector->title ?? 'Direktorat' }} (Sub-Modul Dinamis)
            </p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">{{ $dynamicSubModule->name }}</h2>
            @if($dynamicSubModule->description)
                <p class="mt-1 text-xs text-slate-500">{{ $dynamicSubModule->description }}</p>
            @endif
        </div>

        <!-- Indicator Stat Badge & Action -->
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dynamic-sub-modules.edit', $dynamicSubModule->id) }}" class="h-10 px-3.5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 shadow-sm inline-flex items-center gap-2 transition" title="Ubah Definisi Field Kolom">
                <i data-lucide="settings-2" class="h-4 w-4 text-emerald-600"></i>
                <span class="hidden sm:inline">Pengaturan Kolom</span>
            </a>

            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-md shadow-emerald-500/20">
                    <i data-lucide="database" class="h-5 w-5"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500">Total Baris Data</p>
                    <p class="text-lg font-black text-slate-900">{{ count($rows) }} <span class="text-xs font-normal text-slate-400">entri</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert / Status Message -->
    @if(session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-medium text-emerald-800 shadow-sm flex items-center gap-3">
            <i data-lucide="check-circle" class="h-5 w-5 text-emerald-600 shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-medium text-rose-800 shadow-sm flex items-center gap-3">
            <i data-lucide="alert-circle" class="h-5 w-5 text-rose-600 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Active Template Info Box -->
    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-r from-emerald-900/5 via-emerald-500/5 to-teal-500/5 p-4 md:p-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 rounded-lg bg-emerald-600/10 p-2 text-emerald-600">
                    <i data-lucide="file-spreadsheet" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Struktur Template Aktif saat ini:</h2>
                    <p class="mt-1 text-xs italic text-slate-600">
                        <span class="font-semibold text-emerald-800">Petunjuk:</span> {{ $dynamicSubModule->instructions ?: config('data_direktorat.default_instructions') }}
                    </p>
                    <div class="mt-3 flex flex-wrap items-center gap-1.5">
                        <span class="text-[11px] font-bold text-slate-500">Kolom (Baris 6):</span>
                        @foreach($columns as $idx => $col)
                            <span class="inline-flex items-center gap-1 rounded-md bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200">
                                <span>{{ $col }}</span>
                                @if($idx === 0)
                                    <span class="text-[9px] font-bold text-emerald-600 uppercase tracking-wider bg-emerald-50 px-1 rounded">Key</span>
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar Action Buttons -->
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <!-- Search Filter Input -->
        <div class="relative w-full max-w-xs">
            <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
            <input type="text" x-model="searchQuery" placeholder="Cari isi data..." class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-2 text-xs font-medium outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
        </div>

        <!-- Action Buttons Container -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- 1. Unduh Template (Selalu tampil) -->
            <a href="{{ route('admin.dynamic-sub-modules.download-template', $dynamicSubModule->id) }}" class="h-10 px-4 text-xs font-bold rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 shadow-sm inline-flex items-center justify-center gap-2 transition-all">
                <i data-lucide="download" class="h-4 w-4"></i>
                <span>Unduh Template</span>
            </a>

            <!-- 2. Ganti Template -->
            <button type="button" @click="showChangeTemplateModal = true" class="h-10 px-4 text-xs font-bold rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 shadow-sm inline-flex items-center justify-center gap-2 transition-all">
                <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                <span>Ganti Template</span>
            </button>

            <!-- 3. Import Excel (Memicu Peringatan Backup) -->
            <button type="button" @click="showImportWarningModal = true" class="h-10 px-4 text-xs font-bold rounded-xl border border-emerald-600 bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm inline-flex items-center justify-center gap-2 transition-all">
                <i data-lucide="upload" class="h-4 w-4"></i>
                <span>Import Excel</span>
            </button>

            <!-- 4. Tambah Manual -->
            <button type="button" @click="showTambahModal = true" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 shadow-sm inline-flex items-center justify-center gap-2 transition-all">
                <i data-lucide="plus" class="h-4 w-4 text-emerald-600"></i>
                <span>Tambah Manual</span>
            </button>

            <!-- 5. Backup Data (Saat tabel terisi data) -->
            @if(count($rows) > 0)
                <a href="{{ route('admin.dynamic-sub-modules.backup-data', $dynamicSubModule->id) }}" class="h-10 px-4 text-xs font-bold rounded-xl border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 shadow-sm inline-flex items-center justify-center gap-2 transition-all">
                    <i data-lucide="file-check" class="h-4 w-4"></i>
                    <span>Backup Data</span>
                </a>
            @endif

            <!-- 6. Kosongkan Data (Saat tabel terisi data) -->
            @if(count($rows) > 0)
                <button type="button" @click="openClearModal()" class="h-10 px-4 text-xs font-bold rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 shadow-sm inline-flex items-center justify-center gap-2 transition-all">
                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                    <span>Kosongkan Data</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="admin-card rounded-[1.75rem] p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table min-w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-700">
                        <th class="w-14 px-4 py-3.5 text-center font-bold">No.</th>
                        @foreach($columns as $col)
                            <th class="px-4 py-3.5 font-bold uppercase tracking-wider whitespace-nowrap">{{ $col }}</th>
                        @endforeach
                        <th class="w-28 px-4 py-3.5 text-right font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rows as $index => $row)
                        @php
                            $rowJson = json_encode($row->data);
                            $rowText = implode(' ', array_values($row->data ?? []));
                        @endphp
                        <tr x-show="searchQuery === '' || '{{ strtolower(addslashes($rowText)) }}'.includes(searchQuery.toLowerCase())" class="border-t border-slate-100 transition hover:bg-slate-50">
                            <td class="px-4 py-4 text-center font-semibold text-slate-500 align-middle">{{ $index + 1 }}</td>
                            @foreach($columns as $col)
                                <td class="px-4 py-4 font-medium text-slate-800 whitespace-nowrap align-middle">
                                    {{ $row->data[$col] ?? '-' }}
                                </td>
                            @endforeach
                            <!-- Row Action Buttons -->
                            <td class="px-4 py-4 align-middle text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Edit Button -->
                                    <button type="button" @click="editRowId = {{ $row->id }}; editData = {{ $rowJson }}; showEditModal = true;" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600" title="Edit">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </button>
                                    <!-- Delete Button -->
                                    <button type="button" @click="openDeleteRowModal('{{ route('admin.dynamic-sub-modules.destroy-row', ['dynamic_sub_module' => $dynamicSubModule->id, 'rowId' => $row->id]) }}')" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-500 transition hover:bg-rose-600 hover:text-white" title="Hapus">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 2 }}" class="py-12 px-6">
                                <div class="mx-auto max-w-2xl text-center">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 shadow-inner">
                                        <i data-lucide="file-x-2" class="h-8 w-8"></i>
                                    </div>
                                    <h3 class="mt-4 text-base font-bold text-slate-900">Tabel Masih Kosong</h3>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Sebelum diisi atau diimport, tabel sub-modul ini kosong. Silakan ikuti ringkasan alur kerja di bawah ini untuk memulai pengisian data.
                                    </p>

                                    <!-- Workflow Guide -->
                                    <div class="mt-6 border-t border-slate-100 pt-6">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">Ringkasan Alur Kerja Pengelolaan Data:</h4>
                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-5 text-left">
                                            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-[10px] font-bold text-white mb-2">1</span>
                                                <p class="text-xs font-bold text-slate-800">Unduh Template</p>
                                                <p class="mt-1 text-[11px] text-slate-500">Unduh format Excel resmi hasil generate sistem.</p>
                                            </div>
                                            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-bold text-white mb-2">2</span>
                                                <p class="text-xs font-bold text-slate-800">Ganti Template</p>
                                                <p class="mt-1 text-[11px] text-slate-500">Ubah struktur kolom jika ada penyesuaian baru.</p>
                                            </div>
                                            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-teal-600 text-[10px] font-bold text-white mb-2">3</span>
                                                <p class="text-xs font-bold text-slate-800">Import Excel</p>
                                                <p class="mt-1 text-[11px] text-slate-500">Sistem melakukan upsert data dari baris 7.</p>
                                            </div>
                                            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-slate-700 text-[10px] font-bold text-white mb-2">4</span>
                                                <p class="text-xs font-bold text-slate-800">Tambah Manual</p>
                                                <p class="mt-1 text-[11px] text-slate-500">Input baris data satu persatu melalui form builder.</p>
                                            </div>
                                            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                                                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-600 text-[10px] font-bold text-white mb-2">5</span>
                                                <p class="text-xs font-bold text-slate-800">Backup / Kosongkan</p>
                                                <p class="mt-1 text-[11px] text-slate-500">Aktif otomatis setelah tabel terisi data.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                                        <button type="button" @click="showTambahModal = true" class="h-10 px-5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 shadow-sm inline-flex items-center gap-2 transition-all">
                                            <i data-lucide="plus" class="h-4 w-4 text-emerald-600"></i>
                                            <span>Tambah Baris Manual</span>
                                        </button>
                                        <button type="button" @click="showImportWarningModal = true" class="h-10 px-5 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/20 inline-flex items-center gap-2 transition-all">
                                            <i data-lucide="upload" class="h-4 w-4"></i>
                                            <span>Mulai Import Excel</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL PRE-IMPORT WARNING ("Backup Data Dulu") -->
    <div x-show="showImportWarningModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showImportWarningModal = false" class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                    <i data-lucide="alert-triangle" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Backup Data Terlebih Dahulu</h3>
                    <p class="text-xs text-slate-500">Peringatan sebelum melakukan import data Excel</p>
                </div>
            </div>

            <div class="mt-4 rounded-2xl bg-amber-50/70 p-4 border border-amber-100 text-xs text-amber-900 space-y-2">
                <p>
                    <strong>Perhatian:</strong> Proses import menggunakan metode <strong>Upsert</strong> (data dengan key/kolom pertama yang sama akan diperbarui, data baru akan ditambahkan).
                </p>
                <p>
                    Disarankan untuk mengunduh <strong>Backup Data</strong> terlebih dahulu jika tabel sudah memiliki data.
                </p>
            </div>

            <div class="mt-6 flex justify-end gap-2.5">
                <button type="button" @click="showImportWarningModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                    Batal
                </button>
                <button type="button" @click="showImportWarningModal = false; showImportFileModal = true;" class="h-10 px-5 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/20">
                    Lanjut Import Excel
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL FILE IMPORT EXCEL -->
    <div x-show="showImportFileModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showImportFileModal = false" class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <i data-lucide="upload" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Unggah File Data Excel</h3>
                        <p class="text-xs text-slate-500">Pilih file Excel berformat sesuai template sub-modul ini</p>
                    </div>
                </div>
                <button type="button" @click="showImportFileModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.dynamic-sub-modules.import-excel', $dynamicSubModule->id) }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih File Data Excel (.xlsx / .xls)</label>
                    <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-700 outline-none focus:border-emerald-500">
                    <div class="mt-2.5 rounded-xl border border-emerald-100 bg-emerald-50/60 p-3 text-[11px] text-emerald-800">
                        <p class="font-bold">Sistem Import (Upsert):</p>
                        <ul class="mt-1 list-disc pl-4 space-y-0.5">
                            <li>Header kolom berada pada <strong>Baris 6</strong>.</li>
                            <li>Data diisi mulai dari <strong>Baris 7</strong>.</li>
                            <li>Kolom pertama (<strong>{{ $columns[0] ?? 'Key' }}</strong>) digunakan sebagai <strong>Key Utama</strong>. Baris dengan Key sama akan diperbarui, Key baru akan ditambahkan.</li>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" @click="showImportFileModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="h-10 px-5 text-xs font-bold rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700">Proses Import Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL GANTI TEMPLATE EXCEL -->
    <div x-show="showChangeTemplateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showChangeTemplateModal = false" class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i data-lucide="refresh-cw" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Ganti Template Excel</h3>
                        <p class="text-xs text-slate-500">Unggah file template baru untuk mengubah struktur kolom tabel sub-modul</p>
                    </div>
                </div>
                <button type="button" @click="showChangeTemplateModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.dynamic-sub-modules.change-template', $dynamicSubModule->id) }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih File Template (.xlsx / .xls)</label>
                    <input type="file" name="template_file" accept=".xlsx,.xls,.csv" required class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-700 outline-none focus:border-indigo-500">
                    <p class="mt-1.5 text-[11px] text-slate-500">
                        *Sistem akan membaca header kolom dari baris 6 pada file template baru ini dan otomatis memperbarui struktur kolom sub-modul.
                    </p>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" @click="showChangeTemplateModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="h-10 px-5 text-xs font-bold rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700">Simpan & Perbarui Struktur</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KONFIRMASI KOSONGKAN DATA -->
    <div x-show="showClearConfirmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showClearConfirmModal = false" class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                    <i data-lucide="shield-alert" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Konfirmasi Kosongkan Data</h3>
                    <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <form action="{{ route('admin.dynamic-sub-modules.clear-rows', $dynamicSubModule->id) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <p class="text-xs text-slate-600 leading-relaxed">
                    Semua baris data untuk sub-modul <strong>{{ $dynamicSubModule->name }}</strong> akan dihapus permanen. Ketik kode verifikasi di bawah ini untuk mengonfirmasi:
                </p>

                <div class="rounded-2xl bg-slate-100 p-4 text-center">
                    <p class="text-[11px] font-semibold text-slate-500">Kode Verifikasi Konfirmasi:</p>
                    <p class="mt-1 text-2xl font-mono font-black tracking-widest text-slate-800 select-all" x-text="randomCode"></p>
                </div>

                <div>
                    <input type="text" x-model="userInputCode" placeholder="Masukkan 5 karakter kode..." class="w-full rounded-xl border border-slate-300 p-3 text-center text-sm font-mono font-bold tracking-widest uppercase outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20">
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" @click="showClearConfirmModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" :disabled="userInputCode.trim().toUpperCase() !== randomCode" class="h-10 px-5 text-xs font-bold rounded-xl bg-rose-600 text-white shadow-md shadow-rose-600/20 hover:bg-rose-700 disabled:opacity-40 disabled:cursor-not-allowed">
                        Konfirmasi Kosongkan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH DATA MANUAL (Digenerate Dinamis sesuai Definisi Field) -->
    <div x-show="showTambahModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showTambahModal = false" class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl transition-all max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Tambah Baris Data Manual</h3>
                    <p class="text-xs text-slate-500">Isi field data sesuai formulir yang digenerate otomatis</p>
                </div>
                <button type="button" @click="showTambahModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.dynamic-sub-modules.store-row', $dynamicSubModule->id) }}" method="POST" class="mt-4 space-y-3.5">
                @csrf
                @foreach($fields as $field)
                    @php
                        $fName = $field['name'] ?? '';
                        $fType = $field['type'] ?? 'text';
                        $fReq = !empty($field['required']);
                        $fOpts = $field['options'] ?? [];
                        if (is_string($fOpts)) {
                            $fOpts = array_filter(array_map('trim', explode(',', $fOpts)));
                        }
                        $inputKey = 'col_' . md5($fName);
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            {{ $fName }}
                            @if($fReq)
                                <span class="text-rose-500">* (Wajib)</span>
                            @else
                                <span class="text-slate-400 font-normal">(Opsional)</span>
                            @endif
                        </label>

                        @if($fType === 'number')
                            <input type="number" step="any" name="{{ $inputKey }}" {{ $fReq ? 'required' : '' }} placeholder="Isi angka {{ $fName }}..." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        @elseif($fType === 'date')
                            <input type="date" name="{{ $inputKey }}" {{ $fReq ? 'required' : '' }} class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        @elseif($fType === 'select')
                            <select name="{{ $inputKey }}" {{ $fReq ? 'required' : '' }} class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                <option value="">-- Pilih {{ $fName }} --</option>
                                @foreach($fOpts as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        @elseif($fType === 'textarea')
                            <textarea name="{{ $inputKey }}" rows="3" {{ $fReq ? 'required' : '' }} placeholder="Isi catatan {{ $fName }}..." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"></textarea>
                        @else
                            <input type="text" name="{{ $inputKey }}" {{ $fReq ? 'required' : '' }} placeholder="Isi {{ $fName }}..." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        @endif
                    </div>
                @endforeach

                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" @click="showTambahModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="h-10 px-5 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/20">Tambah Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT DATA MANUAL -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showEditModal = false" class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl transition-all max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Edit Baris Data</h3>
                    <p class="text-xs text-slate-500">Perbarui baris data sub-modul</p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/dynamic-sub-modules/' . $dynamicSubModule->id . '/update-row') }}/' + editRowId" method="POST" class="mt-4 space-y-3.5">
                @csrf
                @method('PUT')
                @foreach($fields as $field)
                    @php
                        $fName = $field['name'] ?? '';
                        $fType = $field['type'] ?? 'text';
                        $fReq = !empty($field['required']);
                        $fOpts = $field['options'] ?? [];
                        if (is_string($fOpts)) {
                            $fOpts = array_filter(array_map('trim', explode(',', $fOpts)));
                        }
                        $inputKey = 'col_' . md5($fName);
                    @endphp
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            {{ $fName }}
                            @if($fReq)
                                <span class="text-rose-500">* (Wajib)</span>
                            @else
                                <span class="text-slate-400 font-normal">(Opsional)</span>
                            @endif
                        </label>

                        @if($fType === 'number')
                            <input type="number" step="any" name="{{ $inputKey }}" :value="editData['{{ addslashes($fName) }}'] || ''" {{ $fReq ? 'required' : '' }} class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        @elseif($fType === 'date')
                            <input type="date" name="{{ $inputKey }}" :value="editData['{{ addslashes($fName) }}'] || ''" {{ $fReq ? 'required' : '' }} class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        @elseif($fType === 'select')
                            <select name="{{ $inputKey }}" :value="editData['{{ addslashes($fName) }}'] || ''" {{ $fReq ? 'required' : '' }} class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                <option value="">-- Pilih {{ $fName }} --</option>
                                @foreach($fOpts as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        @elseif($fType === 'textarea')
                            <textarea name="{{ $inputKey }}" rows="3" :value="editData['{{ addslashes($fName) }}'] || ''" {{ $fReq ? 'required' : '' }} class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"></textarea>
                        @else
                            <input type="text" name="{{ $inputKey }}" :value="editData['{{ addslashes($fName) }}'] || ''" {{ $fReq ? 'required' : '' }} class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        @endif
                    </div>
                @endforeach

                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" @click="showEditModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="h-10 px-5 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/20">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS BARIS DATA -->
    <div x-show="showDeleteRowModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showDeleteRowModal = false" class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                    <i data-lucide="trash-2" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Baris Data</h3>
                    <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus baris data ini dari tabel?
                </p>
            </div>

            <form :action="deleteRowUrl" method="POST" class="mt-6 flex justify-end gap-2.5 border-t border-slate-100 pt-4">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteRowModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="h-10 px-5 text-xs font-bold rounded-xl bg-rose-600 text-white shadow-md shadow-rose-600/20 hover:bg-rose-700 transition">
                    Ya, Hapus Baris
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
