**PHP:** 8.2

### Locally Installation

- `git clone [project]`
- `cd [project]`
- `cp .env.example .env`
- Run `docker-compose up -d`
- Inside php container `docker-compose exec php bash`
- `composer install`
- `php artisan key:generate`
- `php artisan migrate:fresh --seed`
- `php artisan storage:link`
- Run `docker-compose down && docker-compose up -d`
- Open in browser `http://localhost/`

- generate API docks
- Run `docker-compose up -d`
- `php artisan scribe:generate`
- optional: `php artisan optimize`

- default users
- `test1@example.com`
- `test2@example.com`
- `test3@example.com`
- passwords `secret`

- cs fixer install
- Run `docker-compose up -d`
- `composer install --working-dir=tools/php-cs-fixer`
- check style:
- `composer run check_style`
- fix style:
- `composer run fix_style`