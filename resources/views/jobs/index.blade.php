@extends('layouts.app')

@section('title', 'Browse Jobs')
@section('meta_description', 'Browse the latest job openings and find your perfect match.')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Latest Job Openings</h1>

            <div id="job-listing-container">
                <job-listing></job-listing>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // This ensures Vue knows about this container
            if (document.getElementById('job-listing-container')) {
                console.log('Job listing container found, Vue should mount');
            }
        });
    </script>
@endpush
