# 🛒 E-Commerce Simulator

A shopping simulation platform where users experience real e-commerce without paying anything. Full-stack educational project with Laravel, Vue 3, PostgreSQL and Docker.

**Status:** ✅ Functional MVP - Ready for Development

---

## 📋 Table of Contents

- [Features](#features)
- [Prerequisites](#prerequisites)
- [Quick Start](#quick-start)
- [Access & Credentials](#access--credentials)
- [Project Structure](#project-structure)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Development](#development)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)

---

## ✨ Features

### Implemented Features ✅

- 🛍️ **Product Catalog** - 99+ products with categories, images and variations
- 🛒 **Shopping Cart** - Add, remove, update items
- 💳 **Simulated Checkout** - Address and delivery method form
- 📦 **Order History** - Track completed purchases
- 👤 **Full Authentication** - Registration, login and logout with JWT
- 📊 **Observability Dashboard** - Prometheus + Grafana
- 🏆 **Achievement System** - 12 gamified badges
- 🌐 **REST API** - 7 fully functional controllers

### Planned Features 🔜

- 💰 Virtual currency system
- 🎮 Advanced gamification
- 📱 Mobile version
- 🔍 Advanced search with filters
- ⭐ Rating system

---

## 🔧 Prerequisites

- **Docker** and **Docker Compose** installed ([Download](https://www.docker.com/products/docker-desktop))
- **Git** to clone the repository
- ~2GB disk space
- Available ports: 3000, 3001, 5432, 6379, 8000, 9090

---

## 🚀 Quick Start

### 1. Clone the repository

```bash
git clone https://github.com/gtomanini/ecommerce-simulator.git
cd ecommerce-simulator
```

### 2. Configure environment

```bash
# Copy environment file (optional, Docker has defaults)
cp .env.example .env
```

### 3. Start the containers

```bash
docker-compose up -d
```

### 4. Wait for initialization (~2-3 minutes)

```bash
# Check API logs
docker-compose logs -f api

# When you see "Laravel development server started at http://0.0.0.0:8000"
# the application is ready!
```

### 5. Access the application

```
Frontend: http://localhost:3000
API:      http://localhost:8000/api
Grafana:  http://localhost:3001
```

---

## 📋 Access & Credentials

| Service | URL | Credentials |
|---------|-----|-------------|
| **Vue 3 Frontend** | http://localhost:3000 | Create account |
| **Laravel API** | http://localhost:8000/api/health | N/A |
| **Grafana Dashboard** | http://localhost:3001 | `admin` / `admin` |
| **Prometheus Metrics** | http://localhost:9090 | N/A |
| **PostgreSQL Database** | `localhost:5432` | `shopping` / `shopping_password` |
| **Redis Cache** | `localhost:6379` | N/A |

---

## 📁 Project Structure

```
ecommerce-simulator/
│
├── laravel/                          # Laravel 11 Backend
│   ├── app/Http/Controllers/        # 7 API Controllers
│   │   ├── AuthController.php        # Login, Register, Logout
│   │   ├── ProductController.php     # Products with filters
│   │   ├── CartController.php        # Shopping cart CRUD
│   │   ├── OrderController.php       # Orders
│   │   └── ...
│   ├── app/Models/                  # 13 Eloquent Models
│   ├── database/migrations/         # 12 Migrations
│   ├── database/seeders/            # 4 Seeders (99+ products)
│   └── routes/api.php               # Route definitions
│
├── resources/js/                    # Vue 3 Frontend
│   ├── features/                    # Feature-Based Architecture
│   │   ├── auth/                    # Authentication (Login, Register)
│   │   ├── products/                # Product catalog
│   │   ├── cart/                    # Shopping cart
│   │   ├── checkout/                # Checkout process
│   │   ├── orders/                  # Order history
│   │   ├── achievements/            # Badge system
│   │   └── layout/                  # Layout components
│   ├── stores/                      # Pinia State Management (5 stores)
│   ├── composables/                 # Reusable logic (3 composables)
│   ├── router/                      # Vue Router with protected routes
│   └── assets/                      # Styles and constants
│
├── docker-compose.yml               # Service orchestration
├── Dockerfile                       # Container build
├── prometheus.yml                   # Prometheus config
└── package.json                     # Node dependencies

```

---

## 🛠 Tech Stack

### Backend
- **Laravel 11** - PHP Framework
- **Laravel Sanctum** - API Authentication with JWT
- **PostgreSQL 16** - Database
- **Redis** - Cache and sessions
- **Composer** - PHP dependency manager

### Frontend
- **Vue 3** - JavaScript Framework
- **Pinia** - State Management
- **Vue Router** - Client-side routing
- **Axios** - HTTP client
- **Tailwind CSS** - Styling
- **Vite** - Build tool

### DevOps & Observability
- **Docker & Docker Compose** - Containerization
- **Prometheus** - Metrics collection
- **Grafana** - Dashboard visualization
- **Node.js** - Frontend runtime

---

## 🏗 Architecture

### Backend REST API

```
POST   /api/auth/register        → Register user
POST   /api/auth/login           → Login
POST   /api/auth/logout          → Logout (protected)
GET    /api/auth/me              → User data (protected)

GET    /api/products             → List products (with filters)
GET    /api/products/{id}        → Product details
GET    /api/categories           → List categories

GET    /api/cart                 → View cart (protected)
POST   /api/cart                 → Add item (protected)
PUT    /api/cart/{id}            → Update quantity (protected)
DELETE /api/cart/{id}            → Remove item (protected)

GET    /api/orders               → Order history (protected)
GET    /api/orders/{id}          → Order details (protected)
POST   /api/orders               → Create order (protected)

GET    /api/shipping-methods     → Shipping methods
GET    /api/achievements         → User achievements (protected)
```

### Frontend Routes

```
/                    → Home (Product catalog)
/auth/login          → Login page
/auth/register       → Registration page
/products/:id        → Product details
/cart                → Shopping cart (protected)
/checkout            → Checkout (protected)
/orders              → Order history (protected)
/orders/:id          → Order details (protected)
/achievements        → Achievements (protected)
```

---

## 💻 Development

### Run Artisan Commands

```bash
# Run migrations
docker-compose exec api php artisan migrate

# Run seeders
docker-compose exec api php artisan db:seed

# Reset database
docker-compose exec api php artisan migrate:fresh --seed

# Tinker (Interactive shell)
docker-compose exec api php artisan tinker
```

### View Logs

```bash
# API logs
docker-compose logs -f api

# Frontend logs
docker-compose logs -f frontend

# Prometheus logs
docker-compose logs -f prometheus

# All logs
docker-compose logs -f
```

### Stop Application

```bash
# Stop containers (data persists)
docker-compose down

# Stop and clean volumes (deletes data)
docker-compose down -v

# Restart after changes
docker-compose restart
docker-compose up -d --build
```

---

## 🧪 Testing the Application

### 1. Register a new user

```bash
# Frontend: http://localhost:3000
1. Click on "Login"
2. Click on "Register here"
3. Fill in: Name, Email, Password
4. Click on "Create Account"
```

### 2. Browse and filter products

```bash
1. Go to Home
2. Use the search to find products
3. Filter by category
4. Sort by price
```

### 3. Make a purchase

```bash
1. Click on "Add to Cart" on any product
2. See the number on 🛍️ icon (cart)
3. Click on the cart
4. Click on "Proceed to Checkout"
5. Fill in: Name, Email, Phone, Address
6. Select delivery method
7. Click on "Place Order"
```

### 4. View order history

```bash
1. Click on "Orders" in menu
2. See all your orders
```

---

## 🔧 Troubleshooting

### "Port X is already in use"

```bash
# Find which process is using the port
lsof -i :3000  # or the port in use

# Kill the process
kill -9 <PID>

# Or change the port in docker-compose.yml
```

### "Connection refused - API doesn't respond"

```bash
# Wait longer (2-3 minutes)
docker-compose logs -f api

# If it persists, restart
docker-compose restart api
```

### "Error on login/register"

```bash
# Check if migrations ran
docker-compose exec api php artisan migrate:status

# If not, run:
docker-compose exec api php artisan migrate:fresh --seed
```

### "Frontend stuck in infinite loop / won't load"

```bash
# Clear browser cache (Ctrl+Shift+Delete)
# Reload page (Ctrl+F5)
# If it persists, restart frontend:
docker-compose restart frontend
```

### "SQL Error - Table doesn't exist"

```bash
# Run migrations again
docker-compose exec api php artisan migrate:fresh --seed
```

---

## 📊 Observability

### Prometheus

- **URL**: http://localhost:9090
- **API Metrics**: http://localhost:8000/api/metrics
- **Useful Queries**:
  - `http_requests_total` - Total requests
  - `http_request_duration_seconds` - Request latency

### Grafana

- **URL**: http://localhost:3001
- **Login**: `admin` / `admin`
- **Pre-configured Dashboards**:
  - Laravel (requests, errors, response time)
  - PostgreSQL (queries, connections)
  - Redis (memory, commands)
  - System (CPU, memory)

---

## 🚀 Production Deployment (fits 1 core / 1 GB)

The default `docker-compose.yml` is a development stack (Vite dev server,
Redis, Prometheus, Grafana) and is too heavy for a 1 GB instance. For
deployment there is a trimmed production stack in `docker-compose.prod.yml`:

- **Frontend** built to static files and served by nginx (no Node at runtime)
- **API** as a self-contained image (deps installed at build, config/routes cached)
- **PostgreSQL** with reduced `shared_buffers`
- **No Redis** — cache uses the database, sessions use cookies
- **Prometheus + Grafana** are optional (behind the `monitoring` profile)
- Per-container `mem_limit`s; the core stack idles at **~80 MB total**

nginx serves the SPA and proxies `/api` to the API container (same origin,
so there is no CORS to configure).

### Steps

```bash
# 1. Configure environment
cp .env.prod.example .env.prod
# Edit .env.prod: set a real APP_KEY and DB_PASSWORD
#   docker run --rm php:8.3-cli php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"

# 2. Build and start (frontend on port 80; migrations + seed run automatically)
docker compose --env-file .env.prod -f docker-compose.prod.yml up -d --build

# 3. (optional) Start monitoring too
docker compose --env-file .env.prod -f docker-compose.prod.yml --profile monitoring up -d
```

> ⚠️ The image **build** (npm + composer) needs more RAM than the runtime and
> may OOM on a 1 GB box. Build on a machine with more memory (or CI) and push
> the images, or temporarily add ~2 GB of swap on the instance for the build.

---

## 🤝 Contributing

This is an open educational project! Feel free to:

- Report bugs via GitHub Issues
- Suggest improvements in Discussions
- Fork and submit Pull Requests
- Use as a base for your own projects

### How to contribute

```bash
# 1. Fork the project
# 2. Create a branch (git checkout -b feature/AmazingFeature)
# 3. Commit changes (git commit -m 'Add AmazingFeature')
# 4. Push to branch (git push origin feature/AmazingFeature)
# 5. Open a Pull Request
```

---

## 📝 License

MIT License - see LICENSE file for details

---

## 👨‍💻 Developer

Gustavo Tomanini - [@gtomanini](https://github.com/gtomanini)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - Backend framework
- [Vue.js](https://vuejs.org) - Frontend framework
- [Docker](https://www.docker.com) - Containerization
- [PostgreSQL](https://www.postgresql.org) - Database
- [Pinia](https://pinia.vuejs.org) - State management

---

## 📞 Support

If you have questions or problems:

1. Check the [Troubleshooting](#troubleshooting) section
2. Open an [Issue](https://github.com/gtomanini/ecommerce-simulator/issues)
3. Check the logs: `docker-compose logs -f`

Last updated: June 2026
