#!/bin/bash

echo "Running deploy script"

echo "[1/5] Pulling from GitHub"
git pull origin

echo "[2/5] Creating database if one isn't found"
touch database/database.sqlite

echo "[3/5] Installing packages using composer"
composer install

echo "[4/5] Publishing API Platform assets"
php artisan api-platform:install

echo "[5/5] Migrating database"
docker exec -it laravel_app1 bash
php artisan migrate:fresh --seed
php artisan test

echo "The app has been built and deployed!"
