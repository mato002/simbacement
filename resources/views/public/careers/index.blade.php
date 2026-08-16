@extends('layouts.public')

@section('title', 'Careers — '.config('app.name'))
@section('meta_description', 'Build your career with Simba Cement. Explore current opportunities, internships and graduate roles.')

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-14">
            <p class="section-label mb-3">People</p>
            <h1 class="heading-display text-ink">Build Your Career With Us</h1>
            <p class="mt-4 max-w-2xl text-base text-steel sm:text-lg">Join a manufacturing team focused on quality, safety and building Kenya’s future.</p>
        </div>
    </section>

    <section class="py-12">
        <div class="container-page">
            <div class="mb-10 grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-4">
                @foreach ([
                    ['title' => 'Why Work With Us', 'text' => 'Meaningful work in manufacturing, quality and national supply.'],
                    ['title' => 'Internships', 'text' => 'Hands-on exposure for students and early-career talent.'],
                    ['title' => 'Graduate Opportunities', 'text' => 'Structured pathways into operations, engineering and commercial roles.'],
                ] as $item)
                    <div class="border border-line bg-white p-3 sm:p-5 {{ $loop->last ? 'col-span-2 md:col-span-1' : '' }}">
                        <h2 class="font-display text-base font-bold uppercase tracking-wide sm:text-2xl">{{ $item['title'] }}</h2>
                        <p class="mt-2 text-xs text-steel sm:text-sm">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>

            <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Current Opportunities</h2>
            <div class="mt-6 space-y-4">
                @forelse ($jobs as $job)
                    <a href="{{ route('careers.show', $job) }}" class="block border border-line bg-white p-5 transition hover:border-brand">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="font-display text-2xl font-bold uppercase tracking-wide">{{ $job->title }}</h3>
                                <p class="mt-1 text-sm text-steel">
                                    {{ $job->location ?: 'Kenya' }}
                                    @if ($job->department) · {{ $job->department }} @endif
                                    · {{ str_replace('-', ' ', ucfirst($job->employment_type)) }}
                                </p>
                            </div>
                            <span class="text-sm font-semibold text-brand-deep">View & Apply</span>
                        </div>
                    </a>
                @empty
                    <div class="border border-dashed border-line bg-white p-8 text-steel">
                        No open roles at the moment. Check back soon or send a general enquiry via Contact.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
