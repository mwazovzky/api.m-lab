# Laravel Api

## Project setup

## Create Laravel Project (api)
```
composer create-project --prefer-dist laravel/laravel project
cd project
php artisan migrate
```

## Scaffold UI
```
composer require laravel/ui
php artisan ui vue --auth
npm install && npm run dev
```

## Run app: dev environment
```
export MYSQL_ROOT_PASSWORD=root
export MYSQL_DATABASE=homestead
export MYSQL_USER=homestead
export MYSQL_PASSWORD=secret
docker-compose up -d
docker exec -it api php artisan migrate
docker exec -it api php artisan db:seed
docker exec -it api php artisan storage:link
```

## Run app: tests
```
docker-compose up -d
docker exec -it api php artisan test
```
