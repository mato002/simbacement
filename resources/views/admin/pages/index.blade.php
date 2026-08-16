@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Content</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Corporate Pages</h1>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="btn-primary">Add page</a>
    </div>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3 font-semibold">{{ $page->title }}</td>
                        <td class="px-4 py-3 text-steel">/{{ $page->slug }}</td>
                        <td class="px-4 py-3">{{ $page->is_published ? 'Published' : 'Draft' }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ url('/'.$page->slug) }}" target="_blank" class="font-semibold text-steel hover:underline">View</a>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="font-semibold text-brand-deep hover:underline">Edit</a>
                                @unless (in_array($page->slug, ['about', 'manufacturing', 'quality', 'sustainability'], true))
                                    <x-admin.delete-form
                                        :action="route('admin.pages.destroy', $page)"
                                        title="Delete this page?"
                                    />
                                @endunless
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
