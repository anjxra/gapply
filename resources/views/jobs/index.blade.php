@extends('layouts.app')
@section('title', 'Browse Jobs')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <h1 class="page-title">Browse Open Jobs</h1>
        <p class="page-subtitle">{{ $jobs->total() }} position{{ $jobs->total() !== 1 ? 's' : '' }} available</p>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('jobs.index') }}" class="job-search-form" style="display:flex;gap:.75rem;margin-bottom:2rem;flex-wrap:wrap;">
        <div style="flex:1;min-width:220px;display:flex;align-items:center;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:0 .85rem;gap:.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--text-faint);flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Job title, company, or location..."
                class="form-control" style="border:none;box-shadow:none;padding:.5rem 0;background:transparent;">
        </div>
        <select name="type" class="form-control" style="width:auto;min-width:145px;">
            <option value="">All Types</option>
            @foreach(['Full-time','Part-time','Contract','Internship'] as $type)
                <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request('search') || request('type'))
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Clear</a>
        @endif
    </form>

    @if($jobs->isEmpty())
        <div class="card" style="text-align:center;padding:4rem 2rem;">
            <div style="width:48px;height:48px;border-radius:var(--radius);background:var(--surface-alt);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:var(--text-faint);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <h3 style="font-weight:700;color:var(--text);margin-bottom:.4rem;">No jobs found</h3>
            <p style="color:var(--text-muted);font-size:.8125rem;margin-bottom:1.25rem;">Try different keywords or check back later.</p>
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary btn-sm">Clear Search</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:.75rem;">
            @foreach($jobs as $job)
            <div class="job-card">
                <div style="display:flex;align-items:center;gap:1rem;flex:1;min-width:0;">
                    {{-- Company image or initials --}}
                    @if($job->job_image)
                        <img src="{{ asset('images/jobs/' . $job->job_image) }}"
                             alt="{{ $job->employer->name }}"
                             style="width:48px;height:48px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border);flex-shrink:0;">
                    @else
                        <div class="job-logo">{{ strtoupper(substr($job->employer->name, 0, 2)) }}</div>
                    @endif
                    <div style="min-width:0;">
                        <div style="font-weight:600;color:var(--text);margin-bottom:.15rem;font-size:.9rem;">{{ $job->title }}</div>
                        <div style="font-size:.78rem;color:var(--text-muted);">{{ $job->employer->name }} &middot; {{ $job->location }}</div>
                        <div style="margin-top:.4rem;">
                            @php $tc = ['Full-time'=>'badge-full-time','Part-time'=>'badge-part-time','Contract'=>'badge-contract','Internship'=>'badge-internship'][$job->employment_type] ?? ''; @endphp
                            <span class="badge {{ $tc }}">{{ $job->employment_type }}</span>
                        </div>
                    </div>
                </div>
                <div style="flex-shrink:0;">
                    <a href="{{ route('jobs.show', $job) }}" class="btn btn-secondary btn-sm">View Job</a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="table-pagination-row" style="margin-top:.5rem;border-top:none;">
            <span class="table-pagination-info">Showing {{ $jobs->firstItem() }}–{{ $jobs->lastItem() }} of {{ $jobs->total() }} results</span>
            {{ $jobs->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
