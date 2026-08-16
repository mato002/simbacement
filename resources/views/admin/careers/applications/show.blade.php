@extends('layouts.admin')

@section('title', 'Application')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Application</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">{{ $application->full_name }}</h1>
            <p class="mt-2 text-steel">{{ $application->jobListing?->title }}</p>
        </div>
        <a href="{{ route('admin.applications.index') }}" class="btn-dark">Back</a>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <div class="border border-line bg-white p-6">
                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-steel">Email</dt><dd class="font-semibold">{{ $application->email }}</dd></div>
                    <div><dt class="text-steel">Phone</dt><dd class="font-semibold">{{ $application->phone }}</dd></div>
                    <div><dt class="text-steel">Submitted</dt><dd class="font-semibold">{{ $application->created_at?->format('d M Y H:i') }}</dd></div>
                    <div><dt class="text-steel">CV</dt>
                        <dd>
                            <a href="{{ route('admin.applications.cv', $application) }}" class="font-semibold text-brand-deep hover:underline">Download CV</a>
                        </dd>
                    </div>
                </dl>
                @if ($application->cover_letter)
                    <div class="mt-5">
                        <p class="text-sm text-steel">Cover letter</p>
                        <p class="mt-1 whitespace-pre-line text-sm">{{ $application->cover_letter }}</p>
                    </div>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('admin.applications.update', $application) }}" class="border border-line bg-white p-6 space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Status</label>
                <select name="status" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $application->status->value) === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Admin notes</label>
                <textarea name="admin_notes" rows="6" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('admin_notes', $application->admin_notes) }}</textarea>
            </div>
            <button class="btn-primary w-full">Update application</button>
        </form>

        <div class="mt-4 border border-line bg-white p-6">
            <x-admin.delete-form
                :action="route('admin.applications.destroy', $application)"
                label="Delete application"
                title="Delete this application?"
                text="The CV file will also be removed."
            />
        </div>
    </div>
@endsection
