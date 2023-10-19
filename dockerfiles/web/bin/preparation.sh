#!/bin/bash

composer install --no-interaction

php artisan key:generate

php artisan migrate --seed --no-interaction

npm ci
