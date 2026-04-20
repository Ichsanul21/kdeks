@extends('layouts.app')

@section('title', 'Kontak - ' . data_get($setting, 'meta_title', 'KDEKS Kalimantan Timur'))

@section('content')
    <section class="relative min-h-[60vh] overflow-hidden pb-12 pt-32">
        <div
            class="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.08),transparent_40%)]">
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-6">
            <div class="mb-16 text-center">
                <h1 class="font-heading text-4xl font-extrabold tracking-tight text-slate-900 md:text-5xl lg:text-6xl">
                    Hubungi <span class="text-gradient">Kami</span>
                </h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg font-medium text-slate-500">
                    Punya pertanyaan atau ingin berdiskusi? Tim kami siap membantu Anda. Silakan isi buku tamu di bawah ini.
                </p>
            </div>

            <div class="grid gap-12 lg:grid-cols-2">
                <!-- Contact Info -->
                <div class="flex flex-col gap-8 lg:h-full">
                    <div
                        class="rounded-3xl border border-white/80 bg-white/50 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl">
                        <h3 class="mb-6 font-heading text-2xl font-bold text-slate-900">Informasi Kontak</h3>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                    <i data-lucide="map-pin" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Kantor Utama</p>
                                    <p class="mt-1 text-sm font-medium leading-relaxed text-slate-500">
                                        {{ data_get($setting, 'address', 'Jl. Gajah Mada No.2, RW.01, Jawa, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur 75242') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600">
                                    <i data-lucide="mail" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Email</p>
                                    <p class="mt-1 text-sm font-medium text-slate-500">
                                        {{ data_get($setting, 'email', 'kontak@kdeks-kaltim.go.id') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                                    <i data-lucide="phone" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Telepon / WhatsApp</p>
                                    <p class="mt-1 text-sm font-medium text-slate-500">
                                        {{ data_get($setting, 'phone', '+62 812-XXXX-XXXX') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Google Maps Embed -->
                    <div
                        class="flex-1 overflow-hidden rounded-3xl border border-white/80 bg-slate-100 shadow-sm transition hover:shadow-md min-h-[200px]">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.665746820167!2d117.1367558747235!3d-0.5010741994940161!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df67fa0cf3b5609%3A0x9efc250dd531e8b4!2sKantor%20Gubernur%20Kalimantan%20Timur!5e0!3m2!1sid!2sid!4v1776652709502!5m2!1sid!2sid"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="relative">
                    <div class="absolute inset-0 translate-x-4 translate-y-4 rounded-[2.5rem] bg-emerald-500/10 blur-3xl">
                    </div>
                    <div
                        class="relative rounded-[2.5rem] border border-white bg-white/80 p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] backdrop-blur-xl md:p-12">
                        <h3 class="mb-8 font-heading text-2xl font-bold text-slate-900">Buku Tamu</h3>

                        @if(session('status'))
                            <div
                                class="mb-8 rounded-2xl border border-emerald-100 bg-emerald-50/50 p-4 font-medium text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="name" class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama
                                        Lengkap</label>
                                    <input type="text" id="name" name="name" required
                                        class="w-full rounded-2xl border border-slate-100 bg-white px-5 py-4 text-sm font-semibold outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                        placeholder="Lorem Ipsum">
                                </div>
                                <div class="space-y-2">
                                    <label for="email"
                                        class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat
                                        Email</label>
                                    <input type="email" id="email" name="email" required
                                        class="w-full rounded-2xl border border-slate-100 bg-white px-5 py-4 text-sm font-semibold outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                        placeholder="lorem@example.com">
                                </div>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="phone"
                                        class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor
                                        Telepon</label>
                                    <input type="text" id="phone" name="phone"
                                        class="w-full rounded-2xl border border-slate-100 bg-white px-5 py-4 text-sm font-semibold outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                        placeholder="0812...">
                                </div>
                                <div class="space-y-2">
                                    <label for="subject"
                                        class="text-xs font-bold uppercase tracking-wider text-slate-400">Subjek</label>
                                    <input type="text" id="subject" name="subject" required
                                        class="w-full rounded-2xl border border-slate-100 bg-white px-5 py-4 text-sm font-semibold outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                        placeholder="Pertanyaan Layanan">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="message" class="text-xs font-bold uppercase tracking-wider text-slate-400">Tulis
                                    Pesan</label>
                                <textarea id="message" name="message" rows="5" required
                                    class="w-full resize-none rounded-2xl border border-slate-100 bg-white px-5 py-4 text-sm font-semibold outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                    placeholder="Apa yang ingin Anda sampaikan?"></textarea>
                            </div>

                            <button type="submit"
                                class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 py-5 font-bold text-white shadow-xl transition hover:bg-slate-800 active:scale-95">
                                Kirim Pesan
                                <i data-lucide="send"
                                    class="h-4 w-4 transition-transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
