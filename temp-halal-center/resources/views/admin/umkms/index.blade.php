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
            <button type="button" onclick="openIframeModal('{{ route($routePrefix.'.create') }}', 'Tambah UMKM Baru')" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition hover:bg-emerald-400">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah UMKM
            </button>
        </div>
    </div>

    {{-- Import / Export Bar --}}
    <div class="admin-card mb-6 rounded-[1.75rem] p-6">
        <div class="mb-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
            Import bisa pakai `1 file XLSX` dengan 3 sheet (`UMKM Ringkas`, `UMKM Detail`, `Produk UMKM`) atau `3 file CSV` terpisah.
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
                        Import bisa pakai `1 file XLSX` dengan 3 sheet (`UMKM Ringkas`, `UMKM Detail`, `Produk UMKM`) atau `3 file CSV` terpisah.
                    </div>

                    <form method="POST" action="{{ route('admin.umkms.import') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 md:grid-cols-2" data-umkm-import-form>
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

                        <div class="md:col-span-2">
                            <div class="hidden" data-umkm-import-progress>
                                <div class="mb-2 flex items-center justify-between text-xs font-bold text-slate-500">
                                    <span data-umkm-import-label>Uploading...</span>
                                    <span data-umkm-import-percent>0%</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div data-umkm-import-bar class="h-2 w-0 rounded-full bg-gradient-to-r from-cyan-500 to-emerald-500 transition-[width]"></div>
                                </div>
                                <p class="mt-2 text-xs font-semibold text-slate-500">Catatan: setelah upload selesai, server masih memproses data (bisa beberapa menit untuk file besar).</p>
                            </div>
                            <div class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700" data-umkm-import-success></div>
                            <div class="hidden rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600" data-umkm-import-error></div>
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

    {{-- Toolbar --}}
    <div class="admin-card rounded-[1.75rem] p-6">
        <div class="mb-5 flex flex-col gap-3 lg:flex-row">
            <form method="GET" id="searchForm" class="flex-1 flex gap-3">
                <div class="shrink-0">
                    <select name="per_page" class="h-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm outline-none transition focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10" onchange="this.form.submit()">
                        <option value="15" @selected(request('per_page') == 15)>15 Baris</option>
                        <option value="25" @selected(request('per_page') == 25)>25 Baris</option>
                        <option value="50" @selected(request('per_page') == 50)>50 Baris</option>
                        <option value="100" @selected(request('per_page') == 100)>100 Baris</option>
                    </select>
                </div>
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama UMKM, pemilik, kategori, kab/kota..." class="w-full rounded-2xl border border-slate-200 bg-white py-4 pl-12 pr-24 text-sm font-semibold text-slate-900 shadow-sm outline-none transition focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl bg-emerald-500 px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-400">Cari</button>
                </div>
            </form>

            <div class="shrink-0">
                <button type="button" onclick="document.getElementById('filterAccordion').classList.toggle('hidden')" class="inline-flex h-full min-h-[56px] w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 lg:w-auto">
                    <i data-lucide="sliders-horizontal" class="h-5 w-5"></i>
                    <span>Filter & Sort</span>
                </button>
            </div>
        </div>

        <div id="filterAccordion" class="mb-6 hidden rounded-2xl border border-slate-200 bg-slate-50 p-5 @if(request()->hasAny(['status', 'approval', 'kab_kota', 'sort', 'dir'])) !block @endif">
            <form method="GET" class="flex flex-wrap items-end gap-3 lg:gap-4">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <div class="flex-1 min-w-[140px] max-w-full">
                    <label class="mb-1 block text-xs font-bold text-slate-500">Status</label>
                    <select name="status" class="admin-input !py-2.5 text-sm w-full">
                        <option value="">Semua</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[140px] max-w-full">
                    <label class="mb-1 block text-xs font-bold text-slate-500">Approval</label>
                    <select name="approval" class="admin-input !py-2.5 text-sm w-full">
                        <option value="">Semua</option>
                        @foreach(($approvals ?? []) as $value)
                            <option value="{{ $value }}" @selected(request('approval') === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[140px] max-w-full">
                    <label class="mb-1 block text-xs font-bold text-slate-500">Kab/Kota</label>
                    <select name="kab_kota" class="admin-input !py-2.5 text-sm w-full">
                        <option value="">Semua</option>
                        @foreach(($kabKotas ?? []) as $value)
                            <option value="{{ $value }}" @selected(request('kab_kota') === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[140px] max-w-full">
                    <label class="mb-1 block text-xs font-bold text-slate-500">Sort</label>
                    <select name="sort" class="admin-input !py-2.5 text-sm w-full">
                        <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                        <option value="source_id" @selected(request('sort') === 'source_id')>Source ID</option>
                        <option value="nama_umkm" @selected(request('sort') === 'nama_umkm')>Nama UMKM</option>
                        <option value="kab_kota" @selected(request('sort') === 'kab_kota')>Kab/Kota</option>
                        <option value="kategori" @selected(request('sort') === 'kategori')>Kategori</option>
                        <option value="approval" @selected(request('sort') === 'approval')>Approval</option>
                    </select>
                </div>
                <div class="flex w-full flex-wrap gap-2 sm:w-auto">
                    <select name="dir" class="admin-input !py-2.5 text-sm w-full sm:w-[100px]">
                        <option value="desc" @selected(request('dir', 'desc') === 'desc')>Desc</option>
                        <option value="asc" @selected(request('dir') === 'asc')>Asc</option>
                    </select>
                    <button type="submit" class="inline-flex flex-1 sm:flex-none items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-400">
                        Terapkan
                    </button>
                    <a href="{{ route('admin.umkms.index') }}" class="inline-flex flex-1 sm:flex-none items-center justify-center rounded-xl bg-rose-50 px-5 py-2.5 text-sm font-bold text-rose-600 transition hover:bg-rose-100 hover:text-rose-700">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto pb-4">
            <table class="admin-table min-w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="pb-4 pr-4">ID</th>
                        <th class="pb-4 pr-4">Foto</th>
                        <th class="pb-4 pr-4">Nama UMKM</th>
                        <th class="pb-4 pr-4">Nama Pemilik</th>
                        <th class="pb-4 pr-4">Kab/Kota</th>
                        <th class="pb-4 pr-4">Kategori</th>
                        <th class="pb-4 px-2 text-center">Total Product</th>
                        <th class="pb-4 px-2 text-center">Status</th>
                        <th class="pb-4 pl-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $umkmPayload = [
                                'id' => $item->id,
                                'source_id' => $item->source_id,
                                'nama_umkm' => $item->nama_umkm,
                                'nama_pemilik' => $item->nama_pemilik,
                                'kab_kota' => $item->kab_kota,
                                'kategori' => $item->kategori,
                                'approval' => $item->approval,
                                'status' => $item->status,
                                'lembaga' => $item->lembaga,
                                'alamat' => $item->alamat,
                                'nomor_wa' => $item->nomor_wa,
                                'link_pembelian' => $item->link_pembelian,
                                'latitude' => $item->latitude,
                                'longitude' => $item->longitude,
                            ];
                        @endphp
                        <tr class="border-t border-slate-100 transition hover:bg-slate-50" data-umkm="{{ json_encode($umkmPayload) }}">
                            <td class="py-4 pr-4 text-xs font-bold text-slate-500">
                                {{ $item->source_id ?? $item->id }}
                            </td>
                            <td class="py-4 pr-4">
                                @if($item->image_path || $item->foto_url)
                                    <img src="{{ $item->display_image }}" alt="" class="h-10 w-10 rounded-lg object-cover" loading="lazy">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                        <i data-lucide="store" class="h-5 w-5"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 font-medium text-slate-800">{{ $item->nama_umkm ?: '-' }}</td>
                            <td class="py-4 text-slate-600">{{ $item->nama_pemilik ?: '-' }}</td>
                            <td class="py-4 text-slate-600">{{ $item->kab_kota ?: '-' }}</td>
                            <td class="py-4 text-slate-600">
                                <span class="rounded bg-slate-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-600">{{ $item->kategori ?: '-' }}</span>
                            </td>
                            <td class="py-4 text-center">
                                <span class="inline-flex min-w-[2rem] items-center justify-center rounded-full bg-cyan-50 px-2 py-0.5 text-xs font-bold text-cyan-700">
                                    {{ $item->produks_count ?? 0 }}
                                </span>
                            </td>
                            <td class="py-4 text-center">
                                @php
                                    $rawApproval = strtolower(trim($item->approval ?? ''));
                                @endphp
                                @if($rawApproval === 'disetujui' || $rawApproval === 'approved')
                                    <div class="inline-flex items-center justify-center rounded-full bg-emerald-50 px-2 py-1 text-emerald-600" title="{{ $item->approval }}">
                                        <i data-lucide="check-circle-2" class="h-4 w-4"></i>
                                    </div>
                                @elseif($rawApproval === 'ditolak' || $rawApproval === 'rejected' || $rawApproval === 'di kembalikan')
                                    <div class="inline-flex items-center justify-center rounded-full bg-rose-50 px-2 py-1 text-rose-600" title="{{ $item->approval }}">
                                        <i data-lucide="x-circle" class="h-4 w-4"></i>
                                    </div>
                                @else
                                    <div class="inline-flex items-center justify-center rounded-full bg-amber-50 px-2 py-1 text-amber-600" title="{{ $item->approval ?: 'Waiting' }}">
                                        <i data-lucide="clock" class="h-4 w-4"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex justify-end gap-1.5">
                                    <button type="button" data-umkm-view-detail class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-600" title="Detail UMKM">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </button>
                                    <button type="button" onclick="openIframeModal('{{ route($routePrefix.'.edit', $item->id) }}', 'Edit UMKM')" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600" title="Edit UMKM">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </button>
                                    <button type="button" onclick="confirmDeleteAlert({{ $item->id }})" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-500 transition hover:bg-rose-600 hover:text-white" title="Hapus UMKM">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route($routePrefix.'.destroy', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="py-8 text-center text-slate-400">Belum ada data UMKM.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $items->onEachSide(1)->links('components.admin-pagination') }}</div>
    </div>

    <div id="umkmQuickEditModal" class="fixed inset-0 z-[110] hidden">
        <div id="umkmQuickEditBackdrop" class="absolute inset-0 bg-slate-900/40 opacity-0 backdrop-blur-sm transition-opacity" onclick="closeModal('umkmQuickEditModal')"></div>
        <div class="pointer-events-none absolute inset-0 flex items-start justify-center overflow-y-auto px-4 pb-10 pt-16">
            <div id="umkmQuickEditContent" class="pointer-events-auto w-full max-w-3xl scale-95 rounded-3xl bg-white opacity-0 shadow-2xl transition-all">
                <div class="flex items-center justify-between rounded-t-3xl border-b border-slate-100 bg-slate-50 p-6">
                    <div>
                        <h3 class="font-heading text-xl font-extrabold tracking-tight text-slate-900">Detail UMKM</h3>
                        <p id="umkmQuickEditSubtitle" class="text-xs font-medium text-slate-500">Quick update (hanya field yang berubah yang dikirim)</p>
                    </div>
                    <button type="button" onclick="closeModal('umkmQuickEditModal')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <div class="p-6 md:p-8">
                    <form id="umkmQuickEditForm" class="grid grid-cols-1 gap-4 md:grid-cols-2" data-umkm-quick-form>
                        @csrf
                        <input type="hidden" name="id" id="umkmQuickEditId">

                        <div class="md:col-span-2">
                            <label class="mb-1 block text-xs font-bold text-slate-500">Nama UMKM</label>
                            <input type="text" name="nama_umkm" id="umkmQuickEditNama" class="admin-input text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Pemilik</label>
                            <input type="text" name="nama_pemilik" id="umkmQuickEditPemilik" class="admin-input text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Kab/Kota</label>
                            <input type="text" name="kab_kota" id="umkmQuickEditKabKota" class="admin-input text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Kategori</label>
                            <input type="text" name="kategori" id="umkmQuickEditKategori" class="admin-input text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Approval</label>
                            <input type="text" name="approval" id="umkmQuickEditApproval" class="admin-input text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-xs font-bold text-slate-500">Alamat</label>
                            <textarea name="alamat" id="umkmQuickEditAlamat" rows="3" class="admin-input text-sm"></textarea>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">No. WA</label>
                            <input type="text" name="nomor_wa" id="umkmQuickEditWa" class="admin-input text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Link Pembelian</label>
                            <input type="url" name="link_pembelian" id="umkmQuickEditLink" class="admin-input text-sm">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Status</label>
                            <select name="status" id="umkmQuickEditStatus" class="admin-input !py-2.5 text-sm">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="button" id="umkmQuickEditToggleDiff" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                Lihat Perubahan
                            </button>
                        </div>

                        <div id="umkmQuickEditDiff" class="hidden md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600"></div>
                        <div id="umkmQuickEditError" class="hidden md:col-span-2 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600"></div>
                        <div class="md:col-span-2 pt-1">
                            <button type="submit" id="umkmQuickEditSubmit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition hover:bg-emerald-400">
                                <span id="umkmQuickEditSubmitText">Simpan Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div id="umkmDetailModal" class="fixed inset-0 z-[110] hidden">
        <div id="umkmDetailBackdrop" class="absolute inset-0 bg-slate-900/40 opacity-0 backdrop-blur-sm transition-opacity" onclick="closeModal('umkmDetailModal')"></div>
        <div class="pointer-events-none absolute inset-0 flex items-start justify-center overflow-y-auto px-4 pb-10 pt-16">
            <div id="umkmDetailContent" class="pointer-events-auto w-full max-w-3xl scale-95 rounded-3xl bg-white opacity-0 shadow-2xl transition-all">
                <div class="flex items-center justify-between rounded-t-3xl border-b border-slate-100 bg-slate-50 p-6">
                    <div>
                        <h3 class="font-heading text-xl font-extrabold tracking-tight text-slate-900">Detail UMKM</h3>
                        <p class="text-xs font-medium text-slate-500">Melihat data secara detail</p>
                    </div>
                    <button type="button" onclick="closeModal('umkmDetailModal')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-xs font-bold text-slate-500">Nama UMKM</label>
                            <input type="text" id="umkmDetailNama" class="admin-input cursor-not-allowed bg-slate-50 text-sm font-semibold text-slate-700" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Pemilik</label>
                            <input type="text" id="umkmDetailPemilik" class="admin-input cursor-not-allowed bg-slate-50 text-sm font-semibold text-slate-700" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Kab/Kota</label>
                            <input type="text" id="umkmDetailKabKota" class="admin-input cursor-not-allowed bg-slate-50 text-sm font-semibold text-slate-700" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Kategori</label>
                            <input type="text" id="umkmDetailKategori" class="admin-input cursor-not-allowed bg-slate-50 text-sm font-semibold text-slate-700" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Approval</label>
                            <input type="text" id="umkmDetailApproval" class="admin-input cursor-not-allowed bg-slate-50 text-sm font-semibold text-slate-700" readonly>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-xs font-bold text-slate-500">Alamat</label>
                            <textarea id="umkmDetailAlamat" rows="3" class="admin-input cursor-not-allowed bg-slate-50 text-sm font-semibold text-slate-700" readonly></textarea>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">No. WA</label>
                            <input type="text" id="umkmDetailWa" class="admin-input cursor-not-allowed bg-slate-50 text-sm font-semibold text-slate-700" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold text-slate-500">Link Pembelian</label>
                            <input type="url" id="umkmDetailLink" class="admin-input cursor-not-allowed bg-slate-50 text-sm font-semibold text-blue-600" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="umkmDeleteModal" class="fixed inset-0 z-[120] hidden">
        <div id="umkmDeleteBackdrop" class="absolute inset-0 bg-slate-900/60 opacity-0 backdrop-blur-sm transition-opacity" onclick="closeModal('umkmDeleteModal')"></div>
        <div class="pointer-events-none absolute inset-0 flex items-center justify-center p-4">
            <div id="umkmDeleteContent" class="pointer-events-auto w-full max-w-md scale-95 rounded-3xl bg-white opacity-0 shadow-2xl transition-all">
                <div class="p-6 text-center md:py-8">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                        <i data-lucide="alert-triangle" class="h-8 w-8"></i>
                    </div>
                    <h3 class="mb-2 font-heading text-xl font-extrabold text-slate-900">Hapus UMKM?</h3>
                    <p class="mb-6 text-sm font-medium text-slate-500">Tindakan ini tidak dapat dibatalkan. Semua data terkait UMKM beserta produk-produknya akan dihapus permanen.</p>
                    <div class="flex flex-col-reverse gap-3 sm:flex-row">
                        <button type="button" onclick="closeModal('umkmDeleteModal')" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Batalkan</button>
                        <button type="button" id="confirmDeleteBtn" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-rose-600 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-rose-500/30 transition hover:bg-rose-500">Ya, Hapus Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Iframe Modal for Create & Edit --}}
    <div id="umkmIframeModal" class="fixed inset-0 z-[110] hidden">
        <div id="umkmIframeBackdrop" class="absolute inset-0 bg-slate-900/60 opacity-0 backdrop-blur-sm transition-opacity" onclick="closeModal('umkmIframeModal')"></div>
        <div class="pointer-events-none absolute inset-0 flex items-start justify-center overflow-hidden px-4 pb-10 pt-16">
            <div id="umkmIframeContent" class="pointer-events-auto flex h-full w-full max-w-5xl scale-95 flex-col rounded-3xl bg-white opacity-0 shadow-2xl transition-all">
                <div class="flex shrink-0 items-center justify-between rounded-t-3xl border-b border-slate-100 bg-slate-50 p-5">
                    <div>
                        <h3 id="umkmIframeTitle" class="font-heading text-lg font-extrabold tracking-tight text-slate-900">Form UMKM</h3>
                    </div>
                    <button type="button" onclick="closeModal('umkmIframeModal')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <div class="relative flex-1 overflow-hidden bg-slate-50/50">
                    <div id="umkmIframeLoader" class="absolute inset-0 flex items-center justify-center bg-slate-50/50 backdrop-blur-sm">
                        <i data-lucide="loader-2" class="h-8 w-8 animate-spin text-emerald-500"></i>
                    </div>
                    <iframe id="umkmIframe" src="" class="h-full w-full border-none opacity-0 transition-opacity duration-300"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openIframeModal(url, title) {
            document.getElementById('umkmIframeTitle').innerText = title;
            const iframe = document.getElementById('umkmIframe');
            const loader = document.getElementById('umkmIframeLoader');
            
            iframe.style.opacity = '0';
            loader.style.display = 'flex';
            
            iframe.onload = function() {
                loader.style.display = 'none';
                iframe.style.opacity = '1';
                
                try {
                    // Check if iframe redirected back to index (success save)
                    const path = iframe.contentWindow.location.pathname;
                    if (path === '{{ route('admin.umkms.index', [], false) }}') {
                        window.location.reload();
                    }
                } catch(e) {
                    // Cross-origin issues shouldn't happen here, but if so, ignore
                }
            };
            
            // Append is_iframe to hide layout inside iframe
            const finalUrl = url + (url.includes('?') ? '&' : '?') + 'is_iframe=1';
            iframe.src = finalUrl;
            
            openModal('umkmIframeModal');
        }
        // Auto-search Debounce
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            const searchInput = searchForm.querySelector('input[name="search"]');
            let typingTimer;
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => {
                        searchForm.submit();
                    }, 500);
                });
            }
        }

        // Setup detail & delete buttons using event delegation
        document.addEventListener('click', (e) => {
            const detailBtn = e.target.closest('[data-umkm-view-detail]');
            if (detailBtn) {
                const row = detailBtn.closest('tr');
                if (!row) return;
                
                let data = {};
                try {
                    data = JSON.parse(row.dataset.umkm || '{}');
                } catch (err) {
                    console.error("Gagal parse data UMKM", err);
                }
                
                document.getElementById('umkmDetailNama').value = data.nama_umkm || '-';
                document.getElementById('umkmDetailPemilik').value = data.nama_pemilik || '-';
                document.getElementById('umkmDetailKabKota').value = data.kab_kota || '-';
                document.getElementById('umkmDetailKategori').value = data.kategori || '-';
                document.getElementById('umkmDetailApproval').value = data.approval || 'Menunggu';
                document.getElementById('umkmDetailAlamat').value = data.alamat || '-';
                document.getElementById('umkmDetailWa').value = data.nomor_wa || '-';
                document.getElementById('umkmDetailLink').value = data.link_pembelian || '-';
                
                if (typeof openModal === 'function') openModal('umkmDetailModal');
                else if (window.openModal) window.openModal('umkmDetailModal');
            }

            const deleteConfirmBtn = e.target.closest('#confirmDeleteBtn');
            if (deleteConfirmBtn && currentDeleteId) {
                const form = document.getElementById('delete-form-' + currentDeleteId);
                if (form) form.submit();
            }
        });

        let currentDeleteId = null;
        function confirmDeleteAlert(id) {
            currentDeleteId = id;
            if (typeof openModal === 'function') openModal('umkmDeleteModal');
            else if (window.openModal) window.openModal('umkmDeleteModal');
        }
    </script>
@endsection
