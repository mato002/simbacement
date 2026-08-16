@extends('layouts.public')

@section('title', 'Contact Us — '.config('app.name'))
@section('meta_description', 'Contact Simba Cement sales, customer support, head office and factory locations.')

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-14">
            <p class="section-label mb-3">Reach Us</p>
            <h1 class="heading-display text-ink">Contact Us</h1>
            <p class="mt-4 max-w-2xl text-lg text-steel">Talk to sales, support or our operations teams across Kenya.</p>
        </div>
    </section>

    <section class="py-12">
        <div class="container-page grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <form method="POST" action="{{ route('contact.store') }}" class="space-y-5 border border-line bg-white p-6 sm:p-8">
                @csrf
                @if (session('success'))
                    <div class="border border-brand/40 bg-brand/15 px-4 py-3 text-sm">{{ session('success') }}</div>
                @endif

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Company</label>
                        <input type="text" name="company" value="{{ old('company') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">County</label>
                        <input type="text" name="county" value="{{ old('county') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Department</label>
                        <select name="department" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                            <option value="sales" @selected(old('department') === 'sales')>Sales</option>
                            <option value="support" @selected(old('department') === 'support')>Customer Support</option>
                            <option value="general" @selected(old('department', 'general') === 'general')>General</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    @error('subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Message</label>
                    <textarea name="message" rows="6" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary">Submit</button>
            </form>

            <div class="space-y-5">
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Direct contacts</h2>
                    <ul class="mt-4 space-y-3 text-sm text-steel">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-envelope text-brand"></i> Sales: {{ $salesEmail }}</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-headset text-brand"></i> Support: {{ $supportEmail }}</li>
                    </ul>
                    <a href="{{ route('quote.create') }}" class="btn-dark mt-5">Request a Quote</a>
                </div>

                @foreach ($locations as $location)
                    <div class="border border-line bg-white p-6">
                        <p class="text-xs font-semibold tracking-wide text-brand-deep uppercase">{{ $location->type->label() }}</p>
                        <h3 class="mt-1 font-display text-xl font-bold uppercase tracking-wide">{{ $location->name }}</h3>
                        <p class="mt-2 text-sm text-steel">{{ $location->address }}</p>
                        @if ($location->county)
                            <p class="text-sm text-steel">{{ $location->county }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
