@php
    $links = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'ready' => true, 'icon' => 'fa-solid fa-gauge-high'],
        ['label' => 'Products', 'route' => 'admin.products.index', 'match' => 'admin.products.*', 'ready' => true, 'icon' => 'fa-solid fa-box-open'],
        ['label' => 'Categories', 'route' => 'admin.categories.index', 'match' => 'admin.categories.*', 'ready' => true, 'icon' => 'fa-solid fa-tags'],
        ['label' => 'Quotations', 'route' => 'admin.quotes.index', 'match' => 'admin.quotes.*', 'ready' => true, 'icon' => 'fa-solid fa-file-invoice-dollar'],
        ['label' => 'Media', 'route' => 'admin.media.index', 'match' => 'admin.media.*', 'ready' => true, 'icon' => 'fa-solid fa-images'],
        ['label' => 'Solutions', 'route' => 'admin.solutions.index', 'match' => 'admin.solutions.*', 'ready' => true, 'icon' => 'fa-solid fa-diagram-project'],
        ['label' => 'Projects', 'route' => 'admin.projects.index', 'match' => 'admin.projects.*', 'ready' => true, 'icon' => 'fa-solid fa-building'],
        ['label' => 'News', 'route' => 'admin.news.index', 'match' => 'admin.news.*', 'ready' => true, 'icon' => 'fa-solid fa-newspaper'],
        ['label' => 'Pages', 'route' => 'admin.pages.index', 'match' => 'admin.pages.*', 'ready' => true, 'icon' => 'fa-solid fa-file-lines'],
        ['label' => 'Jobs', 'route' => 'admin.jobs.index', 'match' => 'admin.jobs.*', 'ready' => true, 'icon' => 'fa-solid fa-briefcase'],
        ['label' => 'Applications', 'route' => 'admin.applications.index', 'match' => 'admin.applications.*', 'ready' => true, 'icon' => 'fa-solid fa-id-card'],
        ['label' => 'Messages', 'route' => 'admin.messages.index', 'match' => 'admin.messages.*', 'ready' => true, 'icon' => 'fa-solid fa-envelope'],
        ['label' => 'Orders', 'route' => null, 'match' => null, 'ready' => false, 'icon' => 'fa-solid fa-cart-shopping'],
        ['label' => 'Customers', 'route' => null, 'match' => null, 'ready' => false, 'icon' => 'fa-solid fa-users'],
        ['label' => 'Locations', 'route' => 'admin.locations.index', 'match' => 'admin.locations.*', 'ready' => true, 'icon' => 'fa-solid fa-location-dot'],
        ['label' => 'Settings', 'route' => 'admin.settings.edit', 'match' => 'admin.settings.*', 'ready' => true, 'icon' => 'fa-solid fa-gear'],
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
    <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
        <span class="flex h-9 w-9 items-center justify-center bg-brand text-ink font-display text-lg font-bold">SC</span>
        <div>
            <p class="font-display text-lg font-bold tracking-wide uppercase">Simba Admin</p>
            <p class="text-[11px] tracking-wider text-white/50 uppercase">CMS Panel</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-3">
        @foreach ($links as $link)
            @if ($link['ready'] && $link['route'])
                <a
                    href="{{ route($link['route']) }}"
                    class="flex items-center gap-3 rounded-sm px-3 py-2.5 text-sm font-medium {{ request()->routeIs($link['match']) ? 'bg-brand text-ink' : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
                >
                    <i class="{{ $link['icon'] }} w-4 text-center" aria-hidden="true"></i>
                    {{ $link['label'] }}
                </a>
            @else
                <span class="flex items-center justify-between rounded-sm px-3 py-2.5 text-sm text-white/35">
                    <span class="flex items-center gap-3">
                        <i class="{{ $link['icon'] }} w-4 text-center" aria-hidden="true"></i>
                        {{ $link['label'] }}
                    </span>
                    <span class="text-[10px] tracking-wide uppercase">Soon</span>
                </span>
            @endif
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4 text-xs text-white/45">
        Phase 9 active — inbound email notifications for leads and careers.
    </div>
</aside>
