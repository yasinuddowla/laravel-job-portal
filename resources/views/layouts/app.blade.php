<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Job Listing') }} - @yield('title', 'Find Your Dream Job')</title>
    <meta name="description" content="@yield('meta_description', 'Browse the latest job openings and apply to your dream job today.')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Find Your Dream Job') - {{ config('app.name', 'Job Listing') }}">
    <meta property="og:description" content="@yield('meta_description', 'Browse the latest job openings and apply to your dream job today.')">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Find Your Dream Job') - {{ config('app.name', 'Job Listing') }}">
    <meta name="twitter:description" content="@yield('meta_description', 'Browse the latest job openings and apply to your dream job today.')">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'Job Listing') }}</a>

            <!-- Always visible navigation for mobile -->
            <div class="d-flex">
                <a href="{{ route('home') }}" class="btn btn-primary me-1">Home</a>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary me-1">Jobs</a>
                <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- Collapsible navigation content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- These will only be visible on desktop -->
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}"
                            href="{{ route('jobs.index') }}">Jobs</a>
                    </li>

                </ul>
                <form class="d-flex mt-3 mt-lg-0 ms-lg-3" action="{{ route('jobs.index') }}" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search jobs..."
                        aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ config('app.name', 'Job Listing') }}</h5>
                    <p>Find your dream job today!</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Job Listing') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
