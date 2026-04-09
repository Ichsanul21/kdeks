@extends('layouts.app')

@section('title', 'Pustaka Dokumen')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pb-20 pt-28">
        <h1 class="font-heading text-4xl font-extrabold text-slate-900">Pustaka Dokumen</h1>
        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($resources as $resource)
                <a href="{{ route('resources.show', $resource->slug) }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-emerald-600">{{ strtoupper($resource->type) }}</p>
                    <h2 class="mt-3 text-lg font-extrabold text-slate-900">{{ $resource->title }}</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($resource->summary), 140) }}</p>
                </a>
            @endforeach
        </div>
        <div class="mt-10">{{ $resources->links() }}</div>
    </section>
@endsection
