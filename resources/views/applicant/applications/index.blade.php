@extends('layouts.app')
@section('title', 'My Applications')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <h1 class="page-title">My Applications</h1>
        <p class="page-subtitle">Track the status of all your submitted applications.</p>
    </div>

    {{-- Summary stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;margin-bottom:2rem;">
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                <div class="stat-label">Total Applied</div>
            </div>
            <div class="stat-value">{{ $counts['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-icon" style="color:var(--success);"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div class="stat-label">Accepted</div>
            </div>
            <div class="stat-value" style="color:var(--success);">{{ $counts['accepted'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-icon" style="color:#a16207;"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-value" style="color:#a16207;">{{ $counts['pending'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-icon" style="color:var(--danger);"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                <div class="stat-label">Rejected</div>
            </div>
            <div class="stat-value" style="color:var(--danger);">{{ $counts['rejected'] }}</div>
        </div>
    </div>

    @if($applications->isEmpty())
        <div class="card" style="text-align:center;padding:4rem 2rem;">
            <div style="width:48px;height:48px;border-radius:var(--radius);background:var(--surface-alt);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:var(--text-faint);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <h3 style="font-weight:700;color:var(--text);margin-bottom:.4rem;">No applications yet</h3>
            <p style="color:var(--text-muted);font-size:.8125rem;margin-bottom:1.25rem;">Browse open jobs and start applying today.</p>
            <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm">Browse Jobs</a>
        </div>
    @else
        <div class="card" style="overflow:hidden;">
            <table class="gapply-table">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Date Applied</th>
                        <th>Status</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                @if($app->job && $app->job->job_image)
                                    <img src="{{ asset('images/jobs/' . $app->job->job_image) }}"
                                         alt="{{ $app->job->title }}"
                                         style="width:36px;height:36px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--border);flex-shrink:0;">
                                @else
                                    <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:var(--surface-alt);border:1.5px dashed var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text-faint);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                    </div>
                                @endif
                                <span class="text-strong">{{ $app->job->title ?? 'Job Removed' }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $app->job->employer->name ?? 'N/A' }}</td>
                        <td class="text-muted text-small">{{ $app->created_at->format('M d, Y') }}</td>
                        <td>
                            @php $sc = ['Pending'=>'badge-pending','Accepted'=>'badge-accepted','Rejected'=>'badge-rejected']; @endphp
                            <span class="badge {{ $sc[$app->status] ?? '' }}">{{ $app->status }}</span>
                            @if($app->status === 'Rejected' && $app->rejection_reason)
                            <div style="margin-top:.4rem;padding:.4rem .6rem;background:var(--surface-alt);border-left:3px solid var(--danger);border-radius:0 var(--radius-sm) var(--radius-sm) 0;font-size:.72rem;color:var(--text-muted);line-height:1.4;">
                                <strong style="color:var(--danger);display:block;margin-bottom:.1rem;font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;">Reason</strong>
                                {{ $app->rejection_reason }}
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($app->job && $app->job->status === 'open')
                                <a href="{{ route('jobs.show', $app->job) }}" style="color:var(--accent);font-size:.78rem;display:inline-flex;align-items:center;gap:.3rem;">
                                    View
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                </a>
                            @else
                                <span class="text-muted text-small">Closed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-pagination-row">
            {{ $applications->links() }}
        </div>
    @endif

    <div style="margin-top:1.25rem;">
        <a href="{{ route('jobs.index') }}" style="color:var(--text-muted);font-size:.8125rem;display:inline-flex;align-items:center;gap:.4rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Job Board
        </a>
    </div>

</div>
@endsection
