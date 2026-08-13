<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-ink text-white">
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="relative hidden overflow-hidden lg:block">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(230,180,34,0.35),transparent_45%),linear-gradient(160deg,#0f0f0f,#1a1a1a_50%,#2a2414)]"></div>
            <div class="relative flex h-full flex-col justify-between p-12">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center bg-brand text-ink font-display text-xl font-bold">SC</span>
                    <span class="font-display text-2xl font-bold uppercase tracking-wide">Simba Cement</span>
                </div>
                <div>
                    <p class="section-label mb-4 text-brand">Admin Panel</p>
                    <h1 class="heading-display max-w-md">Manage products, quotes, content and operations.</h1>
                </div>
                <p class="text-sm text-white/50">Authorized staff access only.</p>
            </div>
        </div>

        <div class="flex items-center justify-center px-6 py-16">
            <div class="w-full max-w-md border border-white/10 bg-white p-8 text-ink shadow-xl">
                <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Sign in</h2>
                <p class="mt-2 text-sm text-steel">Use your staff credentials to continue.</p>

                <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-semibold">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full border border-line bg-mist px-3 py-2.5 text-sm outline-none focus:border-brand"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-semibold">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="w-full border border-line bg-mist px-3 py-2.5 text-sm outline-none focus:border-brand"
                        >
                    </div>

                    <label class="flex items-center gap-2 text-sm text-steel">
                        <input type="checkbox" name="remember" class="rounded-sm border-line text-brand focus:ring-brand">
                        Remember me
                    </label>

                    <button type="submit" class="btn-primary w-full">Sign in to Admin</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
