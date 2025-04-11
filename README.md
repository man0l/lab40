# Instructions how to start the app

The app is running in php 8.3 and it would work on php 8.4

## Preqreusites

Requires docker to run the application
```
docker compose build
docker compose up -d
```


## Migrations

```
docker compose exec laravel php artisan migrate
```

## Navigate the app


[text](http://localhost:8000)