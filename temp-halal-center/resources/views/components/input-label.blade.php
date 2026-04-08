@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-2 block text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-500']) }}>
    {{ $value ?? $slot }}
</label>
