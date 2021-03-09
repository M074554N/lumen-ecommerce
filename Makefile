up:
	cp src/.env.example src/.env
	docker-compose up -d
	docker exec -it lumen_ecommerce_php composer install
	docker exec -it lumen_ecommerce_php php artisan migrate

up_build:
	cp src/.env.example src/.env
	docker-compose up -d --build
	docker exec -it lumen_ecommerce_php composer install
	docker exec -it lumen_ecommerce_php php artisan migrate

down:
	docker-compose down

migrate:
	docker exec -it lumen_ecommerce_php php artisan migrate

migrate_fresh:
	docker exec -it lumen_ecommerce_php php artisan migrate:fresh

seed:
	docker exec -it lumen_ecommerce_php php artisan db:seed

logs:
	docker-compose logs -f

test:
	docker exec -it lumen_ecommerce_php vendor/bin/phpunit
