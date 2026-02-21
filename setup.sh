#!/usr/bin/env bash

# default mode = self
MODE="${1:-self}"

# Создаём .env если его нет
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo ".env file created from example"
fi

case "$MODE" in

  "self")
    echo "Starting self-contained setup..."
    
    docker compose --profile self up -d
    docker exec -it laravel_self php artisan migrate

    echo "Self-contained setup complete! Open http://localhost:8080"
    ;;

  "dev")
    echo "Starting dev setup..."

    docker compose --profile dev up -d
    docker exec -it laravel_app composer install
    docker exec -it laravel_app php artisan migrate
    docker exec -it laravel_app npm install
    docker exec -it laravel_app npm run build

    echo "Dev setup complete! Open http://localhost:8080"
    ;;

  *)
    echo "Unknown mode '$MODE'. Use 'self' or 'dev'."
    exit 1
    ;;
esac