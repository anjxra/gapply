@extends('layouts.app')
@section('title', $job->title)

@section('content')
<div class="page-wrapper">

    {{-- Breadcrumb --}}
    <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:1.5rem;display:flex;align-items:center;gap:.5rem;">
        <a href="{{ route('jobs.index') }}" style="color:var(--accent);">Job Board</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        <span>{{ $job->title }}</span>
    </div>

    <div class="card" style="max-width:820px;">

        {{-- Job cover image banner --}}
        @if($job->job_image)
        <div style="width:100%;height:200px;overflow:hidden;border-radius:var(--radius-lg) var(--radius-lg) 0 0;border-bottom:1px solid var(--border);">
            <img src="{{ asset('images/jobs/' . $job->job_image) }}"
                 alt="{{ $job->title }}"
                 style="width:100%;height:100%;object-fit:cover;">
        </div>
        @endif

        <div class="card-body">

            {{-- Job Header --}}
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
                <div style="display:flex;align-items:flex-start;gap:1.125rem;">
                    {{-- Company logo/initials --}}
                    @if($job->job_image)
                        <img src="{{ asset('images/jobs/' . $job->job_image) }}"
                             alt="{{ $job->employer->name }}"
                             style="width:56px;height:56px;object-fit:cover;border-radius:var(--radius-lg);border:1px solid var(--border);flex-shrink:0;">
                    @else
                        <div class="job-logo" style="width:56px;height:56px;font-size:.875rem;border-radius:var(--radius-lg);flex-shrink:0;">
                            {{ strtoupper(substr($job->employer->name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <h1 style="font-size:1.375rem;font-weight:800;color:var(--text);margin-bottom:.3rem;line-height:1.2;">{{ $job->title }}</h1>
                        <p style="font-size:.8125rem;color:var(--text-muted);margin-bottom:.75rem;">{{ $job->employer->name }} &middot; {{ $job->location }}</p>
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            @php $tc = ['Full-time'=>'badge-full-time','Part-time'=>'badge-part-time','Contract'=>'badge-contract','Internship'=>'badge-internship'][$job->employment_type] ?? ''; @endphp
                            <span class="badge {{ $tc }}">{{ $job->employment_type }}</span>
                            <span class="badge badge-open">Open</span>
                            <span class="badge" style="background:var(--surface-alt);color:var(--text-muted);border-color:var(--border);">{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->isApplicant())
                        <a href="{{ route('applicant.apply', $job) }}" class="btn btn-primary">Apply Now</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login to Apply</a>
                @endauth
            </div>

            <hr class="divider">

            {{-- Info grid --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.75rem;margin-bottom:1.5rem;">
                @foreach([['Location',$job->location],['Type',$job->employment_type],['Status',ucfirst($job->status)],['Company',$job->employer->name]] as [$label,$val])
                <div style="background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius);padding:.75rem 1rem;">
                    <div style="font-size:.68rem;color:var(--text-faint);margin-bottom:.2rem;text-transform:uppercase;letter-spacing:.08em;font-weight:600;">{{ $label }}</div>
                    <div style="font-size:.875rem;font-weight:600;color:var(--text);">{{ $val }}</div>
                </div>
                @endforeach
            </div>

            {{-- Description --}}
            <div style="margin-bottom:1.75rem;">
                <h3 style="font-size:.875rem;font-weight:700;color:var(--text);margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.04em;">Job Description</h3>
                <div style="font-size:.875rem;color:var(--text-muted);line-height:1.8;white-space:pre-line;">{{ $job->description }}</div>
            </div>

            <hr class="divider">

            <div style="display:flex;justify-content:flex-end;">
                @auth
                    @if(auth()->user()->isApplicant())
                        @php $applied = \App\Models\Application::where('job_id',$job->id)->where('applicant_id',auth()->id())->exists(); @endphp
                        @if($applied)
                            <span class="badge badge-accepted" style="padding:.5rem 1rem;font-size:.8rem;">Already Applied</span>
                        @else
                            <a href="{{ route('applicant.apply', $job) }}" class="btn btn-primary">Apply for this Position</a>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login to Apply</a>
                @endauth
            </div>
        </div>
    </div>

    <div style="margin-top:1rem;">
        <a href="{{ route('jobs.index') }}" style="color:var(--text-muted);font-size:.8125rem;display:inline-flex;align-items:center;gap:.4rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Job Board
        </a>
    </div>

</div>
@endsection
