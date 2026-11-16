@extends('layouts.admin')

@section('content')
@php
    $defaultFields = [
        ['name' => 'Tahun', 'type' => 'number', 'required' => true, 'options' => ''],
        ['name' => 'Nama Usaha', 'type' => 'text', 'required' => true, 'options' => ''],
        ['name' => 'Kategori', 'type' => 'select', 'required' => false, 'options' => 'Kuliner, Fashion, Kosmetik, Farmasi, Jasa'],
        ['name' => 'Tanggal Terbit', 'type' => 'date', 'required' => false, 'options' => ''],
        ['name' => 'Keterangan', 'type' => 'textarea', 'required' => false, 'options' => ''],
    ];

    $existingFields = [];
    if (!empty($subModule->fields) && is_array($subModule->fields)) {
        foreach ($subModule->fields as $f) {
            $opts = $f['options'] ?? '';
            if (is_array($opts)) {
                $opts = implode(', ', $opts);
            }
            $existingFields[] = [
                'name' => $f['name'] ?? '',
                'type' => $f['type'] ?? 'text',
                'required' => !empty($f['required']),
                'options' => $opts,
            ];
        }
    }

    $initialFields = old('fields_json') 
        ? json_decode(old('fields_json'), true) 
        : (!empty($existingFields) ? $existingFields : $defaultFields);
@endphp

