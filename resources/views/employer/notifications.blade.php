@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="page-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 class="page-title">Notifications</h1>
            <p class="page-subtitle">New applications received for your posted jobs.</p>
        </div>
        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
        @if($unread > 0)
        <form method="POST" action="{{ route('employer.notifications.read-all') }}">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">Mark all as read ({{ $unread }})</button>
        </form>
        @endif
    </div>

    @php $notifications = auth()->user()->notifications()->latest()->paginate(8); @endphp

    @if($notifications->isEmpty())
        <div class="card" style="text-align:center;padding:4rem 2rem;">
            <div style="width:52px;height:52px;border-radius:50%;background:var(--surface-alt);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:var(--text-faint);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <h3 style="font-weight:700;color:var(--text);margin-bottom:.4rem;">No notifications yet</h3>
            <p style="color:var(--text-muted);font-size:.8125rem;">When applicants apply to your jobs, you'll be notified here.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:.75rem;">
            @foreach($notifications as $notif)
            @php
                $isUnread  = is_null($notif->read_at);
                $type      = $notif->data['type'] ?? 'new_application';
                $jobTitle  = $notif->data['job_title'] ?? 'a job';
                $appName   = $notif->data['applicant_name'] ?? 'Someone';
                $appEmail  = $notif->data['applicant_email'] ?? '';
                $appId     = $notif->data['application_id'] ?? null;
            @endphp

            <div class="{{ $isUnread ? 'notif-card notif-card-pending notif-card-unread' : 'notif-card' }}">

                {{-- Icon --}}
                <div style="width:38px;height:38px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>

                {{-- Content --}}
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.2rem;">
                        <span style="font-weight:700;font-size:.875rem;color:var(--text);">New Application</span>
                        <span style="font-size:.75rem;color:var(--text-muted);">for</span>
                        <span style="font-weight:600;font-size:.8rem;color:var(--accent);">{{ $jobTitle }}</span>
                        @if($isUnread)
                            <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--accent);">New</span>
                        @endif
                    </div>

                    <p style="font-size:.8rem;color:var(--text-muted);line-height:1.5;margin:0 0 .3rem;">
                        <strong style="color:var(--text);">{{ $appName }}</strong>
                        @if($appEmail) <span style="color:var(--text-faint);">({{ $appEmail }})</span> @endif
                        has submitted an application.
                    </p>

                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-top:.4rem;">
                        @if($appId)
                        <a href="{{ route('employer.applications.index') }}" style="font-size:.76rem;color:var(--accent);font-weight:600;display:inline-flex;align-items:center;gap:.3rem;">
                            Review Application
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                        @endif
                        <span style="font-size:.7rem;color:var(--text-faint);">{{ $notif->created_at->diffForHumans() }} &middot; {{ $notif->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                </div>

                {{-- Mark read --}}
                @if($isUnread)
                <div style="flex-shrink:0;">
                    <form method="POST" action="{{ route('employer.notifications.read', $notif->id) }}">
                        @csrf
                        <button type="submit" style="font-size:.72rem;color:var(--accent);background:none;border:none;cursor:pointer;padding:0;font-family:inherit;white-space:nowrap;">Mark read</button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div style="margin-top:1.5rem;">{{ $notifications->links() }}</div>
    @endif

</div>
@endsection
