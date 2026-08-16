@props([
    'count' => 0,
])

<button
    type="button"
    @click="filtersOpen = true"
    :aria-expanded="filtersOpen.toString()"
    aria-haspopup="dialog"
    {{ $attributes->merge([
        'class' => 'relative inline-flex shrink-0 items-center justify-center gap-2 border border-line bg-white px-3 py-2.5 text-sm font-semibold uppercase tracking-wide text-ink',
    ]) }}
>
    <i class="fa-solid fa-sliders" aria-hidden="true"></i>
    <span>Filter</span>
    @if ((int) $count > 0)
        <span class="flex h-5 min-w-5 items-center justify-center bg-brand px-1 text-[11px] font-bold text-ink">{{ $count }}</span>
    @endif
</button>
