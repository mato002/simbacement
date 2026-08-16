@extends('layouts.admin')

@section('title', 'Message')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Message</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">{{ $message->subject }}</h1>
        </div>
        <a href="{{ route('admin.messages.index') }}" class="btn-dark">Back</a>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <div class="border border-line bg-white p-6">
                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-steel">Name</dt><dd class="font-semibold">{{ $message->name }}</dd></div>
                    <div><dt class="text-steel">Company</dt><dd class="font-semibold">{{ $message->company ?: '—' }}</dd></div>
                    <div><dt class="text-steel">Email</dt><dd class="font-semibold">{{ $message->email }}</dd></div>
                    <div><dt class="text-steel">Phone</dt><dd class="font-semibold">{{ $message->phone ?: '—' }}</dd></div>
                    <div><dt class="text-steel">County</dt><dd class="font-semibold">{{ $message->county ?: '—' }}</dd></div>
                    <div><dt class="text-steel">Department</dt><dd class="font-semibold">{{ ucfirst($message->department) }}</dd></div>
                </dl>
                <div class="mt-5 whitespace-pre-line text-sm">{{ $message->message }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.messages.update', $message) }}" class="border border-line bg-white p-6 space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Status</label>
                <select name="status" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $message->status->value) === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Assigned to</label>
                <select name="assigned_to" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    <option value="">Unassigned</option>
                    @foreach ($staff as $user)
                        <option value="{{ $user->id }}" @selected(old('assigned_to', $message->assigned_to) == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Admin notes</label>
                <textarea name="admin_notes" rows="5" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('admin_notes', $message->admin_notes) }}</textarea>
            </div>
            <button class="btn-primary w-full">Update message</button>
        </form>

        <div class="mt-4 border border-line bg-white p-6">
            <x-admin.delete-form
                :action="route('admin.messages.destroy', $message)"
                label="Delete message"
                title="Delete this message?"
            />
        </div>
    </div>
@endsection
