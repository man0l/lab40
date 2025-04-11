# Instructions how to start the app

The app is running in php 8.3 and it would work on php 8.4

## Preqreusites

Make sure you have installed all the php extensions required to run the app:

```
composer check-platform-reqs
```


## Migrations

```
php artisan migrate
```


## Run dev server

Since the app uses vite for the frontend it needs to run the vite dev command. They're both provided in the composer run dev command


```
composer run dev
```

## Ensure database permissions

Since it uses sqlite, make sure the database is readable and it has 664 permissions:

```
-rw-rw-r--  1 user user 86016 апр 11 11:36 database.sqlite
```

## Navigate the app


[text](http://127.0.0.1:8001)