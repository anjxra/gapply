<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Notifications\NewApplicationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $applicantId = Auth::id();

        // Counts via dedicated queries (not affected by pagination)
        $counts = [
            'total'    => Application::where('applicant_id', $applicantId)->count(),
            'accepted' => Application::where('applicant_id', $applicantId)->where('status', 'Accepted')->count(),
            'pending'  => Application::where('applicant_id', $applicantId)->where('status', 'Pending')->count(),
            'rejected' => Application::where('applicant_id', $applicantId)->where('status', 'Rejected')->count(),
        ];

        $applications = Application::where('applicant_id', $applicantId)
            ->with('job.employer')
            ->latest()
            ->paginate(8);

        return view('applicant.applications.index', compact('applications', 'counts'));
    }

    public function create(Job $job)
    {
        if ($job->status === 'closed') {
            return redirect()->route('jobs.show', $job)->with('error', 'This job is no longer accepting applications.');
        }

        $alreadyApplied = Application::where('job_id', $job->id)
            ->where('applicant_id', Auth::id())
            ->exists();

        if ($alreadyApplied) {
            return redirect()->route('jobs.show', $job)->with('error', 'You have already applied for this job.');
        }

        return view('applicant.apply', compact('job'));
    }

    public function store(Request $request, Job $job)
    {
        if ($job->status === 'closed') {
            return redirect()->route('jobs.index')->with('error', 'This job is no longer accepting applications.');
        }

        $alreadyApplied = Application::where('job_id', $job->id)
            ->where('applicant_id', Auth::id())
            ->exists();

        if ($alreadyApplied) {
            return redirect()->route('jobs.show', $job)->with('error', 'You have already applied for this job.');
        }

        $data = $request->validate([
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email',
            'resume_link' => 'required|url|max:500',
            'cover_note'  => 'nullable|string|max:2000',
        ]);

        $application = Application::create(array_merge($data, [
            'job_id'       => $job->id,
            'applicant_id' => Auth::id(),
            'status'       => 'Pending',
        ]));

        // Notify the employer that a new application was received
        $job->load('employer');
        if ($job->employer) {
            $job->employer->notify(new NewApplicationReceived($application));
        }

        return redirect()->route('applicant.applications')->with('success', 'Your application has been submitted successfully!');
    }
}
