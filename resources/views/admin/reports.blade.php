@extends('layouts.app')
@section('title', 'Jobs Report')

@section('content')
<div class="page-wrapper">

    {{-- Page Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:.75rem;">
        <div>
            <h1 class="page-title">Jobs Report</h1>
            <p class="page-subtitle">All posted jobs, their employers, and full applicant lists.</p>
        </div>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;flex-shrink:0;">
            <a href="{{ route('admin.analytics') }}" class="btn btn-secondary btn-sm">Analytics</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">Dashboard</a>
        </div>
    </div>

    {{-- Summary KPIs --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:1rem;margin-bottom:1.75rem;">
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div>
                <div class="stat-label">Total Jobs</div>
            </div>
            <div class="stat-value">{{ $totalJobs }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-icon" style="color:var(--success);"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div class="stat-label">Open</div>
            </div>
            <div class="stat-value" style="color:var(--success);">{{ $jobs->where('status','open')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <div class="stat-icon" style="color:#7c3aed;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                <div class="stat-label">Applicants</div>
            </div>
            <div class="stat-value" style="color:#7c3aed;">{{ $totalApps }}</div>
        </div>
    </div>

    {{-- Search --}}
    <div style="margin-bottom:1.25rem;">
        <input type="text" id="reportSearch" placeholder="Search by job title or employer..."
               style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--surface);color:var(--text);font-size:.8125rem;font-family:inherit;outline:none;box-sizing:border-box;"
               oninput="filterReport(this.value)">
    </div>

    {{-- Jobs List --}}
    @forelse($jobs as $job)
    @php
        $tc  = ['Full-time'=>'badge-full-time','Part-time'=>'badge-part-time','Contract'=>'badge-contract','Internship'=>'badge-internship'][$job->employment_type] ?? '';
        $sc  = ['Pending'=>'badge-pending','Accepted'=>'badge-accepted','Rejected'=>'badge-rejected'];
        $appCount = $job->applications->count();
    @endphp
    <div class="report-job-card" data-search="{{ strtolower($job->title . ' ' . $job->employer->name) }}">

        {{-- Job Header: image left, info right stacked --}}
        <div style="display:flex;align-items:flex-start;gap:.875rem;margin-bottom:{{ $appCount > 0 ? '.875rem' : '0' }};">

            {{-- Thumbnail --}}
            @if($job->job_image)
                <img src="{{ asset('storage/jobs/' . $job->job_image) }}"
                     style="width:42px;height:42px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--border);flex-shrink:0;">
            @else
                <div style="width:42px;height:42px;border-radius:var(--radius-sm);background:var(--surface-alt);border:1.5px dashed var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text-faint);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
            @endif

            {{-- Info block — all stacked, truncates on narrow screens --}}
            <div style="flex:1;min-width:0;">
                <div style="font-weight:700;color:var(--text);font-size:.9rem;line-height:1.3;margin-bottom:.2rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $job->title }}</div>
                <div style="font-size:.75rem;color:var(--text-muted);font-weight:600;margin-bottom:.1rem;">{{ $job->employer->name }}</div>
                <div style="font-size:.7rem;color:var(--text-faint);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:.1rem;">{{ $job->employer->email }}</div>
                <div style="font-size:.7rem;color:var(--text-faint);margin-bottom:.45rem;">{{ $job->location }} &middot; {{ $job->created_at->format('M d, Y') }}</div>
                {{-- Badges always below the info text --}}
                <div style="display:flex;gap:.35rem;flex-wrap:wrap;align-items:center;">
                    <span class="badge {{ $tc }}">{{ $job->employment_type }}</span>
                    <span class="badge {{ $job->status === 'open' ? 'badge-open' : 'badge-closed' }}">{{ ucfirst($job->status) }}</span>
                    <span style="font-size:.7rem;color:var(--text-muted);background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius-sm);padding:.15rem .45rem;white-space:nowrap;">
                        {{ $appCount }} {{ Str::plural('applicant', $appCount) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Applicants Table --}}
        @if($appCount > 0)
        <div style="border-top:1px solid var(--border);padding-top:.875rem;">
            <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-faint);margin-bottom:.6rem;">Applicants</div>
            {{-- Explicit scroll wrapper --}}
            <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;border-radius:var(--radius-sm);">
                <table class="gapply-table" style="border:none;min-width:480px;">
                    <thead>
                        <tr>
                            <th style="min-width:110px;">Name</th>
                            <th style="min-width:140px;">Email</th>
                            <th style="min-width:60px;">Resume</th>
                            <th style="min-width:100px;">Date Applied</th>
                            <th style="min-width:80px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($job->applications as $app)
                        <tr>
                            <td class="text-strong" style="white-space:nowrap;">{{ $app->full_name }}</td>
                            <td class="text-muted text-small" style="white-space:nowrap;">{{ $app->email }}</td>
                            <td>
                                @if($app->resume_link)
                                    <a href="{{ $app->resume_link }}" target="_blank"
                                       style="color:var(--accent);font-size:.75rem;display:inline-flex;align-items:center;gap:.25rem;white-space:nowrap;">
                                        View
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    </a>
                                @else
                                    <span class="text-muted text-small">—</span>
                                @endif
                            </td>
                            <td class="text-muted text-small" style="white-space:nowrap;">{{ $app->created_at->format('M d, Y') }}</td>
                            <td style="white-space:nowrap;">
                                <span class="badge {{ $sc[$app->status] ?? '' }}">{{ $app->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div style="border-top:1px solid var(--border);padding-top:.7rem;text-align:center;color:var(--text-faint);font-size:.78rem;padding-bottom:.1rem;">
            No applicants yet.
        </div>
        @endif
    </div>
    @empty
    <div style="text-align:center;padding:4rem 2rem;color:var(--text-faint);">No jobs have been posted yet.</div>
    @endforelse

    <div class="table-pagination-row">
        {{ $jobs->links() }}
    </div>

</div>

<script>
function filterReport(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.report-job-card').forEach(card => {
        card.style.display = (card.getAttribute('data-search') || '').includes(q) ? '' : 'none';
    });
}
</script>
@endsection
