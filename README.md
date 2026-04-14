## Rest Api, used to fetch data and save to database, built with Laravel and Mysql using Docker

### MySQL demo access:

```bash
mysql -h bvg4cye81r4dkuvuzyfu-mysql.services.clever-cloud.com -P 3306 -u uldktjna3offrzhv -p bvg4cye81r4dkuvuzyfu
```

> show tables;

Tables_in_bvg4cye81r4dkuvuzyfu |
+--------------------------------+
| api_page_trackers              |
| cache                          |
| cache_locks                    |
| categories                     |
| failed_jobs                    |
| incomes                        |
| job_batches                    |
| jobs                           |
| migrations                     |
| nms                            |
| orders                         |
| password_reset_tokens          |
| sales                          |
| sessions                       |
| stocks                         |
| subjects                       |
| users                          |
| warehouses                     |
+--------------------------------+

> select * from stocks;
+------+------------+------------------+------------------+------------------+-----------+----------+-----------+----------------+---------------+-----------+------------------+--------------------+------+---------+----------+------------------+-----------+-----------+----------+---------------------+---------------------+
| id   | date       | last_change_date | supplier_article | tech_size        | barcode   | quantity | is_supply | is_realization | quantity_full | warehouse | in_way_to_client | in_way_from_client | nm   | subject | category | brand            | sc_code   | price     | discount | created_at          | updated_at          |
+------+------------+------------------+------------------+------------------+-----------+----------+-----------+----------------+---------------+-----------+------------------+--------------------+------+---------+----------+------------------+-----------+-----------+----------+---------------------+---------------------+
| 3174 | 2026-03-10 | 2025-01-19       | c88c5018511d87a8 | 66e7dff9f98764da | 212416691 |        0 |         0 |              1 |             3 |       134 |                0 |                  3 | 1202 |      50 |        6 | c88460ca41fdbb82 | 149490916 |   3021.00 |    28.00 | 2026-03-10 18:02:22 | 2026-03-10 18:02:22 |
| 3175 | 2026-03-10 | 2025-01-29       | 1ebc90010e9f0d8c | 66e7dff9f98764da | 942802682 |        0 |         1 |              0 |             1 |       135 |                0 |                  1 | 1203 |      51 |        6 | f486d1bc7ccbbb35 | 413256454 |    662.00 |    25.00 | 2026-03-10 18:02:22 | 2026-03-10 18:02:22 |
| 3176 | 2026-03-10 | 2025-03-01       | 6b90a7e2b5909076 | 66e7dff9f98764da | 776999398 |        0 |         1 |              0 |             2 |       134 |                0 |
> 


Host: ```bvg4cye81r4dkuvuzyfu-mysql.services.clever-cloud.com```

Database Name: ```bvg4cye81r4dkuvuzyfu```

User: ```uldktjna3offrzhv```

Password: ```kjoaTwocWmlOo92M8uCR```

Port: ```3306```


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

