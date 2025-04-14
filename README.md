# Instructions how to start the app

## Clone the repo

```
git clone git@github.com:man0l/lab40.git
```


The app is running in php 8.4

## Preqreusites

Copy .env.example to .env

```
cp .env.example .env
```


Build the docker containers
```
docker compose build
docker compose up -d
```

## Migrations

```
docker exec $(docker ps -q --filter "name=laravel") php artisan migrate
```

## Seeder

```
docker exec $(docker ps -q --filter "name=laravel") php artisan db:seed
```

## Navigate the app

[http://localhost:8000](http://localhost:8000)