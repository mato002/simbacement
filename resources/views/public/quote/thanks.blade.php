@extends('layouts.public')

@section('title', 'Quote Received — ' . config('app.name'))

@section('content')
    <section class="py-20">
        <div class="container-page">
            <div class="max-w-2xl border border-line bg-white p-8 sm:p-10">
                <p class="section-label mb-3">Thank you</p>
                <h1 class="heading-display text-ink !text-4xl sm:!text-5xl">Your quotation request has been received.</h1>
                <p class="mt-5 text-steel">Our sales team will review your request and follow up shortly.</p>
                <div class="mt-8 border border-brand/40 bg-brand/10 px-5 py-4">
                    <p class="text-sm text-steel">Reference</p>
                    <p class="font-display text-3xl font-bold tracking-wide text-ink">{{ $quote->reference }}</p>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn-dark">Browse Products</a>
                    <a href="{{ route('home') }}" class="btn-primary">Back to Home</a>
                </div>
            </div>
        </div>
    </section>
@endsection
