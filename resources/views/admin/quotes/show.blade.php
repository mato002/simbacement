@extends('layouts.admin')

@section('title', $quote->reference)

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Quotation</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">{{ $quote->reference }}</h1>
            <p class="mt-2 text-steel">Received {{ $quote->created_at?->format('d M Y \a\t H:i') }}</p>
        </div>
        <a href="{{ route('admin.quotes.index') }}" class="btn-dark">Back to inbox</a>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <div class="border border-line bg-white p-6">
                <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Customer</h2>
                <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-steel">Name</dt><dd class="font-semibold">{{ $quote->name }}</dd></div>
                    <div><dt class="text-steel">Company</dt><dd class="font-semibold">{{ $quote->company ?: '—' }}</dd></div>
                    <div><dt class="text-steel">Phone</dt><dd class="font-semibold">{{ $quote->phone }}</dd></div>
                    <div><dt class="text-steel">Email</dt><dd class="font-semibold">{{ $quote->email }}</dd></div>
                    <div><dt class="text-steel">Customer type</dt><dd class="font-semibold">{{ $quote->customer_type->label() }}</dd></div>
                    <div><dt class="text-steel">Delivery location</dt><dd class="font-semibold">{{ $quote->delivery_location ?: '—' }}</dd></div>
                    <div><dt class="text-steel">Preferred date</dt><dd class="font-semibold">{{ $quote->preferred_delivery_date?->format('d M Y') ?: '—' }}</dd></div>
                    <div><dt class="text-steel">Source</dt><dd class="font-semibold">{{ $quote->source }}</dd></div>
                </dl>
                @if ($quote->additional_requirements)
                    <div class="mt-5">
                        <p class="text-sm text-steel">Additional requirements</p>
                        <p class="mt-1 whitespace-pre-line text-sm">{{ $quote->additional_requirements }}</p>
                    </div>
                @endif
            </div>

            <div class="border border-line bg-white p-6">
                <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Requested items</h2>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-line text-xs uppercase tracking-wide text-steel">
                            <tr>
                                <th class="py-2 pr-4">Product</th>
                                <th class="py-2 pr-4">Qty</th>
                                <th class="py-2">Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quote->items as $item)
                                <tr class="border-b border-line/60">
                                    <td class="py-3 pr-4 font-semibold">{{ $item->product_name }}</td>
                                    <td class="py-3 pr-4">{{ rtrim(rtrim(number_format($item->quantity, 2, '.', ''), '0'), '.') }}</td>
                                    <td class="py-3">{{ $item->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="border border-line bg-white p-6">
            <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Workflow</h2>
            <form method="POST" action="{{ route('admin.quotes.update', $quote) }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Status</label>
                    <select name="status" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $quote->status->value) === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Assigned to</label>
                    <select name="assigned_to" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        <option value="">Unassigned</option>
                        @foreach ($staff as $user)
                            <option value="{{ $user->id }}" @selected(old('assigned_to', $quote->assigned_to) == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Admin notes</label>
                    <textarea name="admin_notes" rows="6" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('admin_notes', $quote->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="btn-primary w-full">Update quote</button>
            </form>

            <div class="mt-6 space-y-2 text-xs text-steel">
                <p>Reviewed: {{ $quote->reviewed_at?->format('d M Y H:i') ?: '—' }}</p>
                <p>Quoted: {{ $quote->quoted_at?->format('d M Y H:i') ?: '—' }}</p>
            </div>
        </div>
    </div>
@endsection
