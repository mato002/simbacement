<?php

namespace App\Http\Controllers;

use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Services\LeadMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        $jobs = JobListing::query()
            ->open()
            ->orderByDesc('published_at')
            ->get();

        return view('public.careers.index', compact('jobs'));
    }

    public function show(JobListing $job): View
    {
        abort_unless(
            $job->is_active
            && $job->published_at
            && $job->published_at->lte(now())
            && (! $job->closes_at || $job->closes_at->gte(now()->startOfDay())),
            404
        );

        return view('public.careers.show', compact('job'));
    }

    public function apply(Request $request, JobListing $job): RedirectResponse
    {
        abort_unless(
            $job->is_active
            && $job->published_at
            && $job->published_at->lte(now())
            && (! $job->closes_at || $job->closes_at->gte(now()->startOfDay())),
            404
        );

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:40'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $cvPath = $request->file('cv')->store('cvs/'.$job->id, 'local');

        $application = JobApplication::query()->create([
            'job_listing_id' => $job->id,
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'position' => $job->title,
            'cv_path' => $cvPath,
            'cover_letter' => $data['cover_letter'] ?? null,
            'status' => JobApplicationStatus::Received,
        ]);

        app(LeadMailer::class)->applicationSubmitted($application);

        return redirect()
            ->route('careers.show', $job)
            ->with('success', 'Your application has been submitted successfully.');
    }
}
