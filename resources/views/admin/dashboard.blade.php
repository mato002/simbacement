@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Overview</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Admin Dashboard</h1>
        <p class="mt-2 text-steel">Live operational snapshot across sales, content and HR.</p>
    </div>

    @if (($alerts['new_quotes'] + $alerts['new_messages'] + $alerts['new_applications']) > 0)
        <div class="mb-6 grid gap-3 md:grid-cols-3">
            <a href="{{ route('admin.quotes.index', ['status' => 'new']) }}" class="border border-brand/40 bg-brand/10 px-4 py-3 text-sm font-semibold">
                {{ $alerts['new_quotes'] }} new quotation request{{ $alerts['new_quotes'] === 1 ? '' : 's' }}
            </a>
            <a href="{{ route('admin.messages.index', ['status' => 'new']) }}" class="border border-brand/40 bg-brand/10 px-4 py-3 text-sm font-semibold">
                {{ $alerts['new_messages'] }} new contact message{{ $alerts['new_messages'] === 1 ? '' : 's' }}
            </a>
            <a href="{{ route('admin.applications.index', ['status' => 'received']) }}" class="border border-brand/40 bg-brand/10 px-4 py-3 text-sm font-semibold">
                {{ $alerts['new_applications'] }} new job application{{ $alerts['new_applications'] === 1 ? '' : 's' }}
            </a>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ([
            ['label' => 'Products', 'value' => $stats['products']],
            ['label' => 'Orders', 'value' => $stats['orders']],
            ['label' => 'Quote Requests', 'value' => $stats['quotes']],
            ['label' => 'Contact Messages', 'value' => $stats['messages']],
            ['label' => 'Job Applications', 'value' => $stats['applications']],
            ['label' => 'News Articles', 'value' => $stats['articles']],
        ] as $card)
            <div class="border border-line bg-white p-5">
                <p class="text-sm text-steel">{{ $card['label'] }}</p>
                <p class="mt-2 font-display text-4xl font-bold">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-3">
        <div class="border border-line bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-display text-xl font-bold uppercase tracking-wide">Recent Quotes</h2>
                <a href="{{ route('admin.quotes.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">View all</a>
            </div>
            <ul class="space-y-3 text-sm">
                @forelse ($recentQuotes as $quote)
                    <li class="flex items-start justify-between gap-3 border-b border-line/60 pb-3">
                        <div>
                            <a href="{{ route('admin.quotes.show', $quote) }}" class="font-semibold hover:underline">{{ $quote->reference }}</a>
                            <p class="text-xs text-steel">{{ $quote->name }} · {{ $quote->status->label() }}</p>
                        </div>
                        <span class="text-xs text-steel">{{ $quote->created_at?->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-steel">No quotation requests yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="border border-line bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-display text-xl font-bold uppercase tracking-wide">Recent Messages</h2>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">View all</a>
            </div>
            <ul class="space-y-3 text-sm">
                @forelse ($recentMessages as $message)
                    <li class="flex items-start justify-between gap-3 border-b border-line/60 pb-3">
                        <div>
                            <a href="{{ route('admin.messages.show', $message) }}" class="font-semibold hover:underline">{{ $message->subject }}</a>
                            <p class="text-xs text-steel">{{ $message->name }} · {{ $message->status->label() }}</p>
                        </div>
                        <span class="text-xs text-steel">{{ $message->created_at?->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-steel">No contact messages yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="border border-line bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-display text-xl font-bold uppercase tracking-wide">Recent Applications</h2>
                <a href="{{ route('admin.applications.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">View all</a>
            </div>
            <ul class="space-y-3 text-sm">
                @forelse ($recentApplications as $application)
                    <li class="flex items-start justify-between gap-3 border-b border-line/60 pb-3">
                        <div>
                            <a href="{{ route('admin.applications.show', $application) }}" class="font-semibold hover:underline">{{ $application->full_name }}</a>
                            <p class="text-xs text-steel">{{ $application->jobListing?->title ?: $application->position }} · {{ $application->status->label() }}</p>
                        </div>
                        <span class="text-xs text-steel">{{ $application->created_at?->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-steel">No applications yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
