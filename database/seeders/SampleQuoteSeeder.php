<?php

namespace Database\Seeders;

use App\Enums\CustomerType;
use App\Enums\QuoteStatus;
use App\Models\Product;
use App\Models\QuoteRequest;
use Illuminate\Database\Seeder;

class SampleQuoteSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::query()->first();

        if (! $product) {
            return;
        }

        $quote = QuoteRequest::query()->firstOrCreate(
            ['reference' => 'QT-2026-000001'],
            [
                'customer_type' => CustomerType::Contractor,
                'name' => 'Test Contractor',
                'company' => 'BuildCo Kenya',
                'phone' => '0700000000',
                'email' => 'contractor@example.com',
                'delivery_location' => 'Nairobi',
                'status' => QuoteStatus::New,
                'source' => 'website',
                'additional_requirements' => 'Need delivery within 7 days.',
            ]
        );

        if ($quote->items()->doesntExist()) {
            $quote->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => 100,
                'unit' => $product->unit,
            ]);
        }
    }
}
