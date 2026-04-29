@extends('layouts.guest')
@section('title', 'Register')

@section('content')
<div class="card" style="width:100%;max-width:420px;">
    <div class="card-body">

        {{-- Stacked logo — no divider line --}}
        <div style="text-align:center;margin-bottom:1.75rem;">
            <img src="{{ asset('images/gapply-logo-stacked.jpg') }}"
                 class="logo-img"
                 alt="Gapply"
                 style="height:130px;width:auto;margin:0 auto;">
        </div>

        <div style="text-align:center;margin-bottom:1.5rem;">
            <h1 style="font-size:1.05rem;font-weight:700;color:var(--text);margin-bottom:.2rem;">Create an account</h1>
            <p style="font-size:.8rem;color:var(--text-muted);">Fill in the details below to get started</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input type="text" id="name" name="name"
                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    value="{{ old('name') }}" placeholder="Gabrielle Lacman" required autofocus>
                @error('name')
                    <div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email address</label>
                <input type="email" id="email" name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}" placeholder="gablacman@gapply.com" required>
                @error('email')
                    <div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="Minimum 6 characters" required>
                @error('password')
                    <div style="color:var(--danger);font-size:.75rem;margin-top:.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </form>

        <hr class="divider" style="margin:1.25rem 0;">

        <p style="text-align:center;font-size:.8125rem;color:var(--text-muted);">
            Already have an account?
            <a href="{{ route('login') }}" style="color:var(--accent);font-weight:600;">Sign in</a>
        </p>
    </div>
</div>
@endsection
