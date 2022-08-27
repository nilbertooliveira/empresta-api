#!/bin/sh
set -e

APP_NAME=$(grep DOCKER_APP_NAME .env | cut -d '=' -f2)

echo "Deploying application ..."

# Enter maintenance mode
docker-compose down
    # Update codebase
    git fetch origin master
    git reset --hard origin/master

    # Install dependencies based on lock file
    docker-compose exec ${APP_NAME} composer install

    # Migrate database
    docker exec ${APP_NAME} php artisan migrate

# Exit maintenance mode
docker-compose up

echo "Application deployed!"
