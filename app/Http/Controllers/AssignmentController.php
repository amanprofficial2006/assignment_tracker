<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentRequest;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AssignmentController extends Controller
{
    /**
     * Display a paginated listing of assignments.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status']);

        $assignments = Assignment::query()
            ->with([
                'creator:id,name',
                'reviewer:id,name',
            ])
            ->visibleTo($request->user())
            ->filter($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('assignments.index', [
            'assignments' => $assignments,
            'filters' => $filters,
            'statuses' => Assignment::STATUSES,
        ]);
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(Request $request): View
    {
        return view('assignments.create', [
            'assignment' => new Assignment([
                'status' => Assignment::STATUS_PENDING,
            ]),
            'reviewers' => $this->reviewers($request->user()),
            'statuses' => Assignment::STATUSES,
        ]);
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(AssignmentRequest $request): RedirectResponse
    {
        Assignment::query()->create($this->payload($request));

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Assignment created successfully.');
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(Request $request, Assignment $assignment): View
    {
        $this->authorizeAssignmentAccess($request->user(), $assignment);

        return view('assignments.edit', [
            'assignment' => $assignment,
            'reviewers' => $this->reviewers($request->user()),
            'statuses' => Assignment::STATUSES,
        ]);
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(AssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        $this->authorizeAssignmentAccess($request->user(), $assignment);

        $assignment->update($this->payload($request));

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->authorizeAssignmentAccess($request->user(), $assignment);

        $assignment->delete();

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }

    /**
     * Build a safe payload based on the current user's role.
     *
     * @return array<string, mixed>
     */
    private function payload(AssignmentRequest $request): array
    {
        $user = $request->user();
        $validated = $request->validated();

        $data = [
            'candidate_name' => $validated['candidate_name'],
            'submission_link' => $validated['submission_link'],
            'remarks' => $validated['remarks'] ?? null,
        ];

        if ($user->isAdmin()) {
            $data['status'] = $validated['status'] ?? Assignment::STATUS_PENDING;
            $data['reviewer_id'] = $validated['reviewer_id'] ?? null;
        }

        if (! $request->route('assignment')) {
            $data['created_by'] = $user->id;
        }

        return $data;
    }

    /**
     * Only admins or owners can manage an assignment.
     */
    private function authorizeAssignmentAccess(User $user, Assignment $assignment): void
    {
        abort_unless($user->isAdmin() || $assignment->created_by === $user->id, 403);
    }

    /**
     * Admins can assign reviewers; users do not need reviewer options.
     */
    private function reviewers(User $user): Collection
    {
        if (! $user->isAdmin()) {
            return collect();
        }

        return User::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
