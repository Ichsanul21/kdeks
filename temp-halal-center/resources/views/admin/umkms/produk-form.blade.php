@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">{{ $mode === 'create' ? 'Tambah Produk' : 'Edit Produk' }}</p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">{{ $pageTitle }}</h2>
        </div>
        <a href="{{ route('admin.umkms.edit', $umkm->id) }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">Kembali ke UMKM</a>
    </div>

    <form
        method="POST"
        action="{{ $mode === 'create' ? route('admin.umkms.produks.store', $umkm->id) : route('admin.umkms.produks.update', [$umkm->id, $produk->id]) }}"
        enctype="multipart/form-data"
        class="admin-card grid gap-6 rounded-[1.75rem] p-8"
    >
        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Nomor Produk</label>
            <input type="number" name="nomor" value="{{ old('nomor', $produk->nomor) }}" class="admin-input">
            @error('nomor') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Nama Produk</label>
            <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required class="admin-input">
            @error('nama_produk') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">URL Detail Produk</label>
            <input type="url" name="detail_url" value="{{ old('detail_url', $produk->detail_url) }}" class="admin-input">
            @error('detail_url') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">URL Edit Produk</label>
            <input type="url" name="edit_url" value="{{ old('edit_url', $produk->edit_url) }}" class="admin-input">
            @error('edit_url') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Harga</label>
            <input type="text" name="harga" value="{{ old('harga', $produk->harga) }}" class="admin-input" placeholder="Contoh: Rp 25.000">
            @error('harga') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">LPH / LP3H</label>
            <input type="text" name="lph_lp3h" value="{{ old('lph_lp3h', $produk->lph_lp3h) }}" class="admin-input">
            @error('lph_lp3h') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Akta Halal</label>
            <input type="text" name="akta_halal" value="{{ old('akta_halal', $produk->akta_halal) }}" class="admin-input">
            @error('akta_halal') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Tahun Terbit</label>
            <input type="text" name="tahun_terbit" value="{{ old('tahun_terbit', $produk->tahun_terbit) }}" class="admin-input" placeholder="Contoh: 2025">
            @error('tahun_terbit') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="admin-input">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            @error('deskripsi') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">URL Foto Eksternal</label>
            <input type="url" name="foto_url" value="{{ old('foto_url', $produk->foto_url) }}" class="admin-input">
            @error('foto_url') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Upload Gambar</label>
            <input type="file" name="image_path" class="admin-input file:mr-4 file:rounded-full file:border-0 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-950">
            @if($produk->display_image)
                <div class="mt-3">
                    <img src="{{ $produk->display_image }}" alt="" class="h-20 w-20 rounded-xl object-cover border border-slate-200">
                </div>
            @endif
            @error('image_path') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="rounded-xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition hover:bg-emerald-400">
                {{ $mode === 'create' ? 'Simpan Produk' : 'Perbarui Produk' }}
            </button>
        </div>
    </form>
@endsection
