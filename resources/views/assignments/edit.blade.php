@extends('layouts.app', ['title' => 'Edit Assignment'])

@section('content')
    <section class="page-card stack">
        <div>
            <h1 class="section-title">Edit Assignment</h1>
            <p class="toolbar-note">Update the assignment details below. Admins can manage review status and reviewer assignment.</p>
        </div>

        <form action="{{ route('assignments.update', $assignment) }}" method="POST" class="stack">
            @csrf
            @method('PUT')
            @include('assignments._form', ['submitLabel' => 'Save Changes'])
        </form>
    </section>

    <section class="page-card danger-zone">
        <div class="page-actions">
            <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Delete this assignment?');">
                @csrf
                @method('DELETE')
                <button class="button danger" type="submit">Delete Assignment</button>
            </form>
        </div>
    </section>
@endsection
