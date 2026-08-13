<?php

namespace App\Http\Controllers\Admin;

use App\Enums\JobApplicationStatus;
use App\Enums\MessageStatus;
use App\Enums\QuoteStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\NewsArticle;
use App\Models\Product;
use App\Models\QuoteRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $alerts = [
            'new_quotes' => QuoteRequest::query()->where('status', QuoteStatus::New)->count(),
            'new_messages' => ContactMessage::query()->where('status', MessageStatus::New)->count(),
            'new_applications' => JobApplication::query()->where('status', JobApplicationStatus::Received)->count(),
        ];

        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::query()->count(),
                'orders' => 0,
                'quotes' => QuoteRequest::query()->count(),
                'messages' => ContactMessage::query()->count(),
                'applications' => JobApplication::query()->count(),
                'articles' => NewsArticle::query()->count(),
            ],
            'alerts' => $alerts,
            'recentQuotes' => QuoteRequest::query()->latest()->limit(5)->get(),
            'recentMessages' => ContactMessage::query()->latest()->limit(5)->get(),
            'recentApplications' => JobApplication::query()->with('jobListing')->latest()->limit(5)->get(),
        ]);
    }
}
