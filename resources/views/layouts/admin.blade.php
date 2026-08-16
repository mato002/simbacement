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
    x-data="{ sidebarOpen: false }"
    @if (session('success')) data-flash-success="{{ e(session('success')) }}" @endif
    @if (session('error')) data-flash-error="{{ e(session('error')) }}" @endif
    @if ($errors->any()) data-flash-errors="{{ e(json_encode($errors->all())) }}" @endif
>
    <div class="flex min-h-screen">
        @include('partials.admin.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-line bg-white/95 backdrop-blur">
                <div class="flex h-[4.25rem] items-center justify-between gap-4 px-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button" class="inline-flex h-10 w-10 items-center justify-center border border-line lg:hidden" @click="sidebarOpen = true" aria-label="Open sidebar">
                            <i class="fa-solid fa-bars" aria-hidden="true"></i>
                        </button>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold uppercase tracking-wide">@yield('title', 'Admin')</p>
                            <p class="hidden text-xs text-steel sm:block">Simba Cement control centre</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="hidden items-center gap-2 border border-line px-3 py-2 text-xs font-semibold uppercase tracking-wide text-ink hover:border-brand sm:inline-flex">
                            <i class="fa-solid fa-globe" aria-hidden="true"></i>
                            Website
                        </a>
                        <div class="hidden text-right md:block">
                            <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-xs capitalize text-steel">{{ str_replace('-', ' ', auth()->user()->getRoleNames()->first() ?: 'staff') }}</p>
                        </div>
                        <span class="hidden h-10 w-10 items-center justify-center bg-ink font-display text-sm font-bold text-brand md:inline-flex">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <form
                            method="POST"
                            action="{{ route('admin.logout') }}"
                            data-swal-confirm
                            data-swal-title="Sign out?"
                            data-swal-text="You will need to log in again to access the admin panel."
                            data-swal-confirm-text="Yes, logout"
                            data-swal-danger="0"
                            data-swal-icon="question"
                        >
                            @csrf
                            <button type="submit" class="btn-dark !px-3 !py-2 !text-xs" title="Logout">
                                <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
