<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Dasar -->
    <title>@yield('title', data_get($setting, 'meta_title', 'Halal Center Kaltim'))</title>
    <meta name="description" content="{{ strip_tags((string) data_get($setting, 'meta_description', 'Digital hub regional ekonomi halal dan sertifikasi terpadu Kalimantan Timur.')) }}">
    <meta name="keywords" content="{{ data_get($setting, 'meta_keywords', 'halal kaltim, sertifikasi halal, umkm halal, ekonomi syariah') }}">
    
    <!-- Canonical URL (Penting untuk SEO menghindari duplikat konten) -->
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph / WhatsApp / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', data_get($setting, 'meta_title', 'Halal Center Kaltim'))">
    <meta property="og:description" content="{{ strip_tags((string) data_get($setting, 'meta_description', 'Digital hub regional ekonomi halal dan sertifikasi terpadu Kalimantan Timur.')) }}">
    <!-- Logika Gambar: Jika ada setting og_image, pakai itu. Jika tidak, pakai logo default -->
    <meta property="og:image" content="{{ data_get($setting, 'og_image_path') ? asset('storage/'.data_get($setting, 'og_image_path')) : asset('storage/branding/logo.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', data_get($setting, 'meta_title', 'Halal Center Kaltim'))">
    <meta name="twitter:description" content="{{ strip_tags((string) data_get($setting, 'meta_description', 'Digital hub regional ekonomi halal dan sertifikasi terpadu Kalimantan Timur.')) }}">
    <meta name="twitter:image" content="{{ data_get($setting, 'og_image_path') ? asset('storage/'.data_get($setting, 'og_image_path')) : asset('storage/branding/logo.png') }}">

    <!-- Favicon & Icons (Path: public/assets/img/favicon_io/) -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon_io/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/img/favicon_io/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('assets/img/favicon_io/android-chrome-512x512.png') }}">
    
    <!-- Jika Anda memiliki file site.webmanifest, bisa tambahkan baris ini (opsional) -->
    <!-- <link rel="manifest" href="{{ asset('assets/img/favicon_io/site.webmanifest') }}"> -->

    @yield('meta')
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative overflow-x-clip bg-[#f8fafc] font-sans text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">
    <div class="relative min-h-screen overflow-x-clip">
        @include('components.watermark-overlay', ['setting' => $setting ?? null])
        
        <div class="organic-shape pointer-events-none left-[-100px] top-[-100px] h-[500px] w-[500px] bg-emerald-200/50"></div>
        <div class="organic-shape pointer-events-none right-[-200px] top-[20%] h-[600px] w-[600px] bg-cyan-200/50 [animation-delay:2s]"></div>
        <div class="organic-shape pointer-events-none bottom-[10%] left-[20%] h-[400px] w-[400px] bg-blue-200/40 [animation-delay:4s]"></div>

        <div class="page-shell relative">
            @include('components.navbar', ['setting' => $setting, 'locale' => $locale ?? 'id'])
            <main>
                @yield('content')
            </main>
            @include('components.footer', ['setting' => $setting])
            @include('components.shared-modals')
        </div>
    </div>
</body>
</html>
