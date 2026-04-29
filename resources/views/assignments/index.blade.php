@extends('layouts.app', ['title' => 'Assignments'])

@section('content')
    @php($isAdmin = auth()->user()->isAdmin())

    <section class="page-card stack">
        <div class="page-header">
            <div>
                <h1 class="section-title">Assignments</h1>
                <p class="toolbar-note">
                    {{ $isAdmin ? 'Admins can review all assignments, set statuses, and assign reviewers.' : 'You can only see and manage assignments that you created.' }}
                </p>
            </div>

            <div class="page-actions">
                @if ($isAdmin)
                    <a class="button secondary" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                @endif
                <a class="button" href="{{ route('assignments.create') }}">Create Assignment</a>
            </div>
        </div>

        <form action="{{ route('assignments.index') }}" method="GET" class="filter-form">
            <div class="field">
                <label for="search">Candidate Name</label>
                <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}" placeholder="Search by candidate name">
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button class="button" type="submit">Apply Filters</button>
                <a class="button-link secondary" href="{{ route('assignments.index') }}">Clear</a>
            </div>
        </form>
    </section>

    <section class="page-card table-card stack">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Status</th>
                        <th>Submission</th>
                        <th>Created By</th>
                        <th>Reviewer</th>
                        <th>Created</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->candidate_name }}</td>
                            <td>
                                <span class="status-pill status-{{ $assignment->status }}">{{ $assignment->status }}</span>
                            </td>
                            <td>
                                <a class="button-link secondary" href="{{ $assignment->submission_link }}" target="_blank" rel="noopener noreferrer">
                                    Open Link
                                </a>
                            </td>
                            <td>{{ $assignment->creator?->name ?? 'Unknown' }}</td>
                            <td>{{ $assignment->reviewer?->name ?? 'Not assigned' }}</td>
                            <td>{{ $assignment->created_at?->format('d M Y') }}</td>
                            <td>{{ $assignment->remarks ? \Illuminate\Support\Str::limit($assignment->remarks, 60) : '-' }}</td>
                            <td>
                                <div class="table-actions">
                                    <a class="button-link secondary" href="{{ route('assignments.edit', $assignment) }}">Edit</a>

                                    <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Delete this assignment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button-link danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-text">No assignments found for the current filters.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($assignments->hasPages())
            {{ $assignments->links('components.pagination') }}
        @endif
    </section>
@endsection
