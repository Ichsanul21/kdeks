@extends('layouts.app')

@section('title', 'Tentang Kami - ' . data_get($setting, 'meta_title', 'KDEKS Kalimantan Timur'))

@section('content')
    <section class="relative min-h-[45vh] overflow-hidden pt-32 pb-16">
        <div
            class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.05),transparent_40%)]">
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-6">
            {{-- <nav class="mb-8 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                <a href="{{ route('home') }}" class="transition hover:text-emerald-600">Beranda</a>
                <i data-lucide="chevron-right" class="h-3 w-3"></i>
                <span class="text-emerald-600">Profil</span>
                <i data-lucide="chevron-right" class="h-3 w-3"></i>
                <span class="text-slate-500">Tentang Kami</span>
            </nav> --}}

            <div class="max-w-4xl">
                <h1 class="font-heading text-4xl font-extrabold leading-tight text-slate-900 md:text-5xl lg:text-6xl">
                    Membangun <span class="text-gradient">Ekonomi Syariah</span><br>
                    di Kalimantan Timur
                </h1>
                <p class="mt-6 text-lg font-medium leading-relaxed text-slate-500">
                    Mengenal lebih dekat Komite Daerah Ekonomi dan Keuangan Syariah (KDEKS) dan komitmen kami bagi kemajuan
                    daerah.
                </p>
            </div>
        </div>
    </section>

    <section class="relative pb-24">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-16 lg:grid-cols-12">
                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <div
                        class="group relative overflow-hidden rounded-[2.5rem] border border-white bg-white/80 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl md:p-12">
                        <div
                            class="absolute right-0 top-0 h-48 w-48 translate-x-16 -translate-y-16 rounded-full bg-emerald-500/5 blur-3xl transition-all group-hover:bg-emerald-500/10">
                        </div>

                        <div class="relative">
                            <div class="mb-10 flex items-center gap-4">
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                                    <i data-lucide="award" class="h-8 w-8"></i>
                                </div>
                                <div>
                                    <h2 class="font-heading text-2xl font-bold text-slate-900">Deskripsi Umum</h2>
                                    <p class="text-sm font-medium text-slate-400 uppercase tracking-widest">Tentang KDEKS
                                    </p>
                                </div>
                            </div>

                            <div
                                class="prose prose-emerald prose-lg max-w-none prose-p:text-slate-600 prose-p:leading-relaxed prose-strong:text-slate-900">
                                <p>
                                    Komite Daerah Ekonomi dan Keuangan Syariah (KDEKS) merupakan bagian dari upaya
                                    pemerintah Indonesia untuk memperkuat ekonomi syariah di tingkat nasional dan daerah.
                                    Pembentukan KDEKS ini terkait erat dengan perkembangan ekonomi syariah yang semakin
                                    penting di Indonesia, serta dorongan untuk memperluas implementasi ekonomi syariah ke
                                    berbagai daerah di seluruh Indonesia.
                                </p>
                                <p>
                                    KDEKS Kalimantan Timur dibentuk berdasarkan Keputusan Gubernur Kalimantan Timur.
                                    Pembentukan KDEKS merupakan salah satu langkah strategis untuk memastikan bahwa
                                    kebijakan dan inisiatif ekonomi syariah yang digagas oleh KNEKS dapat diterapkan secara
                                    efektif di daerah-daerah, dengan melibatkan pemerintah daerah, lembaga keuangan syariah,
                                    industri halal, serta masyarakat lokal.
                                </p>
                                <p>
                                    Latar belakang pembentukan KDEKS merupakan sebuah kebijakan untuk memperkuat ekonomi
                                    syariah di Indonesia dimulai dengan pembentukan Komite Nasional Keuangan Syariah (KNKS)
                                    pada tahun 2016, yang kemudian bertransformasi menjadi KNEKS pada tahun 2020.
                                    Selanjutnya, untuk mendorong perkembangan ekonomi syariah secara lebih luas dan
                                    menyeluruh, baik di sektor keuangan maupun industri halal, perlu adanya koordinasi di
                                    tingkat daerah.
                                </p>
                                <p>
                                    Oleh karena itu, KDEKS dibentuk sebagai lembaga yang memiliki peran dalam mendukung
                                    implementasi strategi ekonomi syariah di daerah.
                                </p>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Our Mission -->
                    <div class="rounded-[2.5rem] bg-emerald-600 p-8 text-white shadow-xl shadow-emerald-600/20">
                        <h3 class="mb-6 font-heading text-xl font-bold">Fokus Utama KDEKS</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <i data-lucide="check-circle" class="h-5 w-5 shrink-0 text-emerald-200"></i>
                                <span class="text-sm font-medium leading-relaxed">Penguatan koordinasi antar pemangku
                                    kepentingan di daerah.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i data-lucide="check-circle" class="h-5 w-5 shrink-0 text-emerald-200"></i>
                                <span class="text-sm font-medium leading-relaxed">Implementasi inisiatif ekonomi syariah di
                                    sektor industri halal.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i data-lucide="check-circle" class="h-5 w-5 shrink-0 text-emerald-200"></i>
                                <span class="text-sm font-medium leading-relaxed">Pengembangan lembaga keuangan syariah di
                                    Kalimantan Timur.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Call Tracking -->
                    <div class="rounded-[2.5rem] border border-slate-100 bg-white p-8 shadow-sm">
                        <h3 class="mb-4 font-heading text-lg font-bold text-slate-900">Informasi Publik</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">Akses dokumen kebijakan dan regulasi terbaru
                            mengenai ekonomi syariah.</p>
                        <a href="{{ route('regulations.index') }}"
                            class="flex items-center justify-center gap-2 rounded-xl bg-slate-50 py-3 text-sm font-bold text-slate-900 transition hover:bg-slate-100">
                            Lihat Regulasi
                            <i data-lucide="arrow-right" class="h-4 w-4 text-emerald-600"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
