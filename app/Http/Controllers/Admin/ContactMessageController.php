<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = ContactMessage::query()
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
            ->when($request->string('q')->toString(), function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'statuses' => MessageStatus::cases(),
        ]);
    }

    public function show(ContactMessage $message): View
    {
        $message->load('assignee');

        return view('admin.messages.show', [
            'message' => $message,
            'statuses' => MessageStatus::cases(),
            'staff' => User::query()
                ->role(['super-admin', 'administrator', 'sales-manager', 'customer-support'])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, ContactMessage $message): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(MessageStatus::class)],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $message->update([
            'status' => $data['status'],
            'assigned_to' => $data['assigned_to'] ?: null,
            'admin_notes' => $data['admin_notes'] ?? null,
        ]);

        return back()->with('success', 'Message updated.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('success', 'Message deleted.');
    }
}
