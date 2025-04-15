<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Notifications\NewJobApplication;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobApplicationController extends Controller
{
    /**
     * Submit a new job application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Job $job)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Check if this email has already applied to this job
                Rule::unique('job_applications')->where(function ($query) use ($job) {
                    return $query->where('job_id', $job->id);
                }),
            ],
            'cover_letter' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'email.unique' => 'You have already applied for this job with this email address.',
        ]);

        // Handle resume upload if provided
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
            $validated['resume'] = $resumePath;
        }

        $validated['job_id'] = $job->id;
        $validated['status'] = 'pending';

        // Create application
        $application = JobApplication::create($validated);

        // Send notification to company
        $job->company->notify(new NewJobApplication($application));

        return response()->json([
            'success' => true,
            'message' => 'Your application has been submitted successfully.',
            'data' => [
                'id' => $application->id,
                'job_id' => $application->job_id,
                'name' => $application->name,
                'email' => $application->email,
                'status' => $application->status,
                'created_at' => $application->created_at,
            ],
        ]);
    }
}
