@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Portfolio</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Projects</h1>
        </div>
        <a href="{{ route('admin.projects.create') }}" class="btn-primary">Add project</a>
    </div>

    <form method="GET" class="mb-6 flex flex-col gap-3 border border-line bg-white p-4 sm:flex-row">
        <select name="category" class="w-full border border-line bg-mist px-3 py-2 text-sm sm:max-w-xs">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->value }}" @selected(request('category') === $category->value)>{{ $category->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-dark !py-2">Filter</button>
    </form>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Project</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Year</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $project->title }}</p>
                            <p class="text-xs text-steel">{{ $project->location }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $project->category->label() }}</td>
                        <td class="px-4 py-3">{{ $project->year ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $project->is_published ? 'Published' : 'Draft' }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="font-semibold text-brand-deep hover:underline">Edit</a>
                                <x-admin.delete-form
                                    :action="route('admin.projects.destroy', $project)"
                                    title="Delete this project?"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-steel">No projects yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $projects->links() }}</div>
@endsection
