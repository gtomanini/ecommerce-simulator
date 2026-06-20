#!/bin/bash

# Setup Laravel Fresh Installation
echo "Setting up Laravel..."

# Copy .env if not exists
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✓ .env file created"
fi

# Generate app key if not set
if grep -q "APP_KEY=base64:dev" .env; then
    php artisan key:generate
    echo "✓ APP_KEY generated"
fi

# Install composer dependencies
if [ ! -d vendor ]; then
    composer install --no-interaction --no-dev
    echo "✓ Composer dependencies installed"
fi

# Install npm dependencies
if [ ! -d node_modules ]; then
    npm install
    echo "✓ NPM dependencies installed"
fi

# Run migrations and seeders
echo "Running migrations..."
php artisan migrate:fresh --seed

echo "✓ Setup completed!"
echo ""
echo "🚀 Services available at:"
echo "   - API: http://localhost:8000"
echo "   - Frontend: http://localhost:3000"
echo "   - Grafana: http://localhost:3001 (admin/admin)"
echo "   - Prometheus: http://localhost:9090"
