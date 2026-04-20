@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400">Content Management</p>
            <h2 class="mt-2 font-heading text-3xl font-extrabold text-slate-900 md:text-4xl">{{ $pageTitle }}</h2>
        </div>
        <div class="flex flex-wrap gap-3">
            @if($publicIndexRoute)
                <a href="{{ route($publicIndexRoute) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <i data-lucide="external-link" class="h-4 w-4"></i>
                    Lihat Publik
                </a>
            @endif
            <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-emerald-500/30 transition hover:bg-emerald-400">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah Data
            </a>
        </div>
    </div>

    <div class="admin-card rounded-[1.75rem] p-6">
        <form method="GET" class="mb-6">
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 focus-within:border-emerald-500/50 focus-within:ring-2 focus-within:ring-emerald-500/20">
                <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data..." class="w-full border-none bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400">
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="admin-table min-w-full text-left text-sm">
                <thead>
                    <tr>
                        @foreach($tableColumns as $column)
                            <th class="pb-4">{{ $column['label'] }}</th>
                        @endforeach
                        <th class="pb-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="border-t border-slate-100 transition hover:bg-slate-50">
                            @foreach($tableColumns as $column)
                                <td class="py-4">
                                    @php
                                        $val = data_get($item, $column['key']);
                                        $isIzinStatus = ($column['key'] === 'izin_status');
                                        if (isset($column['options']) && isset($column['options'][$val])) {
                                            $val = $column['options'][$val];
                                        }
                                    @endphp
                                    @if($isIzinStatus && filled($val))
                                        <span class="inline-flex items-center rounded-full @if(data_get($item, 'izin_status') === 'disetujui') bg-emerald-50 text-emerald-600 @else bg-rose-50 text-rose-600 @endif px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider">
                                            {{ $val }}
                                        </span>
                                    @else
                                        <span class="font-medium text-slate-700">{{ $val }}</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($routePrefix === 'admin.sehati-registrations' && $item->status === 'diproses')
                                        <form method="POST" action="{{ route('admin.sehati-registrations.approve', $item->id) }}" style="display: contents;">
                                            @csrf
                                            <input type="hidden" name="decision" value="yes">
                                            <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white transition hover:bg-emerald-600" title="Setujui">
                                                <i data-lucide="check" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.sehati-registrations.approve', $item->id) }}" style="display: contents;">
                                            @csrf
                                            <input type="hidden" name="decision" value="no">
                                            <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-rose-500 text-white transition hover:bg-rose-600" title="Tolak">
                                                <i data-lucide="x" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(
                                        $publicShowRoute
                                        && ($publicShowRouteKey === null || filled(data_get($item, $publicShowRouteKey)))
                                        && (! filled(data_get($item, 'status')) || data_get($item, 'status') === 'published')
                                    )
                                        <a
                                            href="{{ $publicShowRouteKey ? route($publicShowRoute, data_get($item, $publicShowRouteKey)) : route($publicShowRoute) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-600"
                                            title="Pratinjau"
                                        >
                                            <i data-lucide="external-link" class="h-4 w-4"></i>
                                        </a>
                                    @endif

                                    @if($routePrefix === 'admin.consultation-requests' && filled($item->phone))
                                        @php
                                            $phone = preg_replace('/[^0-9]/', '', $item->phone);
                                            if (str_starts_with($phone, '0')) {
                                                $phone = '62' . substr($phone, 1);
                                            }
                                        @endphp
                                        <a href="https://wa.me/{{ $phone }}" target="_blank" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white transition hover:bg-emerald-600" title="Hubungi WA">
                                            <i data-lucide="message-square" class="h-4 w-4"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route($routePrefix.'.edit', $item->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600" title="Edit">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route($routePrefix.'.destroy', $item->id) }}" onsubmit="return confirm('Hapus data ini?')" style="display: contents;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-500 transition hover:bg-rose-600 hover:text-white" title="Hapus">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($tableColumns) + 1 }}" class="py-8 text-center text-slate-400">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $items->links() }}</div>
    </div>
@endsection
