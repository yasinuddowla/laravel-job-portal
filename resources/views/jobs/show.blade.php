@extends('layouts.app')

@section('title', $job->title)
@section('meta_description', Str::limit(strip_tags($job->description), 160))

@section('content')

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('jobs.index') }}">Jobs</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $job->title }}</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-lg-8">

            <div id="app">
                <job-details :job-id="{{ $job->id }}"></job-details>
            </div>
        </div>

        <div class="col-lg-4">
            <div id="application-form">
                <job-application-form :job-id="{{ $job->id }}"></job-application-form>
            </div>
        </div>
    </div>
@endsection
