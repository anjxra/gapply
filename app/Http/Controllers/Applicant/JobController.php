<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('employer')->where('status', 'open');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('employer', fn ($eq) => $eq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('type')) {
            $query->where('employment_type', $request->type);
        }

        $jobs = $query->latest()->paginate(9);

        return view('jobs.index', compact('jobs'));
    }

    public function show(Job $job)
    {
        if ($job->status === 'closed') {
            return redirect()->route('jobs.index')->with('error', 'This job posting is no longer available.');
        }

        $job->load('employer', 'applications');
        return view('jobs.show', compact('job'));
    }
}
