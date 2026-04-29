@extends('layouts.app', ['title' => 'Access Denied'])

@section('content')
    <section class="page-card stack auth-shell">
        <div>
            <h1 class="section-title">Access Denied</h1>
            <p class="toolbar-note">You do not have permission to open this page.</p>
        </div>

        <div class="page-actions">
            @auth
                <a class="button" href="{{ route('assignments.index') }}">Back to Assignments</a>
            @else
                <a class="button" href="{{ route('login') }}">Go to Login</a>
            @endauth
        </div>
    </section>
@endsection
