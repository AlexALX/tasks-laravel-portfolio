param(
    [string]$mode = "self"  # default mode = self
)

if (!(Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host ".env file created from example"
}

switch ($mode) {

    "self" {
        Write-Host "Starting self-contained setup..."

        docker compose --profile self up -d
        docker exec -it laravel_self php artisan migrate

        Write-Host "Self-contained setup complete! Run http://localhost:8080"
    }

    "dev" {
        Write-Host "Starting dev setup..."
        docker compose --profile dev up -d
        docker exec -it laravel_app composer install
        docker exec -it laravel_app php artisan migrate
        docker exec -it laravel_app npm install
        docker exec -it laravel_app npm run build

        Write-Host "Dev setup complete! Run http://localhost:8080"
    }

    default {
        Write-Host "Unknown mode '$mode'. Use 'self' or 'dev'."
    }
}