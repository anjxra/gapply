@extends('layouts.app')
@section('title', 'My Notifications')

@section('content')
<div class="page-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 class="page-title">My Notifications</h1>
            <p class="page-subtitle">Status updates for all your job applications.</p>
        </div>
        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
        @if($unread > 0)
        <form method="POST" action="{{ route('notifications.read-all') }}">
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
            <p style="color:var(--text-muted);font-size:.8125rem;">Once employers update your application status, you'll see it here.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:.75rem;">
            @foreach($notifications as $notif)
            @php
                $status   = $notif->data['status'] ?? 'Pending';
                $reason   = $notif->data['rejection_reason'] ?? null;
                $title    = $notif->data['job_title'] ?? 'a job';
                $isUnread = is_null($notif->read_at);

                $cardClass = [
                    'Accepted' => 'notif-card-accepted',
                    'Rejected' => 'notif-card-rejected',
                    'Pending'  => 'notif-card-pending',
                ][$status] ?? 'notif-card-pending';

                $iconColor = [
                    'Accepted' => 'var(--success)',
                    'Rejected' => 'var(--danger)',
                    'Pending'  => '#d97706',
                ][$status] ?? '#d97706';

                $icons = [
                    'Accepted' => '<circle cx="12" cy="12" r="10"/><polyline points="20 6 9 17 4 12"/>',
                    'Rejected' => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>',
                    'Pending'  => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                ];
                $icon = $icons[$status] ?? $icons['Pending'];

                $badgeClass = ['Accepted'=>'badge-accepted','Rejected'=>'badge-rejected','Pending'=>'badge-pending'][$status] ?? 'badge-pending';
            @endphp

            <div class="notif-card {{ $cardClass }} {{ $isUnread ? 'notif-card-unread' : '' }}">

                {{-- Status Icon Circle --}}
                <div style="width:38px;height:38px;border-radius:50%;background:{{ $iconColor }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                </div>

                {{-- Content --}}
                <div style="flex:1;min-width:0;">
                    {{-- Title row --}}
                    <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.25rem;">
                        <span style="font-weight:700;font-size:.875rem;color:var(--text);">{{ $title }}</span>
                        <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                        @if($isUnread)
                            <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:{{ $iconColor }};">New</span>
                        @endif
                    </div>

                    {{-- Message --}}
                    <p style="font-size:.8rem;color:var(--text-muted);line-height:1.5;margin:0 0 .3rem;">
                        {{ $notif->data['message'] ?? '' }}
                    </p>

                    {{-- Rejection Reason block --}}
                    @if($status === 'Rejected' && $reason)
                    <div class="rejection-reason-block">
                        <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--danger);margin-bottom:.2rem;">Reason for Rejection</div>
                        <p style="font-size:.8rem;color:var(--text);line-height:1.5;margin:0;">{{ $reason }}</p>
                    </div>
                    @elseif($status === 'Rejected' && !$reason)
                    <div style="font-size:.75rem;color:var(--text-faint);font-style:italic;margin-bottom:.3rem;">No specific reason was provided.</div>
                    @endif

                    {{-- Timestamp --}}
                    <div style="font-size:.7rem;color:var(--text-faint);margin-top:.1rem;">
                        {{ $notif->created_at->diffForHumans() }} &middot; {{ $notif->created_at->format('M d, Y g:i A') }}
                    </div>
                </div>

                {{-- Mark read action --}}
                @if($isUnread)
                <div style="flex-shrink:0;">
                    <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
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
