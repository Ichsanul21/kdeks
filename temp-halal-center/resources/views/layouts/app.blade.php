<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', data_get($setting, 'meta_title', 'Halal Center Kaltim'))</title>
    <meta name="description" content="{{ data_get($setting, 'meta_description', 'Digital hub regional ekonomi halal dan sertifikasi terpadu Kalimantan Timur.') }}">
    <meta name="keywords" content="{{ data_get($setting, 'meta_keywords', 'halal kaltim, sertifikasi halal, umkm halal, ekonomi syariah') }}">
    <meta property="og:title" content="@yield('title', data_get($setting, 'meta_title', 'Halal Center Kaltim'))">
    <meta property="og:description" content="{{ data_get($setting, 'meta_description', 'Digital hub regional ekonomi halal dan sertifikasi terpadu Kalimantan Timur.') }}">
    <meta property="og:image" content="{{ data_get($setting, 'og_image_path') ? asset('storage/'.data_get($setting, 'og_image_path')) : asset('storage/branding/logo.png') }}">
    <meta property="og:type" content="website">
    @yield('meta')
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative overflow-x-hidden bg-[#f8fafc] font-sans text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">
    @include('components.watermark-overlay', ['setting' => $setting ?? null])
    <div class="organic-shape left-[-100px] top-[-100px] h-[500px] w-[500px] bg-emerald-200/50"></div>
    <div class="organic-shape right-[-200px] top-[20%] h-[600px] w-[600px] bg-cyan-200/50 [animation-delay:2s]"></div>
    <div class="organic-shape bottom-[10%] left-[20%] h-[400px] w-[400px] bg-blue-200/40 [animation-delay:4s]"></div>
    <div class="page-shell">
        @include('components.navbar', ['setting' => $setting, 'locale' => $locale ?? 'id'])
        <main>
            @yield('content')
        </main>
        @include('components.footer', ['setting' => $setting])
    </div>
</body>
</html>