<div x-data="{
    name: '{{ addslashes(old('name', $subModule->name ?? '')) }}',
    description: '{{ addslashes(old('description', $subModule->description ?? '')) }}',
    instructions: '{{ addslashes(old('instructions', $subModule->instructions ?: config('data_direktorat.default_instructions'))) }}',
    fields: {{ json_encode($initialFields) }},
    activeTab: 'builder',
    showNoticeModal: false,
    noticeTitle: '',
    noticeMessage: '',
    showDeleteFieldModal: false,
    deleteFieldIndex: null,

    openNoticeModal(title, msg) {
        this.noticeTitle = title;
        this.noticeMessage = msg;
        this.showNoticeModal = true;
    },
    addField() {
        this.fields.push({
            name: '',
            type: 'text',
            required: false,
            options: ''
        });
    },
    promptRemoveField(index) {
        if (this.fields.length <= 1) {
            this.openNoticeModal('Tidak Dapat Menghapus Kolom', 'Sub-modul minimal harus memiliki 1 definisi field kolom.');
            return;
        }
        this.deleteFieldIndex = index;
        this.showDeleteFieldModal = true;
    },
    executeRemoveField() {
        if (this.deleteFieldIndex !== null) {
            this.fields.splice(this.deleteFieldIndex, 1);
            this.deleteFieldIndex = null;
        }
        this.showDeleteFieldModal = false;
    },
    removeField(index) {
        this.promptRemoveField(index);
    },
    moveUp(index) {
        if (index > 0) {
            const item = this.fields.splice(index, 1)[0];
            this.fields.splice(index - 1, 0, item);
        }
    },
    moveDown(index) {
        if (index < this.fields.length - 1) {
            const item = this.fields.splice(index, 1)[0];
            this.fields.splice(index + 1, 0, item);
        }
    },
    fieldsJson() {
        return JSON.stringify(this.fields);
    }
}" class="space-y-6">

    <!-- Header Section -->
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dynamic-sub-modules.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition">
                    &larr; Kembali ke Daftar
                </a>
            </div>
            <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">
                Form Builder &bull; {{ $currentDirectorate->title ?? 'Direktorat' }}
            </p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">
                {{ $isEdit ? 'Edit Form Builder Sub-Modul' : 'Buat Sub-Modul Dinamis Baru' }}
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Rancang kolom data sesuai kebutuhan direktorat Anda. Sistem akan mengenerate form input manual dan template Excel secara otomatis.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" @click="activeTab = 'builder'" :class="activeTab === 'builder' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="h-10 px-4 text-xs font-bold rounded-xl transition">
                Form Builder
            </button>
            <button type="button" @click="activeTab = 'preview'" :class="activeTab === 'preview' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="h-10 px-4 text-xs font-bold rounded-xl transition inline-flex items-center gap-1.5">
                <i data-lucide="eye" class="h-4 w-4"></i>
                <span>Live Preview</span>
            </button>
        </div>
    </div>

    <!-- Error Validation Alert -->
    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-medium text-rose-800 shadow-sm">
            <p class="font-bold mb-1">Terjadi kesalahan pada pengisian form:</p>
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.dynamic-sub-modules.update', $subModule->id) : route('admin.dynamic-sub-modules.store') }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <input type="hidden" name="fields_json" :value="fieldsJson()">

        <div class="space-y-6">

            <!-- Tab 1: Form Builder -->
            <div x-show="activeTab === 'builder'" class="space-y-6">

                <!-- 1. Metadata Sub-Modul -->
                <div class="admin-card rounded-[1.75rem] p-6 border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i data-lucide="info" class="h-4 w-4 text-emerald-600"></i>
                        <span>1. Informasi Umum Sub-Modul</span>
                    </h3>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Nama Sub-Modul <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" x-model="name" required placeholder="Contoh: Data UMKM Binaan Halal" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-medium text-slate-800 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            <p class="mt-1 text-[11px] text-slate-400">Nama sub-modul yang akan tampil di menu dan judul tabel.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Direktorat Pemilik
                            </label>
                            <input type="text" readonly value="{{ $currentDirectorate->title ?? 'Direktorat' }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs font-semibold text-slate-500 cursor-not-allowed">
                            <p class="mt-1 text-[11px] text-slate-400">Terikat otomatis dengan hak akses direktorat Anda.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Deskripsi Sub-Modul (Opsional)
                            </label>
                            <input type="text" name="description" x-model="description" placeholder="Penjelasan singkat mengenai peruntukan sub-modul ini..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-medium text-slate-800 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Petunjuk Pengisian Template Excel
                            </label>
                            <input type="text" name="instructions" x-model="instructions" placeholder="Isi mulai baris 7; jangan ubah kolom induk di baris 6..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-medium text-slate-800 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            <p class="mt-1 text-[11px] text-slate-400">Teks petunjuk yang akan dicetak pada Baris 3 template Excel.</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Form Builder: Definisi Field Kolom -->
                <div class="admin-card rounded-[1.75rem] p-6 border border-slate-100 shadow-sm space-y-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                                <i data-lucide="table" class="h-4 w-4 text-emerald-600"></i>
                                <span>2. Definisi Field Kolom (Tabel & Form Input)</span>
                            </h3>
                            <p class="mt-0.5 text-xs text-slate-400">
                                Kolom pertama akan bertindak sebagai <strong>Key Utama (Upsert)</strong> saat proses import Excel.
                            </p>
                        </div>

                        <button type="button" @click="addField()" class="h-9 px-4 text-xs font-bold rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 shadow-sm inline-flex items-center gap-1.5 transition">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            <span>Tambah Field</span>
                        </button>
                    </div>

                    <!-- Fields List Container -->
                    <div class="space-y-3">
                        <template x-for="(field, index) in fields" :key="index">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 transition hover:border-emerald-200 hover:bg-white shadow-sm space-y-3">
                                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-600 text-xs font-bold text-white" x-text="index + 1"></span>
                                        <span class="text-xs font-bold text-slate-800" x-text="field.name ? field.name : 'Field Baru'"></span>
                                        <template x-if="index === 0">
                                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-800">Key Utama (Upsert)</span>
                                        </template>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="moveUp(index)" :disabled="index === 0" class="h-7 w-7 rounded-lg border border-slate-200 bg-white text-slate-500 hover:text-emerald-600 disabled:opacity-30 disabled:cursor-not-allowed inline-flex items-center justify-center" title="Pindah Ke Atas">
                                            <i data-lucide="chevron-up" class="h-3.5 w-3.5"></i>
                                        </button>
                                        <button type="button" @click="moveDown(index)" :disabled="index === fields.length - 1" class="h-7 w-7 rounded-lg border border-slate-200 bg-white text-slate-500 hover:text-emerald-600 disabled:opacity-30 disabled:cursor-not-allowed inline-flex items-center justify-center" title="Pindah Ke Bawah">
                                            <i data-lucide="chevron-down" class="h-3.5 w-3.5"></i>
                                        </button>
                                        <button type="button" @click="removeField(index)" class="h-7 w-7 rounded-lg border border-rose-200 bg-white text-rose-500 hover:bg-rose-50 hover:text-rose-600 inline-flex items-center justify-center" title="Hapus Field">
                                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-12">
                                    <!-- Nama Field -->
                                    <div class="sm:col-span-5">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">
                                            Nama Field / Kolom <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" x-model="field.name" placeholder="Contoh: Nama Usaha" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-800 outline-none focus:border-emerald-500">
                                    </div>

                                    <!-- Tipe Field -->
                                    <div class="sm:col-span-4">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">
                                            Tipe Input Data
                                        </label>
                                        <select x-model="field.type" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-800 outline-none focus:border-emerald-500">
                                            <option value="text">Teks Singkat (Text)</option>
                                            <option value="number">Angka / Numerik (Number)</option>
                                            <option value="date">Tanggal (Date)</option>
                                            <option value="select">Pilihan Dropdown (Select)</option>
                                            <option value="textarea">Teks Panjang (Textarea)</option>
                                        </select>
                                    </div>

                                    <!-- Wajib / Opsional -->
                                    <div class="sm:col-span-3 flex items-center pt-5">
                                        <label class="relative flex items-center gap-2 cursor-pointer select-none">
                                            <input type="checkbox" x-model="field.required" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-xs font-bold text-slate-700">Wajib Diisi</span>
                                        </label>
                                    </div>

                                    <!-- Input Opsi jika tipe Select -->
                                    <template x-if="field.type === 'select'">
                                        <div class="sm:col-span-12 rounded-xl border border-indigo-100 bg-indigo-50/50 p-3">
                                            <label class="block text-[11px] font-bold text-indigo-900 mb-1">
                                                Pilihan Opsi Dropdown (Pisahkan dengan koma atau baris baru)
                                            </label>
                                            <input type="text" x-model="field.options" placeholder="Contoh: Aktif, Menunggu, Non-Aktif" class="w-full rounded-xl border border-indigo-200 bg-white px-3 py-2 text-xs font-medium text-slate-800 outline-none focus:border-indigo-500">
                                            <p class="mt-1 text-[10px] text-indigo-600">Pengguna akan memilih salah satu dari opsi ini pada form manual atau import Excel.</p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="pt-2">
                        <button type="button" @click="addField()" class="w-full rounded-2xl border-2 border-dashed border-slate-200 p-3.5 text-xs font-bold text-slate-500 hover:border-emerald-500 hover:text-emerald-600 transition flex items-center justify-center gap-2">
                            <i data-lucide="plus-circle" class="h-4 w-4"></i>
                            <span>Tambah Field Kolom Lainnya</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Tab 2: Live Preview -->
            <div x-show="activeTab === 'preview'" class="space-y-6">

                <!-- Preview 1: Template Excel -->
                <div class="admin-card rounded-[1.75rem] p-6 border border-slate-100 shadow-sm space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-700">
                        <i data-lucide="file-spreadsheet" class="h-4 w-4 text-emerald-600"></i>
                        <span>Preview Struktur Template Excel Otomatis</span>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full text-xs">
                            <tbody>
                                <!-- Row 1 Title -->
                                <tr>
                                    <td class="bg-slate-100 font-mono text-[10px] text-slate-400 p-2 text-center w-10 border-r border-b border-slate-200">1</td>
                                    <td :colspan="fields.length" class="bg-emerald-700 text-white font-bold p-3 text-sm">
                                        TEMPLATE UPLOAD: <span x-text="name ? name.toUpperCase() : 'NAMA SUB-MODUL'"></span>
                                    </td>
                                </tr>
                                <!-- Row 2 Blank -->
                                <tr>
                                    <td class="bg-slate-100 font-mono text-[10px] text-slate-400 p-1 text-center border-r border-b border-slate-200">2</td>
                                    <td :colspan="fields.length" class="bg-slate-50 p-1 border-b border-slate-200"></td>
                                </tr>
                                <!-- Row 3 Instructions -->
                                <tr>
                                    <td class="bg-slate-100 font-mono text-[10px] text-slate-400 p-2 text-center border-r border-b border-slate-200">3</td>
                                    <td :colspan="fields.length" class="italic text-slate-600 p-2 border-b border-slate-200 text-[11px]">
                                        PETUNJUK: <span x-text="instructions"></span>
                                    </td>
                                </tr>
                                <!-- Row 4 & 5 Blank -->
                                <tr>
                                    <td class="bg-slate-100 font-mono text-[10px] text-slate-400 p-1 text-center border-r border-b border-slate-200">4-5</td>
                                    <td :colspan="fields.length" class="bg-slate-50 p-1 border-b border-slate-200"></td>
                                </tr>
                                <!-- Row 6 Column Headers -->
                                <tr>
                                    <td class="bg-slate-100 font-mono text-[10px] text-slate-400 p-2 text-center border-r border-b border-slate-200">6</td>
                                    <template x-for="(f, i) in fields" :key="i">
                                        <td class="bg-emerald-600 text-white font-bold p-2.5 text-center border-r border-b border-emerald-700 whitespace-nowrap">
                                            <span x-text="f.name ? f.name : 'Kolom ' + (i+1)"></span>
                                            <template x-if="f.required">
                                                <span class="text-amber-200">*</span>
                                            </template>
                                        </td>
                                    </template>
                                </tr>
                                <!-- Row 7 Sample Data -->
                                <tr>
                                    <td class="bg-slate-100 font-mono text-[10px] text-slate-400 p-2 text-center border-r border-b border-slate-200">7</td>
                                    <template x-for="(f, i) in fields" :key="i">
                                        <td class="p-2.5 text-slate-400 italic border-r border-b border-slate-200 text-center">
                                            (Isi <span x-text="f.type"></span>)
                                        </td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Preview 2: Form Input Manual -->
                <div class="admin-card rounded-[1.75rem] p-6 border border-slate-100 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-700">
                        <i data-lucide="form-input" class="h-4 w-4 text-emerald-600"></i>
                        <span>Preview Modal Tambah Data Manual Otomatis</span>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-6 space-y-4 max-w-xl">
                        <h4 class="font-bold text-sm text-slate-900 border-b border-slate-200 pb-2">
                            Tambah Baris Data: <span x-text="name ? name : 'Sub-Modul'"></span>
                        </h4>

                        <div class="space-y-3">
                            <template x-for="(f, i) in fields" :key="i">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        <span x-text="f.name ? f.name : 'Field ' + (i+1)"></span>
                                        <template x-if="f.required">
                                            <span class="text-rose-500">* (Wajib)</span>
                                        </template>
                                    </label>

                                    <!-- Render Text -->
                                    <template x-if="f.type === 'text'">
                                        <input type="text" disabled :placeholder="'Isi ' + (f.name || 'teks') + '...'" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-600">
                                    </template>

                                    <!-- Render Number -->
                                    <template x-if="f.type === 'number'">
                                        <input type="number" disabled :placeholder="'Isi angka ' + (f.name || '') + '...'" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-600">
                                    </template>

                                    <!-- Render Date -->
                                    <template x-if="f.type === 'date'">
                                        <input type="date" disabled class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-600">
                                    </template>

                                    <!-- Render Select -->
                                    <template x-if="f.type === 'select'">
                                        <select disabled class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-600">
                                            <option>-- Pilih Opsi --</option>
                                        </select>
                                    </template>

                                    <!-- Render Textarea -->
                                    <template x-if="f.type === 'textarea'">
                                        <textarea rows="2" disabled :placeholder="'Isi catatan ' + (f.name || '') + '...'" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-600"></textarea>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Form Action Footer -->
            <div class="flex items-center justify-between border-t border-slate-100 pt-6">
                <a href="{{ route('admin.dynamic-sub-modules.index') }}" class="h-11 px-5 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition inline-flex items-center">
                    Batal
                </a>

                <button type="submit" class="h-11 px-6 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/20 inline-flex items-center gap-2 transition">
                    <i data-lucide="check" class="h-4 w-4"></i>
                    <span>{{ $isEdit ? 'Simpan Perubahan Sub-Modul' : 'Simpan & Buat Sub-Modul' }}</span>
                </button>
            </div>

        </div>
    </form>

    <!-- MODAL PERINGATAN BUILDER -->
    <div x-show="showNoticeModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showNoticeModal = false" class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                    <i data-lucide="alert-triangle" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900" x-text="noticeTitle"></h3>
                    <p class="text-xs text-slate-500">Pemberitahuan Sistem</p>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs text-slate-600 leading-relaxed" x-text="noticeMessage"></p>
            </div>

            <div class="mt-6 flex justify-end border-t border-slate-100 pt-4">
                <button type="button" @click="showNoticeModal = false" class="h-10 px-5 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS FIELD KOLOM -->
    <div x-show="showDeleteFieldModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div @click.away="showDeleteFieldModal = false" class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center gap-4 border-b border-slate-100 pb-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                    <i data-lucide="trash-2" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Hapus Kolom Field</h3>
                    <p class="text-xs text-slate-500">Konfirmasi penghapusan kolom pada form builder</p>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus kolom <strong class="text-slate-900" x-text="deleteFieldIndex !== null && fields[deleteFieldIndex] ? (fields[deleteFieldIndex].name || 'Kolom Baru') : ''"></strong> dari definisi sub-modul ini?
                </p>
            </div>

            <div class="mt-6 flex justify-end gap-2.5 border-t border-slate-100 pt-4">
                <button type="button" @click="showDeleteFieldModal = false" class="h-10 px-4 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="button" @click="executeRemoveField()" class="h-10 px-5 text-xs font-bold rounded-xl bg-rose-600 text-white shadow-md shadow-rose-600/20 hover:bg-rose-700 transition">
                    Ya, Hapus Kolom
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
