@extends('layouts.admin')

@section('content')
    @php $isAdminDirektorat = $isAdminDirektorat ?? false; @endphp
    <div class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">{{ $mode === 'create' ? 'Tambah Data' : 'Edit Data' }}</p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">{{ $pageTitle }}</h2>
        </div>
        <div class="flex items-center gap-3">
            @if($isAdminDirektorat && $mode === 'edit')
                {{-- Toggle edit mode button for AdminDirektorat --}}
                <button
                    type="button"
                    id="edit-mode-toggle"
                    class="inline-flex items-center gap-2.5 rounded-xl border px-5 py-3 text-sm font-bold shadow-sm transition"
                    data-edit-active="0"
                    onclick="toggleEditMode()"
                >
                    <span id="edit-toggle-icon" class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-current transition">
                        <svg id="lock-icon" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <svg id="unlock-icon" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>
                    </span>
                    <span id="edit-toggle-label">Aktifkan Edit</span>
                </button>
            @endif

            @if(!($isAdminDirektorat && $routePrefix === 'admin.sector-items'))
                <a href="{{ route($routePrefix.'.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">Kembali</a>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ $mode === 'create' ? route($routePrefix.'.store') : route($routePrefix.'.update', $item->id) }}" enctype="multipart/form-data" class="admin-card grid gap-10 rounded-[1.75rem] p-8" id="crud-form">
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

            @if($type === 'hidden')
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @continue
            @endif

            <div class="{{ $type === 'richtext' ? 'mb-16' : '' }}">
                @if($type !== 'checkbox')
                    <label class="mb-2.5 block text-sm font-bold text-slate-700">{{ $field['label'] ?? '' }}</label>
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
                    <select name="{{ $name }}" @if(isset($field['id'])) id="{{ $field['id'] }}" @endif @if(isset($field['readonly']) && $field['readonly']) disabled @endif @if(isset($field['disabled']) && $field['disabled']) disabled @endif class="admin-input {{ isset($field['readonly']) && $field['readonly'] ? 'bg-slate-50 opacity-70 cursor-not-allowed' : '' }}">
                        <option value="">Pilih salah satu</option>
                        @foreach(($field['options'] ?? []) as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                        @endforeach
                    </select>
                    @if(isset($field['readonly']) && $field['readonly'])
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    @endif
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
                        @if(isset($field['readonly']) && $field['readonly']) readonly @endif
                        @if(isset($field['disabled']) && $field['disabled']) disabled @endif
                        class="admin-input {{ isset($field['readonly']) && $field['readonly'] ? 'bg-slate-50' : '' }}"
                    >
                @endif

                @error($name)
                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <div class="pt-4" id="form-actions">
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
                <button type="submit" id="submit-btn" class="rounded-xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition hover:bg-emerald-400">
                    {{ $mode === 'create' ? 'Simpan Data' : 'Perbarui Data' }}
                </button>
            </div>
        </div>
    </form>

    @if($isAdminDirektorat)
    {{-- Locked overlay hint --}}
    <div id="locked-notice" class="mt-4 flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        Form terkunci. Klik <strong class="mx-1">Aktifkan Edit</strong> di atas untuk mulai mengedit.
    </div>
    @endif
@endsection

@include('components.indonesia-address-script')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Role select directorate toggle (for user form) ---
        const roleSelect = document.getElementById('role-select');
        const directorateContainer = document.getElementById('directorate-container');

        if (roleSelect && directorateContainer) {
            const parentDiv = directorateContainer.closest('div');

            function toggleDirectorate() {
                if (roleSelect.value === 'AdminDirektorat') {
                    parentDiv.style.display = 'block';
                } else {
                    parentDiv.style.display = 'none';
                }
            }

            roleSelect.addEventListener('change', toggleDirectorate);
            toggleDirectorate();
        }

        // --- AdminDirektorat edit mode toggle ---
        @if($isAdminDirektorat ?? false)
        const form = document.getElementById('crud-form');
        const submitBtn = document.getElementById('submit-btn');
        const formActions = document.getElementById('form-actions');
        const lockedNotice = document.getElementById('locked-notice');
        const toggleBtn = document.getElementById('edit-mode-toggle');
        const toggleLabel = document.getElementById('edit-toggle-label');
        const lockIcon = document.getElementById('lock-icon');
        const unlockIcon = document.getElementById('unlock-icon');

        const FIELD_SELECTORS = 'input:not([type=hidden]):not([name=_token]):not([name=_method]), textarea, select';

        function setFormLocked(locked) {
            if (!form) return;
            form.querySelectorAll(FIELD_SELECTORS).forEach(function(el) {
                el.disabled = locked;
                if (locked) {
                    el.classList.add('opacity-60', 'cursor-not-allowed');
                } else {
                    el.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            });

            // Richtext editor (Quill / trix / etc.) — disable pointer events on wrapper
            form.querySelectorAll('[data-richtext]').forEach(function(el) {
                el.style.pointerEvents = locked ? 'none' : '';
                el.style.opacity = locked ? '0.6' : '';
            });

            if (submitBtn) submitBtn.style.display = locked ? 'none' : '';
            if (formActions) {
                // Keep form-actions visible for preview link but hide submit when locked
            }
            if (lockedNotice) lockedNotice.style.display = locked ? 'flex' : 'none';

            // Toggle button style
            if (toggleBtn) {
                if (locked) {
                    toggleBtn.className = 'inline-flex items-center gap-2.5 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 shadow-sm transition hover:bg-slate-50';
                    if (lockIcon) lockIcon.classList.remove('hidden');
                    if (unlockIcon) unlockIcon.classList.add('hidden');
                    if (toggleLabel) toggleLabel.textContent = 'Aktifkan Edit';
                } else {
                    toggleBtn.className = 'inline-flex items-center gap-2.5 rounded-xl border border-emerald-300 bg-emerald-50 px-5 py-3 text-sm font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-100';
                    if (lockIcon) lockIcon.classList.add('hidden');
                    if (unlockIcon) unlockIcon.classList.remove('hidden');
                    if (toggleLabel) toggleLabel.textContent = 'Mode Edit Aktif';
                }
                toggleBtn.dataset.editActive = locked ? '0' : '1';
            }
        }

        // Initialize locked on page load only for edit mode
        setFormLocked({{ $mode === 'edit' ? 'true' : 'false' }});
 
        window.toggleEditMode = function() {
            const currentlyLocked = toggleBtn && toggleBtn.dataset.editActive === '0';
            setFormLocked(!currentlyLocked);
        };
        @else
        window.toggleEditMode = function() {};
        @endif
    });
</script>
@endpush
