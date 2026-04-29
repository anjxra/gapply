<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gapply')</title>
    <link rel="icon" href="data:,">
    <script>(function(){var t=localStorage.getItem('gapply_theme')||'light';document.documentElement.setAttribute('data-theme',t);})()</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/gapply.css') }}">
</head>
<body>

{{-- ═══ NAVBAR ═══ --}}
<nav class="gapply-nav">
    <div class="gapply-nav-inner">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="gapply-logo">
            <img src="{{ asset('images/gapply-logo-inline.jpg') }}"
                 class="logo-img" alt="Gapply" style="height:27px;width:auto;">
            <span class="logo-subtitle">Hiring Platform</span>
        </a>

        <div style="flex:1;"></div>

        {{-- ── Nav Links: desktop only, hidden on mobile ── --}}
        <div class="nav-links-desktop">

            @guest
                <a href="{{ route('jobs.index') }}" class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    <span class="nav-label">Browse Jobs</span>
                </a>
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a>
            @endguest

            @auth
                @if(auth()->user()->role === 'superadmin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span class="nav-label">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        <span class="nav-label">Analytics</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        <span class="nav-label">Reports</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'employer')
                    <a href="{{ route('employer.dashboard') }}" class="nav-link {{ request()->routeIs('employer.dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                        <span class="nav-label">Dashboard</span>
                    </a>
                    <a href="{{ route('employer.jobs.index') }}" class="nav-link {{ request()->routeIs('employer.jobs.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        <span class="nav-label">My Jobs</span>
                    </a>
                    <a href="{{ route('employer.applications.index') }}" class="nav-link {{ request()->routeIs('employer.applications.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span class="nav-label">Applications</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'applicant')
                    <a href="{{ route('jobs.index') }}" class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        <span class="nav-label">Browse Jobs</span>
                    </a>
                    <a href="{{ route('applicant.applications') }}" class="nav-link {{ request()->routeIs('applicant.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        <span class="nav-label">My Applications</span>
                    </a>
                @endif

                <div style="width:1px;height:20px;background:var(--border);margin:0 .3rem;flex-shrink:0;"></div>
            @endauth

        </div>{{-- end nav-links-desktop --}}

        {{-- ── Always-visible: theme + notif + profile + hamburger ── --}}
        <div class="nav-actions">

            {{-- Theme Toggle --}}
            <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>

            @auth
            {{-- Notification Bell — Applicants + Employers — ALWAYS visible (desktop + mobile) --}}
            @if(in_array(auth()->user()->role, ['applicant', 'employer']))
            @php
                $unreadNotifs = auth()->user()->unreadNotifications;
                $viewAllRoute = auth()->user()->role === 'employer'
                    ? route('employer.notifications')
                    : route('applicant.notifications');
                $readAllRoute = auth()->user()->role === 'employer'
                    ? route('employer.notifications.read-all')
                    : route('notifications.read-all');
                $readOneRouteName = auth()->user()->role === 'employer'
                    ? 'employer.notifications.read'
                    : 'notifications.read';
            @endphp
            <div class="nav-notif" id="navNotif">
                <button class="nav-notif-btn" onclick="toggleNotif(event)" title="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    @if($unreadNotifs->count() > 0)
                        <span class="notif-badge">{{ $unreadNotifs->count() }}</span>
                    @endif
                </button>
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <span style="font-weight:700;font-size:.8rem;color:var(--text);">Notifications</span>
                        <div style="display:flex;gap:.75rem;align-items:center;">
                            @if($unreadNotifs->count() > 0)
                            <form method="POST" action="{{ $readAllRoute }}" style="display:inline;">
                                @csrf
                                <button type="submit" style="font-size:.72rem;color:var(--text-muted);background:none;border:none;cursor:pointer;padding:0;font-family:inherit;">Mark all read</button>
                            </form>
                            @endif
                            <a href="{{ $viewAllRoute }}" style="font-size:.72rem;color:var(--accent);font-weight:600;">View All</a>
                        </div>
                    </div>
                    @php $allNotifs = auth()->user()->notifications()->latest()->take(8)->get(); @endphp
                    @forelse($allNotifs as $notif)
                    <div class="notif-item {{ $notif->read_at ? '' : 'notif-unread' }}" style="position:relative;cursor:pointer;"
                         onclick="window.location='{{ $viewAllRoute }}'">
                        @php
                            $notifType = $notif->data['type'] ?? 'status_update';
                            if ($notifType === 'new_application') {
                                $dotColor = 'var(--accent)';
                            } else {
                                $status = $notif->data['status'] ?? 'Pending';
                                $statusColors = ['Accepted'=>'var(--success)','Rejected'=>'var(--danger)','Pending'=>'#d97706'];
                                $dotColor = $statusColors[$status] ?? 'var(--text-muted)';
                            }
                        @endphp
                        <div style="display:flex;align-items:flex-start;gap:.6rem;">
                            <div style="width:8px;height:8px;border-radius:50%;background:{{ $dotColor }};flex-shrink:0;margin-top:4px;"></div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:.78rem;color:var(--text);line-height:1.4;">{{ $notif->data['message'] ?? '' }}</div>
                                <div style="font-size:.68rem;color:var(--text-faint);margin-top:.2rem;">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @if(!$notif->read_at)
                        <form method="POST" action="{{ route($readOneRouteName, $notif->id) }}" style="margin-top:.4rem;margin-left:1.4rem;position:relative;z-index:1;"
                              onclick="event.stopPropagation()">
                            @csrf
                            <button type="submit" style="font-size:.68rem;color:var(--accent);background:none;border:none;cursor:pointer;padding:0;font-family:inherit;">Dismiss</button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <div style="padding:1.5rem;text-align:center;color:var(--text-faint);font-size:.8rem;">No notifications yet.</div>
                    @endforelse
                </div>
            </div>
            @endif

            {{-- Profile Dropdown — ALWAYS visible --}}
            <div class="nav-profile" id="navProfile">
                <button class="nav-profile-btn" onclick="toggleProfile(event)">
                    <div class="nav-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <span class="nav-username">{{ auth()->user()->name }}</span>
                    <svg class="nav-chevron" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-dropdown">
                    <div class="dropdown-info">
                        <div class="dropdown-name">{{ auth()->user()->name }}</div>
                        <div class="dropdown-role">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <hr class="dropdown-divider">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
            @endauth

            {{-- Hamburger — CSS shows this only on mobile --}}
            <button class="hamburger" id="hamburgerBtn" onclick="toggleMobileMenu()" aria-label="Menu">
                <svg id="ham-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>

        </div>{{-- end nav-actions --}}
    </div>

    {{-- Mobile Menu Drawer --}}
    <div class="mobile-menu" id="mobileMenu">
        @guest
            <a href="{{ route('jobs.index') }}" class="mobile-nav-link">Browse Jobs</a>
            <a href="{{ route('login') }}" class="mobile-nav-link">Login</a>
            <a href="{{ route('register') }}" class="mobile-nav-link" style="color:var(--accent);font-weight:600;">Sign Up</a>
        @endguest
        @auth
            @if(auth()->user()->role === 'superadmin')
                <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link">Dashboard</a>
                <a href="{{ route('admin.analytics') }}" class="mobile-nav-link">Analytics</a>
                <a href="{{ route('admin.reports') }}" class="mobile-nav-link">Reports</a>
            @endif
            @if(auth()->user()->role === 'employer')
                <a href="{{ route('employer.dashboard') }}" class="mobile-nav-link">Dashboard</a>
                <a href="{{ route('employer.jobs.index') }}" class="mobile-nav-link">My Jobs</a>
                <a href="{{ route('employer.applications.index') }}" class="mobile-nav-link">Applications</a>
            @endif
            @if(auth()->user()->role === 'applicant')
                <a href="{{ route('jobs.index') }}" class="mobile-nav-link">Browse Jobs</a>
                <a href="{{ route('applicant.applications') }}" class="mobile-nav-link">My Applications</a>
            @endif
            <div class="mobile-divider"></div>
            <div style="padding:.5rem 1.25rem;font-size:.78rem;color:var(--text-muted);">{{ auth()->user()->name }} &middot; {{ ucfirst(auth()->user()->role) }}</div>
            <form method="POST" action="{{ route('logout') }}" style="padding:.25rem 1rem .75rem;">
                @csrf
                <button type="submit" class="mobile-nav-link" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;font-family:inherit;color:var(--danger);">Sign Out</button>
            </form>
        @endauth
    </div>
</nav>

{{-- ═══ Flash Messages ═══ --}}
@if(session('success') || session('error') || session('warning'))
<div style="max-width:1200px;margin:1rem auto;padding:0 1.5rem;">
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif
</div>
@endif

<main style="flex:1;">@yield('content')</main>

<footer class="gapply-footer">
    <p>&copy; {{ date('Y') }} <strong>Gapply</strong> &mdash; All rights reserved.</p>
</footer>

@stack('scripts')
<script>
    /* ── Theme ── */
    function toggleTheme() {
        const html = document.documentElement;
        const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('gapply_theme', next);
        updateThemeIcons(next);
    }
    function updateThemeIcons(theme) {
        const sun  = document.getElementById('icon-sun');
        const moon = document.getElementById('icon-moon');
        if (sun)  sun.style.display  = theme === 'dark' ? 'block' : 'none';
        if (moon) moon.style.display = theme === 'dark' ? 'none'  : 'block';
    }
    updateThemeIcons(document.documentElement.getAttribute('data-theme') || 'light');

    /* ── Profile Dropdown ── */
    function toggleProfile(event) {
        event.stopPropagation();
        document.getElementById('navProfile')?.classList.toggle('open');
        document.getElementById('navNotif')?.classList.remove('open');
    }

    /* ── Notification Dropdown ── */
    function toggleNotif(event) {
        event.stopPropagation();
        document.getElementById('navNotif')?.classList.toggle('open');
        document.getElementById('navProfile')?.classList.remove('open');
    }

    document.addEventListener('click', function(e) {
        if (!document.getElementById('navProfile')?.contains(e.target))
            document.getElementById('navProfile')?.classList.remove('open');
        if (!document.getElementById('navNotif')?.contains(e.target))
            document.getElementById('navNotif')?.classList.remove('open');
    });

    /* ── Mobile Menu ── */
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const ham  = document.getElementById('ham-icon');
        const cls  = document.getElementById('close-icon');
        const open = menu.classList.toggle('open');
        ham.style.display = open ? 'none'  : '';
        cls.style.display = open ? 'block' : 'none';
    }

    /* ── Collapse inline grid-template-columns on mobile ── */
    function collapseInlineGrids() {
        const mobile = window.innerWidth <= 768;
        document.querySelectorAll('[style*="grid-template-columns"]:not(.js-keep-grid)').forEach(el => {
            if (mobile) {
                if (!el.dataset.ogGrid) el.dataset.ogGrid = el.style.gridTemplateColumns;
                el.style.gridTemplateColumns = '1fr';
            } else if (el.dataset.ogGrid) {
                el.style.gridTemplateColumns = el.dataset.ogGrid;
                delete el.dataset.ogGrid;
            }
        });
    }
    document.addEventListener('DOMContentLoaded', collapseInlineGrids);
    window.addEventListener('resize', collapseInlineGrids);
</script>
</body>
</html>
