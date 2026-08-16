@extends('layouts.admin')

@section('title', $job->exists ? 'Edit Job' : 'Add Job')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Careers</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">{{ $job->exists ? 'Edit Job' : 'Add Job' }}</h1>
    </div>

    <form method="POST" action="{{ $job->exists ? route('admin.jobs.update', $job) : route('admin.jobs.store') }}" class="max-w-4xl space-y-5 border border-line bg-white p-6">
        @csrf
        @if ($job->exists) @method('PUT') @endif

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Title</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $job->slug) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            </div>
        </div>
        <div class="grid gap-5 md:grid-cols-3">
            <input type="text" name="location" value="{{ old('location', $job->location) }}" placeholder="Location" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            <input type="text" name="department" value="{{ old('department', $job->department) }}" placeholder="Department" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            <select name="employment_type" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                @foreach (['full-time' => 'Full time', 'part-time' => 'Part time', 'contract' => 'Contract', 'internship' => 'Internship', 'graduate' => 'Graduate'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('employment_type', $job->employment_type) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <textarea name="summary" rows="3" placeholder="Summary" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('summary', $job->summary) }}</textarea>
        <textarea name="responsibilities" rows="5" placeholder="Responsibilities" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('responsibilities', $job->responsibilities) }}</textarea>
        <textarea name="requirements" rows="5" placeholder="Requirements" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('requirements', $job->requirements) }}</textarea>
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Closes at</label>
                <input type="date" name="closes_at" value="{{ old('closes_at', optional($job->closes_at)->format('Y-m-d')) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            </div>
            <div class="flex items-end gap-4 pb-2">
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $job->is_active))> Active</label>
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', (bool) $job->published_at))> Published</label>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="btn-primary">{{ $job->exists ? 'Save job' : 'Create job' }}</button>
            <a href="{{ route('admin.jobs.index') }}" class="btn-dark">Back</a>
        </div>
    </form>

    @if ($job->exists)
        <div class="mt-6">
            <x-admin.delete-form
                :action="route('admin.jobs.destroy', $job)"
                label="Delete job"
                title="Delete this job listing?"
            />
        </div>
    @endif
@endsection
