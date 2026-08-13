@extends('layouts.public')

@section('title', $job->title.' — Careers — '.config('app.name'))
@section('meta_description', $job->summary ?: 'Apply for '.$job->title.' at Simba Cement.')

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-12">
            <nav class="mb-6 text-sm text-steel">
                <a href="{{ route('careers.index') }}" class="hover:text-ink">Careers</a>
                <span class="mx-2">/</span>
                <span class="text-ink">{{ $job->title }}</span>
            </nav>
            <p class="section-label mb-3">{{ $job->department ?: 'Opportunity' }}</p>
            <h1 class="heading-display text-ink !text-5xl">{{ $job->title }}</h1>
            <p class="mt-4 text-steel">
                {{ $job->location ?: 'Kenya' }} · {{ str_replace('-', ' ', ucfirst($job->employment_type)) }}
                @if ($job->closes_at) · Closes {{ $job->closes_at->format('d M Y') }} @endif
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="container-page grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-8">
                @if ($job->summary)
                    <div>
                        <h2 class="font-display text-2xl font-bold uppercase tracking-wide">About the role</h2>
                        <p class="mt-3 whitespace-pre-line text-steel">{{ $job->summary }}</p>
                    </div>
                @endif
                @if ($job->responsibilities)
                    <div>
                        <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Responsibilities</h2>
                        <p class="mt-3 whitespace-pre-line text-steel">{{ $job->responsibilities }}</p>
                    </div>
                @endif
                @if ($job->requirements)
                    <div>
                        <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Requirements</h2>
                        <p class="mt-3 whitespace-pre-line text-steel">{{ $job->requirements }}</p>
                    </div>
                @endif
            </div>

            <div class="border border-line bg-white p-6">
                <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Apply Now</h2>
                @if (session('success'))
                    <div class="mt-4 border border-brand/40 bg-brand/15 px-4 py-3 text-sm">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('careers.apply', $job) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Full name</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">CV upload (PDF/DOC)</label>
                        <input type="file" name="cv" accept=".pdf,.doc,.docx" required class="w-full border border-line bg-mist px-3 py-2 text-sm">
                        @error('cv') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Cover letter</label>
                        <textarea name="cover_letter" rows="5" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('cover_letter') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full">Submit Application</button>
                </form>
            </div>
        </div>
    </section>
@endsection
