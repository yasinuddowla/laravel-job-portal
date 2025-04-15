<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index()
    {
        $jobs = Job::with('company')
            ->where('is_active', true)
            ->withCount(['applications', 'views'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Format response exactly as expected by the Vue component
        return response()->json([
            'data' => $jobs->items(),
            'links' => $jobs->linkCollection()->toArray(),
            'meta' => [
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
                'per_page' => $jobs->perPage(),
                'total' => $jobs->total(),
            ]
        ]);
    }

    /**
     * Display the specified job.
     */
    public function show(Job $job)
    {
        // Load related data
        $job->load('company');
        $job->loadCount('applications');

        return response()->json($job);
    }
}
