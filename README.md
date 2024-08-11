# CRYPTO NEWS
This application fetches the latest news from the Crypto Panic API and stores it in the redis. It also provides endpoints to fetch the news based on the filters.

## Architectural Features
- **Scheduled Task**: Automatically fetches the latest news every minute using a Laravel command scheduled job.
- **Form-Request Validation**: Request data is valid before processing.
- **DTO Integration**: Organizes request data into structured type safety objects.
- **Resource-Based Responses**: Formats API responses consistently,

## Requirements
- PHP 8.2
- Composer
- Redis

## Setup for development
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Change the `CRYPTO_PANIC_AUTH_TOKEN` in the `.env` file
5. Run `php artisan key:generate`

## Running the application
- Run `php artisan serve`

## Running Scheduler
- Run `php artisan schedule:work`

## API Endpoints
- `POST /api/news/search-by-symbol` - Fetches the latest 20 news based on the symbol
    - Parameters:
        - **symbol** : string **(required)**\*
    - Example Body:
        ```
        {
            "symbol": "BTC"
        }
        ```
    - Example Response:
        ```
        [
            {
                "id": "19782963",
                "title": "Is $250,000 next?",
                "symbols": [
                    "BTC"
                ],
                "published_at": "2024-08-11T16:43:47Z"
            }
        ]
        ```
- `POST /api/news/search-by-time` - Fetches news based on the time and symbol
    - Parameters:
        - **symbol** : string (optional)
        - **fromDate** : datetime (optional)
        - **toDate** : datetime **(required)**\*
    - Example Body:
        ```
        {
            "symbol": "ETH",
            "toDate": "2024-09-11 20:30:00"
        }
        ```
    - Example Response:
        ```
        [
            {
                "id": "19782548",
                "title": "Prestigious Ranking: Details",
                "symbols": [
                    "ETH",
                    "ADA"
                ],
                "published_at": "2024-08-11T12:15:57Z"
            }
        ]
        ```
