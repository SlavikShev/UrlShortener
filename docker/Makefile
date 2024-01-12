start:
	docker-compose -p shortener up -d --build

stop:
	docker-compose -p shortener down

restart: stop start

ssh:
	docker exec -it php-container-8.3 bash

init: start
	docker exec -it php-container-8.3 composer install

	docker exec -it php-container-8.3 php bin/console doctrine:database:create
	docker exec -it php-container-8.3 php bin/console doctrine:migrations:migrate --no-interaction

	docker exec -it php-container-8.3 php bin/console doctrine:database:create --env=test
	docker exec -it php-container-8.3 php bin/console doctrine:migrations:migrate --no-interaction --env=test