@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">Content Management</p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">{{ $pageTitle }}</h2>
            <div class="mt-2 flex gap-4 text-sm font-semibold text-slate-500">
                <span><span class="text-emerald-600">{{ number_format($totalUmkm) }}</span> UMKM</span>
                <span>·</span>
                <span><span class="text-cyan-600">{{ number_format($totalProduk) }}</span> Produk</span>
            </div>
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
                Tambah UMKM
            </a>
        </div>
    </div>

    {{-- Import / Export Bar --}}
    <div class="admin-card mb-6 rounded-[1.75rem] p-6">
        <div class="mb-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
            Import bisa pakai `1 file XLSX` dengan 3 sheet (`UMKM Ringkas`, `UMKM Detail`, `Produk UMKM`) atau `3 file CSV` terpisah sesuai hasil scrape:
            `umkm-terverifikasi.csv`, `umkm-terverifikasi-detail.csv`, dan `umkm-terverifikasi-produk.csv`.
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="openModal('umkmImportModal')" class="inline-flex items-center gap-2 rounded-xl bg-cyan-500 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-cyan-500/30 transition hover:bg-cyan-400">
                    <i data-lucide="upload" class="h-4 w-4"></i>
                    Import Data
                </button>
                <button type="button" onclick="openModal('umkmExportModal')" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Export Data
                </button>
            </div>
            <p class="text-xs font-semibold text-slate-500">Batas unggah: 20 MB per file.</p>
        </div>

        @error('export')
            <p class="mt-3 text-sm text-rose-500">{{ $message }}</p>
        @enderror
        @error('import')
            <p class="mt-3 text-sm text-rose-500">{{ $message }}</p>
        @enderror
    </div>

    <div id="umkmImportModal" class="fixed inset-0 z-[100] hidden" @if($errors->hasAny(['import', 'import_file', 'umkm_file', 'umkm_detail_file', 'produk_file'])) data-open-on-load="true" @endif>
        <div id="umkmImportBackdrop" class="absolute inset-0 bg-slate-900/40 opacity-0 backdrop-blur-sm transition-opacity" onclick="closeModal('umkmImportModal')"></div>
        <div class="pointer-events-none absolute inset-0 flex items-start justify-center overflow-y-auto px-4 pb-10 pt-20">
            <div id="umkmImportContent" class="pointer-events-auto w-full max-w-3xl scale-95 rounded-3xl bg-white opacity-0 shadow-2xl transition-all">
                <div class="flex items-center justify-between rounded-t-3xl border-b border-slate-100 bg-slate-50 p-6">
                    <div>
                        <h3 class="font-heading text-xl font-extrabold tracking-tight text-slate-900">Import Data UMKM</h3>
                        <p class="text-xs font-medium text-slate-500">Unggah XLSX (3 sheet) atau CSV terpisah</p>
                    </div>
                    <button type="button" onclick="closeModal('umkmImportModal')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <div class="p-6 md:p-8">
                    <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        Import bisa pakai `1 file XLSX` dengan 3 sheet (`UMKM Ringkas`, `UMKM Detail`, `Produk UMKM`) atau `3 file CSV` terpisah:
                        `umkm-terverifikasi.csv`, `umkm-terverifikasi-detail.csv`, dan `umkm-terverifikasi-produk.csv`.
                    </div>

                    <form method="POST" action="{{ route('admin.umkms.import') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-xs font-bold text-slate-500">Bundle XLSX / CSV</label>
                            <input type="file" name="import_file" accept=".csv,.xlsx,.xls"
                                   class="admin-input w-full text-sm file:mr-3 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-xs file:font-bold file:text-emerald-600">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">CSV UMKM Ringkas</label>
                            <input type="file" name="umkm_file" accept=".csv"
                                   class="admin-input w-full text-sm file:mr-3 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-xs file:font-bold file:text-emerald-600">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">CSV UMKM Detail</label>
                            <input type="file" name="umkm_detail_file" accept=".csv"
                                   class="admin-input w-full text-sm file:mr-3 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-xs file:font-bold file:text-emerald-600">
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-xs font-bold text-slate-500">CSV Produk</label>
                            <input type="file" name="produk_file" accept=".csv"
                                   class="admin-input w-full text-sm file:mr-3 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-xs file:font-bold file:text-emerald-600">
                        </div>

                        @error('import')
                            <p class="md:col-span-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                        @foreach (['import_file', 'umkm_file', 'umkm_detail_file', 'produk_file'] as $field)
                            @error($field)
                                <p class="md:col-span-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endforeach

                        <div class="md:col-span-2 pt-2">
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-cyan-500 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-cyan-500/30 transition hover:bg-cyan-400">
                                <i data-lucide="upload" class="h-4 w-4"></i>
                                Upload & Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="umkmExportModal" class="fixed inset-0 z-[100] hidden" @if($errors->has('export')) data-open-on-load="true" @endif>
        <div id="umkmExportBackdrop" class="absolute inset-0 bg-slate-900/40 opacity-0 backdrop-blur-sm transition-opacity" onclick="closeModal('umkmExportModal')"></div>
        <div class="pointer-events-none absolute inset-0 flex items-start justify-center overflow-y-auto px-4 pb-10 pt-20">
            <div id="umkmExportContent" class="pointer-events-auto w-full max-w-xl scale-95 rounded-3xl bg-white opacity-0 shadow-2xl transition-all">
                <div class="flex items-center justify-between rounded-t-3xl border-b border-slate-100 bg-slate-50 p-6">
                    <div>
                        <h3 class="font-heading text-xl font-extrabold tracking-tight text-slate-900">Export Data UMKM</h3>
                        <p class="text-xs font-medium text-slate-500">Pilih format lalu unduh</p>
                    </div>
                    <button type="button" onclick="closeModal('umkmExportModal')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <div class="p-6 md:p-8">
                    <form method="GET" action="{{ route('admin.umkms.export') }}" class="grid gap-4">
                        <div class="grid gap-2">
                            <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="file-spreadsheet" class="h-4 w-4 text-emerald-600"></i>
                                    XLSX (1 file)
                                </span>
                                <input type="radio" name="format" value="xlsx" checked class="h-4 w-4">
                            </label>
                            <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="file-archive" class="h-4 w-4 text-cyan-600"></i>
                                    CSV (ZIP, 3 file)
                                </span>
                                <input type="radio" name="format" value="csv" class="h-4 w-4">
                            </label>
                        </div>

                        @error('export')
                            <p class="text-sm text-rose-500">{{ $message }}</p>
                        @enderror

                        <button type="submit" onclick="closeModal('umkmExportModal')" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800">
                            <i data-lucide="download" class="h-4 w-4"></i>
                            Download
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="admin-card rounded-[1.75rem] p-6">
        <form method="GET" class="mb-6">
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 focus-within:border-emerald-500/50 focus-within:ring-2 focus-within:ring-emerald-500/20">
                <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama UMKM, pemilik, kategori, kab/kota..." class="w-full border-none bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400">
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="admin-table min-w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="pb-4 pr-4">Foto</th>
                        @foreach($tableColumns as $column)
                            <th class="pb-4">{{ $column['label'] }}</th>
                        @endforeach
                        <th class="pb-4 text-center">Produk</th>
                        <th class="pb-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="border-t border-slate-100 transition hover:bg-slate-50">
                            <td class="py-3 pr-4">
                                @if($item->image_path || $item->foto_url)
                                    <img src="{{ $item->display_image }}" alt="" class="h-10 w-10 rounded-lg object-cover" loading="lazy">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                        <i data-lucide="store" class="h-5 w-5"></i>
                                    </div>
                                @endif
                            </td>
                            @foreach($tableColumns as $column)
                                <td class="py-3">
                                    <span class="font-medium text-slate-700">{{ data_get($item, $column['key']) }}</span>
                                </td>
                            @endforeach
                            <td class="py-3 text-center">
                                <span class="inline-flex min-w-[2rem] items-center justify-center rounded-full bg-cyan-50 px-2 py-0.5 text-xs font-bold text-cyan-700">
                                    {{ $item->produks_count ?? 0 }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="flex justify-end gap-2">
                                    @if($publicShowRoute && filled(data_get($item, 'slug')) && data_get($item, 'status') === 'published')
                                        <a href="{{ route($publicShowRoute, data_get($item, $publicShowRouteKey)) }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-600">
                                            Preview
                                        </a>
                                    @endif
                                    <a href="{{ route($routePrefix.'.edit', $item->id) }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600">Edit</a>
                                    <form method="POST" action="{{ route($routePrefix.'.destroy', $item->id) }}" onsubmit="return confirm('Hapus UMKM ini beserta semua produknya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-rose-500 transition hover:border-rose-200 hover:bg-rose-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($tableColumns) + 3 }}" class="py-8 text-center text-slate-400">Belum ada data UMKM.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $items->links() }}</div>
    </div>
@endsection
