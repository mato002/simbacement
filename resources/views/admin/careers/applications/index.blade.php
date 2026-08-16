@extends('layouts.admin')

@section('title', 'Applications')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Careers</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Applications</h1>
    </div>

    <form method="GET" class="mb-6 flex gap-3 border border-line bg-white p-4">
        <select name="status" class="w-full border border-line bg-mist px-3 py-2 text-sm sm:max-w-xs">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        <button class="btn-dark !py-2">Filter</button>
    </form>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Applicant</th>
                    <th class="px-4 py-3">Position</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applications as $application)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $application->full_name }}</p>
                            <p class="text-xs text-steel">{{ $application->email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $application->jobListing?->title ?: $application->position }}</td>
                        <td class="px-4 py-3">{{ $application->status->label() }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.applications.show', $application) }}" class="font-semibold text-brand-deep hover:underline">Open</a>
                                <x-admin.delete-form
                                    :action="route('admin.applications.destroy', $application)"
                                    title="Delete this application?"
                                    text="The CV file will also be removed."
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-steel">No applications yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $applications->links() }}</div>
@endsection
