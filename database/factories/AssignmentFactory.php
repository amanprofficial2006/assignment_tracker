<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Assignment>
     */
    protected $model = Assignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'candidate_name' => fake()->name(),
            'status' => fake()->randomElement(Assignment::STATUSES),
            'submission_link' => fake()->url(),
            'reviewer_id' => null,
            'created_by' => User::factory(),
            'remarks' => fake()->optional()->sentence(),
        ];
    }
}
