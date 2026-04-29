<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $employerJobIds = Job::where('employer_id', Auth::id())->pluck('id');

        // Counts via dedicated queries (not affected by pagination)
        $counts = [
            'total'    => Application::whereIn('job_id', $employerJobIds)->count(),
            'pending'  => Application::whereIn('job_id', $employerJobIds)->where('status', 'Pending')->count(),
            'accepted' => Application::whereIn('job_id', $employerJobIds)->where('status', 'Accepted')->count(),
            'rejected' => Application::whereIn('job_id', $employerJobIds)->where('status', 'Rejected')->count(),
        ];

        $query = Application::whereIn('job_id', $employerJobIds)
            ->with(['job', 'applicant'])
            ->latest();

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $applications = $query->paginate(8)->withQueryString();
        $jobs = Job::where('employer_id', Auth::id())->get();

        return view('employer.applications.index', compact('applications', 'jobs', 'counts'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $employerJobIds = Job::where('employer_id', Auth::id())->pluck('id');

        if (!$employerJobIds->contains($application->job_id)) {
            abort(403, 'You do not have permission to update this application.');
        }

        $validated = $request->validate([
            'status'           => 'required|in:Pending,Accepted,Rejected',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        // Clear rejection reason when not rejecting
        if ($validated['status'] !== 'Rejected') {
            $validated['rejection_reason'] = null;
        }

        $application->update($validated);

        // Notify the applicant for every status change
        $application->applicant->notify(new ApplicationStatusUpdated($application));

        return redirect()->route('employer.applications.index')
            ->with('success', "Application status updated to {$validated['status']}.");
    }
}
