@extends('layouts.app', ['title' => 'Create Assignment'])

@section('content')
    <section class="page-card stack">
        <div>
            <h1 class="section-title">Create Assignment</h1>
            <p class="toolbar-note">Users create assignments for themselves. Admins can also set status and assign reviewers.</p>
        </div>

        <form action="{{ route('assignments.store') }}" method="POST" class="stack">
            @csrf
            @include('assignments._form', ['submitLabel' => 'Create Assignment'])
        </form>
    </section>
@endsection
