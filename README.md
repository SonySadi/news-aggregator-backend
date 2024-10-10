# News Aggregator Backend

This project is a Laravel-based backend for a news aggregator application.

## Prerequisites

-   Docker
-   Docker Compose

## Setup and Installation

1. Clone the repository:

    ```
    git clone https://github.com/SonySadi/news-aggregator-backend.git
    cd news-aggregator-backend
    ```

2. Copy the `.env.example` file to `.env` and update the necessary environment variables:

    ```
    cp .env.example .env
    ```

3. Generate API keys for news sources:

    - The Guardian API:

        1. Visit [The Guardian Open Platform](https://open-platform.theguardian.com/)
        2. Sign up for a free account
        3. Generate an API key
        4. Add the key to your `.env` file: `GUARDIAN_API_KEY=your_api_key_here`

    - The New York Times API:

        1. Go to [The New York Times Developer Network](https://developer.nytimes.com/)
        2. Create a free account
        3. Create a new app and generate an API key
        4. Add the key to your `.env` file: `NYTIMES_API_KEY=your_api_key_here`

    - NewsAPI:
        1. Visit [NewsAPI](https://newsapi.org/)
        2. Sign up for a free account
        3. Copy your API key
        4. Add the key to your `.env` file: `NEWSAPI_API_KEY=your_api_key_here`

4. Build and start the Docker containers:

    ```
    docker-compose up -d --build
    ```

5. Install PHP dependencies:

    ```
    docker-compose exec app composer install
    ```

6. Generate application key:

    ```
    docker-compose exec app php artisan key:generate
    ```

7. Run database migrations:
    ```
    docker-compose exec app php artisan migrate
    ```

## Running the Application

Once the setup is complete, you can access the application at `http://localhost:80`.

## Database Management

-   To access phpMyAdmin, visit `http://localhost:8081`

## Useful Commands

-   Start the containers: `docker-compose up -d`
-   Stop the containers: `docker-compose down`
-   View container logs: `docker-compose logs`
-   Run Artisan commands: `docker-compose exec app php artisan [command]`

## Scraping News

To scrape news from different sources:

```
docker-compose exec app php artisan scrape:guardian-news
docker-compose exec app php artisan scrape:newsapi
docker-compose exec app php artisan scrape:nytimes-archive
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
