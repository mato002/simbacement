<?php

namespace App\Http\Controllers;

use App\Enums\MessageStatus;
use App\Models\ContactMessage;
use App\Models\Location;
use App\Models\Setting;
use App\Services\LeadMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('public.contact.create', [
            'locations' => Location::query()->active()->orderBy('sort_order')->get(),
            'salesEmail' => Setting::getValue('email_sales', 'sales@simbacement.local', 'company'),
            'supportEmail' => Setting::getValue('email_support', 'support@simbacement.local', 'company'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:160'],
            'county' => ['nullable', 'string', 'max:80'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
            'department' => ['required', 'in:sales,support,general'],
        ]);

        $message = ContactMessage::query()->create([
            ...$data,
            'status' => MessageStatus::New,
            'ip_address' => $request->ip(),
        ]);

        app(LeadMailer::class)->contactSubmitted($message);

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you. Your message has been sent to our team.');
    }
}
