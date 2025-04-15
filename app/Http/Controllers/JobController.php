<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JobController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index()
    {
        return view('jobs.index');
    }

    /**
     * Display the specified job.
     */
    public function show(Job $job, Request $request)
    {
        // Track view
        $this->trackJobView($job, $request);

        // Load related data
        $job->load('company');
        $job->loadCount('applications');

        return view('jobs.show', compact('job'));
    }

    /**
     * Track job view with duplicate prevention
     */
    private function trackJobView(Job $job, Request $request)
    {
        $sessionId = Session::getId();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Check if this user/session has viewed this job in the last 24 hours
        $existingView = JobView::where('job_id', $job->id)
            ->where(function ($query) use ($sessionId, $ipAddress) {
                $query->where('session_id', $sessionId)
                    ->orWhere('ip_address', $ipAddress);
            })
            ->where('viewed_at', '>', now()->subHours(24))
            ->first();

        if (!$existingView) {
            // Create new view record
            JobView::create([
                'job_id' => $job->id,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'session_id' => $sessionId,
                'viewed_at' => now(),
            ]);

            // Increment view count on job
            $job->incrementViewCount();
        }
    }
}
