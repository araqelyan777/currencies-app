# Currencies app

## Set up

`cp .env.example .env`

change DB settings according to your server

`composer install`

`php artisan key:generate`

`php artisan migrate`

`npm install`

`npm run build`

Run server:

`php artisan serve`

Import data:

`php artisan currencies:fetch`

This command will import 30 days by default.

Pass a number to specify how many days to import.

`php artisan currencies:fetch 60`

Open `http://127.0.0.1:8000`
