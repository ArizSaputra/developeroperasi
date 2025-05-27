#!/bin/bash

echo "Running deploy script baru bang"

echo "[1/5] Pulling from GitHub"
git pull origin

echo "[2/5] Ensuring correct database host configuration"
# Check that DB_HOST in .env matches the actual MySQL container name if using Docker
# Example: DB_HOST=laravel_db (This should match your MySQL container name)
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=laravel_db/g' .env

echo "[3/5] Installing packages using composer"
composer install

echo "[4/5] Publishing API Platform assets"
php artisan api-platform:install

echo "[5/5] Running fresh migrations and seeding"
docker exec -it laravel_app1 bash
php artisan migrate:fresh --seed

echo "The app has been built and deployed!"
