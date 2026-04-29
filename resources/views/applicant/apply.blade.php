@extends('layouts.app')
@section('title', 'Apply — ' . $job->title)

@section('content')
<div class="page-wrapper">

    {{-- Breadcrumb --}}
    <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:1.5rem;display:flex;align-items:center;gap:.5rem;">
        <a href="{{ route('jobs.index') }}" style="color:var(--accent);">Job Board</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        <a href="{{ route('jobs.show', $job) }}" style="color:var(--accent);">{{ $job->title }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        <span>Apply</span>
    </div>

    {{-- Job Banner --}}
    <div style="background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius-lg);padding:1rem 1.5rem;margin-bottom:2rem;display:flex;align-items:center;gap:1rem;">
        <div class="job-logo">{{ strtoupper(substr($job->employer->name, 0, 2)) }}</div>
        <div>
            <div style="font-weight:700;color:var(--text);font-size:.9rem;">{{ $job->title }}</div>
            <div style="font-size:.78rem;color:var(--text-muted);margin-top:.15rem;">{{ $job->employer->name }} &middot; {{ $job->location }} &middot; {{ $job->employment_type }}</div>
        </div>
    </div>

    <div class="page-header">
        <h1 class="page-title">Submit Your Application</h1>
    </div>

    <div class="card" style="max-width:640px;">
        <div class="card-body">
            <form method="POST" action="{{ route('applicant.apply.store', $job) }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="full_name">Full Name <span style="color:var(--danger);">*</span></label>
                    <input type="text" id="full_name" name="full_name"
                        class="form-control {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                        value="{{ old('full_name', auth()->user()->name) }}" required>
                    @error('full_name')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address <span style="color:var(--danger);">*</span></label>
                    <input type="email" id="email" name="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="resume_link">Resume / CV Link <span style="color:var(--danger);">*</span></label>
                    <input type="url" id="resume_link" name="resume_link"
                        class="form-control {{ $errors->has('resume_link') ? 'is-invalid' : '' }}"
                        value="{{ old('resume_link') }}" placeholder="https://drive.google.com/..." required>
                    <p class="form-text">Paste a shareable link to your resume (Google Drive, Dropbox, etc.)</p>
                    @error('resume_link')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="cover_note">Cover Note <span style="color:var(--text-faint);font-weight:400;">(Optional)</span></label>
                    <textarea id="cover_note" name="cover_note" class="form-control" rows="5"
                        placeholder="Write a short message to the employer...">{{ old('cover_note') }}</textarea>
                </div>

                <hr class="divider">

                <div style="display:flex;gap:.75rem;justify-content:flex-end;">
                    <a href="{{ route('jobs.show', $job) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
