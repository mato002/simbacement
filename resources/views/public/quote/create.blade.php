@extends('layouts.public')

@section('title', 'Request a Quote — ' . config('app.name'))
@section('meta_description', 'Request a quotation for Simba Cement products. Individuals, contractors, developers, hardware stores and distributors welcome.')

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-14 sm:py-16">
            <p class="section-label mb-3">Sales</p>
            <h1 class="heading-display text-ink !text-5xl">Request a Quote</h1>
            <p class="mt-4 max-w-2xl text-lg text-steel">
                Tell us what you need. Our sales team will respond with a quotation reference.
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="container-page">
            <form method="POST" action="{{ route('quote.store') }}" class="max-w-3xl space-y-5 border border-line bg-white p-6 sm:p-8">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Customer type</label>
                        <select name="customer_type" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                            @foreach ($customerTypes as $type)
                                <option value="{{ $type->value }}" @selected(old('customer_type') === $type->value)>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        @error('customer_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Full name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Company</label>
                        <input type="text" name="company" value="{{ old('company') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Product</label>
                        <select name="product_id" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                            <option value="">Select a product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(old('product_id', $selectedProductId) == $product->id)>{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Or product name</label>
                        <input type="text" name="product_name" value="{{ old('product_name') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm" placeholder="If not listed">
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" step="0.01" min="0.01" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Unit</label>
                        <input type="text" name="unit" value="{{ old('unit', 'bag') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Delivery location</label>
                        <input type="text" name="delivery_location" value="{{ old('delivery_location') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Preferred delivery date</label>
                        <input type="date" name="preferred_delivery_date" value="{{ old('preferred_delivery_date') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Additional requirements</label>
                    <textarea name="additional_requirements" rows="5" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('additional_requirements') }}</textarea>
                </div>

                <button type="submit" class="btn-primary">Request Quote</button>
            </form>
        </div>
    </section>
@endsection
