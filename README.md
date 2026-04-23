# Expense Approval System

![CI](https://github.com/hijrahassalam/expense-approval-system/actions/workflows/tests.yml/badge.svg)

**Live Demo:** https://expense-approval.onrender.com
**API Docs:** https://expense-approval.onrender.com/api/documentation

> Fullstack expense management with multi-role approval workflow.
> Built with Laravel 13 + Vue 3 + PostgreSQL in a monorepo with single-deployment architecture.

## Demo Credentials

| Role    | Email              | Password |
|---------|--------------------|----------|
| Admin   | admin@demo.com     | password |
| Manager | manager@demo.com   | password |
| Staff   | staff@demo.com     | password |
| Staff 2 | staff2@demo.com    | password |

## Tech Stack

**Backend:** Laravel 13 · PHP 8.4 · PostgreSQL · Sanctum · PHPUnit · L5-Swagger
**Frontend:** Vue 3 (Composition API) · Pinia · Vue Router · Tailwind CSS · Chart.js
**Infrastructure:** Docker · Render.com · Neon (PostgreSQL) · GitHub Actions

## Features

- Multi-role authentication (staff, manager, admin) with Sanctum SPA cookies
- Expense workflow: `draft → submitted → approved / rejected`
- Approval queue with mandatory comments and audit trail
- Admin dashboard with CSV export
- Interactive dashboard: summary cards + monthly trend chart
- Policy-based authorization
- Swagger API documentation at `/api/documentation`
- Dockerised local environment
- CI: automated PHPUnit tests + frontend build check

## Architecture

This is a **monorepo** with **single-deployment**:

- Vue builds to `backend/public/build/`
- Laravel serves both the API (`/api/*`) and the SPA (catch-all route)
- Same-origin requests → no CORS, Sanctum cookies work naturally
- One URL to deploy, one URL to share

```
expense-approval-system/
├── backend/          # Laravel 13 API + SPA server
├── frontend/         # Vue 3 SPA (Phase 4)
├── docker-compose.yml
├── render.yaml       # Deployment config (Phase 5)
└── build.sh          # Deploy script (Phase 5)
```

## Local Development

```bash
git clone https://github.com/hijrahassalam/expense-approval-system.git
cd expense-approval-system

# Start services
docker-compose up -d

# Install dependencies
docker-compose exec app composer install

# Run migrations + seed
docker-compose exec app php artisan migrate --seed

# Frontend (Phase 4)
cd frontend
npm install
npm run dev
```

- Backend API: http://localhost:8000
- Frontend dev: http://localhost:5173 (Phase 4)
- Swagger docs: http://localhost:8000/api/documentation (Phase 3)

## API Endpoints

| Method | Endpoint                      | Auth | Role     | Description        |
|--------|-------------------------------|------|----------|--------------------|
| POST   | /api/v1/login                 | —    | —        | Login user         |
| POST   | /api/v1/logout                | ✓    | —        | Logout user        |
| GET    | /api/v1/me                    | ✓    | —        | Get current user   |
| GET    | /api/v1/expenses              | ✓    | —        | List expenses      |
| POST   | /api/v1/expenses              | ✓    | —        | Create expense     |
| GET    | /api/v1/expenses/{id}         | ✓    | —        | Show expense       |
| PATCH  | /api/v1/expenses/{id}         | ✓    | owner    | Update expense     |
| DELETE | /api/v1/expenses/{id}         | ✓    | owner    | Delete expense     |
| POST   | /api/v1/expenses/{id}/submit  | ✓    | owner    | Submit expense     |
| POST   | /api/v1/expenses/{id}/approve | ✓    | manager+ | Approve expense    |
| POST   | /api/v1/expenses/{id}/reject  | ✓    | manager+ | Reject expense     |
| GET    | /api/v1/admin/expenses/export | ✓    | admin    | Export CSV         |
| GET    | /api/v1/dashboard             | ✓    | —        | Dashboard summary  |
| GET    | /api/documentation            | —    | —        | Swagger UI         |

## Running Tests

```bash
cd backend
php artisan test
```

## Deployment

See [render.yaml](render.yaml) and [build.sh](build.sh) for deployment configuration.

**Environment Variables:**

| Variable                  | Description                          |
|---------------------------|--------------------------------------|
| APP_KEY                   | Laravel app key                      |
| APP_ENV                   | `production`                         |
| APP_URL                   | https://your-app.onrender.com        |
| DB_HOST                   | Neon host (ep-xxx.neon.tech)         |
| DB_DATABASE               | Neon database name                   |
| DB_USERNAME               | Neon username                        |
| DB_PASSWORD               | Neon password                        |
| SANCTUM_STATEFUL_DOMAINS  | your-app.onrender.com                |
| SESSION_DOMAIN            | .onrender.com                        |

## Roadmap

- [x] Phase 1: Foundation (Laravel 13, Sanctum, PostgreSQL)
- [ ] Phase 2: Core Domain (Expenses, Approval workflow)
- [ ] Phase 3: Admin & Dashboard API + Swagger docs
- [ ] Phase 4: Frontend (Vue 3 + Tailwind)
- [ ] Phase 5: Integration & Deployment

## License

MIT
