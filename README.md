# Demo payment + Sonata admin


## Quick start

Please run the following commands to get the application up and running

```shell
git clone git@github.com:Radakovic/DemoPayment.git
cd DemoPayment
docker compose up
```
Git will clone project to your local using SSH.

Everything is set and you can run app on `http://localhost:8088`.

Admin panel url `http://localhost:8088/admin`

`docker compose up` will create containers (PHP, Nginx, Postgres) with all needed dependencies 
When you first time run `dcu` it will take some time, next time will be faster.

Docker will automatically execute:
- `composer install --prefer-dist --no-scripts --no-interaction` install required bundles
- `php bin/console doctrine:migrations:migrate -n` create database tables
- `php bin/console doctrine:fixtures:load -n` load fixture data to database
- `php bin/console importmap:install` install all FE assets
- `php bin/console asset-map:compile` compile all assets to the public directory



Database:
- url: 127.0.0.2:5432
- database name: postgres
- user: postgres
- pass: postgres

Tests can be executed in couple ways:
- `docker compose exec php vendor/bin/phpunit` - (it will throw couple deprecation notices - didnt want to waste time on that)
- `docker compose exec php vendor/bin/paratest` - it will speed up execution of tests can be used in larger projects with high number of tests
