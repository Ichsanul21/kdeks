@extends('layouts.app')

@section('title', 'Profil Pengurus - KDEKS Kalimantan Timur')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#f8fafb] pb-24 pt-28">
    {{-- Background Decoration --}}
    <div class="pointer-events-none absolute inset-0 z-0">
        <div class="absolute -top-24 left-[10%] h-[500px] w-[500px] rounded-full bg-emerald-200/10 blur-[120px]"></div>
        <div class="absolute -bottom-24 right-[10%] h-[500px] w-[500px] rounded-full bg-cyan-200/10 blur-[120px]"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-5xl px-6">

        {{-- Breadcrumb / Back --}}
        <a href="{{ route('profile.organization') }}" class="group mb-12 inline-flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-slate-400 transition hover:text-emerald-600">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-slate-200 transition group-hover:ring-emerald-100 group-hover:translate-x-[-4px]">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
            </div>
            Kembali ke Struktur Organisasi
        </a>

        <div class="grid gap-8 lg:grid-cols-12">

            {{-- Profile Card (Sidebar-ish) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-32 space-y-6">
                    <div class="overflow-hidden rounded-[2.5rem] border border-white bg-white p-2 shadow-2xl shadow-slate-200/50">
                        <div class="relative aspect-square overflow-hidden rounded-[2rem] bg-slate-50">
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 text-slate-200">
                                <i data-lucide="user" class="h-32 w-32"></i>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/20 to-transparent"></div>

                            {{-- Role Badge --}}
                            <div class="absolute bottom-4 left-4 right-4 rounded-2xl bg-white/90 px-4 py-3 text-center backdrop-blur-md shadow-lg border border-white/50">
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-600">Pengurus Inti</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-slate-100 bg-white p-6 shadow-sm">
                        <h4 class="mb-4 text-xs font-bold uppercase tracking-[0.15em] text-slate-400">Hubungi</h4>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                    <i data-lucide="mail" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Email</p>
                                    <p class="text-sm font-bold text-slate-700">email@kaltim.go.id</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">
                                    <i data-lucide="phone" class="h-4 w-4"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Telepon</p>
                                    <p class="text-sm font-bold text-slate-700">+62 812 XXXX XXXX</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Profile Content --}}
            <div class="lg:col-span-8">
                <div class="space-y-8">
                    {{-- Identity Header --}}
                    <div class="rounded-[2.5rem] border border-white bg-white/80 p-8 shadow-xl shadow-slate-200/40 backdrop-blur-md md:p-12">
                        <h1 class="font-heading text-4xl font-extrabold tracking-tight text-slate-900 md:text-5xl">Nama Lengkap</h1>
                        <p class="mt-4 text-xl font-bold text-slate-500">Jabatan Pengurus</p>

                        <div class="mt-10 grid gap-6 sm:grid-cols-3">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Status</p>
                                <p class="mt-1 font-bold text-slate-700">Aktif</p>
                            </div>
                        </div>
                    </div>

                    {{-- Biography Section --}}
                    <div class="rounded-[2.5rem] bg-white p-8 shadow-sm md:p-12">
                        <div class="mb-8 flex items-center justify-between border-b border-slate-100 pb-6">
                            <h3 class="text-xl font-extrabold text-slate-900">Biografi Profesional</h3>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-300">
                                <i data-lucide="quote" class="h-5 w-5"></i>
                            </div>
                        </div>
                        <div class="prose prose-slate max-w-none">
                            <p class="text-lg leading-relaxed text-slate-500">
                                Beliau merupakan figur profesional yang memiliki dedikasi tinggi dalam pengembangan ekonomi syariah di Kalimantan Timur. Dengan pengalaman luas di berbagai sektor publik dan swasta, saat ini beliau dipercaya untuk mengemban amanah strategis dalam kepengurusan KDEKS Kaltim guna mewujudkan visi Kalimantan Timur sebagai pusat ekonomi dan keuangan syariah yang inklusif dan berkelanjutan.
                            </p>
                            <p class="mt-6 text-lg leading-relaxed text-slate-500">
                                Fokus utama beliau meliputi koordinasi lintas sektor, pengembangan potensi industri produk halal, serta penguatan ekosistem keuangan syariah di seluruh wilayah kabupaten dan kota se-Kalimantan Timur.
                            </p>
                        </div>

                        {{-- Social Links --}}
                        <div class="mt-12 flex items-center gap-4">
                            <span class="text-xs font-bold uppercase tracking-[0.15em] text-slate-400">Media Sosial</span>
                            <div class="h-px w-12 bg-slate-100"></div>
                            <div class="flex gap-3">
                                <a href="#" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition hover:bg-emerald-500 hover:text-white">
                                    <i data-lucide="facebook" class="h-4 w-4"></i>
                                </a>
                                <a href="#" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition hover:bg-emerald-500 hover:text-white">
                                    <i data-lucide="instagram" class="h-4 w-4"></i>
                                </a>
                                <a href="#" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition hover:bg-emerald-500 hover:text-white">
                                    <i data-lucide="linkedin" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
