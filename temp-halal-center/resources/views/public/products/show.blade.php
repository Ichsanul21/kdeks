@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <section class="mx-auto max-w-5xl px-6 pb-20 pt-28">
        <a href="{{ route('products.index') }}" class="text-sm font-bold text-emerald-600">← Kembali ke direktori</a>
        <div class="mt-8 grid gap-8 lg:grid-cols-[1.2fr,0.8fr]">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">{{ $product->category }}</p>
                <h1 class="mt-3 font-heading text-4xl font-extrabold text-slate-900">{{ $product->name }}</h1>
                <p class="mt-2 text-sm font-medium text-slate-500">{{ $product->brand_name }} · {{ $product->region?->name }}</p>
                <div class="mt-8 prose prose-slate max-w-none">
                    <p>{{ $product->description ?: 'Deskripsi produk belum tersedia.' }}</p>
                </div>
            </div>
            <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="font-heading text-xl font-extrabold text-slate-900">Informasi Sertifikasi</h2>
                <dl class="mt-6 space-y-4 text-sm">
                    <div><dt class="font-bold text-slate-700">Nomor Sertifikat</dt><dd class="text-slate-500">{{ $product->certificate_number ?: '-' }}</dd></div>
                    <div><dt class="font-bold text-slate-700">Terbit</dt><dd class="text-slate-500">{{ optional($product->certificate_issued_at)->translatedFormat('d F Y') ?: '-' }}</dd></div>
                    <div><dt class="font-bold text-slate-700">Berlaku Sampai</dt><dd class="text-slate-500">{{ optional($product->certificate_expires_at)->translatedFormat('d F Y') ?: '-' }}</dd></div>
                </dl>
            </div>
        </div>

        @if($relatedProducts->isNotEmpty())
            <div class="mt-14">
                <h2 class="font-heading text-2xl font-extrabold text-slate-900">Produk Terkait</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="rounded-2xl border border-slate-200 bg-white p-4 text-sm font-bold text-slate-700 transition hover:border-emerald-300 hover:text-emerald-600">
                            {{ $relatedProduct->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
