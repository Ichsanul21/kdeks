@extends('layouts.app')

@section('title', $location->name)

@section('content')
    <section class="mx-auto max-w-5xl px-6 pb-20 pt-28">
        <a href="{{ route('home') }}#webgis" class="text-sm font-bold text-emerald-600">← Kembali ke peta</a>
        <div class="mt-8 grid gap-8 lg:grid-cols-[1.2fr,0.8fr]">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">{{ $location->category }}</p>
                <h1 class="mt-3 font-heading text-4xl font-extrabold text-slate-900">{{ $location->name }}</h1>
                <p class="mt-2 text-sm font-medium text-slate-500">{{ $location->city_name ?: $location->region?->name }} · {{ $location->lphPartner?->name ?: 'Mitra belum tersedia' }}</p>
                <p class="mt-6 text-sm leading-7 text-slate-500">{{ $location->description ?: 'Detail lokasi usaha halal.' }}</p>
            </div>
            <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="font-heading text-xl font-extrabold text-slate-900">Detail Lokasi</h2>
                <dl class="mt-6 space-y-4 text-sm">
                    <div><dt class="font-bold text-slate-700">Alamat</dt><dd class="text-slate-500">{{ $location->address ?: '-' }}</dd></div>
                    <div><dt class="font-bold text-slate-700">Produk</dt><dd class="text-slate-500">{{ $location->product_name ?: '-' }}</dd></div>
                    <div><dt class="font-bold text-slate-700">Brand</dt><dd class="text-slate-500">{{ $location->brand_name ?: '-' }}</dd></div>
                    <div><dt class="font-bold text-slate-700">Nomor Sertifikat</dt><dd class="text-slate-500">{{ $location->certificate_number ?: '-' }}</dd></div>
                </dl>
            </div>
        </div>
    </section>
@endsection
