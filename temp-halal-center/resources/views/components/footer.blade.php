<footer class="border-t border-slate-200 bg-slate-50 pb-8 pt-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-12 grid grid-cols-1 gap-10 md:grid-cols-4">
            <div class="md:col-span-1">
                <div class="mb-4 flex items-center gap-2">
                    <img src="{{ asset('storage/'.data_get($setting, 'logo_path', 'branding/logo.png')) }}" alt="{{ data_get($setting, 'institution_name', 'KDEKS Kalimantan Timur') }}" class="h-10 w-auto object-contain">
                </div>
                <p class="mb-4 text-xs font-medium leading-relaxed text-slate-500">
                    {{ data_get($setting, 'address', 'Jl. Gajah Mada No.2, RW.01, Jawa, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur 75242') }}
                </p>
            </div>
            <div>
                <h4 class="mb-4 text-sm font-extrabold text-slate-900">Layanan Publik</h4>
                <ul class="space-y-2 text-xs font-medium text-slate-500">
                    <li><a href="{{ route('home') }}#sehati" class="transition hover:text-emerald-600">Pendaftaran Sertifikasi</a></li>
                    <li><a href="{{ route('products.index') }}" class="transition hover:text-emerald-600">Direktori Produk</a></li>
                    <li><a href="{{ route('home') }}#webgis" class="transition hover:text-emerald-600">Peta Sebaran UMKM</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-4 text-sm font-extrabold text-slate-900">Informasi</h4>
                <ul class="space-y-2 text-xs font-medium text-slate-500">
                    <li><a href="{{ route('articles.index') }}" class="transition hover:text-emerald-600">Berita & Publikasi</a></li>
                    <li><a href="{{ route('resources.index') }}" class="transition hover:text-emerald-600">Dokumen & Regulasi</a></li>

                </ul>
            </div>
            <div>
                <h4 class="mb-4 text-sm font-extrabold text-slate-900">Pusat Bantuan</h4>
                <a href="{{ data_get($setting, 'consultation_url', '#') }}" class="flex w-full items-center justify-between rounded-lg border border-slate-200 bg-white px-4 py-2 text-left text-xs font-bold shadow-sm transition hover:border-emerald-500">
                    Hubungi Call Center
                    <i data-lucide="phone" class="h-3 w-3 text-emerald-500"></i>
                </a>
            </div>
        </div>
        <div class="flex flex-col items-center justify-between border-t border-slate-200 pt-6 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 sm:flex-row">
            <span>&copy; {{ now()->year }} Komite Daerah Keuangan dan Ekonomi Syariah (KDEKS) Kalimantan Timur.</span>
            <span class="mt-4 flex items-center gap-2 sm:mt-0">
                <i data-lucide="users" class="h-4 w-4"></i>
                Total Kunjungan: {{ number_format(data_get($setting, 'visitor_count', 0), 0, ',', '.') }}
            </span>
        </div>
    </div>
</footer>
