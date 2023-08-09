up:
	docker-compose up -d

up-build:
	docker-compose up --build -d --remove-orphans

down:
	docker-compose down

restart:
	make down && make up

php-connect:
	docker-compose exec php-fpm sh

run-node:
	docker-compose run --rm node bash

php-fix:
	docker compose run --rm php-fpm php vendor/bin/php-cs-fixer fix

node-fix:
	docker compose run --rm node npm run fix