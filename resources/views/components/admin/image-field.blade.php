@props([
    'name',
    'label' => null,
    'multiple' => false,
    'existingUrl' => null,
    'existingAlt' => '',
    'hint' => null,
    'aspect' => 'video',
    'accept' => 'image/jpeg,image/png,image/webp,image/gif',
    'inputClass' => 'mt-4 w-full border border-line bg-mist px-3 py-2 text-sm',
])

@php
    $aspectClass = $aspect === 'square' ? 'aspect-square' : 'aspect-video';
@endphp

<div
    x-data="{
        previews: [],
        onChange(event) {
            this.previews.forEach((url) => URL.revokeObjectURL(url));
            this.previews = Array.from(event.target.files || [])
                .filter((file) => file.type.startsWith('image/'))
                .map((file) => URL.createObjectURL(file));
        },
    }"
    {{ $attributes }}
>
    @if ($label)
        <h2 class="font-display text-2xl font-bold uppercase tracking-wide">{{ $label }}</h2>
    @endif

    @if ($hint)
        <p class="mt-2 text-xs text-steel">{{ $hint }}</p>
    @endif

    @if ($existingUrl && ! $multiple)
        <div x-show="previews.length === 0" class="mt-4 overflow-hidden border border-line bg-mist">
            <img src="{{ $existingUrl }}" alt="{{ $existingAlt }}" class="{{ $aspectClass }} w-full object-cover">
        </div>
    @endif

    {{ $slot }}

    @if ($multiple)
        <div x-show="previews.length > 0" x-cloak class="mt-4 grid grid-cols-2 gap-3">
            <template x-for="(src, index) in previews" :key="src">
                <div class="overflow-hidden border border-line bg-mist">
                    <img :src="src" :alt="'Selected image ' + (index + 1)" class="aspect-square w-full object-cover">
                </div>
            </template>
        </div>
    @else
        <div x-show="previews.length > 0" x-cloak class="mt-4 overflow-hidden border border-line bg-mist">
            <img :src="previews[0]" alt="Selected preview" class="{{ $aspectClass }} w-full object-cover">
        </div>
    @endif

    <input
        type="file"
        name="{{ $name }}"
        accept="{{ $accept }}"
        @if ($multiple) multiple @endif
        @change="onChange"
        class="{{ $inputClass }}"
    >
</div>
