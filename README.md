


## Setup

Same way you would install a typical laravel application.

    composer install

    npm install

Generate environment variable file:

```shell
cp .env.example .env
```

Generate application key:

```shell
php artisan key:generate
```

Migrate database

```shell
php artisan migrate
```

Seed the database

```shell
php artisan db:seed
```

Start Vite server

```shell
npm run dev
```

Serve application

```shell
php artisan serve
```

Open another terminal tab and start the queue worker

```shell
php artisan queue:work
```

The UI is displayed on the root page

## Extra Notes

Run tests for backend implementation

```shell
php artisan test
```

Run tests for the order submission form to ensure correctness of calculations:

```shell
npm run test
```
