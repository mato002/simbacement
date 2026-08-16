@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_eyebrow', 'Operations')

@php
    $quoteStatusBadge = function ($status) {
        return match ($status->value) {
            'new' => 'admin-badge admin-badge-new',
            'under_review', 'quoted' => 'admin-badge admin-badge-progress',
            'accepted' => 'admin-badge admin-badge-success',
            'rejected', 'expired' => 'admin-badge admin-badge-danger',
            default => 'admin-badge admin-badge-muted',
        };
    };

    $messageStatusBadge = function ($status) {
        return match ($status->value) {
            'new' => 'admin-badge admin-badge-new',
            'in_progress' => 'admin-badge admin-badge-progress',
            'resolved' => 'admin-badge admin-badge-success',
            default => 'admin-badge admin-badge-muted',
        };
    };

    $applicationStatusBadge = function ($status) {
        return match ($status->value) {
            'received' => 'admin-badge admin-badge-new',
            'shortlisted' => 'admin-badge admin-badge-progress',
            'hired' => 'admin-badge admin-badge-success',
            'rejected' => 'admin-badge admin-badge-danger',
            default => 'admin-badge admin-badge-muted',
        };
    };

    $pipelineTotal = max(1, $quotePipeline->sum());
@endphp

@section('content')
    <div class="mb-8 flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="section-label mb-2">Operations Centre</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide lg:text-5xl">
                {{ $greeting }}, {{ auth()->user()->name }}
            </h1>
            <p class="mt-2 text-steel">
                {{ $todayLabel }} · Live sales, content and HR snapshot for Simba Cement.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.quotes.index') }}" class="btn-primary !py-2.5 !text-xs">
                <i class="fa-solid fa-inbox" aria-hidden="true"></i>
                Quote inbox
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn-dark !py-2.5 !text-xs">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                Add product
            </a>
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border border-line bg-white px-4 py-2.5 text-xs font-semibold uppercase tracking-wide text-ink hover:border-brand">
                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                View site
            </a>
        </div>
    </div>

    @if ($attentionCount > 0)
        <div class="mb-6 overflow-hidden border border-brand/40 bg-gradient-to-r from-brand/15 via-white to-white">
            <div class="flex flex-col gap-4 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center bg-brand text-ink">
                        <i class="fa-solid fa-bell" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wide">Needs attention</p>
                        <p class="mt-1 text-sm text-steel">
                            {{ $attentionCount }} item{{ $attentionCount === 1 ? '' : 's' }} waiting for action across quotes, messages and careers.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.quotes.index', ['status' => 'new']) }}" class="border border-brand/50 bg-white px-3 py-2 text-xs font-semibold">
                        {{ $alerts['new_quotes'] }} new quotation request{{ $alerts['new_quotes'] === 1 ? '' : 's' }}
                    </a>
                    <a href="{{ route('admin.messages.index', ['status' => 'new']) }}" class="border border-brand/50 bg-white px-3 py-2 text-xs font-semibold">
                        {{ $alerts['new_messages'] }} new contact message{{ $alerts['new_messages'] === 1 ? '' : 's' }}
                    </a>
                    <a href="{{ route('admin.applications.index', ['status' => 'received']) }}" class="border border-brand/50 bg-white px-3 py-2 text-xs font-semibold">
                        {{ $alerts['new_applications'] }} new job application{{ $alerts['new_applications'] === 1 ? '' : 's' }}
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($kpis as $kpi)
            <a href="{{ $kpi['href'] }}" class="admin-kpi group">
                <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full {{ $kpi['accent'] === 'brand' ? 'bg-brand/15' : 'bg-ink/5' }} transition group-hover:scale-110"></div>
                <div class="relative flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-steel uppercase">{{ $kpi['label'] }}</p>
                        <p class="mt-3 font-display text-4xl font-bold leading-none">{{ number_format($kpi['value']) }}</p>
                    </div>
                    <span class="inline-flex h-10 w-10 items-center justify-center {{ $kpi['accent'] === 'brand' ? 'bg-brand text-ink' : 'bg-ink text-white' }}">
                        <i class="{{ $kpi['icon'] }}" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="relative mt-4 flex items-center justify-between gap-2 text-xs">
                    <span class="text-steel">{{ $kpi['meta'] }}</span>
                    @if ($kpi['delta'])
                        <span class="font-semibold {{ $kpi['delta_up'] ? 'text-emerald-700' : 'text-red-600' }}">{{ $kpi['delta'] }}</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.4fr_0.8fr]">
        <div class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <h2 class="font-display text-xl font-bold uppercase tracking-wide">Quote pipeline</h2>
                    <p class="mt-1 text-xs text-steel">{{ $quotesThisWeek }} new request{{ $quotesThisWeek === 1 ? '' : 's' }} in the last 7 days</p>
                </div>
                <a href="{{ route('admin.quotes.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">Manage quotes</a>
            </div>
            <div class="space-y-4 p-5">
                @foreach (\App\Enums\QuoteStatus::cases() as $status)
                    @php
                        $count = (int) ($quotePipeline[$status->value] ?? 0);
                        $pct = round(($count / $pipelineTotal) * 100);
                    @endphp
                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3 text-sm">
                            <span class="font-semibold">{{ $status->label() }}</span>
                            <span class="text-steel">{{ $count }} · {{ $pct }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden bg-mist">
                            <div class="h-full {{ $status === \App\Enums\QuoteStatus::New ? 'bg-brand' : 'bg-ink' }}" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <h2 class="font-display text-xl font-bold uppercase tracking-wide">Catalogue pulse</h2>
                    <p class="mt-1 text-xs text-steel">Public content inventory</p>
                </div>
            </div>
            <div class="divide-y divide-line">
                @foreach ($inventory as $item)
                    <a href="{{ $item['href'] }}" class="flex items-center justify-between gap-3 px-5 py-3.5 text-sm transition hover:bg-mist">
                        <span class="font-medium text-ink">{{ $item['label'] }}</span>
                        <span class="font-display text-2xl font-bold">{{ number_format($item['value']) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Review new quotes', 'desc' => 'Prioritise inbound RFQs', 'href' => route('admin.quotes.index', ['status' => 'new']), 'icon' => 'fa-solid fa-clipboard-check'],
            ['label' => 'Reply to messages', 'desc' => 'Clear the contact queue', 'href' => route('admin.messages.index', ['status' => 'new']), 'icon' => 'fa-solid fa-reply'],
            ['label' => 'Screen applicants', 'desc' => 'Advance careers pipeline', 'href' => route('admin.applications.index', ['status' => 'received']), 'icon' => 'fa-solid fa-user-check'],
            ['label' => 'Update settings', 'desc' => 'Company, SEO and stats', 'href' => route('admin.settings.edit'), 'icon' => 'fa-solid fa-sliders'],
        ] as $action)
            <a href="{{ $action['href'] }}" class="admin-panel flex items-start gap-3 p-4 transition hover:border-brand">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center bg-mist text-ink">
                    <i class="{{ $action['icon'] }}" aria-hidden="true"></i>
                </span>
                <span>
                    <span class="block text-sm font-bold">{{ $action['label'] }}</span>
                    <span class="mt-0.5 block text-xs text-steel">{{ $action['desc'] }}</span>
                </span>
            </a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <section class="admin-panel">
            <div class="admin-panel-header">
                <h2 class="font-display text-xl font-bold uppercase tracking-wide">Recent Quotes</h2>
                <a href="{{ route('admin.quotes.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">View all</a>
            </div>
            <ul class="divide-y divide-line">
                @forelse ($recentQuotes as $quote)
                    <li>
                        <a href="{{ route('admin.quotes.show', $quote) }}" class="flex items-start justify-between gap-3 px-5 py-3.5 transition hover:bg-mist">
                            <div class="min-w-0">
                                <p class="truncate font-semibold">{{ $quote->reference }}</p>
                                <p class="mt-0.5 truncate text-xs text-steel">{{ $quote->name }}@if($quote->company) · {{ $quote->company }}@endif</p>
                                <span class="{{ $quoteStatusBadge($quote->status) }} mt-2">{{ $quote->status->label() }}</span>
                            </div>
                            <span class="shrink-0 text-[11px] text-steel">{{ $quote->created_at?->diffForHumans() }}</span>
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-8 text-sm text-steel">No quotation requests yet.</li>
                @endforelse
            </ul>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-header">
                <h2 class="font-display text-xl font-bold uppercase tracking-wide">Recent Messages</h2>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">View all</a>
            </div>
            <ul class="divide-y divide-line">
                @forelse ($recentMessages as $message)
                    <li>
                        <a href="{{ route('admin.messages.show', $message) }}" class="flex items-start justify-between gap-3 px-5 py-3.5 transition hover:bg-mist">
                            <div class="min-w-0">
                                <p class="truncate font-semibold">{{ $message->subject }}</p>
                                <p class="mt-0.5 truncate text-xs text-steel">{{ $message->name }} · {{ $message->email }}</p>
                                <span class="{{ $messageStatusBadge($message->status) }} mt-2">{{ $message->status->label() }}</span>
                            </div>
                            <span class="shrink-0 text-[11px] text-steel">{{ $message->created_at?->diffForHumans() }}</span>
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-8 text-sm text-steel">No contact messages yet.</li>
                @endforelse
            </ul>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-header">
                <h2 class="font-display text-xl font-bold uppercase tracking-wide">Recent Applications</h2>
                <a href="{{ route('admin.applications.index') }}" class="text-xs font-semibold text-brand-deep hover:underline">View all</a>
            </div>
            <ul class="divide-y divide-line">
                @forelse ($recentApplications as $application)
                    <li>
                        <a href="{{ route('admin.applications.show', $application) }}" class="flex items-start justify-between gap-3 px-5 py-3.5 transition hover:bg-mist">
                            <div class="min-w-0">
                                <p class="truncate font-semibold">{{ $application->full_name }}</p>
                                <p class="mt-0.5 truncate text-xs text-steel">{{ $application->jobListing?->title ?: $application->position }}</p>
                                <span class="{{ $applicationStatusBadge($application->status) }} mt-2">{{ $application->status->label() }}</span>
                            </div>
                            <span class="shrink-0 text-[11px] text-steel">{{ $application->created_at?->diffForHumans() }}</span>
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-8 text-sm text-steel">No applications yet.</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection
