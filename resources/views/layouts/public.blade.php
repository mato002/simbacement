<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $defaultTitle = $siteSeo['default_title'] ?? config('app.name');
        $defaultDescription = $siteSeo['default_description'] ?? 'High-quality cement and building materials engineered for strength, durability and performance across Kenya.';
        $pageTitle = trim($__env->yieldContent('title')) ?: $defaultTitle;
        $pageDescription = trim($__env->yieldContent('meta_description')) ?: $defaultDescription;
        $canonical = trim($__env->yieldContent('canonical')) ?: url()->current();
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:site_name" content="{{ $siteCompany['legal_name'] ?? config('app.name') }}">
    <meta property="og:title" content="{{ trim($__env->yieldContent('og_title')) ?: $pageTitle }}">
    <meta property="og:description" content="{{ trim($__env->yieldContent('og_description')) ?: $pageDescription }}">
    <meta property="og:type" content="{{ trim($__env->yieldContent('og_type')) ?: 'website' }}">
    <meta property="og:url" content="{{ $canonical }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col">
    @include('partials.public.header')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.public.footer')
    @include('partials.public.whatsapp-float')

    @stack('scripts')
</body>
</html>
