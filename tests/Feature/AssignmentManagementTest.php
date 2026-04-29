<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignmentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_only_sees_assignments_they_created(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        Assignment::factory()->create([
            'candidate_name' => 'Visible Candidate',
            'created_by' => $owner->id,
        ]);

        Assignment::factory()->create([
            'candidate_name' => 'Hidden Candidate',
            'created_by' => $otherUser->id,
        ]);

        $response = $this->actingAs($owner)->get(route('assignments.index'));

        $response->assertOk();
        $response->assertSee('Visible Candidate');
        $response->assertDontSee('Hidden Candidate');
    }

    public function test_admin_can_filter_assignments_by_status_and_candidate_name(): void
    {
        $admin = User::factory()->admin()->create();

        Assignment::factory()->create([
            'candidate_name' => 'Alpha Candidate',
            'status' => Assignment::STATUS_REVIEWED,
            'created_by' => $admin->id,
        ]);

        Assignment::factory()->create([
            'candidate_name' => 'Beta Candidate',
            'status' => Assignment::STATUS_PENDING,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('assignments.index', [
            'search' => 'Alpha',
            'status' => Assignment::STATUS_REVIEWED,
        ]));

        $response->assertOk();
        $response->assertSee('Alpha Candidate');
        $response->assertDontSee('Beta Candidate');
    }

    public function test_user_can_create_an_assignment_with_pending_status(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('assignments.store'), [
            'candidate_name' => 'Fresh Candidate',
            'submission_link' => 'https://example.com/submissions/fresh-candidate',
            'remarks' => 'Portfolio received.',
            'status' => Assignment::STATUS_REJECTED,
        ]);

        $response->assertRedirect(route('assignments.index'));

        $this->assertDatabaseHas('assignments', [
            'candidate_name' => 'Fresh Candidate',
            'created_by' => $user->id,
            'status' => Assignment::STATUS_PENDING,
        ]);
    }

    public function test_user_cannot_edit_another_users_assignment(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $assignment = Assignment::factory()->create([
            'created_by' => $owner->id,
        ]);

        $response = $this->actingAs($otherUser)->get(route('assignments.edit', $assignment));

        $response->assertForbidden();
    }
}
