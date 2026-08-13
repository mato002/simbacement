<?php

namespace App\Http\Controllers;

use App\Enums\CustomerType;
use App\Enums\QuoteStatus;
use App\Models\Product;
use App\Models\QuoteRequest;
use App\Services\LeadMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function create(Request $request): View
    {
        return view('public.quote.create', [
            'products' => Product::query()->published()->orderBy('name')->get(['id', 'name', 'unit']),
            'customerTypes' => CustomerType::cases(),
            'selectedProductId' => $request->integer('product'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_type' => ['required', Rule::enum(CustomerType::class)],
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:160'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:160'],
            'delivery_location' => ['nullable', 'string', 'max:255'],
            'preferred_delivery_date' => ['nullable', 'date', 'after_or_equal:today'],
            'additional_requirements' => ['nullable', 'string', 'max:5000'],
            'product_id' => ['nullable', 'exists:products,id'],
            'product_name' => ['nullable', 'string', 'max:160'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'string', 'max:40'],
        ]);

        $product = ! empty($data['product_id'])
            ? Product::query()->find($data['product_id'])
            : null;

        $productName = $product?->name ?: ($data['product_name'] ?? null);

        if (! $productName) {
            return back()
                ->withInput()
                ->withErrors(['product_id' => 'Please select a product or enter a product name.']);
        }

        $quote = DB::transaction(function () use ($request, $data, $product, $productName) {
            $quote = QuoteRequest::query()->create([
                'reference' => QuoteRequest::generateReference(),
                'customer_type' => $data['customer_type'],
                'name' => $data['name'],
                'company' => $data['company'] ?? null,
                'phone' => $data['phone'],
                'email' => $data['email'],
                'delivery_location' => $data['delivery_location'] ?? null,
                'preferred_delivery_date' => $data['preferred_delivery_date'] ?? null,
                'additional_requirements' => $data['additional_requirements'] ?? null,
                'status' => QuoteStatus::New,
                'source' => 'website',
                'ip_address' => $request->ip(),
            ]);

            $quote->items()->create([
                'product_id' => $product?->id,
                'product_name' => $productName,
                'quantity' => $data['quantity'],
                'unit' => $data['unit'],
            ]);

            return $quote;
        });

        app(LeadMailer::class)->quoteSubmitted($quote);

        return redirect()
            ->route('quote.thanks', $quote)
            ->with('success', 'Your quotation request has been received.');
    }

    public function thanks(QuoteRequest $quote): View
    {
        return view('public.quote.thanks', compact('quote'));
    }
}
