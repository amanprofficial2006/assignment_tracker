@extends('layouts.app', ['title' => 'Admin Dashboard'])

@section('content')
    <section class="page-card stack">
        <div class="page-header">
            <div>
                <h1 class="section-title">Admin Dashboard</h1>
                <p class="toolbar-note">Admins can review everything, update statuses, and assign reviewers from here or from the assignment list.</p>
            </div>

            <div class="page-actions">
                <a class="button secondary" href="{{ route('assignments.index') }}">View Assignments</a>
                <a class="button" href="{{ route('assignments.create') }}">Create Assignment</a>
            </div>
        </div>

        <div class="stats-grid">
            <article class="stat-card">
                <span class="stat-label">Total Assignments</span>
                <strong class="stat-value">{{ (int) ($stats->total_count ?? 0) }}</strong>
            </article>
            <article class="stat-card">
                <span class="stat-label">Pending</span>
                <strong class="stat-value">{{ (int) ($stats->pending_count ?? 0) }}</strong>
            </article>
            <article class="stat-card">
                <span class="stat-label">Reviewed</span>
                <strong class="stat-value">{{ (int) ($stats->reviewed_count ?? 0) }}</strong>
            </article>
            <article class="stat-card">
                <span class="stat-label">Rejected</span>
                <strong class="stat-value">{{ (int) ($stats->rejected_count ?? 0) }}</strong>
            </article>
            <article class="stat-card">
                <span class="stat-label">Users</span>
                <strong class="stat-value">{{ $usersCount }}</strong>
            </article>
        </div>
    </section>

    <section class="page-card table-card stack">
        <div>
            <h2 class="section-title">Recent Assignments</h2>
            <p class="toolbar-note">Quick snapshot of the latest activity.</p>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Reviewer</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentAssignments as $assignment)
                        <tr>
                            <td>{{ $assignment->candidate_name }}</td>
                            <td>
                                <span class="status-pill status-{{ $assignment->status }}">{{ $assignment->status }}</span>
                            </td>
                            <td>{{ $assignment->creator?->name ?? 'Unknown' }}</td>
                            <td>{{ $assignment->reviewer?->name ?? 'Not assigned' }}</td>
                            <td>{{ $assignment->created_at?->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-text">No assignments yet.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
