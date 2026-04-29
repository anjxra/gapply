@extends('layouts.app')
@section('title', 'My Jobs')

@section('content')
<div class="page-wrapper">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 class="page-title">My Job Postings</h1>
            <p class="page-subtitle">Manage and monitor your active listings.</p>
        </div>
        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Post New Job
        </a>
    </div>

    @if($jobs->isEmpty())
        <div class="card" style="text-align:center;padding:4rem 2rem;">
            <div style="width:48px;height:48px;border-radius:var(--radius);background:var(--surface-alt);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:var(--text-faint);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <h3 style="font-weight:700;color:var(--text);margin-bottom:.4rem;">No jobs posted yet</h3>
            <p style="color:var(--text-muted);font-size:.8125rem;margin-bottom:1.25rem;">Start by creating your first job posting.</p>
            <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary btn-sm">Post Your First Job</a>
        </div>
    @else
        <div class="card" style="overflow:hidden;">
            <table class="gapply-table">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Applicants</th>
                        <th>Posted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                {{-- Thumbnail --}}
                                @if($job->job_image)
                                    <img src="{{ asset('storage/jobs/' . $job->job_image) }}"
                                         alt="{{ $job->title }}"
                                         style="width:36px;height:36px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--border);flex-shrink:0;">
                                @else
                                    <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:var(--surface-alt);border:1.5px dashed var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text-faint);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                    </div>
                                @endif
                                <span class="text-strong">{{ $job->title }}</span>
                            </div>
                        </td>
                        <td class="text-muted text-small">{{ $job->location }}</td>
                        <td>
                            @php $tc = ['Full-time'=>'badge-full-time','Part-time'=>'badge-part-time','Contract'=>'badge-contract','Internship'=>'badge-internship'][$job->employment_type] ?? ''; @endphp
                            <span class="badge {{ $tc }}">{{ $job->employment_type }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $job->status === 'open' ? 'badge-open' : 'badge-closed' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-strong">{{ $job->applications_count }}</span>
                            <span class="text-muted text-small"> applied</span>
                        </td>
                        <td class="text-muted text-small">{{ $job->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                                <a href="{{ route('employer.jobs.edit', $job) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('employer.jobs.destroy', $job) }}"
                                    onsubmit="return confirm('Delete this job posting? This will also remove all related applications.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-pagination-row">
            {{ $jobs->links() }}
        </div>
    @endif
</div>
@endsection
