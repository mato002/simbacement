<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QuoteStatus;
use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function index(Request $request): View
    {
        $quotes = QuoteRequest::query()
            ->withCount('items')
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
            ->when($request->string('q')->toString(), function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('reference', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.quotes.index', [
            'quotes' => $quotes,
            'statuses' => QuoteStatus::cases(),
        ]);
    }

    public function show(QuoteRequest $quote): View
    {
        $quote->load(['items.product', 'assignee']);

        return view('admin.quotes.show', [
            'quote' => $quote,
            'statuses' => QuoteStatus::cases(),
            'staff' => User::query()
                ->role([
                    'super-admin',
                    'administrator',
                    'sales-manager',
                    'customer-support',
                ])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, QuoteRequest $quote): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(QuoteStatus::class)],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $status = QuoteStatus::from($data['status']);

        $quote->fill([
            'status' => $status,
            'assigned_to' => $data['assigned_to'] ?: null,
            'admin_notes' => $data['admin_notes'] ?? null,
        ]);

        if ($status === QuoteStatus::UnderReview && ! $quote->reviewed_at) {
            $quote->reviewed_at = now();
        }

        if ($status === QuoteStatus::Quoted && ! $quote->quoted_at) {
            $quote->quoted_at = now();
        }

        $quote->save();

        return back()->with('success', "Quote {$quote->reference} updated.");
    }

    public function destroy(QuoteRequest $quote): RedirectResponse
    {
        $reference = $quote->reference;
        $quote->items()->delete();
        $quote->delete();

        return redirect()
            ->route('admin.quotes.index')
            ->with('success', "Quote {$reference} deleted.");
    }
}
