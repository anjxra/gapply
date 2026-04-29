<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::where('employer_id', Auth::id())
            ->withCount('applications')
            ->latest()
            ->paginate(8);

        return view('employer.jobs.index', compact('jobs'));
    }


    public function create()
    {
        return view('employer.jobs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'location'        => 'required|string|max:255',
            'employment_type' => 'required|in:Full-time,Part-time,Contract,Internship',
            'description'     => 'required|string|min:20',
            'status'          => 'required|in:open,closed',
            'job_image'       => 'nullable|image|mimes:jpeg,jpg,png|max:3072',
        ]);

        // Handle image upload
        if ($request->hasFile('job_image')) {
            $file     = $request->file('job_image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->extension();
            $file->storeAs('jobs', $filename, 'public');
            $data['job_image'] = $filename;
        }

        Job::create(array_merge($data, ['employer_id' => Auth::id()]));

        return redirect()->route('employer.jobs.index')->with('success', 'Job posting created successfully.');
    }

    public function edit(Job $job)
    {
        $this->authorizeJob($job);
        return view('employer.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorizeJob($job);

        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'location'        => 'required|string|max:255',
            'employment_type' => 'required|in:Full-time,Part-time,Contract,Internship',
            'description'     => 'required|string|min:20',
            'status'          => 'required|in:open,closed',
            'job_image'       => 'nullable|image|mimes:jpeg,jpg,png|max:3072',
        ]);

        // Handle image upload
        if ($request->hasFile('job_image')) {
            // Delete old image from storage
            if ($job->job_image) {
                Storage::disk('public')->delete('jobs/' . $job->job_image);
            }
            $file     = $request->file('job_image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->extension();
            $file->storeAs('jobs', $filename, 'public');
            $data['job_image'] = $filename;
        } else {
            // Keep existing image
            unset($data['job_image']);
        }

        $job->update($data);

        return redirect()->route('employer.jobs.index')->with('success', 'Job posting updated successfully.');
    }

    public function destroy(Job $job)
    {
        $this->authorizeJob($job);

        // Delete image from storage
        if ($job->job_image) {
            Storage::disk('public')->delete('jobs/' . $job->job_image);
        }

        $title = $job->title;
        $job->delete();

        return redirect()->route('employer.jobs.index')->with('success', "Job posting \"{$title}\" has been deleted.");
    }

    private function authorizeJob(Job $job): void
    {
        if ($job->employer_id !== Auth::id()) {
            abort(403, 'You do not have permission to manage this job posting.');
        }
    }
}
