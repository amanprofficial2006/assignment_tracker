@extends('layouts.app', ['title' => 'Login'])

@section('content')
    <div class="auth-shell">
        <section class="page-card stack">
            <div>
                <h1 class="auth-title">Login</h1>
                <p class="toolbar-note">Use your account to access the assignment system.</p>
            </div>

            <form action="{{ route('login.store') }}" method="POST" class="stack">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
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

                <label class="field-inline" for="remember">
                    <input class="checkbox" id="remember" name="remember" type="checkbox" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span>Keep me signed in</span>
                </label>

                <div class="page-actions">
                    <button class="button" type="submit">Login</button>
                    <a class="button-link secondary" href="{{ route('register') }}">Create account</a>
                </div>
            </form>
        </section>
    </div>
@endsection
