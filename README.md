## Rest Api, used to fetch data and save to database, built with Laravel and Mysql using Docker

### PhpMyAdmin demo access:

`
PhpMyAdmin: https://pma-mysql-addon-clevercloud-customers.services.clever-cloud.com/index.php

Host: bvg4cye81r4dkuvuzyfu-mysql.services.clever-cloud.com

Database Name: bvg4cye81r4dkuvuzyfu

User: uldktjna3offrzhv

Password: kjoaTwocWmlOo92M8uCR

Port: 3306

`

[Tables]: stocks, incomes, sales, orders, nms, subjects, categories, warehouses


## Technical Requirements

[PHP 8.4](https://www.php.net/releases/8.4/en.php)
[Composer (System Requirements)](https://getcomposer.org/doc/00-intro.md#system-requirements)
[Laravel 12.11.2](https://laravel.com/docs/12.x)
[MySQL 9.1.0](https://hub.docker.com/r/mysql/mysql-server#!)
[Testing: PHPUnit](https://docs.phpunit.de/)
[Containerization: Docker 24.* + Docker Compose 2.*](https://www.docker.com)

## Installation

git clone https://github.com/sevarostov/rest-api-db.git

#### Copy file `.env.example` to `.env`
```
cp .env.example .env
```

#### Make Composer install the project's dependencies into vendor/

```
composer install
```

## Generate key
```
php artisan key:generate
```

## Build the project

```
docker build -t php:latest --file ./docker/php/Dockerfile --target php ./docker
```

## Docker compose:
```
docker compose up -d
docker compose down
```

## Create database schema

```
docker exec -i php php artisan migrate
```

## Fetch data and save to db
````
docker exec php php artisan get
````



## Run tests

```
docker exec -i php vendor/bin/phpunit
```

