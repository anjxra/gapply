@extends('layouts.app')
@section('title', 'Edit Job')

@section('content')
<div class="page-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 class="page-title">Edit Job Posting</h1>
            <p class="page-subtitle">Update the details for "{{ $job->title }}".</p>
        </div>
        <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Back to My Jobs
        </a>
    </div>

    <div class="card" style="max-width:720px;">
        <div class="card-body">
            <form method="POST" action="{{ route('employer.jobs.update', $job) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Company Image Upload --}}
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label">Company / Job Cover Image</label>
                    <div style="display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">

                        {{-- Current image or placeholder --}}
                        @if($job->job_image)
                            <img id="imagePreview" src="{{ asset('storage/jobs/' . $job->job_image) }}"
                                 alt="Current image"
                                 style="width:100px;height:100px;object-fit:cover;border-radius:var(--radius-lg);border:1px solid var(--border);flex-shrink:0;">
                        @else
                            <div id="imagePlaceholder" style="width:100px;height:100px;border:2px dashed var(--border);border-radius:var(--radius-lg);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.5rem;color:var(--text-faint);background:var(--surface-alt);flex-shrink:0;cursor:pointer;" onclick="document.getElementById('job_image').click()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                <span style="font-size:.65rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Upload</span>
                            </div>
                            <img id="imagePreview" src="" alt="Preview" style="display:none;width:100px;height:100px;object-fit:cover;border-radius:var(--radius-lg);border:1px solid var(--border);flex-shrink:0;">
                        @endif

                        <div>
                            <input type="file" id="job_image" name="job_image" accept="image/jpeg,image/jpg,image/png"
                                class="{{ $errors->has('job_image') ? 'is-invalid' : '' }}"
                                style="display:none;"
                                onchange="previewImage(this)">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('job_image').click()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                                {{ $job->job_image ? 'Replace Image' : 'Choose Image' }}
                            </button>
                            <p class="form-text" style="margin-top:.4rem;">PNG or JPG, max 3MB. Leave blank to keep current image.</p>
                            @error('job_image')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="title">Job Title <span style="color:var(--danger)">*</span></label>
                        <input type="text" id="title" name="title"
                            class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                            value="{{ old('title', $job->title) }}" required>
                        @error('title')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="location">Location <span style="color:var(--danger)">*</span></label>
                        <input type="text" id="location" name="location"
                            class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}"
                            value="{{ old('location', $job->location) }}" required>
                        @error('location')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="employment_type">Employment Type <span style="color:var(--danger)">*</span></label>
                        <select id="employment_type" name="employment_type" class="form-control" required>
                            @foreach(['Full-time','Part-time','Contract','Internship'] as $type)
                                <option value="{{ $type }}" {{ old('employment_type', $job->employment_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status <span style="color:var(--danger)">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="open"   {{ old('status', $job->status) === 'open'   ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ old('status', $job->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Job Description <span style="color:var(--danger)">*</span></label>
                    <textarea id="description" name="description" rows="8"
                        class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" required>{{ old('description', $job->description) }}</textarea>
                    @error('description')<div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>@enderror
                </div>

                <hr class="divider">

                <div style="display:flex;justify-content:flex-end;gap:.75rem;">
                    <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePlaceholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
