@php
    $sehatiErrors = $errors->sehatiRegistration ?? new \Illuminate\Support\MessageBag();
@endphp

<!-- SEHATI Registration Modal -->
<div id="sehatiModal" class="fixed inset-0 z-[100] hidden" @if($sehatiErrors->any()) data-open-on-load="true" @endif>
    <div id="sehatiBackdrop" class="absolute inset-0 bg-slate-900/40 opacity-0 backdrop-blur-sm transition-opacity" onclick="closeModal('sehatiModal')"></div>
    <div class="pointer-events-none absolute inset-0 flex items-start justify-center overflow-y-auto px-4 pb-10 pt-20">
        <div id="sehatiContent" class="pointer-events-auto w-full max-w-2xl scale-95 rounded-3xl bg-white opacity-0 shadow-2xl transition-all">
            <div class="flex items-center justify-between rounded-t-3xl border-b border-slate-100 bg-slate-50 p-6">
                <div>
                    <h3 class="font-heading text-xl font-extrabold tracking-tight text-slate-900">Formulir SEHATI</h3>
                    <p class="text-xs font-medium text-slate-500">Pengajuan Sertifikasi Halal Gratis</p>
                </div>
                <button onclick="closeModal('sehatiModal')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('sehati.store') }}" class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Lembaga Pendamping (LP3H)</label>
                        <select name="lph_partner_id" class="w-full cursor-pointer rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white">
                            <option value="">-- Pilih Lembaga Pendamping --</option>
                            @foreach($lphPartners as $partner)
                                @if($partner->partner_type === 'lp3h')
                                    <option value="{{ $partner->id }}" @selected(old('lph_partner_id') == $partner->id)>{{ $partner->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @if($sehatiErrors->has('lph_partner_id'))
                            <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('lph_partner_id') }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Nama Pemilik UMKM</label>
                        <input type="text" name="owner_name" value="{{ old('owner_name') }}" placeholder="Sesuai KTP" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white" required>
                        @if($sehatiErrors->has('owner_name'))
                            <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('owner_name') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Nama UMKM</label>
                        <input type="text" name="business_name" value="{{ old('business_name') }}" placeholder="Contoh: Kedai Mubarok" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white" required>
                        @if($sehatiErrors->has('business_name'))
                            <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('business_name') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Nama Produk yang Diajukan</label>
                        <input type="text" name="product_name" value="{{ old('product_name') }}" placeholder="Jenis/Nama Produk" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white" required>
                        @if($sehatiErrors->has('product_name'))
                            <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('product_name') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Nomor HP / WA</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white" required>
                        @if($sehatiErrors->has('phone'))
                            <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('phone') }}</p>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-500">Deskripsi Singkat Usaha</label>
                        <textarea name="description" rows="3" placeholder="Jelaskan secara singkat..." class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition-all focus:border-emerald-500/50 focus:bg-white">{{ old('description') }}</textarea>
                        @if($sehatiErrors->has('description'))
                            <p class="mt-2 text-sm text-rose-500">{{ $sehatiErrors->first('description') }}</p>
                        @endif
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 py-3.5 text-sm font-extrabold text-white shadow-md transition-colors hover:bg-emerald-400">
                            Ajukan Pendaftaran
                            <i data-lucide="send" class="h-4 w-4"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Integrated Search Modal -->
<div id="searchModal" class="fixed inset-0 z-[100] hidden">
    <div id="searchBackdrop" class="absolute inset-0 bg-white/95 opacity-0 backdrop-blur-md transition-opacity" onclick="closeModal('searchModal')"></div>
    <div id="searchContent" class="absolute left-1/2 top-20 w-full max-w-2xl -translate-x-1/2 scale-95 px-4 opacity-0 transition-all sm:top-24">
        <div class="relative">
            <i data-lucide="search" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400 sm:left-5 sm:h-6 sm:w-6"></i>
            <input id="globalSearchInput" type="text" placeholder="Ketik kata kunci..." class="w-full rounded-2xl border border-slate-200 bg-white py-4 pl-12 pr-16 text-base font-bold text-slate-900 shadow-xl focus:border-emerald-500/50 focus:outline-none sm:py-5 sm:pl-16 sm:text-lg">
            <button onclick="closeModal('searchModal')" class="absolute right-3 top-1/2 -translate-y-1/2 rounded bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-400 hover:text-slate-900 sm:right-5 sm:text-xs">ESC</button>
        </div>
        <div class="mt-4 flex flex-wrap justify-center gap-2">
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">Alur Sertifikasi</span>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">LPH Samarinda</span>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">Produk Halal</span>
        </div>
        <div id="searchResults" class="mt-5 grid gap-3 sm:grid-cols-2"></div>
    </div>
</div>
