@php
    $nav = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About Us', 'route' => 'about'],
        ['label' => 'Products', 'route' => 'products.index'],
        ['label' => 'Solutions', 'route' => 'solutions.index'],
        ['label' => 'Projects', 'route' => 'projects.index'],
        ['label' => 'Quality', 'route' => 'quality'],
        ['label' => 'Sustainability', 'route' => 'sustainability'],
        ['label' => 'News & Media', 'route' => 'news.index'],
        ['label' => 'Careers', 'route' => 'careers.index'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<header
    x-data="{ open: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 12)"
    x-effect="document.body.classList.toggle('overflow-hidden', open)"
    class="sticky top-0 z-50 border-b border-line/70 transition duration-300"
    :class="scrolled ? 'bg-white/95 backdrop-blur shadow-sm' : 'bg-mist/95 backdrop-blur'"
>
    <div class="container-page">
        <div class="flex h-16 items-center justify-between gap-4 lg:h-20">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center bg-ink text-brand font-display text-xl font-bold">SC</span>
                <span class="leading-tight">
                    <span class="block font-display text-xl font-bold tracking-wide uppercase text-ink">Simba Cement</span>
                    <span class="hidden text-xs tracking-[0.18em] text-steel uppercase sm:block">Building Kenya</span>
                </span>
            </a>

            <nav class="hidden items-center gap-5 xl:flex" aria-label="Primary">
                @foreach ($nav as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="nav-link {{ request()->routeIs($item['route']) ? 'nav-link-active' : '' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('quote.create') }}" class="btn-primary !px-3 !py-2.5 !text-xs">
                    <i class="fa-solid fa-file-invoice" aria-hidden="true"></i>
                    <span class="hidden sm:inline">Get a Quote</span>
                </a>

                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center border border-line bg-white text-ink xl:hidden"
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-controls="mobile-nav"
                    aria-label="Toggle menu"
                >
                    <i x-show="!open" class="fa-solid fa-bars" aria-hidden="true"></i>
                    <i x-cloak x-show="open" class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>

    <div
        id="mobile-nav"
        x-cloak
        x-show="open"
        x-transition.opacity
        class="border-t border-line bg-white xl:hidden"
    >
        <nav class="container-page flex flex-col gap-1 py-4" aria-label="Mobile">
            @foreach ($nav as $item)
                <a
                    href="{{ route($item['route']) }}"
                    class="rounded-sm px-3 py-2.5 text-sm font-semibold {{ request()->routeIs($item['route']) ? 'bg-concrete text-ink' : 'text-ink/80 hover:bg-mist' }}"
                    @click="open = false"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
            <a href="{{ route('quote.create') }}" class="btn-primary mt-2" @click="open = false">
                <i class="fa-solid fa-file-invoice" aria-hidden="true"></i>
                Get a Quote
            </a>
        </nav>
    </div>
</header>
