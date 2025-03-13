## Project Documentation

### Table of Contents
1. Introduction
2. Installation
3. Configuration
4. Running the Application
5. Scheduled Jobs
6. Testing
7. API Endpoints

### Introduction
This is a Laravel-based news aggregation application that fetches news articles from various sources such as The Guardian, BBC, and The New York Times. The application allows users to set their preferences and fetch articles based on those preferences.

### Installation
To set up the project locally, follow these steps:

1. **Clone the repository:**
    ```bash
    git clone https://github.com/your-repo/news.git
    cd news
    ```

2. **Install dependencies:**
    ```bash
    composer install

    ```

3. **Copy the .env.example file to .env and configure your environment variables:**
    ```bash
    cp .env.example .env
    ```

4. **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

5. **Run database migrations and seeders:**
    ```bash
    php artisan migrate --seed
    ```

### Configuration
Ensure that you have configured the following environment variables in your .env file:

- **Database Configuration:**
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=news
    DB_USERNAME=root
    DB_PASSWORD=
    ```

- **API Keys for News Sources:**
    ```env
    GUARDIAN_API_KEY=your_guardian_api_key
    NYTIMES_API_KEY=your_nytimes_api_key
    BBC_API_KEY=your_bbc_api_key
    ```

### Running the Application
To run the application locally, use the following command:

```bash
php artisan serve
```

You can now access the application at `http://localhost:8000`.

### Scheduled Jobs
The application includes scheduled jobs to fetch news articles daily from various sources. The jobs are defined in the app.php file:

```php
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\FetchGuardianNewsJob;
use App\Jobs\FetchNYTimesNewsJob;
use App\Jobs\FetchBBCNewsJob;

return Application::configure(basePath: dirname(__DIR__))
    ->withSchedule(function (Schedule $schedule) {
        $schedule->job(new FetchGuardianNewsJob())->daily();
        $schedule->job(new FetchBBCNewsJob())->daily();
        $schedule->job(new FetchNYTimesNewsJob())->daily();
    })
    ->create();
```

To run the scheduler, use the following command:

```bash
php artisan schedule:work
```

### Testing
The application includes unit and feature tests to ensure the functionality works as expected.

- **Run all tests:**
    ```bash
    php artisan test
    ```

- **Unit Tests:**
    Unit tests are located in the Unit directory.

- **Feature Tests:**
    Feature tests are located in the Feature directory.

### API Endpoints
The application provides several API endpoints for user authentication, fetching articles, and managing user preferences.

- **Authentication:**
    - `POST /register` - Register a new user
    - `POST /login` - Login a user
    - `POST /resetpassword` - Request a password reset
    - `POST /resetpassword-link` - Reset password

- **Articles:**
    - `POST /fetch` - Fetch articles based on filters
    - `GET /fetchone/{id}` - Fetch a single article by ID

- **User Preferences:**
    - `POST /getPreferences` - Get user preferences
    - `POST /setPreferences` - Set user preferences

- **User Preference Articles:**
    - `POST /fetch-articles` - Fetch articles based on user preferences

### Conclusion
This documentation provides a basic overview of the Laravel news aggregation application. For more detailed information, refer to the Laravel documentation and the source code of the project.
