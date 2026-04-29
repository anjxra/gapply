@extends('layouts.app')
@section('title', 'Applicant Reviews')

@section('content')
<div class="page-wrapper">

    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 class="page-title">Applicant Reviews</h1>
            <p class="page-subtitle">Review and update statuses for candidates who applied to your jobs.</p>
        </div>

        <form method="GET" action="{{ route('employer.applications.index') }}" style="display:flex;align-items:center;gap:.65rem;">
            <label style="font-size:.8rem;color:var(--text-muted);white-space:nowrap;">Filter by Job:</label>
            <select name="job_id" class="form-control" style="width:auto;min-width:175px;" onchange="this.form.submit()">
                <option value="">All Jobs</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Summary counts --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:.75rem;margin-bottom:1.5rem;">
        <div class="stat-card" style="padding:.85rem 1rem;">
            <div class="stat-left">
                <div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-value">{{ $counts['total'] }}</div>
        </div>
        <div class="stat-card" style="padding:.85rem 1rem;">
            <div class="stat-left">
                <div class="stat-icon" style="color:#a16207;"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-value" style="color:#a16207;">{{ $counts['pending'] }}</div>
        </div>
        <div class="stat-card" style="padding:.85rem 1rem;">
            <div class="stat-left">
                <div class="stat-icon" style="color:var(--success);"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div class="stat-label">Accepted</div>
            </div>
            <div class="stat-value" style="color:var(--success);">{{ $counts['accepted'] }}</div>
        </div>
        <div class="stat-card" style="padding:.85rem 1rem;">
            <div class="stat-left">
                <div class="stat-icon" style="color:var(--danger);"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                <div class="stat-label">Rejected</div>
            </div>
            <div class="stat-value" style="color:var(--danger);">{{ $counts['rejected'] }}</div>
        </div>
    </div>

    <div class="card" style="overflow:hidden;">
        <table class="gapply-table">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Email</th>
                    <th>Job Applied</th>
                    <th>Resume</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.65rem;">
                            <div class="avatar-initials">{{ strtoupper(substr($app->full_name, 0, 2)) }}</div>
                            <span class="text-strong">{{ $app->full_name }}</span>
                        </div>
                    </td>
                    <td class="text-muted text-small">{{ $app->email }}</td>
                    <td style="font-size:.8125rem;">{{ $app->job->title ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ $app->resume_link }}" target="_blank" style="color:var(--accent);font-size:.78rem;display:inline-flex;align-items:center;gap:.3rem;">
                            Open
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        </a>
                    </td>
                    <td class="text-muted text-small">{{ $app->created_at->format('M d, Y') }}</td>
                    <td>
                        @php $sc = ['Pending'=>'badge-pending','Accepted'=>'badge-accepted','Rejected'=>'badge-rejected']; @endphp
                        <div>
                            <span class="badge {{ $sc[$app->status] ?? '' }}">{{ $app->status }}</span>
                            @if($app->status === 'Rejected' && $app->rejection_reason)
                                <div style="font-size:.68rem;color:var(--text-faint);margin-top:.25rem;max-width:160px;line-height:1.3;">
                                    {{ Str::limit($app->rejection_reason, 60) }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td style="min-width:220px;">
                        <form method="POST" action="{{ route('employer.applications.status', $app) }}"
                              id="form-{{ $app->id }}"
                              style="display:flex;flex-direction:column;gap:.4rem;">
                            @csrf @method('PATCH')
                            <div style="display:flex;gap:.4rem;align-items:center;">
                                <select name="status"
                                        class="form-control"
                                        style="width:115px;padding:.3rem .55rem;font-size:.78rem;"
                                        onchange="toggleReason(this, {{ $app->id }})">
                                    @foreach(['Pending','Accepted','Rejected'] as $s)
                                        <option value="{{ $s }}" {{ $app->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                            </div>
                            {{-- Rejection reason textarea --}}
                            <div id="reason-{{ $app->id }}"
                                 style="display:{{ $app->status === 'Rejected' ? 'block' : 'none' }};">
                                <textarea name="rejection_reason"
                                          placeholder="Reason for rejection (optional)..."
                                          rows="2"
                                          style="width:100%;padding:.4rem .6rem;font-size:.75rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--surface-alt);color:var(--text);font-family:inherit;resize:vertical;outline:none;box-sizing:border-box;">{{ $app->rejection_reason }}</textarea>
                            </div>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text-faint);padding:3rem;">No applications found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-pagination-row">
        {{ $applications->links() }}
    </div>

</div>

<script>
function toggleReason(select, id) {
    const box = document.getElementById('reason-' + id);
    if (box) box.style.display = select.value === 'Rejected' ? 'block' : 'none';
}
</script>
@endsection
