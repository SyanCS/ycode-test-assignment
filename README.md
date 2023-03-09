
php artisan sail:install
./vendor/bin/sail up
docker exec -it ycode-test-assignment_app_1 bash
php artisan migrate
php artisan db:seed --class=OrdersTableSeeder
php artisan db:seed --class=ProductsTableSeeder
php artisan db:seed --class=OrderItemsTableSeeder

sudo chmod -R 775 storage

npm run dev

to sync with ycode when submit the form the queue must be turned on:

php artisan queue:listen