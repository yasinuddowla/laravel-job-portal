<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_api_returns_job_listings()
    {
        // Create a company and several jobs
        $company = Company::factory()->create();
        $jobs = Job::factory()->count(5)->create(['company_id' => $company->id]);

        // Make API request
        $response = $this->getJson('/api/jobs');

        // Check response structure
        $response->assertStatus(200);

        // Assert response contains expected structure
        $response->assertJsonStructure([
            'data',
            'links',
            'meta' => ['current_page', 'last_page', 'per_page', 'total']
        ]);

        // Get the jobs data from the response
        $responseData = $response->json('data');
        $this->assertNotEmpty($responseData);

        // Check that at least one job is returned with the expected structure
        $this->assertNotEmpty($responseData);
        $this->assertArrayHasKey('id', $responseData[0]);
        $this->assertArrayHasKey('title', $responseData[0]);
    }

    public function test_api_returns_single_job()
    {
        // Create a company and job
        $company = Company::factory()->create();
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'title' => 'Senior Developer',
            'description' => 'A detailed job description here'
        ]);

        // Make API request
        $response = $this->getJson("/api/jobs/{$job->id}");

        // Check response
        $response->assertStatus(200);
        $response->assertJsonPath('id', $job->id);
        $response->assertJsonPath('title', 'Senior Developer');
        $response->assertJsonPath('description', 'A detailed job description here');
        $response->assertJsonPath('company_id', $company->id);
    }

    public function test_api_returns_404_for_nonexistent_job()
    {
        // Make API request for a job that doesn't exist
        $response = $this->getJson("/api/jobs/9999");

        // Check response
        $response->assertStatus(404);
    }
}
