@props([
    'action',
    'label' => 'Delete',
    'title' => 'Delete this item?',
    'text' => 'This action cannot be undone.',
    'confirmText' => 'Yes, delete',
])

<form
    method="POST"
    action="{{ $action }}"
    {{ $attributes->class('inline') }}
    data-swal-confirm
    data-swal-title="{{ $title }}"
    data-swal-text="{{ $text }}"
    data-swal-confirm-text="{{ $confirmText }}"
    data-swal-danger="1"
>
    @csrf
    @method('DELETE')
    <button type="submit" class="text-sm font-semibold text-red-700 hover:underline">
        {{ $label }}
    </button>
</form>
