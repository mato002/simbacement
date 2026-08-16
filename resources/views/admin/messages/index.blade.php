@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Inbox</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Contact Messages</h1>
    </div>

    <form method="GET" class="mb-6 flex flex-col gap-3 border border-line bg-white p-4 sm:flex-row">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, email, subject" class="w-full border border-line bg-mist px-3 py-2 text-sm">
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
                    <th class="px-4 py-3">From</th>
                    <th class="px-4 py-3">Subject</th>
                    <th class="px-4 py-3">Department</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $message->name }}</p>
                            <p class="text-xs text-steel">{{ $message->email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $message->subject }}</td>
                        <td class="px-4 py-3">{{ ucfirst($message->department) }}</td>
                        <td class="px-4 py-3">{{ $message->status->label() }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.messages.show', $message) }}" class="font-semibold text-brand-deep hover:underline">Open</a>
                                <x-admin.delete-form
                                    :action="route('admin.messages.destroy', $message)"
                                    title="Delete this message?"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-steel">No messages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $messages->links() }}</div>
@endsection
