@extends('layouts.app')
@section('title', 'Employer Dashboard')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <h1 class="page-title">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="page-subtitle">Here's an overview of your activity.</p>
    </div>

    {{-- Quick stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(175px,1fr));gap:1rem;margin-bottom:2.5rem;">
        <div class="stat-card">
            <div class="stat-left"><div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div><div class="stat-label">Total Jobs</div></div>
            <div class="stat-value">{{ $jobCount }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left"><div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="20 6 9 17 4 12"/></svg></div><div class="stat-label">Open Jobs</div></div>
            <div class="stat-value">{{ $openCount }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left"><div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><div class="stat-label">Total Applicants</div></div>
            <div class="stat-value">{{ $appCount }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left"><div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div class="stat-label">Pending Review</div></div>
            <div class="stat-value">{{ $pending }}</div>
        </div>
    </div>

    {{-- Action cards — centered --}}
    <div style="display:flex;gap:1.25rem;justify-content:center;flex-wrap:wrap;max-width:640px;margin:0 auto;">

        <a href="{{ route('employer.jobs.index') }}" style="text-decoration:none;flex:1;min-width:240px;max-width:300px;">
            <div class="card" style="padding:2rem 1.5rem;text-align:center;cursor:pointer;transition:box-shadow 0.15s,border-color 0.15s;height:100%;"
                onmouseover="this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='#c7d2fe';"
                onmouseout="this.style.boxShadow='';this.style.borderColor='var(--border)';">
                <div style="width:52px;height:52px;border-radius:var(--radius);background:var(--surface-alt);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;color:var(--text-muted);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
                <div style="font-size:.9375rem;font-weight:700;color:var(--text);margin-bottom:.4rem;">Manage Jobs</div>
                <p style="font-size:.8rem;color:var(--text-muted);margin:0 0 1.25rem;line-height:1.6;">Create, edit, or remove job postings</p>
                <span class="btn btn-primary btn-block" style="font-size:.8125rem;">Go to My Jobs</span>
            </div>
        </a>

        <a href="{{ route('employer.applications.index') }}" style="text-decoration:none;flex:1;min-width:240px;max-width:300px;">
            <div class="card" style="padding:2rem 1.5rem;text-align:center;cursor:pointer;transition:box-shadow 0.15s,border-color 0.15s;height:100%;"
                onmouseover="this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='#c7d2fe';"
                onmouseout="this.style.boxShadow='';this.style.borderColor='var(--border)';">
                <div style="width:52px;height:52px;border-radius:var(--radius);background:var(--surface-alt);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;color:var(--text-muted);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div style="font-size:.9375rem;font-weight:700;color:var(--text);margin-bottom:.4rem;">View Applications</div>
                <p style="font-size:.8rem;color:var(--text-muted);margin:0 0 1.25rem;line-height:1.6;">Review and manage applicants</p>
                <span class="btn btn-primary btn-block" style="font-size:.8125rem;">Go to Applications</span>
            </div>
        </a>

    </div>
</div>
@endsection
