@extends('layouts.admin')

@section('title', 'Jobs')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Careers</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Job Listings</h1>
        </div>
        <a href="{{ route('admin.jobs.create') }}" class="btn-primary">Add job</a>
    </div>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Location</th>
                    <th class="px-4 py-3">Applications</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobs as $job)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3 font-semibold">{{ $job->title }}</td>
                        <td class="px-4 py-3">{{ $job->location ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $job->applications_count }}</td>
                        <td class="px-4 py-3">{{ $job->published_at && $job->is_active ? 'Open' : 'Closed/Draft' }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.jobs.edit', $job) }}" class="font-semibold text-brand-deep hover:underline">Edit</a>
                                <x-admin.delete-form
                                    :action="route('admin.jobs.destroy', $job)"
                                    title="Delete this job listing?"
                                />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $jobs->links() }}</div>
@endsection
