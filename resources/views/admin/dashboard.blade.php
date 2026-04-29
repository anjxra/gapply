@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">Manage employer and applicant accounts on the platform.</p>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(175px,1fr));gap:1rem;margin-bottom:2rem;">
        <div class="stat-card">
            <div class="stat-left"><div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="15" rx="2"/><path d="M16 7V4a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"/></svg></div><div class="stat-label">Total Employers</div></div>
            <div class="stat-value">{{ $stats['total_employers'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left"><div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div><div class="stat-label">Active Employers</div></div>
            <div class="stat-value">{{ $stats['active_employers'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left"><div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div><div class="stat-label">Total Applicants</div></div>
            <div class="stat-value">{{ $stats['total_applicants'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left"><div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div class="stat-label">Active Applicants</div></div>
            <div class="stat-value">{{ $stats['active_applicants'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-left"><div class="stat-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><div class="stat-label">All Accounts</div></div>
            <div class="stat-value">{{ $stats['all_accounts'] }}</div>
        </div>
    </div>

    <div class="card">
        {{-- Tabs --}}
        <div style="border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem;flex-wrap:wrap;gap:.5rem;">
            <div style="display:flex;">
                <button class="tab-btn active" id="btn-employers" onclick="switchTab('employers')">
                    Employers <span class="tab-count">{{ $stats['total_employers'] }}</span>
                </button>
                <button class="tab-btn" id="btn-applicants" onclick="switchTab('applicants')">
                    Applicants <span class="tab-count">{{ $stats['total_applicants'] }}</span>
                </button>
            </div>
            <button class="btn btn-primary btn-sm" id="addEmpBtn" onclick="toggleForm()">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Employer
            </button>
        </div>

        <div style="padding:1.5rem;">

            {{-- Create Employer Form --}}
            <div id="createEmpForm" style="display:none;background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1.5rem;">
                <form method="POST" action="{{ route('admin.employers.store') }}">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:.75rem;align-items:end;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                value="{{ old('name') }}" placeholder="Gabrielle Lacman" required>
                            @error('name')<div style="color:var(--danger);font-size:.72rem;margin-top:.25rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                value="{{ old('email') }}" placeholder="gablacman@gapply.com" required>
                            @error('email')<div style="color:var(--danger);font-size:.72rem;margin-top:.25rem;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Temporary Password</label>
                            <input type="text" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Min. 6 characters" required>
                            @error('password')<div style="color:var(--danger);font-size:.72rem;margin-top:.25rem;">{{ $message }}</div>@enderror
                        </div>
                        <div><button type="submit" class="btn btn-success">Create</button></div>
                    </div>
                </form>
            </div>

            {{-- Employers Table --}}
            <div id="tab-employers">
                <table class="gapply-table">
                    <thead><tr><th>Name</th><th>Email</th><th>Registered</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($employers as $user)
                        <tr>
                            <td class="text-strong">{{ $user->name }}</td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td class="text-muted text-small">{{ $user->created_at->format('M d, Y') }}</td>
                            <td><span class="badge {{ $user->status === 'active' ? 'badge-active' : 'badge-disabled' }}">{{ ucfirst($user->status) }}</span></td>
                            <td>
                                <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        onclick="openEdit({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}')">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-warning' : 'btn-success' }}">
                                            {{ $user->status === 'active' ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ $user->email }}', '{{ route('admin.users.delete', $user) }}')">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;color:var(--text-faint);padding:2.5rem;">No employer accounts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="table-pagination-row">
                    {{ $employers->links() }}
                </div>
            </div>

            {{-- Applicants Table --}}
            <div id="tab-applicants" style="display:none;">
                <table class="gapply-table">
                    <thead><tr><th>Name</th><th>Email</th><th>Registered</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($applicants as $user)
                        <tr>
                            <td class="text-strong">{{ $user->name }}</td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td class="text-muted text-small">{{ $user->created_at->format('M d, Y') }}</td>
                            <td><span class="badge {{ $user->status === 'active' ? 'badge-active' : 'badge-disabled' }}">{{ ucfirst($user->status) }}</span></td>
                            <td>
                                <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        onclick="openEdit({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}')">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-warning' : 'btn-success' }}">
                                            {{ $user->status === 'active' ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ $user->email }}', '{{ route('admin.users.delete', $user) }}')">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;color:var(--text-faint);padding:2.5rem;">No applicant accounts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="table-pagination-row">
                    {{ $applicants->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Edit Modal ── --}}
<div id="editModal" class="modal-overlay" style="display:none;">
    <div class="modal-box" style="max-width:460px;">
        <div class="modal-title" style="margin-bottom:1.25rem;">Edit Account</div>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" id="editEmail" class="form-control" required>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">New Password <span style="color:var(--text-faint);font-weight:400;">(leave blank to keep unchanged)</span></label>
                <input type="text" name="password" class="form-control" placeholder="Minimum 6 characters">
            </div>
            <hr class="divider" style="margin:1.25rem 0;">
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeEdit()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Delete Modal ── --}}
<div id="deleteModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-title">Confirm Deletion</div>
        <p class="modal-body" id="deleteModalBody">Are you sure you want to delete this account?</p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <form id="deleteForm" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const BASE_UPDATE_URL = '{{ url("admin/users") }}';

    // Persist active tab in URL so pagination doesn't reset it
    function getTabFromUrl() {
        return new URLSearchParams(window.location.search).get('tab') || 'employers';
    }
    function switchTab(tab) {
        document.getElementById('tab-employers').style.display = tab === 'employers' ? 'block' : 'none';
        document.getElementById('tab-applicants').style.display = tab === 'applicants' ? 'block' : 'none';
        document.getElementById('btn-employers').classList.toggle('active', tab === 'employers');
        document.getElementById('btn-applicants').classList.toggle('active', tab === 'applicants');
        const addBtn = document.getElementById('addEmpBtn');
        if (tab === 'applicants') { addBtn.style.display = 'none'; document.getElementById('createEmpForm').style.display = 'none'; }
        else { addBtn.style.display = ''; }
        // Update URL without reloading
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
    }
    // Restore tab on load
    switchTab(getTabFromUrl());

    function toggleForm() {
        const f = document.getElementById('createEmpForm');
        const btn = document.getElementById('addEmpBtn');
        const isOpen = f.style.display !== 'none';
        f.style.display = isOpen ? 'none' : 'block';
        btn.textContent = isOpen ? '+ Add Employer' : 'Cancel';
    }

    function openEdit(id, name, email) {
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editForm').action = BASE_UPDATE_URL + '/' + id;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEdit() { document.getElementById('editModal').style.display = 'none'; }

    function confirmDelete(email, action) {
        document.getElementById('deleteModalBody').textContent = `Delete "${email}"? This cannot be undone.`;
        document.getElementById('deleteForm').action = action;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeModal() { document.getElementById('deleteModal').style.display = 'none'; }

    @if($errors->any())
        document.getElementById('createEmpForm').style.display = 'block';
    @endif
</script>
@endpush
@endsection
