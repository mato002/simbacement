@php
    $socialLinks = collect([
        'facebook' => ['icon' => 'fa-brands fa-facebook-f', 'label' => 'Facebook'],
        'linkedin' => ['icon' => 'fa-brands fa-linkedin-in', 'label' => 'LinkedIn'],
        'instagram' => ['icon' => 'fa-brands fa-instagram', 'label' => 'Instagram'],
        'x' => ['icon' => 'fa-brands fa-x-twitter', 'label' => 'X'],
        'youtube' => ['icon' => 'fa-brands fa-youtube', 'label' => 'YouTube'],
    ])->filter(fn ($meta, $key) => filled($siteSocial[$key] ?? null));
@endphp

<footer class="mt-auto bg-ink text-white">
    <div class="container-page grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <div class="mb-4 flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center bg-brand text-ink font-display text-xl font-bold">SC</span>
                <span class="font-display text-2xl font-bold tracking-wide uppercase">{{ $siteCompany['legal_name'] ?? 'Simba Cement' }}</span>
            </div>
            <p class="max-w-sm text-sm leading-relaxed text-white/70">
                {{ $siteCompany['short_description'] ?? 'High-quality cement and building materials engineered for strength, durability and performance.' }}
            </p>
            @if ($socialLinks->isNotEmpty())
                <div class="mt-5 flex gap-3 text-white/70">
                    @foreach ($socialLinks as $key => $meta)
                        <a href="{{ $siteSocial[$key] }}" target="_blank" rel="noopener" class="inline-flex h-9 w-9 items-center justify-center border border-white/15 transition hover:border-brand hover:text-brand" aria-label="{{ $meta['label'] }}">
                            <i class="{{ $meta['icon'] }}" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h3 class="mb-4 font-display text-lg font-semibold tracking-wide uppercase">Explore</h3>
            <ul class="space-y-2 text-sm text-white/70">
                <li><a class="hover:text-brand" href="{{ route('products.index') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>Products</a></li>
                <li><a class="hover:text-brand" href="{{ route('solutions.index') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>Solutions</a></li>
                <li><a class="hover:text-brand" href="{{ route('projects.index') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>Projects</a></li>
                <li><a class="hover:text-brand" href="{{ route('quality') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>Quality</a></li>
                <li><a class="hover:text-brand" href="{{ route('careers.index') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>Careers</a></li>
            </ul>
        </div>

        <div>
            <h3 class="mb-4 font-display text-lg font-semibold tracking-wide uppercase">Company</h3>
            <ul class="space-y-2 text-sm text-white/70">
                <li><a class="hover:text-brand" href="{{ route('about') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>About Us</a></li>
                <li><a class="hover:text-brand" href="{{ route('manufacturing') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>Manufacturing</a></li>
                <li><a class="hover:text-brand" href="{{ route('sustainability') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>Sustainability</a></li>
                <li><a class="hover:text-brand" href="{{ route('news.index') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>News & Media</a></li>
                <li><a class="hover:text-brand" href="{{ route('contact') }}"><i class="fa-solid fa-angle-right mr-2 text-brand" aria-hidden="true"></i>Contact</a></li>
            </ul>
        </div>

        <div>
            <h3 class="mb-4 font-display text-lg font-semibold tracking-wide uppercase">Talk to Sales</h3>
            <p class="mb-4 text-sm text-white/70">Need pricing, delivery, or technical guidance for your next project?</p>
            <ul class="mb-5 space-y-2 text-sm text-white/70">
                @if (! empty($siteCompany['email_sales']))
                    <li class="flex items-center gap-2"><i class="fa-solid fa-envelope text-brand" aria-hidden="true"></i> {{ $siteCompany['email_sales'] }}</li>
                @endif
                @if (! empty($siteCompany['phone_sales']))
                    <li class="flex items-center gap-2"><i class="fa-solid fa-phone text-brand" aria-hidden="true"></i> {{ $siteCompany['phone_sales'] }}</li>
                @endif
            </ul>
            <a href="{{ route('quote.create') }}" class="btn-primary">
                <i class="fa-solid fa-file-signature" aria-hidden="true"></i>
                Request a Quote
            </a>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-page flex flex-col gap-2 py-5 text-xs text-white/50 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ $siteCompany['legal_name'] ?? config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ url('/sitemap.xml') }}" class="hover:text-white">Sitemap</a>
                ·
                <a href="{{ url('/robots.txt') }}" class="hover:text-white">Robots</a>
            </p>
        </div>
    </div>
</footer>
