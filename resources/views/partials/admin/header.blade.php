@php
    $user = auth()->user();
    $role = str_replace('-', ' ', $user?->getRoleNames()->first() ?: 'staff');
    $initials = collect(preg_split('/\s+/', trim((string) $user?->name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('') ?: 'SC';

    $attentionCount = (int) ($adminAttentionCount ?? 0);
    $alerts = $adminAlerts ?? [
        'new_quotes' => 0,
        'new_messages' => 0,
        'new_applications' => 0,
    ];
@endphp

<header
    class="admin-header sticky top-0 z-30 border-b border-line bg-white/95 backdrop-blur"
    x-data="{ notificationsOpen: false, userMenuOpen: false, quickOpen: false }"
    @keydown.escape.window="notificationsOpen = false; userMenuOpen = false; quickOpen = false"
>
    <div class="flex h-[4.5rem] items-center gap-3 px-4 sm:gap-4 sm:px-6">
        {{-- Left: mobile toggle + page context --}}
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <button
                type="button"
                class="inline-flex h-10 w-10 shrink-0 items-center justify-center border border-line bg-mist text-ink transition hover:border-brand lg:hidden"
                @click="sidebarOpen = true"
                aria-label="Open sidebar"
            >
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>

            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <p class="text-[10px] font-semibold tracking-[0.18em] text-steel uppercase">
                        @hasSection('header_eyebrow')
                            @yield('header_eyebrow')
                        @else
                            Control centre
                        @endif
                    </p>
                    <span class="hidden text-line sm:inline" aria-hidden="true">/</span>
                    <p class="hidden text-[10px] font-semibold tracking-[0.14em] text-ink/50 uppercase sm:inline">
                        {{ now()->format('D, d M Y') }}
                    </p>
                </div>
                <h1 class="truncate font-display text-xl font-bold uppercase tracking-wide text-ink sm:text-2xl">
                    @yield('title', 'Admin')
                </h1>
            </div>
        </div>

        {{-- Right: actions --}}
        <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
            {{-- Quick create --}}
            <div class="relative hidden md:block">
                <button
                    type="button"
                    class="admin-header-btn"
                    @click="quickOpen = !quickOpen; notificationsOpen = false; userMenuOpen = false"
                    :aria-expanded="quickOpen.toString()"
                >
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span class="hidden lg:inline">Create</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-steel" aria-hidden="true"></i>
                </button>

                <div
                    x-cloak
                    x-show="quickOpen"
                    x-transition.origin.top.right
                    @click.outside="quickOpen = false"
                    class="admin-header-menu right-0 w-56"
                >
                    <p class="admin-header-menu-label">Quick create</p>
                    <a href="{{ route('admin.products.create') }}" class="admin-header-menu-item" @click="quickOpen = false">
                        <i class="fa-solid fa-box-open w-4 text-center text-brand-deep" aria-hidden="true"></i>
                        New product
                    </a>
                    <a href="{{ route('admin.news.create') }}" class="admin-header-menu-item" @click="quickOpen = false">
                        <i class="fa-solid fa-newspaper w-4 text-center text-brand-deep" aria-hidden="true"></i>
                        New article
                    </a>
                    <a href="{{ route('admin.jobs.create') }}" class="admin-header-menu-item" @click="quickOpen = false">
                        <i class="fa-solid fa-briefcase w-4 text-center text-brand-deep" aria-hidden="true"></i>
                        New job listing
                    </a>
                    <a href="{{ route('admin.projects.create') }}" class="admin-header-menu-item" @click="quickOpen = false">
                        <i class="fa-solid fa-building w-4 text-center text-brand-deep" aria-hidden="true"></i>
                        New project
                    </a>
                </div>
            </div>

            {{-- Notifications --}}
            <div class="relative">
                <button
                    type="button"
                    class="admin-header-icon-btn"
                    @click="notificationsOpen = !notificationsOpen; userMenuOpen = false; quickOpen = false"
                    :aria-expanded="notificationsOpen.toString()"
                    aria-label="Notifications"
                >
                    <i class="fa-solid fa-bell" aria-hidden="true"></i>
                    @if ($attentionCount > 0)
                        <span class="admin-header-badge">{{ $attentionCount > 9 ? '9+' : $attentionCount }}</span>
                    @endif
                </button>

                <div
                    x-cloak
                    x-show="notificationsOpen"
                    x-transition.origin.top.right
                    @click.outside="notificationsOpen = false"
                    class="admin-header-menu right-0 w-[19rem] sm:w-80"
                >
                    <div class="flex items-center justify-between border-b border-line px-4 py-3">
                        <div>
                            <p class="text-sm font-bold">Inbox alerts</p>
                            <p class="text-xs text-steel">{{ $attentionCount }} item{{ $attentionCount === 1 ? '' : 's' }} need attention</p>
                        </div>
                        @if ($attentionCount > 0)
                            <span class="admin-badge admin-badge-new">Live</span>
                        @endif
                    </div>

                    <a href="{{ route('admin.quotes.index', ['status' => 'new']) }}" class="admin-header-alert" @click="notificationsOpen = false">
                        <span class="admin-header-alert-icon bg-brand/20 text-brand-deep">
                            <i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-semibold">New quotations</span>
                            <span class="block text-xs text-steel">Review inbound RFQs</span>
                        </span>
                        <span class="font-display text-lg font-bold">{{ number_format($alerts['new_quotes']) }}</span>
                    </a>

                    <a href="{{ route('admin.messages.index', ['status' => 'new']) }}" class="admin-header-alert" @click="notificationsOpen = false">
                        <span class="admin-header-alert-icon bg-mist text-ink">
                            <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-semibold">Contact messages</span>
                            <span class="block text-xs text-steel">Clear the sales desk queue</span>
                        </span>
                        <span class="font-display text-lg font-bold">{{ number_format($alerts['new_messages']) }}</span>
                    </a>

                    <a href="{{ route('admin.applications.index', ['status' => 'received']) }}" class="admin-header-alert" @click="notificationsOpen = false">
                        <span class="admin-header-alert-icon bg-mist text-ink">
                            <i class="fa-solid fa-id-card" aria-hidden="true"></i>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-semibold">Job applications</span>
                            <span class="block text-xs text-steel">Screen new candidates</span>
                        </span>
                        <span class="font-display text-lg font-bold">{{ number_format($alerts['new_applications']) }}</span>
                    </a>

                    <div class="border-t border-line p-2">
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-center text-xs font-semibold uppercase tracking-wide text-brand-deep hover:bg-mist" @click="notificationsOpen = false">
                            Open operations dashboard
                        </a>
                    </div>
                </div>
            </div>

            <a
                href="{{ route('home') }}"
                target="_blank"
                rel="noopener"
                class="admin-header-btn hidden sm:inline-flex"
                title="Open public website"
            >
                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                <span class="hidden lg:inline">Website</span>
            </a>

            <div class="mx-1 hidden h-8 w-px bg-line sm:block" aria-hidden="true"></div>

            {{-- User menu --}}
            <div class="relative">
                <button
                    type="button"
                    class="flex items-center gap-2.5 border border-transparent py-1 pl-1 pr-2 transition hover:border-line hover:bg-mist sm:pr-2.5"
                    @click="userMenuOpen = !userMenuOpen; notificationsOpen = false; quickOpen = false"
                    :aria-expanded="userMenuOpen.toString()"
                >
                    <span class="inline-flex h-10 w-10 items-center justify-center bg-ink font-display text-sm font-bold text-brand">
                        {{ $initials }}
                    </span>
                    <span class="hidden min-w-0 text-left md:block">
                        <span class="block max-w-[9rem] truncate text-sm font-semibold leading-tight">{{ $user?->name }}</span>
                        <span class="block text-[11px] capitalize leading-tight text-steel">{{ $role }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-down hidden text-[10px] text-steel md:inline" aria-hidden="true"></i>
                </button>

                <div
                    x-cloak
                    x-show="userMenuOpen"
                    x-transition.origin.top.right
                    @click.outside="userMenuOpen = false"
                    class="admin-header-menu right-0 w-64"
                >
                    <div class="border-b border-line px-4 py-3">
                        <p class="truncate text-sm font-bold">{{ $user?->name }}</p>
                        <p class="truncate text-xs text-steel">{{ $user?->email }}</p>
                        <span class="admin-badge admin-badge-progress mt-2 capitalize">{{ $role }}</span>
                    </div>
                    <a href="{{ route('admin.settings.edit') }}" class="admin-header-menu-item" @click="userMenuOpen = false">
                        <i class="fa-solid fa-gear w-4 text-center text-steel" aria-hidden="true"></i>
                        Workspace settings
                    </a>
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="admin-header-menu-item sm:hidden" @click="userMenuOpen = false">
                        <i class="fa-solid fa-globe w-4 text-center text-steel" aria-hidden="true"></i>
                        View website
                    </a>
                    <div class="border-t border-line p-2">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-sm px-3 py-2.5 text-left text-sm font-semibold text-red-700 transition hover:bg-red-50">
                                <i class="fa-solid fa-right-from-bracket w-4 text-center" aria-hidden="true"></i>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="h-0.5 w-full bg-gradient-to-r from-brand via-brand/40 to-transparent" aria-hidden="true"></div>
</header>
