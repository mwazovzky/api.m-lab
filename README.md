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

## Run app: prod environment
``` 
// start.sh - run once to init app

export MYSQL_ROOT_PASSWORD=***
export MYSQL_DATABASE=***
export MYSQL_USER=***
export MYSQL_PASSWORD=***

docker-compose up -d --build

docker exec -it api php artisan migrate
docker exec -it api php artisan db:seed
docker exec -it api php artisan storage:link

// deploy.sh - run every time app code is updated 

# Turn on maintenance mode
docker exec -it api php artisan down

# Pull the latest changes from the git repository
git pull

# Install/update composer dependecies
docker exec -it api php /usr/local/bin/composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Run database migrations
docker exec -it api php artisan migrate --force

# Clear caches
docker exec -it api php artisan cache:clear

# Clear expired password reset tokens
docker exec -it api php artisan auth:clear-resets

# Clear and cache config
docker exec -it api php artisan config:clear
docker exec -it api php artisan config:cache

# Turn off maintenance mode
docker exec -it api php artisan up
// 
```
