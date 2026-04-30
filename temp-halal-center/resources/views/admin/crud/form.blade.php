@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">{{ $mode === 'create' ? 'Tambah Data' : 'Edit Data' }}</p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">{{ $pageTitle }}</h2>
        </div>
        <a href="{{ route($routePrefix.'.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">Kembali</a>
    </div>

    <form method="POST" action="{{ $mode === 'create' ? route($routePrefix.'.store') : route($routePrefix.'.update', $item->id) }}" enctype="multipart/form-data" class="admin-card grid gap-10 rounded-[1.75rem] p-8">
        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        @foreach($formFields as $field)
            @php
                $name = $field['name'];
                $type = $field['type'] ?? 'text';
                $value = old($name, data_get($item, $name));
            @endphp

            <div class="{{ $type === 'richtext' ? 'mb-16' : '' }}">
                @if($type !== 'checkbox')
                    <label class="mb-2.5 block text-sm font-bold text-slate-700">{{ $field['label'] }}</label>
                @endif

                @if($type === 'textarea')
                    <textarea name="{{ $name }}" rows="4" @if(isset($field['id'])) id="{{ $field['id'] }}" @endif @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif class="admin-input">{{ is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value }}</textarea>
                @elseif($type === 'richtext')
                    <input type="hidden" id="input-{{ $name }}" name="{{ $name }}" value="{{ is_array($value) ? json_encode($value) : $value }}">
                    <div data-richtext data-input="input-{{ $name }}" class="admin-editor mb-12"></div>
                @elseif($type === 'map-picker')
                    <div
                        data-map-picker
                        data-latitude-target="{{ $field['latitude_target'] }}"
                        data-longitude-target="{{ $field['longitude_target'] }}"
                        data-address-target="{{ $field['address_target'] }}"
                        class="space-y-4 rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5"
                    >
                        <div class="grid gap-3 md:grid-cols-[1fr,auto,auto]">
                            <input type="text" data-map-search class="admin-input" placeholder="Cari alamat atau nama tempat...">
                            <button type="button" data-map-search-button class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700">Cari Lokasi</button>
                            <button type="button" data-map-fetch-address class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">Gunakan Alamat di Atas</button>
                            <button type="button" data-map-reverse-button class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700">Isi Alamat dari Titik</button>
                        </div>
                        <div data-map-canvas class="h-[360px] overflow-hidden rounded-[1.25rem] border border-slate-200"></div>
                        <p data-map-status class="text-xs font-medium text-slate-500">Klik peta untuk mengisi latitude dan longitude, lalu gunakan tombol alamat jika dibutuhkan.</p>
                    </div>
                @elseif($type === 'select')
                    <select name="{{ $name }}" @if(isset($field['id'])) id="{{ $field['id'] }}" @endif class="admin-input">
                        <option value="">Pilih salah satu</option>
                        @foreach(($field['options'] ?? []) as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                        @endforeach
                    </select>
                @elseif($type === 'checkbox')
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="{{ $name }}" value="1" @checked((bool) $value) class="h-5 w-5 rounded border-white/20 bg-transparent text-emerald-400">
                        <span class="text-sm font-medium text-slate-700">{{ $field['label'] }}</span>
                    </label>
                @elseif(in_array($type, ['image', 'file'], true))
                    <input type="file" name="{{ $name }}" class="admin-input file:mr-4 file:rounded-full file:border-0 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-950">
                    @if($value)
                        <p class="mt-2 text-xs text-slate-400">File saat ini: {{ $value }}</p>
                    @endif
                @else
                    <input
                        type="{{ $type }}"
                        name="{{ $name }}"
                        @if(isset($field['id'])) id="{{ $field['id'] }}" @endif
                        value="{{ $type === 'datetime-local' && $value ? \Illuminate\Support\Carbon::parse($value)->format('Y-m-d\TH:i') : (is_array($value) ? json_encode($value) : $value) }}"
                        @if(isset($field['step'])) step="{{ $field['step'] }}" @endif
                        @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
                        class="admin-input"
                    >
                @endif

                @error($name)
                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <div class="pt-4">
            <div class="flex flex-wrap gap-3">
                @if(
                    $mode === 'edit'
                    && $publicShowRoute
                    && ($publicShowRouteKey === null || filled(data_get($item, $publicShowRouteKey)))
                    && (! filled(data_get($item, 'status')) || data_get($item, 'status') === 'published')
                )
                    <a
                        href="{{ $publicShowRouteKey ? route($publicShowRoute, data_get($item, $publicShowRouteKey)) : route($publicShowRoute) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50"
                    >
                        Preview Publik
                    </a>
                @endif
                <button type="submit" class="rounded-xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition hover:bg-emerald-400">
                    {{ $mode === 'create' ? 'Simpan Data' : 'Perbarui Data' }}
                </button>
            </div>
        </div>
    </form>
@endsection

@include('components.indonesia-address-script')
