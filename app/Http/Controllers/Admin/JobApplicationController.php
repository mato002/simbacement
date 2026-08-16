<?php

namespace App\Http\Controllers\Admin;

use App\Enums\JobApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = JobApplication::query()
            ->with('jobListing')
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.careers.applications.index', [
            'applications' => $applications,
            'statuses' => JobApplicationStatus::cases(),
        ]);
    }

    public function show(JobApplication $application): View
    {
        $application->load(['jobListing', 'reviewer']);

        return view('admin.careers.applications.show', [
            'application' => $application,
            'statuses' => JobApplicationStatus::cases(),
        ]);
    }

    public function update(Request $request, JobApplication $application): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(JobApplicationStatus::class)],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $application->update([
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? null,
            'reviewed_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Application updated.');
    }

    public function destroy(JobApplication $application): RedirectResponse
    {
        if ($application->cv_path && Storage::disk('local')->exists($application->cv_path)) {
            Storage::disk('local')->delete($application->cv_path);
        }

        $application->delete();

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application deleted.');
    }

    public function downloadCv(JobApplication $application): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($application->cv_path), 404);

        return Storage::disk('local')->download(
            $application->cv_path,
            basename($application->cv_path)
        );
    }
}
