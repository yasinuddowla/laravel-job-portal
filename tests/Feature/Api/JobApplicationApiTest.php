<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Notifications\NewJobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class JobApplicationApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_can_submit_job_application()
    {
        Notification::fake();

        // Create a company and job
        $company = Company::factory()->create();
        $job = Job::factory()->create(['company_id' => $company->id]);

        // Application data
        $applicationData = [
            'name' => 'Test Applicant',
            'email' => 'applicant@example.com',
            'cover_letter' => 'This is my application',
        ];

        // Submit application via API
        $response = $this->postJson("/api/jobs/{$job->id}/apply", $applicationData);

        // Check success response
        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);

        // Assert application was created in database
        $this->assertDatabaseHas('job_applications', [
            'job_id' => $job->id,
            'name' => $applicationData['name'],
            'email' => $applicationData['email'],
        ]);

        // Assert notification was sent to company
        Notification::assertSentTo(
            $company,
            NewJobApplication::class
        );
    }

    public function test_application_requires_valid_data()
    {
        // Create a job
        $job = Job::factory()->create();

        // Submit incomplete application
        $response = $this->postJson("/api/jobs/{$job->id}/apply", [
            'name' => '',
            'email' => 'not-an-email',
        ]);

        // Check validation errors
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email']);

        // Assert no application was created
        $this->assertEquals(0, JobApplication::count());
    }

    public function test_cannot_apply_to_nonexistent_job()
    {
        // Application data
        $applicationData = [
            'name' => 'Test Applicant',
            'email' => 'applicant@example.com',
            'cover_letter' => 'This is my application',
        ];

        // Submit application to non-existent job
        $response = $this->postJson("/api/jobs/9999/apply", $applicationData);

        // Check error response
        $response->assertStatus(404);

        // Assert no application was created
        $this->assertEquals(0, JobApplication::count());
    }
}
