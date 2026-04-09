@extends('layouts.app')

@section('title', 'Direktori Produk Halal')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pb-20 pt-28">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">Direktori</p>
                <h1 class="mt-2 font-heading text-4xl font-extrabold text-slate-900">Produk Halal</h1>
            </div>
            <form method="GET" class="w-full max-w-md">
                <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Cari produk, brand, kategori..." class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none">
            </form>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($products as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-emerald-600">{{ $product->category }}</p>
                    <h2 class="mt-3 text-lg font-extrabold text-slate-900">{{ $product->name }}</h2>
                    <p class="mt-2 text-sm font-medium text-slate-500">{{ $product->brand_name }} · {{ $product->region?->name }}</p>
                    <p class="mt-4 text-sm leading-7 text-slate-500">{{ \Illuminate\Support\Str::limit($product->description, 120) }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $products->links() }}</div>
    </section>
@endsection
