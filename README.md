# 🛒 E-Commerce Simulator

A shopping simulation platform where users experience real e-commerce without paying anything. Full-stack educational project with Laravel, Vue 3, PostgreSQL and Docker.

**Status:** ✅ Production-ready — auto-deployed to Oracle Cloud via GitHub Actions

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
- [Testing](#testing)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)

---

## ✨ Features

### Implemented Features ✅

- 🛍️ **Product Catalog** - 99 products with categories, images and variations (search, filter, sort, pagination)
- 🛒 **Shopping Cart** - Add, remove, update items
- 👤 **Full Authentication** - Registration, login and logout with token auth (Laravel Sanctum)
- 🧑‍💼 **User Profile** - Editable profile (phone/address) that **pre-fills the checkout** form
- 💳 **Checkout + Payment Screen** - Address form, then a dedicated payment screen with **credit/debit card (masked inputs) and Pix**
- 📦 **Order History** - Track orders and their payment status
- 🏆 **Achievement System** - 12 gamified badges
- 🛠️ **Admin Panel** - Filament admin at `/admin` to manage products & categories (role-gated by `is_admin`)
- 📝 **Audit / Activity Log** - Every key action (register, login, cart, order, payment, profile update) is recorded in an `audit_logs` table
- 📈 **Metrics & Observability** - `/api/metrics` (Prometheus format) + Prometheus + Grafana dashboards
- 🌐 **REST API** - 10 controllers, fully tested (**~98% line coverage**, min 80% enforced)
- 🚀 **CI/CD** - Push to `main` auto-builds images and deploys to Oracle Cloud

### Planned Features 🔜

- 💰 Virtual currency system
- 🎮 Advanced gamification
- 📱 Mobile version
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
| **Admin Panel (Filament)** | http://localhost:8000/admin | Admin account (seeded on first run) |
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
│   ├── app/Http/Controllers/        # 10 API Controllers (auth, profile,
│   │                                #   products, cart, orders, payment,
│   │                                #   achievements, metrics, ...)
│   ├── app/Services/AuditService.php # Records actions + Prometheus metrics
│   ├── app/Models/                  # 14 Eloquent Models (incl. AuditLog)
│   ├── database/factories/          # Model factories (for tests/seeds)
│   ├── database/migrations/         # Migrations (catalog, profile, audit_logs)
│   ├── database/seeders/            # Seeders (99 products) — idempotent
│   ├── tests/                       # PHPUnit suite (~98% coverage)
│   └── routes/api.php               # Route definitions
│
├── resources/js/                    # Vue 3 Frontend
│   ├── features/                    # Feature-Based Architecture
│   │   ├── auth/                    # Authentication (Login, Register)
│   │   ├── products/                # Product catalog
│   │   ├── cart/                    # Shopping cart
│   │   ├── checkout/                # Checkout + Payment screen
│   │   ├── orders/                  # Order history
│   │   ├── profile/                 # User profile
│   │   ├── achievements/            # Badge system
│   │   └── layout/                  # Layout components
│   ├── stores/                      # Pinia State Management
│   ├── composables/                 # Reusable logic (useApi, ...)
│   └── router/                      # Vue Router with protected routes
│
├── docker-compose.yml               # Dev stack orchestration
├── docker-compose.prod.yml          # Production stack (1 core / 1 GB)
├── Dockerfile / Dockerfile.frontend.prod
├── laravel/Dockerfile.prod          # Production API image
├── nginx.conf                       # SPA + /api reverse proxy (prod)
├── .github/workflows/deploy.yml     # CI/CD: build → GHCR → deploy
├── DEPLOY.md                        # Deployment guide (Oracle Cloud)
└── prometheus.yml                   # Prometheus config
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
POST   /api/auth/register            → Register user
POST   /api/auth/login               → Login
POST   /api/auth/logout              → Logout (protected)
GET    /api/auth/me                  → User data (protected)

GET    /api/profile                  → Get profile (protected)
PUT    /api/profile                  → Update profile (protected)

GET    /api/products                 → List products (search/filter/sort/paginate)
GET    /api/products/{id}            → Product details
GET    /api/categories               → List categories

GET    /api/cart                     → View cart (protected)
POST   /api/cart                     → Add item (protected)
PUT    /api/cart/{id}                → Update quantity (protected)
DELETE /api/cart/{id}                → Remove item (protected)

GET    /api/orders                   → Order history (protected)
GET    /api/orders/{id}              → Order details (protected)
POST   /api/orders                   → Create order — status "pending" (protected)
POST   /api/orders/{order}/payment   → Pay an order — card/Pix (protected)

GET    /api/shipping-methods         → Shipping methods
GET    /api/achievements             → User achievements (protected)

GET    /api/health                   → Health check
GET    /api/metrics                  → Prometheus metrics (from the audit log)
```

### Frontend Routes

```
/                    → Home (Product catalog)
/auth/login          → Login page
/auth/register       → Registration page
/products/:id        → Product details
/cart                → Shopping cart (protected)
/checkout            → Checkout / delivery details (protected)
/orders/:id/payment  → Payment screen — card / Pix (protected)
/orders              → Order history (protected)
/orders/:id          → Order details (protected)
/profile             → User profile (protected)
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

## 🧪 Manual Walkthrough

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
3. Click on the cart → "Proceed to Checkout"
4. The delivery form is pre-filled from your profile (edit if needed)
5. Select a delivery method → "Continue to Payment"
6. On the payment screen, pick Credit Card / Debit Card / Pix
7. (cards) the number, expiry and CVV fields are masked as you type
8. Confirm payment → order becomes "confirmed"
```

### 4. View order history

```bash
1. Click on "Orders" in menu
2. See all your orders and their status
```

---

## 🧪 Testing

The backend has an automated test suite (PHPUnit) with **~98% line coverage**
(a minimum of **80%** is enforced).

```bash
# Run the full suite
docker-compose exec api ./vendor/bin/phpunit

# Run with coverage + enforce the 80% minimum (fails below it)
docker-compose exec api composer test:coverage
# HTML report: laravel/coverage/html/index.html
```

Tests run against an isolated in-memory SQLite database, so they never touch
your PostgreSQL data. Coverage spans every model, controller and the
`AuditService` (auth, products, cart, orders, payment, profile, achievements,
metrics).

---

## 🚀 Deployment

The repo ships with a **production stack** and an **automated CI/CD pipeline**.

- **`docker-compose.prod.yml`** — a trimmed stack tuned for a 1 core / 1 GB
  instance: the frontend is built to static files and served by nginx (which
  also proxies `/api`), Redis is dropped (cache → database, sessions →
  cookie), and Prometheus/Grafana are optional (`monitoring` profile). The
  core services idle at **~80 MB** total.
- **`.github/workflows/deploy.yml`** — on every push to `main`, GitHub Actions
  builds the API + frontend images, pushes them to **GHCR**, and deploys to
  the server over SSH (`docker compose pull && up -d`). Building happens in CI,
  never on the small instance.

This project is deployed to an **Oracle Cloud** VM (Ubuntu 22.04,
VM.Standard.E2.1.Micro). Full step-by-step instructions — server setup, the
two-layer Oracle firewall, GitHub secrets and troubleshooting — are in
**[DEPLOY.md](DEPLOY.md)**.

```bash
# Run the production stack locally
cp .env.prod.example .env.prod   # set a real APP_KEY and DB_PASSWORD
docker compose --env-file .env.prod -f docker-compose.prod.yml up -d --build
# App on http://localhost
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

Last updated: June 2026 — production-ready, auto-deployed to Oracle Cloud.
