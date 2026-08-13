@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Configuration</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Site Settings</h1>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="border border-line bg-white p-6">
            <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Company</h2>
            <p class="mt-2 text-sm text-steel">
                Sales / support / careers emails also receive inbound website notifications (quotes, contact forms, job applications).
            </p>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                @foreach ([
                    'legal_name' => 'Legal name',
                    'tagline' => 'Tagline',
                    'phone_sales' => 'Sales phone',
                    'phone_support' => 'Support phone',
                    'email_sales' => 'Sales email',
                    'email_support' => 'Support email',
                    'email_careers' => 'Careers email',
                    'address_head_office' => 'Head office address',
                ] as $key => $label)
                    <div class="{{ $key === 'address_head_office' ? 'md:col-span-2' : '' }}">
                        <label class="mb-1.5 block text-sm font-semibold">{{ $label }}</label>
                        <input type="text" name="company[{{ $key }}]" value="{{ old("company.$key", $company[$key] ?? '') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                @endforeach
                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-sm font-semibold">Short description</label>
                    <textarea name="company[short_description]" rows="3" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('company.short_description', $company['short_description'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="border border-line bg-white p-6">
            <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Social</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                @foreach (['facebook', 'linkedin', 'instagram', 'x', 'youtube'] as $network)
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">{{ ucfirst($network) }} URL</label>
                        <input type="url" name="social[{{ $network }}]" value="{{ old("social.$network", $social[$network] ?? '') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm" placeholder="https://">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="border border-line bg-white p-6">
            <h2 class="font-display text-2xl font-bold uppercase tracking-wide">SEO Defaults</h2>
            <div class="mt-4 space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Default title</label>
                    <input type="text" name="seo[default_title]" value="{{ old('seo.default_title', $seo['default_title'] ?? '') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Default description</label>
                    <textarea name="seo[default_description]" rows="3" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('seo.default_description', $seo['default_description'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="border border-line bg-white p-6">
            <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Homepage Stats</h2>
            <p class="mt-2 text-sm text-steel">Leave blank to show XX+ placeholders until verified.</p>
            <div class="mt-4 grid gap-4 md:grid-cols-4">
                @foreach ([
                    'years_experience' => 'Years of experience',
                    'products_count' => 'Products',
                    'distribution_points' => 'Distribution points',
                    'projects_served' => 'Projects served',
                ] as $key => $label)
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">{{ $label }}</label>
                        <input type="text" name="stats[{{ $key }}]" value="{{ old("stats.$key", $stats[$key] ?? '') }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm" placeholder="e.g. 20+">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="border border-line bg-white p-6">
            <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Site Mode</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Positioning</label>
                    <select name="site[positioning]" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        <option value="official_manufacturer" @selected(($site['positioning'] ?? '') === 'official_manufacturer')>Official manufacturer</option>
                        <option value="authorized_distributor" @selected(($site['positioning'] ?? '') === 'authorized_distributor')>Authorized distributor</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Commerce mode</label>
                    <select name="site[commerce_mode]" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        <option value="quotes_only" @selected(($site['commerce_mode'] ?? '') === 'quotes_only')>Quotes only</option>
                        <option value="ecommerce" @selected(($site['commerce_mode'] ?? '') === 'ecommerce')>Ecommerce (future)</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary">Save settings</button>
    </form>
@endsection
