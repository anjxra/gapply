@extends('layouts.app')
@section('title', 'Home')

@section('content')

{{-- ══ HERO ══ --}}
<section class="hero-section">
    <div style="max-width:660px;margin:0 auto;">

        {{-- Hero logo: horizontally centered block, subtitle indented to align under 'gapply' text --}}
        <div style="display:flex;justify-content:center;margin-bottom:2.5rem;">
            <div style="display:inline-flex;flex-direction:column;align-items:flex-start;gap:0;">
                <img src="{{ asset('images/gapply-logo-inline.jpg') }}"
                     alt="Gapply"
                     style="height:58px;width:auto;display:block;filter:invert(1);mix-blend-mode:screen;">
                <span style="font-family:'Space Grotesk',sans-serif;font-size:.6rem;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.42);font-weight:500;line-height:1;margin-top:1px;padding-left:66px;">Hiring Platform</span>
            </div>
        </div>

        <h1 class="hero-title">Hiring, Simplified.<br><span style="opacity:.75;">Careers, Amplified.</span></h1>
        <p class="hero-subtitle">Built for Everyone</p>
        <div class="hero-cta">
            <a href="{{ route('jobs.index') }}" class="btn-hero-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Browse Open Jobs
            </a>
            <a href="{{ route('register') }}" class="btn-hero-secondary">
                Create Free Account
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ══ STATS STRIP ══ --}}
<section style="background:var(--surface);border-bottom:1px solid var(--border);padding:2rem 1.5rem;">
    <div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:2rem;text-align:center;">
        @php
            $openJobs = \App\Models\Job::where('status','open')->count();
            $empCount = \App\Models\User::where('role','employer')->count();
            $appCount = \App\Models\User::where('role','applicant')->count();
        @endphp
        <div>
            <div style="font-size:2rem;font-weight:800;color:var(--accent);">{{ $openJobs }}+</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-top:.25rem;font-weight:500;">Open Positions</div>
        </div>
        <div>
            <div style="font-size:2rem;font-weight:800;color:var(--accent);">{{ $empCount }}+</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-top:.25rem;font-weight:500;">Registered Employers</div>
        </div>
        <div>
            <div style="font-size:2rem;font-weight:800;color:var(--accent);">{{ $appCount }}+</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-top:.25rem;font-weight:500;">Active Applicants</div>
        </div>
        <div>
            <div style="font-size:2rem;font-weight:800;color:var(--accent);">100%</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-top:.25rem;font-weight:500;">Free to Apply</div>
        </div>
    </div>
</section>

{{-- ══ FEATURES ══ --}}
<section style="padding:4rem 1.5rem;background:var(--bg);">
    <div style="max-width:1100px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:3rem;">
            <h2 style="font-size:1.5rem;font-weight:800;color:var(--text);margin-bottom:.5rem;letter-spacing:-.3px;">Why Gapply?</h2>
            <p style="color:var(--text-muted);font-size:.875rem;max-width:420px;margin:0 auto;">Everything you need to connect the right people with the right opportunities — fast.</p>
        </div>

        <div class="feature-grid">
            {{-- Card 1 --}}
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <div class="feature-title">Find Your Match, Faster</div>
                <div class="feature-desc">Smart filtering and clean listings make it easy to discover roles that actually fit — no noise, no wasted time. Your next opportunity is one search away.</div>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm" style="margin-top:1.25rem;">Explore Open Roles</a>
            </div>

            {{-- Card 2 --}}
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div class="feature-title">Stay in the Loop</div>
                <div class="feature-desc">Real-time status updates keep everyone informed from the moment a position is posted to the moment an offer is made. Transparency at every step.</div>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="margin-top:1.25rem;">Get Started Free</a>
            </div>

            {{-- Card 3 --}}
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                </div>
                <div class="feature-title">Everything, Organized</div>
                <div class="feature-desc">One clean dashboard. All your listings, applicants, and decisions — structured, searchable, and always up to date. No spreadsheets required.</div>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="margin-top:1.25rem;">Start Hiring Today</a>
            </div>
        </div>
    </div>
</section>

{{-- ══ LATEST JOBS ══ --}}
<section style="background:var(--surface);padding:4rem 1.5rem;border-top:1px solid var(--border);">
    <div style="max-width:900px;margin:0 auto;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:var(--text);margin-bottom:.25rem;letter-spacing:-.3px;">Latest Openings</h2>
                <p style="color:var(--text-muted);font-size:.8125rem;">Fresh opportunities posted recently</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary btn-sm">View All Jobs</a>
        </div>

        <div style="display:flex;flex-direction:column;gap:.75rem;">
            @foreach(\App\Models\Job::where('status','open')->with('employer')->latest()->take(4)->get() as $job)
            <div class="job-card" style="cursor:pointer;" onclick="window.location='{{ route('jobs.show', $job) }}'">
                <div style="display:flex;align-items:center;gap:1rem;flex:1;">
                    {{-- Company image or initials --}}
                    @if($job->job_image)
                        <img src="{{ asset('images/jobs/' . $job->job_image) }}"
                             alt="{{ $job->employer->name }}"
                             style="width:48px;height:48px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border);flex-shrink:0;">
                    @else
                        <div class="job-logo">{{ strtoupper(substr($job->employer->name, 0, 2)) }}</div>
                    @endif
                    <div>
                        <div style="font-weight:600;color:var(--text);margin-bottom:.15rem;font-size:.9rem;">{{ $job->title }}</div>
                        <div style="font-size:.78rem;color:var(--text-muted);">{{ $job->employer->name }} &middot; {{ $job->location }}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;flex-shrink:0;">
                    @php
                        $tc = ['Full-time'=>'badge-full-time','Part-time'=>'badge-part-time','Contract'=>'badge-contract','Internship'=>'badge-internship'][$job->employment_type] ?? '';
                    @endphp
                    <span class="badge {{ $tc }}">{{ $job->employment_type }}</span>
                    <a href="{{ route('jobs.show', $job) }}" class="btn btn-secondary btn-sm" onclick="event.stopPropagation()">View</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA BANNER ══ --}}
<section style="background:#1d2d3f;color:#fff;padding:4rem 1.5rem;text-align:center;">
    <h2 style="font-size:1.75rem;font-weight:800;margin-bottom:.75rem;letter-spacing:-.5px;">Ready to Find Your Next Role?</h2>
    <p style="opacity:.65;margin:0 auto 2rem;max-width:460px;font-size:.9rem;line-height:1.7;">
        Join applicants already using Gapply to land their dream jobs.
    </p>
    <div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('register') }}" class="btn-hero-primary">Create Free Account</a>
        <a href="{{ route('jobs.index') }}" class="btn-hero-secondary">Browse Jobs</a>
    </div>
</section>

@endsection
