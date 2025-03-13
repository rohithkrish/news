## Project Documentation
Post man publish link
```
https://documenter.getpostman.com/view/43059315/2sAYk8wPYR
```

open api documentation
```yaml
openapi: 3.0.0
info:
  title: news
  version: 1.0.0
servers:
  - url: http://news.test
components:
  securitySchemes:
    noauthAuth:
      type: http
      scheme: noauth
    bearerAuth:
      type: http
      scheme: bearer
paths:
  /api/register:
    post:
      tags:
        - default
      summary: register
      requestBody:
        content:
          application/json:
            schema:
              type: object
              example:
                name: John1 2Doe
                email: john21@example.com
                password: password123
                password_confirmation: password123
      parameters:
        - name: Accept
          in: header
          schema:
            type: string
          example: application/json
        - name: Content-Type
          in: header
          schema:
            type: string
          example: application/json
      responses:
        '200':
          description: Successful response
          content:
            application/json: {}
  /api/login:
    post:
      tags:
        - default
      summary: login
      requestBody:
        content:
          application/json:
            schema:
              type: object
              example:
                email: john1@example.com
                password: password123
      parameters:
        - name: Accept
          in: header
          schema:
            type: string
          example: application/json
        - name: Content-Type
          in: header
          schema:
            type: string
          example: application/json
      responses:
        '200':
          description: Successful response
          content:
            application/json: {}
  /api/resetpasssword:
    post:
      tags:
        - default
      summary: reset
      requestBody:
        content:
          application/json:
            schema:
              type: object
              example:
                email: bimax33971@payposs.com
      security:
        - noauthAuth: []
      parameters:
        - name: Accept
          in: header
          schema:
            type: string
          example: application/json
        - name: Content-Type
          in: header
          schema:
            type: string
          example: application/json
      responses:
        '200':
          description: Successful response
          content:
            application/json: {}
  /api/resetpasssword-link:
    post:
      tags:
        - default
      summary: rest password link
      requestBody:
        content:
          application/json:
            schema:
              type: object
              example:
                password: '123456'
      security:
        - noauthAuth: []
      parameters:
        - name: Accept
          in: header
          schema:
            type: string
          example: application/json
        - name: Content-Type
          in: header
          schema:
            type: string
          example: application/json
        - name: token
          in: query
          schema:
            type: string
          example: 050f98185851ec538d040f8007a106e9360953e485b5a266a75eff82502e1518
        - name: email
          in: query
          schema:
            type: string
          example: bimax33971@payposs.com
      responses:
        '200':
          description: Successful response
          content:
            application/json: {}
  /api/fetch:
    post:
      tags:
        - default
      summary: fetch news
      requestBody:
        content:
          application/json:
            schema:
              type: object
              example:
                per_page: 10
                page: 1
                source: NY Times
                start_date: '2025-01-01'
                end_date: '2025-12-31'
                category: arts
                author: ''
      security:
        - bearerAuth: []
      parameters:
        - name: Accept
          in: header
          schema:
            type: string
          example: application/json
        - name: Content-Type
          in: header
          schema:
            type: string
          example: application/json
      responses:
        '200':
          description: Successful response
          content:
            application/json: {}
  /api/setPreferences:
    post:
      tags:
        - default
      summary: set prefrences
      requestBody:
        content:
          application/json:
            schema:
              type: object
              example:
                categories:
                  - Technology
                  - sports
                  - arts
      security:
        - bearerAuth: []
      parameters:
        - name: Accept
          in: header
          schema:
            type: string
          example: application/json
        - name: Content-Type
          in: header
          schema:
            type: string
          example: application/json
      responses:
        '200':
          description: Successful response
          content:
            application/json: {}
  /api/getPreferences:
    post:
      tags:
        - default
      summary: get ptrefrences
      requestBody:
        content:
          application/json:
            schema:
              type: object
              example: ''
      security:
        - bearerAuth: []
      parameters:
        - name: Accept
          in: header
          schema:
            type: string
          example: application/json
        - name: Content-Type
          in: header
          schema:
            type: string
          example: application/json
      responses:
        '200':
          description: Successful response
          content:
            application/json: {}
  /api/fetchuserArticles:
    post:
      tags:
        - default
      summary: get  user articles
      requestBody:
        content:
          application/json:
            schema:
              type: object
              example: ''
      security:
        - bearerAuth: []
      parameters:
        - name: Accept
          in: header
          schema:
            type: string
          example: application/json
        - name: Content-Type
          in: header
          schema:
            type: string
          example: application/json
      responses:
        '200':
          description: Successful response
          content:
            application/json: {}
  /api/fetchone/eyJpdiI6IlJVMGpXVlBFYVNibTV4eWZsZ0tsUGc9PSIsInZhbHVlIjoiQjl4TlgzcUVueVRUaGlKYmhtU1pVZz09IiwibWFjIjoiOTdjMGYzNmUwMzAwY2VhNjU4MzgzNmE3YjM0YzVlOGVkMTE3ZTc2OTVkM2YyODg3NzU2YzYxMDZkYzYxNjFiYyIsInRhZyI6IiJ9:
    get:
      tags:
        - default
      summary: fetch news single
      description: encrypted id of news is sended to fetch data
      security:
        - bearerAuth: []
      parameters:
        - name: Accept
          in: header
          schema:
            type: string
          example: application/json
        - name: Content-Type
          in: header
          schema:
            type: string
          example: application/json
      responses:
        '200':
          description: Successful response
          content:
            application/json: {}
```

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
    git clone https://github.com/rohithkrish/news.git
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
### sheduled command
```
news:fetch
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

-- **Docker setup guide:**
Step 1: Check if Docker Compose is Installed
 -Run this command to check the installed version:
```
docker compose version
```
  Step 2: Install Docker Compose (For Ubuntu)
 Since Docker now includes Compose by default, you just need to install Docker properly.
 ```
   sudo apt update
   sudo apt install docker-compose-plugin 
```
then run docker
```
      docker compose up -d --build
````
project will be host on
```
http://localhost:8081/
```

### Conclusion
This documentation provides a basic overview of the Laravel news aggregation application. For more detailed information, refer to the Laravel documentation and the source code of the project.
