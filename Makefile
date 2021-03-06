up:
	cd src && docker-compose up -d
	docker exec -it lumen_ecommerce_php php artisan migrate

up_build:
	cd src && docker-compose up --build -d
	docker exec -it lumen_ecommerce_php php artisan migrate

down:
	cd src && docker-compose down

migrate:
	docker exec -it lumen_ecommerce_php php artisan migrate

migrate_fresh:
	docker exec -it lumen_ecommerce_php php artisan migrate:fresh

seed:
	docker exec -it lumen_ecommerce_php php artisan db:seed

logs:
	cd src && docker-compose logs -f

test:
	cd src && docker exec -it lumen_ecommerce_php vendor/bin/phpunit
