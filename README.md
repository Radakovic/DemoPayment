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
- `php bin/console doctrine:fixtures:load -n` load fixture data
- `php bin/console importmap:install` install bootstrap dependencies
- `php bin/console asset-map:compile` install sonata admin dependencies



Database:
- url: 127.0.0.2:5432
- database name: postgres
- user: postgres
- pass: postgres
