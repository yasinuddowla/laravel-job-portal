# Job Listing Application

A simple job listing application built with Laravel (backend) and Vue.js (frontend). This application allows companies to post jobs, candidates to view job listings, and apply with minimal information.

## Features Implemented

-   **Job Listing Page**

    -   Displays all available jobs with title, company name, description, location, job type, salary range
    -   Shows view counts and application counts for each job
    -   Pagination for large number of listings
    -   Mobile-responsive design

-   **Job Details Page**

    -   Comprehensive job information with company details
    -   SEO optimized with proper meta tags for better discoverability
    -   Real-time view count tracking

-   **Application System**

    -   Simple form requiring just name and email (with optional cover letter and resume)
    -   Form validation both client and server-side
    -   Success notifications

-   **View Counting**

    -   Tracks unique views based on session ID and IP address
    -   Prevents duplicate counts from the same user within 24 hours
    -   Maintains accurate view statistics

-   **Email Notifications**

    -   Companies receive email notifications when candidates apply
    -   Notifications are queued for better performance under high traffic

-   **Testing**
    -   Unit tests for view counting mechanism
    -   Feature tests for application submission process

## Technology Stack

-   **Backend**: Laravel 12
-   **Frontend**: Vue.js 3
-   **Database**: MySQL/SQLite
-   **CSS Framework**: Bootstrap 5
-   **Icons**: Font Awesome

## Setup Instructions

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js and NPM
-   MySQL or SQLite

### Installation Steps

1. **Clone the repository**

    ```
    git clone <repository-url>
    cd laravel-job-portal
    ```

2. **Install PHP dependencies**

    ```
    composer install
    ```

3. **Install JavaScript dependencies**

    ```
    npm install
    ```

4. **Environment setup**

    ```
    cp .env.example .env
    php artisan key:generate
    ```

5. **Configure the database**

    Edit the `.env` file and set your database credentials:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel-job-portal
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    Alternatively, for SQLite:

    ```
    DB_CONNECTION=sqlite
    # Comment out or remove other DB_* settings
    ```

    Then create the SQLite file:

    ```
    touch database/database.sqlite
    ```

6. **Run migrations and seeders**

    ```
    php artisan migrate --seed
    ```

7. **Compile assets**

    ```
    npm run dev
    ```

8. **Start the development server**

    ```
    php artisan serve
    ```

9. **Access the application**

    Navigate to `http://localhost:8000` in your browser.

### Email Configuration

To test email notifications:

1. Configure mail settings in `.env`:

    ```
    MAIL_MAILER=smtp
    MAIL_HOST=mailhog
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```

2. For local development, you can use Mailhog or Mailtrap:
    - Mailhog: `brew install mailhog` (for macOS)
    - Mailtrap: Create an account and update MAIL\_\* settings

## Running Tests

```
php artisan test
```

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgements

-   Laravel and Vue.js communities for excellent documentation and resources
-   Bootstrap for responsive design components
-   Font Awesome for icon set
