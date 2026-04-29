@extends('layouts.app')
@section('title', 'Post New Job')

@section('content')
<div class="page-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 class="page-title">Post New Job</h1>
            <p class="page-subtitle">Fill in the details below to publish your job listing.</p>
        </div>
        <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Back to My Jobs
        </a>
    </div>

    <div class="card" style="max-width:720px;">
        <div class="card-body">
            <form method="POST" action="{{ route('employer.jobs.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Company Image Upload --}}
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label">Company / Job Cover Image <span style="color:var(--text-faint);font-weight:400;">(Optional)</span></label>
                    <div id="imagePreviewArea" style="display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">
                        {{-- Placeholder --}}
                        <div id="imagePlaceholder" style="width:100px;height:100px;border:2px dashed var(--border);border-radius:var(--radius-lg);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.5rem;color:var(--text-faint);background:var(--surface-alt);flex-shrink:0;cursor:pointer;" onclick="document.getElementById('job_image').click()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            <span style="font-size:.65rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Upload</span>
                        </div>
                        <img id="imagePreview" src="" alt="Preview" style="display:none;width:100px;height:100px;object-fit:cover;border-radius:var(--radius-lg);border:1px solid var(--border);">
                        <div>
                            <input type="file" id="job_image" name="job_image" accept="image/jpeg,image/jpg,image/png"
                                class="{{ $errors->has('job_image') ? 'is-invalid' : '' }}"
                                style="display:none;"
                                onchange="previewImage(this)">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('job_image').click()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                                Choose Image
                            </button>
                            <p class="form-text" style="margin-top:.4rem;">PNG or JPG, max 3MB</p>
                            @error('job_image')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="title">Job Title <span style="color:var(--danger)">*</span></label>
                        <input type="text" id="title" name="title"
                            class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                            value="{{ old('title') }}" placeholder="e.g. Senior Developer" required>
                        @error('title')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="location">Location <span style="color:var(--danger)">*</span></label>
                        <input type="text" id="location" name="location"
                            class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}"
                            value="{{ old('location') }}" placeholder="e.g. Cebu City, Philippines" required>
                        @error('location')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="employment_type">Employment Type <span style="color:var(--danger)">*</span></label>
                        <select id="employment_type" name="employment_type"
                            class="form-control {{ $errors->has('employment_type') ? 'is-invalid' : '' }}" required>
                            <option value="" disabled selected>Select type</option>
                            @foreach(['Full-time','Part-time','Contract','Internship'] as $type)
                                <option value="{{ $type }}" {{ old('employment_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('employment_type')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status <span style="color:var(--danger)">*</span></label>
                        <select id="status" name="status"
                            class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                            <option value="open" {{ old('status','open') === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Job Description <span style="color:var(--danger)">*</span></label>
                    <textarea id="description" name="description" rows="8"
                        class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                        placeholder="Describe the role, responsibilities, and requirements (minimum 20 characters)..." required>{{ old('description') }}</textarea>
                    @error('description')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                </div>

                <hr class="divider">

                <div style="display:flex;justify-content:flex-end;gap:.75rem;">
                    <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Publish Job
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const placeholder = document.getElementById('imagePlaceholder');
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
