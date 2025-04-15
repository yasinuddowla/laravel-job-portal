<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'reviewed', 'interviewed', 'rejected', 'accepted'];

        return [
            'job_id' => Job::factory(),
            'name' => fake()->name(),
            'email' => fake()->email(),
            'cover_letter' => fake()->paragraphs(3, true),
            'resume' => 'resumes/resume-' . fake()->uuid() . '.pdf',
            'status' => fake()->randomElement($statuses),
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
