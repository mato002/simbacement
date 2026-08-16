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
    x-data="{
        notificationsOpen: false,
        userMenuOpen: false,
        createOpen: false,
        searchOpen: false,
        searchQuery: '',
        searchLoading: false,
        searchResults: [],
        searchIndex: -1,
        searchTimer: null,
        searchUrl: @js(route('admin.search')),
        closeMenus() {
            this.notificationsOpen = false;
            this.userMenuOpen = false;
            this.createOpen = false;
            this.searchOpen = false;
            this.searchIndex = -1;
        },
        groupedResults() {
            const groups = {};
            this.searchResults.forEach((item) => {
                if (! groups[item.group]) {
                    groups[item.group] = [];
                }
                groups[item.group].push(item);
            });
            return groups;
        },
        flatResults() {
            return this.searchResults;
        },
        resultKey(item) {
            return `${item.group}|${item.title}|${item.url}`;
        },
        isActive(item) {
            const current = this.flatResults()[this.searchIndex];
            return current ? this.resultKey(current) === this.resultKey(item) : false;
        },
        async runSearch() {
            const q = this.searchQuery.trim();
            if (q.length < 1) {
                this.searchResults = [];
                this.searchLoading = false;
                this.searchOpen = false;
                return;
            }

            this.searchLoading = true;
            this.searchOpen = true;

            try {
                const response = await fetch(`${this.searchUrl}?q=${encodeURIComponent(q)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const data = await response.json();
                this.searchResults = data.results || [];
                this.searchIndex = this.searchResults.length ? 0 : -1;
            } catch (error) {
                this.searchResults = [];
                this.searchIndex = -1;
            } finally {
                this.searchLoading = false;
            }
        },
        queueSearch() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => this.runSearch(), 220);
        },
        moveSelection(direction) {
            const items = this.flatResults();
            if (! items.length) {
                return;
            }
            if (this.searchIndex < 0) {
                this.searchIndex = 0;
                return;
            }
            this.searchIndex = (this.searchIndex + direction + items.length) % items.length;
        },
        openSelected() {
            const item = this.flatResults()[this.searchIndex];
            if (item?.url) {
                window.location.href = item.url;
            }
        },
        focusSearch() {
            this.$refs.searchInput?.focus();
            this.searchOpen = this.searchQuery.trim().length > 0;
        }
    }"
    @admin-focus-search.window="focusSearch()"
    @keydown.escape.window="closeMenus()"
>
    <div class="flex h-[4.5rem] items-center gap-3 px-4 sm:gap-4 sm:px-6">
        <div class="flex min-w-0 items-center gap-3 lg:w-auto">
            <button
                type="button"
                class="inline-flex h-10 w-10 shrink-0 items-center justify-center border border-line bg-mist text-ink transition hover:border-brand lg:hidden"
                @click="sidebarOpen = true"
                aria-label="Open sidebar"
            >
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>

            <button
                type="button"
                class="hidden h-10 w-10 shrink-0 items-center justify-center border border-line bg-white text-ink transition hover:border-brand hover:bg-mist lg:inline-flex"
                @click="toggleSidebarCollapsed()"
                :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
            >
                <i class="fa-solid" :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'" aria-hidden="true"></i>
            </button>

            <div class="hidden min-w-0 xl:block">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <p class="text-[10px] font-semibold tracking-[0.18em] text-steel uppercase">
                        @hasSection('header_eyebrow')
                            @yield('header_eyebrow')
                        @else
                            Control centre
                        @endif
                    </p>
                    <span class="text-line" aria-hidden="true">/</span>
                    <p class="text-[10px] font-semibold tracking-[0.14em] text-ink/50 uppercase">
                        {{ now()->format('D, d M Y') }}
                    </p>
                </div>
                <h1 class="truncate font-display text-xl font-bold uppercase tracking-wide text-ink sm:text-2xl">
                    @yield('title', 'Admin')
                </h1>
            </div>
        </div>

        {{-- Global search --}}
        <div class="relative min-w-0 flex-1 max-w-2xl">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-steel" aria-hidden="true"></i>
                <input
                    type="search"
                    x-ref="searchInput"
                    x-model="searchQuery"
                    @input="queueSearch()"
                    @focus="searchOpen = searchQuery.trim().length > 0"
                    @keydown.down.prevent="moveSelection(1)"
                    @keydown.up.prevent="moveSelection(-1)"
                    @keydown.enter.prevent="openSelected()"
                    @keydown.escape.stop="searchOpen = false; searchIndex = -1"
                    placeholder="Search modules, actions, products, quotes…"
                    class="admin-global-search-input"
                    autocomplete="off"
                    aria-label="Global search"
                >
                <span class="pointer-events-none absolute top-1/2 right-3 hidden -translate-y-1/2 rounded-sm border border-line bg-mist px-1.5 py-0.5 text-[10px] font-semibold tracking-wide text-steel uppercase sm:inline">
                    Ctrl K
                </span>
            </div>

            <div
                x-cloak
                x-show="searchOpen"
                x-transition.origin.top
                @click.outside="searchOpen = false"
                class="admin-global-search-menu"
            >
                <div class="border-b border-line px-4 py-2.5 text-xs text-steel">
                    <span x-show="searchLoading">Searching…</span>
                    <span x-show="!searchLoading && searchResults.length">
                        <span x-text="searchResults.length"></span> result<span x-text="searchResults.length === 1 ? '' : 's'"></span>
                    </span>
                    <span x-show="!searchLoading && !searchResults.length && searchQuery.trim().length">No matches found</span>
                </div>

                <div class="max-h-[24rem] overflow-y-auto">
                    <template x-for="(items, group) in groupedResults()" :key="group">
                        <div>
                            <p class="admin-header-menu-label" x-text="group"></p>
                            <template x-for="item in items" :key="resultKey(item)">
                                <a
                                    :href="item.url"
                                    class="admin-global-search-item"
                                    :class="isActive(item) ? 'bg-mist' : ''"
                                    @mouseenter="searchIndex = flatResults().findIndex((row) => resultKey(row) === resultKey(item))"
                                    @click="searchOpen = false"
                                >
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center bg-mist text-ink">
                                        <i :class="item.icon" aria-hidden="true"></i>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-semibold" x-text="item.title"></span>
                                        <span class="block truncate text-xs text-steel" x-text="item.subtitle"></span>
                                    </span>
                                    <i class="fa-solid fa-arrow-right text-xs text-steel" aria-hidden="true"></i>
                                </a>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
            {{-- Quick create — always visible in the header --}}
            <div class="flex items-center gap-1 sm:gap-1.5" role="group" aria-label="Quick create">
                <a
                    href="{{ route('admin.products.create') }}"
                    class="admin-header-btn !border-brand !bg-brand hover:!bg-brand-dark"
                    title="New product"
                >
                    <i class="fa-solid fa-box-open" aria-hidden="true"></i>
                    <span class="hidden 2xl:inline">Product</span>
                </a>
                <a
                    href="{{ route('admin.news.create') }}"
                    class="admin-header-btn"
                    title="New article"
                >
                    <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
                    <span class="hidden 2xl:inline">Article</span>
                </a>
                <a
                    href="{{ route('admin.jobs.create') }}"
                    class="admin-header-btn"
                    title="New job listing"
                >
                    <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
                    <span class="hidden 2xl:inline">Job</span>
                </a>
                <a
                    href="{{ route('admin.projects.create') }}"
                    class="admin-header-btn"
                    title="New project"
                >
                    <i class="fa-solid fa-building" aria-hidden="true"></i>
                    <span class="hidden 2xl:inline">Project</span>
                </a>

                <div class="relative">
                    <button
                        type="button"
                        class="admin-header-icon-btn"
                        @click="createOpen = !createOpen; notificationsOpen = false; userMenuOpen = false; searchOpen = false"
                        :aria-expanded="createOpen.toString()"
                        aria-label="More create options"
                        title="More"
                    >
                        <i class="fa-solid fa-ellipsis" aria-hidden="true"></i>
                    </button>

                    <div
                        x-cloak
                        x-show="createOpen"
                        x-transition.origin.top.right
                        @click.outside="createOpen = false"
                        class="admin-header-menu right-0 w-56"
                    >
                        <p class="admin-header-menu-label">More</p>
                        <a href="{{ route('admin.solutions.create') }}" class="admin-header-menu-item" @click="createOpen = false">
                            <i class="fa-solid fa-diagram-project w-4 text-center text-steel" aria-hidden="true"></i>
                            New solution
                        </a>
                        <a href="{{ route('admin.pages.create') }}" class="admin-header-menu-item" @click="createOpen = false">
                            <i class="fa-solid fa-file-lines w-4 text-center text-steel" aria-hidden="true"></i>
                            New page
                        </a>
                        <a href="{{ route('admin.categories.create') }}" class="admin-header-menu-item" @click="createOpen = false">
                            <i class="fa-solid fa-tags w-4 text-center text-steel" aria-hidden="true"></i>
                            New category
                        </a>
                        <a href="{{ route('admin.locations.create') }}" class="admin-header-menu-item" @click="createOpen = false">
                            <i class="fa-solid fa-location-dot w-4 text-center text-steel" aria-hidden="true"></i>
                            New location
                        </a>
                    </div>
                </div>
            </div>

            <div class="relative">
                <button
                    type="button"
                    class="admin-header-icon-btn"
                    @click="notificationsOpen = !notificationsOpen; userMenuOpen = false; createOpen = false; searchOpen = false"
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
                </div>
            </div>

            <div class="relative">
                <button
                    type="button"
                    class="flex items-center gap-2.5 border border-line bg-white py-1 pl-1 pr-2.5 transition hover:border-brand hover:bg-mist"
                    @click="userMenuOpen = !userMenuOpen; notificationsOpen = false; createOpen = false; searchOpen = false"
                    :aria-expanded="userMenuOpen.toString()"
                    aria-label="Open profile menu"
                >
                    <span class="inline-flex h-9 w-9 items-center justify-center bg-ink font-display text-sm font-bold text-brand">
                        {{ $initials }}
                    </span>
                    <span class="hidden min-w-0 text-left sm:block">
                        <span class="block max-w-[8.5rem] truncate text-sm font-semibold leading-tight">{{ $user?->name }}</span>
                        <span class="block text-[11px] capitalize leading-tight text-steel">{{ $role }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-steel" aria-hidden="true"></i>
                </button>

                <div
                    x-cloak
                    x-show="userMenuOpen"
                    x-transition.origin.top.right
                    @click.outside="userMenuOpen = false"
                    class="admin-header-menu right-0 w-72"
                >
                    <div class="border-b border-line px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center bg-ink font-display text-sm font-bold text-brand">
                                {{ $initials }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold">{{ $user?->name }}</p>
                                <p class="truncate text-xs text-steel">{{ $user?->email }}</p>
                                <span class="admin-badge admin-badge-progress mt-1.5 capitalize">{{ $role }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="admin-header-menu-item" @click="userMenuOpen = false">
                            <i class="fa-solid fa-globe w-4 text-center text-steel" aria-hidden="true"></i>
                            View website
                        </a>
                        <a href="{{ route('admin.settings.edit') }}" class="admin-header-menu-item" @click="userMenuOpen = false">
                            <i class="fa-solid fa-gear w-4 text-center text-steel" aria-hidden="true"></i>
                            Workspace settings
                        </a>
                    </div>

                    <div class="border-t border-line p-2">
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
                            <button type="submit" class="flex w-full items-center gap-3 rounded-sm px-3 py-2.5 text-left text-sm font-semibold text-red-700 transition hover:bg-red-50">
                                <i class="fa-solid fa-right-from-bracket w-4 text-center" aria-hidden="true"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-line px-4 py-2 xl:hidden">
        <p class="truncate text-sm font-bold uppercase tracking-wide">@yield('title', 'Admin')</p>
    </div>
    <div class="h-0.5 w-full bg-gradient-to-r from-brand via-brand/40 to-transparent" aria-hidden="true"></div>
</header>
