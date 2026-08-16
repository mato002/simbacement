@props([
    'title' => 'Filters',
])

<div
    x-cloak
    x-show="filtersOpen"
    x-effect="document.body.classList.toggle('overflow-hidden', filtersOpen)"
    class="fixed inset-0 z-[70] lg:hidden"
    role="dialog"
    aria-modal="true"
    :aria-hidden="(!filtersOpen).toString()"
    @keydown.escape.window="filtersOpen = false"
>
    <div
        class="absolute inset-0 bg-ink/50"
        x-show="filtersOpen"
        x-transition.opacity.duration.200ms
        @click="filtersOpen = false"
    ></div>

    <div
        x-show="filtersOpen"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="absolute inset-x-0 bottom-0 flex max-h-[88vh] flex-col rounded-t-2xl bg-white shadow-2xl"
        @click.stop
    >
        <div class="relative flex shrink-0 items-center justify-between border-b border-line px-5 pt-5 pb-4">
            <span class="absolute left-1/2 top-2 h-1 w-10 -translate-x-1/2 rounded-full bg-line" aria-hidden="true"></span>
            <h2 class="font-display text-xl font-bold uppercase tracking-wide">{{ $title }}</h2>
            <button
                type="button"
                class="inline-flex h-9 w-9 items-center justify-center border border-line text-ink"
                @click="filtersOpen = false"
                aria-label="Close filters"
            >
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>

        <div class="overflow-y-auto px-5 py-5">
            {{ $slot }}
        </div>
    </div>
</div>
