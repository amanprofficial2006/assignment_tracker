<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_protected_pages(): void
    {
        $response = $this->get(route('assignments.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_first_registered_user_becomes_admin(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'First User',
            'email' => 'first@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'first@example.com',
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_next_registered_user_becomes_standard_user(): void
    {
        User::factory()->admin()->create();

        $response = $this->post(route('register.store'), [
            'name' => 'Second User',
            'email' => 'second@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('assignments.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'second@example.com',
            'role' => User::ROLE_USER,
        ]);
    }
}
