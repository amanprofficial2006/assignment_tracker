<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name', 'AgileTech') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @php($currentUser = auth()->user())

    <div class="app-shell">
        <header class="topbar">
            <div>
                <a class="brand" href="{{ $currentUser ? ($currentUser->isAdmin() ? route('admin.dashboard') : route('assignments.index')) : route('login') }}">
                    AgileTech
                </a>
                <p class="subtitle">Simple assignment tracking with role-based access.</p>
            </div>

            <div class="topbar-side">
                @if ($currentUser)
                    <div class="user-chip">
                        Signed in as <strong>{{ $currentUser->name }}</strong> ({{ ucfirst($currentUser->role) }})
                    </div>
                @endif

                <nav class="nav-links">
                    @guest
                        <a class="nav-link {{ request()->routeIs('login') ? 'is-active' : '' }}" href="{{ route('login') }}">Login</a>
                        <a class="nav-link {{ request()->routeIs('register') ? 'is-active' : '' }}" href="{{ route('register') }}">Register</a>
                    @endguest

                    @auth
                        <a class="nav-link {{ request()->routeIs('assignments.*') ? 'is-active' : '' }}" href="{{ route('assignments.index') }}">Assignments</a>
                        <a class="nav-link {{ request()->routeIs('assignments.create') ? 'is-active' : '' }}" href="{{ route('assignments.create') }}">New Assignment</a>

                        @if ($currentUser->isAdmin())
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="button secondary" type="submit">Logout</button>
                        </form>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="page-shell">
            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if (session('status'))
                <div class="alert info">{{ session('status') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
