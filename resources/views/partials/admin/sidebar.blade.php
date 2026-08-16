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
    class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-ink text-white transition lg:static lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex h-[4.25rem] items-center gap-3 border-b border-white/10 px-5">
        <span class="flex h-10 w-10 items-center justify-center bg-brand text-ink font-display text-lg font-bold">SC</span>
        <div>
            <p class="font-display text-lg font-bold tracking-wide uppercase">Simba Cement</p>
            <p class="text-[11px] tracking-[0.16em] text-white/50 uppercase">Enterprise CMS</p>
        </div>
    </div>

    <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-4">
        @foreach ($navGroups as $group)
            <div>
                <p class="mb-1.5 px-3 text-[10px] font-semibold tracking-[0.18em] text-white/35 uppercase">{{ $group['label'] }}</p>
                <div class="space-y-0.5">
                    @foreach ($group['items'] as $link)
                        @if ($link['ready'] && $link['route'])
                            <a
                                href="{{ route($link['route']) }}"
                                class="flex items-center gap-3 rounded-sm px-3 py-2.5 text-sm font-medium {{ request()->routeIs($link['match']) ? 'bg-brand text-ink' : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                                @click="sidebarOpen = false"
                            >
                                <i class="{{ $link['icon'] }} w-4 text-center" aria-hidden="true"></i>
                                {{ $link['label'] }}
                            </a>
                        @else
                            <span class="flex items-center justify-between rounded-sm px-3 py-2.5 text-sm text-white/30">
                                <span class="flex items-center gap-3">
                                    <i class="{{ $link['icon'] }} w-4 text-center" aria-hidden="true"></i>
                                    {{ $link['label'] }}
                                </span>
                                <span class="text-[10px] tracking-wide uppercase">Soon</span>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4">
        <div class="rounded-sm border border-white/10 bg-white/5 p-3">
            <p class="text-[11px] font-semibold tracking-wide text-brand uppercase">Workspace</p>
            <p class="mt-1 text-xs text-white/65">Quotes-only commerce · content publishing · careers intake</p>
        </div>
    </div>
</aside>
