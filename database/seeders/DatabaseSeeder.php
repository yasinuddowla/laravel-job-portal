<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobView;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 companies
        $companies = Company::factory(10)->create();

        // For each company, create 1-5 jobs
        $companies->each(function ($company) {
            $jobs = Job::factory(rand(1, 5))->create([
                'company_id' => $company->id,
            ]);

            // For each job, create 0-10 applications
            $jobs->each(function ($job) {
                $applicationCount = rand(0, 10);

                if ($applicationCount > 0) {
                    JobApplication::factory($applicationCount)->create([
                        'job_id' => $job->id,
                    ]);
                }

                // For each job, create 5-50 views
                JobView::factory(rand(5, 50))->create([
                    'job_id' => $job->id,
                ]);
            });
        });
    }
}
