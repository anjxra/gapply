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
<body style="justify-content:center;align-items:center;padding:2rem 1rem;gap:0;">

    {{-- Back to home --}}
    <div style="width:100%;max-width:420px;margin-bottom:.75rem;">
        <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:.4rem;font-size:.8rem;color:var(--text-muted);font-weight:500;transition:color .15s;" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-muted)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Home
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success" style="max-width:420px;width:100%;margin-bottom:1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="max-width:420px;width:100%;margin-bottom:1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @yield('content')

    {{-- Dark mode toggle (floating) --}}
    <button onclick="toggleTheme()" title="Toggle theme"
        style="position:fixed;bottom:1.25rem;right:1.25rem;width:36px;height:36px;border-radius:50%;border:1px solid var(--border);background:var(--surface);color:var(--text-muted);cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:var(--shadow-lg);z-index:50;">
        <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
    </button>

    <p style="font-size:.72rem;color:var(--text-faint);margin-top:1.5rem;">&copy; {{ date('Y') }} Gapply &mdash; All rights reserved.</p>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('gapply_theme', next);
            updateIcons(next);
        }
        function updateIcons(t) {
            const s = document.getElementById('icon-sun');
            const m = document.getElementById('icon-moon');
            if (!s || !m) return;
            s.style.display = t === 'dark' ? 'block' : 'none';
            m.style.display = t === 'dark' ? 'none'  : 'block';
        }
        updateIcons(document.documentElement.getAttribute('data-theme') || 'light');
    </script>
</body>
</html>
