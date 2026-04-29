@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<div class="card" style="width:100%;max-width:400px;">
    <div class="card-body">

        {{-- Stacked logo — no divider line --}}
        <div style="text-align:center;margin-bottom:1.75rem;">
            <img src="{{ asset('images/gapply-logo-stacked.jpg') }}"
                 class="logo-img"
                 alt="Gapply"
                 style="height:130px;width:auto;margin:0 auto;">
        </div>

        <div style="text-align:center;margin-bottom:1.5rem;">
            <h1 style="font-size:1.05rem;font-weight:700;color:var(--text);margin-bottom:.2rem;">Sign in to your account</h1>
            <p style="font-size:.8rem;color:var(--text-muted);">Welcome back</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email address</label>
                <input type="email" id="email" name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}" placeholder="gablacman@gapply.com" required autofocus>
                @error('email')
                    <div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="••••••••" required>
                @error('password')
                    <div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;align-items:center;margin-bottom:1.25rem;gap:.5rem;">
                <input type="checkbox" id="remember" name="remember" style="width:15px;height:15px;accent-color:var(--accent);cursor:pointer;">
                <label for="remember" style="font-size:.8125rem;color:var(--text-muted);cursor:pointer;">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </form>

        <hr class="divider" style="margin:1.25rem 0;">

        <p style="text-align:center;font-size:.8125rem;color:var(--text-muted);">
            Don't have an account?
            <a href="{{ route('register') }}" style="color:var(--accent);font-weight:600;">Register here</a>
        </p>
    </div>
</div>
@endsection
