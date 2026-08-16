@php
    $navGroups = [
        [
            'label' => 'Overview',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'ready' => true, 'icon' => 'fa-solid fa-gauge-high'],
            ],
        ],
        [
            'label' => 'Catalogue',
            'items' => [
                ['label' => 'Products', 'route' => 'admin.products.index', 'match' => 'admin.products.*', 'ready' => true, 'icon' => 'fa-solid fa-box-open'],
                ['label' => 'Categories', 'route' => 'admin.categories.index', 'match' => 'admin.categories.*', 'ready' => true, 'icon' => 'fa-solid fa-tags'],
                ['label' => 'Media', 'route' => 'admin.media.index', 'match' => 'admin.media.*', 'ready' => true, 'icon' => 'fa-solid fa-images'],
            ],
        ],
        [
            'label' => 'Commercial',
            'items' => [
                ['label' => 'Quotations', 'route' => 'admin.quotes.index', 'match' => 'admin.quotes.*', 'ready' => true, 'icon' => 'fa-solid fa-file-invoice-dollar'],
                ['label' => 'Messages', 'route' => 'admin.messages.index', 'match' => 'admin.messages.*', 'ready' => true, 'icon' => 'fa-solid fa-envelope'],
                ['label' => 'Orders', 'route' => null, 'match' => null, 'ready' => false, 'icon' => 'fa-solid fa-cart-shopping'],
                ['label' => 'Customers', 'route' => null, 'match' => null, 'ready' => false, 'icon' => 'fa-solid fa-users'],
            ],
        ],
        [
            'label' => 'Content',
            'items' => [
                ['label' => 'Solutions', 'route' => 'admin.solutions.index', 'match' => 'admin.solutions.*', 'ready' => true, 'icon' => 'fa-solid fa-diagram-project'],
                ['label' => 'Projects', 'route' => 'admin.projects.index', 'match' => 'admin.projects.*', 'ready' => true, 'icon' => 'fa-solid fa-building'],
                ['label' => 'News', 'route' => 'admin.news.index', 'match' => 'admin.news.*', 'ready' => true, 'icon' => 'fa-solid fa-newspaper'],
                ['label' => 'Pages', 'route' => 'admin.pages.index', 'match' => 'admin.pages.*', 'ready' => true, 'icon' => 'fa-solid fa-file-lines'],
            ],
        ],
        [
            'label' => 'People',
            'items' => [
                ['label' => 'Jobs', 'route' => 'admin.jobs.index', 'match' => 'admin.jobs.*', 'ready' => true, 'icon' => 'fa-solid fa-briefcase'],
                ['label' => 'Applications', 'route' => 'admin.applications.index', 'match' => 'admin.applications.*', 'ready' => true, 'icon' => 'fa-solid fa-id-card'],
            ],
        ],
        [
            'label' => 'System',
            'items' => [
                ['label' => 'Locations', 'route' => 'admin.locations.index', 'match' => 'admin.locations.*', 'ready' => true, 'icon' => 'fa-solid fa-location-dot'],
                ['label' => 'Settings', 'route' => 'admin.settings.edit', 'match' => 'admin.settings.*', 'ready' => true, 'icon' => 'fa-solid fa-gear'],
            ],
        ],
    ];
@endphp

<div
    x-cloak
    x-show="sidebarOpen"
    class="fixed inset-0 z-40 bg-ink/50 lg:hidden"
    @click="sidebarOpen = false"
></div>

<aside
    class="fixed inset-y-0 left-0 z-50 flex h-dvh w-72 flex-col bg-ink text-white transition-all duration-300"
    :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        sidebarCollapsed ? 'lg:w-[4.75rem]' : 'lg:w-72'
    ]"
>
    <div class="flex h-[4.5rem] shrink-0 items-center gap-3 border-b border-white/10 px-3 sm:px-4">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center bg-brand text-ink font-display text-lg font-bold">SC</span>
        <div class="min-w-0 flex-1 overflow-hidden" :class="sidebarCollapsed ? 'lg:hidden' : ''">
            <p class="truncate font-display text-lg font-bold tracking-wide uppercase">Simba Cement</p>
            <p class="truncate text-[11px] tracking-[0.16em] text-white/50 uppercase">Enterprise CMS</p>
        </div>
        <button
            type="button"
            class="ml-auto hidden h-8 w-8 shrink-0 items-center justify-center border border-white/15 text-white/70 transition hover:border-brand hover:text-brand lg:inline-flex"
            @click="toggleSidebarCollapsed()"
            :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
            :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
        >
            <i class="fa-solid text-xs" :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'" aria-hidden="true"></i>
        </button>
        <button
            type="button"
            class="ml-auto inline-flex h-8 w-8 items-center justify-center border border-white/15 text-white/70 lg:hidden"
            @click="sidebarOpen = false"
            aria-label="Close sidebar"
        >
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    <nav class="admin-sidebar-scroll min-h-0 flex-1 space-y-5 overflow-y-auto overscroll-contain px-2 py-4 sm:px-3">
        @foreach ($navGroups as $group)
            <div>
                <p
                    class="mb-1.5 px-3 text-[10px] font-semibold tracking-[0.18em] text-white/35 uppercase"
                    :class="sidebarCollapsed ? 'lg:hidden' : ''"
                >
                    {{ $group['label'] }}
                </p>
                <div class="space-y-0.5">
                    @foreach ($group['items'] as $link)
                        @if ($link['ready'] && $link['route'])
                            <a
                                href="{{ route($link['route']) }}"
                                class="flex items-center gap-3 rounded-sm px-3 py-2.5 text-sm font-medium {{ request()->routeIs($link['match']) ? 'bg-brand text-ink' : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''"
                                title="{{ $link['label'] }}"
                                @click="sidebarOpen = false"
                            >
                                <i class="{{ $link['icon'] }} w-4 shrink-0 text-center" aria-hidden="true"></i>
                                <span :class="sidebarCollapsed ? 'lg:hidden' : ''">{{ $link['label'] }}</span>
                            </a>
                        @else
                            <span
                                class="flex items-center justify-between rounded-sm px-3 py-2.5 text-sm text-white/30"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''"
                                title="{{ $link['label'] }} (Soon)"
                            >
                                <span class="flex items-center gap-3">
                                    <i class="{{ $link['icon'] }} w-4 shrink-0 text-center" aria-hidden="true"></i>
                                    <span :class="sidebarCollapsed ? 'lg:hidden' : ''">{{ $link['label'] }}</span>
                                </span>
                                <span class="text-[10px] tracking-wide uppercase" :class="sidebarCollapsed ? 'lg:hidden' : ''">Soon</span>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="shrink-0 border-t border-white/10 p-3" :class="sidebarCollapsed ? 'lg:hidden' : ''">
        <div class="rounded-sm border border-white/10 bg-white/5 p-3">
            <p class="text-[11px] font-semibold tracking-wide text-brand uppercase">Workspace</p>
            <p class="mt-1 text-xs text-white/65">Quotes-only commerce · content publishing · careers intake</p>
        </div>
    </div>

    <div class="shrink-0 border-t border-white/10 p-2 lg:hidden">
        <button
            type="button"
            class="flex w-full items-center justify-center gap-2 border border-white/15 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-white/70"
            @click="sidebarOpen = false"
        >
            <i class="fa-solid fa-angles-left" aria-hidden="true"></i>
            Hide menu
        </button>
    </div>
</aside>
