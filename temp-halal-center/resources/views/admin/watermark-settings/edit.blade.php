@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">Developer Only</p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">{{ $pageTitle }}</h2>
            <p class="mt-2 text-sm font-medium text-slate-500">Kelola watermark tanpa mengubah pengaturan profil website.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.watermark-settings.update') }}" enctype="multipart/form-data" class="admin-card grid gap-6 rounded-[1.75rem] p-8">
        @csrf
        @method('PUT')

        <div>
            <label class="flex items-center gap-3">
                <input type="checkbox" name="watermark_enabled" value="1" @checked(old('watermark_enabled', (bool) $setting->watermark_enabled)) class="h-5 w-5 rounded border-white/20 bg-transparent text-emerald-400">
                <span class="text-sm font-medium text-slate-700">Aktifkan Watermark Global</span>
            </label>
            @error('watermark_enabled')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Teks Watermark</label>
            <input type="text" name="watermark_text" value="{{ old('watermark_text', $setting->watermark_text) }}" class="admin-input" placeholder="Contoh: UNPAID PREVIEW">
            @error('watermark_text')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Opacity Watermark</label>
            <input type="number" name="watermark_opacity" value="{{ old('watermark_opacity', $setting->watermark_opacity ?? 0.18) }}" min="0.05" max="1" step="0.05" class="admin-input">
            @error('watermark_opacity')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Gambar Watermark (PNG)</label>
            <input type="file" name="watermark_image_path" class="admin-input file:mr-4 file:rounded-full file:border-0 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-950">
            @if($setting->watermark_image_path)
                <p class="mt-2 text-xs text-slate-400">File saat ini: {{ $setting->watermark_image_path }}</p>
                <img src="{{ asset('storage/'.$setting->watermark_image_path) }}" alt="Preview watermark" class="mt-4 h-24 w-auto rounded-xl border border-slate-200 bg-white p-2">
            @endif
            @error('watermark_image_path')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-sm font-bold text-slate-800">Catatan</p>
            <p class="mt-2 text-sm text-slate-500">Nilai watermark yang sudah tersimpan tetap dipakai. Halaman ini hanya memisahkan pengelolaan watermark dari profil website utama.</p>
        </div>

        <div class="pt-4">
            <button type="submit" class="rounded-xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition hover:bg-emerald-400">
                Simpan Watermark
            </button>
        </div>
    </form>
@endsection
