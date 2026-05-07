<footer class="border-t border-slate-200 bg-slate-50 pb-8 pt-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-12 flex flex-col gap-10 md:flex-row md:items-start md:justify-between">
            <div class="grid w-full grid-cols-1 gap-8 sm:grid-cols-2 md:w-auto md:grid-cols-[1.5fr_1fr_1fr_auto]">
                <div>
                    <div class="mb-4 flex items-center gap-2">
                        <img src="{{ asset('storage/'.data_get($setting, 'logo_path', 'branding/logo.png')) }}" alt="{{ data_get($setting, 'institution_name', 'KDEKS Kalimantan Timur') }}" class="h-10 w-auto object-contain">
                    </div>
                    <p class="mb-4 text-xs font-medium leading-relaxed text-slate-500">
                        {{ data_get($setting, 'address', 'Jl. Gajah Mada No.2, RW.01, Jawa, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur 75242') }}
                    </p>
                </div>
                <div>
                    <h4 class="mb-4 text-sm font-extrabold text-slate-900">Layanan Utama</h4>
                    <ul class="space-y-2 text-xs font-medium text-slate-500">
                        <li><a href="{{ route('home') }}#webgis" class="transition hover:text-emerald-600">Pemetaan Wilayah</a></li>
                        <li><a href="{{ route('home') }}#sektor" class="transition hover:text-emerald-600">Sektor Ekonomi Syariah</a></li>
                        <li><a href="{{ route('resources.index') }}" class="transition hover:text-emerald-600">Ruang Pengetahuan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 text-sm font-extrabold text-slate-900">Informasi</h4>
                    <ul class="space-y-2 text-xs font-medium text-slate-500">
                        <li><a href="{{ route('articles.index') }}" class="transition hover:text-emerald-600">Berita Terkini</a></li>
                        <li><a href="{{ route('siaran-pers') }}" class="transition hover:text-emerald-600">Siaran Pers</a></li>
                        <li><a href="{{ route('regulations.index') }}" class="transition hover:text-emerald-600">Regulasi & Dokumen</a></li>
                    </ul>
                </div>
                <div class="w-min">
                    <h4 class="mb-4 text-sm font-extrabold text-slate-900">Pusat Bantuan</h4>
                    <ul class="space-y-2 text-xs font-medium text-slate-500">
                        <li class="flex items-center gap-2">
                            <i data-lucide="mail" class="h-3.5 w-3.5 text-slate-400"></i>
                            <span>{{ data_get($setting, 'email', 'diskominfo@kaltimprov.go.id') }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="phone" class="h-3.5 w-3.5 text-slate-400"></i>
                            <span>{{ data_get($setting, 'phone', '+62 8XX-XXXX-XXXX') }}</span>
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}" class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700">
                        <i data-lucide="message-square" class="h-4 w-4"></i>
                        <span>Kontak Kami</span>
                    </a>
                </div>
            </div>
            <div class="flex shrink-0 flex-col items-center gap-5 md:ml-6">
                <h4 class="text-base font-extrabold text-slate-900">Supported by</h4>
                <img src="{{ asset('assets/img/logo/bpd.png') }}" alt="BPD" class="h-20 w-auto object-contain">
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