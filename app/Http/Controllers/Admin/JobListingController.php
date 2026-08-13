<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobListingController extends Controller
{
    public function index(): View
    {
        $jobs = JobListing::query()
            ->withCount('applications')
            ->latest('published_at')
            ->latest('id')
            ->paginate(20);

        return view('admin.careers.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        return view('admin.careers.jobs.form', [
            'job' => new JobListing([
                'employment_type' => 'full-time',
                'is_active' => true,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $job = JobListing::query()->create($this->validated($request));

        return redirect()
            ->route('admin.jobs.edit', $job)
            ->with('success', "Job “{$job->title}” created.");
    }

    public function edit(JobListing $job): View
    {
        return view('admin.careers.jobs.form', compact('job'));
    }

    public function update(Request $request, JobListing $job): RedirectResponse
    {
        $job->update($this->validated($request, $job));

        return redirect()
            ->route('admin.jobs.edit', $job)
            ->with('success', "Job “{$job->title}” updated.");
    }

    public function destroy(JobListing $job): RedirectResponse
    {
        $job->delete();

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Job deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?JobListing $job = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('job_listings', 'slug')->ignore($job?->id)],
            'location' => ['nullable', 'string', 'max:120'],
            'department' => ['nullable', 'string', 'max:120'],
            'employment_type' => ['required', 'string', 'max:60'],
            'summary' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'closes_at' => ['nullable', 'date'],
        ]);

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');
        $data['published_at'] = $request->boolean('is_published')
            ? ($job?->published_at ?? now())
            : null;

        return $data;
    }
}
