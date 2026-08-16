@extends('layouts.admin')

@section('title', 'Quotations')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Sales</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Quote Inbox</h1>
    </div>

    <form method="GET" class="mb-6 flex flex-col gap-3 border border-line bg-white p-4 sm:flex-row">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search reference, name, company, email" class="w-full border border-line bg-mist px-3 py-2 text-sm">
        <select name="status" class="w-full border border-line bg-mist px-3 py-2 text-sm sm:max-w-xs">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-dark !py-2">Filter</button>
    </form>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Reference</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Items</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Received</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotes as $quote)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3 font-semibold">{{ $quote->reference }}</td>
                        <td class="px-4 py-3">
                            <p>{{ $quote->name }}</p>
                            <p class="text-xs text-steel">{{ $quote->company ?: $quote->email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $quote->customer_type->label() }}</td>
                        <td class="px-4 py-3">{{ $quote->items_count }}</td>
                        <td class="px-4 py-3">{{ $quote->status->label() }}</td>
                        <td class="px-4 py-3 text-steel">{{ $quote->created_at?->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.quotes.show', $quote) }}" class="font-semibold text-brand-deep hover:underline">Open</a>
                                <x-admin.delete-form
                                    :action="route('admin.quotes.destroy', $quote)"
                                    title="Delete this quotation?"
                                    text="{{ $quote->reference }} will be permanently removed."
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-steel">No quotation requests yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $quotes->links() }}
    </div>
@endsection
