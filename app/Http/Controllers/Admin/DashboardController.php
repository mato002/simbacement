<?php

namespace App\Http\Controllers\Admin;

use App\Enums\JobApplicationStatus;
use App\Enums\MessageStatus;
use App\Enums\QuoteStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\NewsArticle;
use App\Models\Product;
use App\Models\Project;
use App\Models\QuoteRequest;
use App\Models\Solution;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $now = now();
        $weekStart = $now->copy()->subDays(6)->startOfDay();
        $prevWeekStart = $now->copy()->subDays(13)->startOfDay();
        $prevWeekEnd = $now->copy()->subDays(7)->endOfDay();

        $alerts = [
            'new_quotes' => QuoteRequest::query()->where('status', QuoteStatus::New)->count(),
            'under_review_quotes' => QuoteRequest::query()->where('status', QuoteStatus::UnderReview)->count(),
            'new_messages' => ContactMessage::query()->where('status', MessageStatus::New)->count(),
            'new_applications' => JobApplication::query()->where('status', JobApplicationStatus::Received)->count(),
        ];

        $attentionCount = $alerts['new_quotes'] + $alerts['new_messages'] + $alerts['new_applications'];

        $quotePipeline = collect(QuoteStatus::cases())->mapWithKeys(function (QuoteStatus $status) {
            return [$status->value => QuoteRequest::query()->where('status', $status)->count()];
        });

        $quotesThisWeek = QuoteRequest::query()->where('created_at', '>=', $weekStart)->count();
        $quotesPrevWeek = QuoteRequest::query()
            ->whereBetween('created_at', [$prevWeekStart, $prevWeekEnd])
            ->count();

        $messagesThisWeek = ContactMessage::query()->where('created_at', '>=', $weekStart)->count();
        $applicationsThisWeek = JobApplication::query()->where('created_at', '>=', $weekStart)->count();

        return view('admin.dashboard', [
            'greeting' => $this->greeting($now),
            'todayLabel' => $now->format('l, d F Y'),
            'attentionCount' => $attentionCount,
            'alerts' => $alerts,
            'kpis' => [
                [
                    'label' => 'Open quotes',
                    'value' => $alerts['new_quotes'] + $alerts['under_review_quotes'],
                    'meta' => $alerts['new_quotes'].' new · '.$alerts['under_review_quotes'].' in review',
                    'delta' => $this->deltaLabel($quotesThisWeek, $quotesPrevWeek),
                    'delta_up' => $quotesThisWeek >= $quotesPrevWeek,
                    'icon' => 'fa-solid fa-file-invoice-dollar',
                    'href' => route('admin.quotes.index', ['status' => 'new']),
                    'accent' => 'brand',
                ],
                [
                    'label' => 'Unread messages',
                    'value' => $alerts['new_messages'],
                    'meta' => $messagesThisWeek.' received this week',
                    'delta' => null,
                    'delta_up' => true,
                    'icon' => 'fa-solid fa-envelope-open-text',
                    'href' => route('admin.messages.index', ['status' => 'new']),
                    'accent' => 'ink',
                ],
                [
                    'label' => 'Applications queue',
                    'value' => $alerts['new_applications'],
                    'meta' => $applicationsThisWeek.' received this week',
                    'delta' => null,
                    'delta_up' => true,
                    'icon' => 'fa-solid fa-id-card',
                    'href' => route('admin.applications.index', ['status' => 'received']),
                    'accent' => 'ink',
                ],
                [
                    'label' => 'Published products',
                    'value' => Product::query()->published()->count(),
                    'meta' => Product::query()->count().' total in catalogue',
                    'delta' => null,
                    'delta_up' => true,
                    'icon' => 'fa-solid fa-box-open',
                    'href' => route('admin.products.index'),
                    'accent' => 'ink',
                ],
            ],
            'inventory' => [
                ['label' => 'Live projects', 'value' => Project::query()->published()->count(), 'href' => route('admin.projects.index')],
                ['label' => 'Active solutions', 'value' => Solution::query()->where('is_active', true)->count(), 'href' => route('admin.solutions.index')],
                ['label' => 'Published news', 'value' => NewsArticle::query()->published()->count(), 'href' => route('admin.news.index')],
                ['label' => 'Open job roles', 'value' => JobListing::query()->open()->count(), 'href' => route('admin.jobs.index')],
                ['label' => 'All quotes', 'value' => QuoteRequest::query()->count(), 'href' => route('admin.quotes.index')],
                ['label' => 'All messages', 'value' => ContactMessage::query()->count(), 'href' => route('admin.messages.index')],
            ],
            'quotePipeline' => $quotePipeline,
            'quotesThisWeek' => $quotesThisWeek,
            'recentQuotes' => QuoteRequest::query()->latest()->limit(6)->get(),
            'recentMessages' => ContactMessage::query()->latest()->limit(6)->get(),
            'recentApplications' => JobApplication::query()->with('jobListing')->latest()->limit(6)->get(),
        ]);
    }

    private function greeting(Carbon $now): string
    {
        $hour = (int) $now->format('G');

        return match (true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default => 'Good evening',
        };
    }

    private function deltaLabel(int $current, int $previous): string
    {
        if ($previous === 0 && $current === 0) {
            return 'Flat vs prior week';
        }

        if ($previous === 0) {
            return '+'.$current.' vs prior week';
        }

        $change = (int) round((($current - $previous) / max($previous, 1)) * 100);

        return ($change >= 0 ? '+' : '').$change.'% vs prior week';
    }
}
