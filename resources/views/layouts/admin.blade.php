<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="min-h-screen bg-mist text-ink"
    x-data="{
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('adminSidebarCollapsed') === '1',
        toggleSidebarCollapsed() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('adminSidebarCollapsed', this.sidebarCollapsed ? '1' : '0');
        }
    }"
    @keydown.window="
        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
            event.preventDefault();
            window.dispatchEvent(new CustomEvent('admin-focus-search'));
        }
    "
    @if (session('success')) data-flash-success="{{ e(session('success')) }}" @endif
    @if (session('error')) data-flash-error="{{ e(session('error')) }}" @endif
    @if ($errors->any()) data-flash-errors="{{ e(json_encode($errors->all())) }}" @endif
>
    @include('partials.admin.sidebar')

    <div
        class="flex min-h-screen min-w-0 flex-col transition-[padding] duration-300"
        :class="sidebarCollapsed ? 'lg:pl-[4.75rem]' : 'lg:pl-72'"
    >
        @include('partials.admin.header')

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
