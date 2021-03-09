# Lumen + MySQL Sample Ecommerce API

This is an example on using the Lumen PHP framework with MySQL for building a sample ecommerce API. It has two simple entities:
- Product: which holds the product information (name, price, category_id, timestamps)
- Category: which holds the category information (name, timestamps)

The application is using:
- docker (19.03.0+)
- nginx (latest-alpine)
- PHP (8.0-alpine)
- Lumen (8.2.3)
- Mysql (8.0.20)
- Sqlite for testing database

## The Make Command
To easily interact with the containers and the app, we make use of the Make command. These are the available commands:
- `make up` starts all containers
- `make up_build` builds & starts all containers
- `make down` shuts down all containers
- `make migrate` runs migrations
- `make migrate_fresh` runs fresh migrations
- `make seed` runs all database seeds (insert test records into DB)
- `make logs` see containers logs
- `make test` run all unit tests

## Setting up and installation

#### If you have `make` command on your system:
1. run `git clone https://github.com/M074554N/lumen-ecommerce.git`
2. then `cd ./lumen-ecommerce`
3. and `make up` this will bring all containers up and run any MySQL migrations
4. and `make seed` this will insert some dummy data into the database

#### If you don't have `make` command on your system:
1. run `git clone https://github.com/M074554N/lumen-ecommerce.git`
2. then `cd ./lumen-ecommerce && cp src/.env.example src/.env`
3. and `docker-compose up -d`
4. and `docker exec -it lumen_ecommerce_php composer install`
5. and `docker exec -it lumen_ecommerce_php php artisan migrate`
6. and `docker exec -it lumen_ecommerce_php php artisan db:seed`

Then after everything is up and running, you can head to [http://localhost:8080](http://localhost:8080) to see the main 
endpoint's welcome message.

## API Documentation
The API is fully documented using [Swagger (Open API)](https://swagger.io/) and you can see and test all endpoints using 
the swagger-ui by heading to this url [/docs-ui](http://localhost:8080/docs-ui) and give it a try.

All the swagger api documentation schema can be accessed from this endpoint [/v1/docs](http://localhost:8080/v1/docs)

## Things Todo
- refactoring of exceptions and logging
- fix pagination max number limit
