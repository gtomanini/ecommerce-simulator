#!/bin/sh
set -e

echo "Waiting for PostgreSQL at ${DB_HOST}:${DB_PORT:-5432}..."
until pg_isready -h "${DB_HOST}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME}" >/dev/null 2>&1; do
  sleep 2
done
echo "Database is ready."

# Apply schema and seed the catalog (DatabaseSeeder is idempotent).
php artisan migrate --force
php artisan db:seed --force

# Cache framework config/routes for faster, lower-memory runtime.
php artisan config:cache
php artisan route:cache

echo "Starting Laravel API on 0.0.0.0:8000"
exec php artisan serve --host=0.0.0.0 --port=8000
