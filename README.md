# Lumen + MySQL Sample Ecommerce API 

This is an example on using the Lumen PHP framework with MySQL for building a sample ecommerce API.

#### If you have `make` command on your system:
1. run `git clone https://github.com/M074554N/lumen-ecommerce.git`
2. then `cd ./lumen-ecommerce`
3. and `make up` this will bring all containers up and run any MySQL migrations

#### If you don't have `make` command on your system:
1. run `git clone https://github.com/M074554N/lumen-ecommerce.git`
2. then `cd ./lumen-ecommerce`
3. and `cd app && docker-compose up -d`
4. and `docker exec -it lumen_ecommerce_php php artisan migrate`
