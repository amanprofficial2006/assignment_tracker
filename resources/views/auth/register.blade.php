@extends('layouts.app', ['title' => 'Register'])

@section('content')
    <div class="auth-shell">
        <section class="page-card stack">
            <div>
                <h1 class="auth-title">Create Account</h1>
                <p class="toolbar-note">The first registered account becomes admin automatically. All later accounts are regular users.</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="stack">
                @csrf

                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>

                <div class="page-actions">
                    <button class="button" type="submit">Register</button>
                    <a class="button-link secondary" href="{{ route('login') }}">Already have an account?</a>
                </div>
            </form>
        </section>
    </div>
@endsection
