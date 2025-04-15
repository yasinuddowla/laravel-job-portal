<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jobTypes = ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship'];

        return [
            'company_id' => Company::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(5, true),
            'location' => fake()->city() . ', ' . fake()->country(),
            'type' => fake()->randomElement($jobTypes),
            'salary_range' => '$' . fake()->numberBetween(30, 200) . 'K - $' . fake()->numberBetween(50, 250) . 'K',
            'is_active' => fake()->boolean(80), // 80% chance of being active
            'view_count' => fake()->numberBetween(0, 1000),
        ];
    }
}
