# CRYPTO NEWS

## Setup for development
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Change the `CRYPTO_PANIC_AUTH_TOKEN` in the `.env` file
5. Run `php artisan key:generate`


## Running the application
1. Run `php artisan serve`


## Running Scheduler
1. Run `php artisan schedule:work`
