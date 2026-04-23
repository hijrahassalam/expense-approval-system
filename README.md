# Expense Approval System

![CI](https://github.com/hijrahassalam/expense-approval-system/actions/workflows/tests.yml/badge.svg)

**Live Demo:** https://expense-approval.onrender.com
**API Docs:** https://expense-approval.onrender.com/api/documentation

> Fullstack expense management with multi-role approval workflow.
> Built with Laravel 13 + Vue 3 + PostgreSQL as a single SPA application.

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

Single SPA application — Vue 3 lives inside Laravel's `resources/js/`:

```
expense-approval-system/
├── app/                    # Laravel backend
├── resources/
│   ├── js/                 # Vue 3 SPA
│   │   ├── views/          # Page components
│   │   ├── components/     # Reusable components
│   │   ├── stores/         # Pinia stores
│   │   ├── router/         # Vue Router
│   │   └── lib/            # Axios config
│   ├── css/
│   └── views/
│       └── spa.blade.php   # SPA entry point
├── public/
│   └── build/              # Vite output
├── docker-compose.yml
└── README.md
```

- Laravel serves both the API (`/api/*`) and the SPA (catch-all route)
- Same-origin requests → no CORS, Sanctum cookies work naturally

## Local Development

```bash
git clone https://github.com/hijrahassalam/expense-approval-system.git
cd expense-approval-system

# Start services
docker-compose up -d

# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install

# Run migrations + seed
docker-compose exec app php artisan migrate --seed

# Frontend dev (with HMR)
npm run dev
```

- Backend API: http://localhost:8000
- Frontend dev: http://localhost:5173 (proxies /api to backend)

## API Endpoints

| Method | Endpoint                      | Auth | Role     | Description        |
|--------|-------------------------------|------|----------|--------------------|
| POST   | /api/login                    | —    | —        | Login user         |
| POST   | /api/logout                   | ✓    | —        | Logout user        |
| GET    | /api/me                       | ✓    | —        | Get current user   |
| GET    | /api/expenses                 | ✓    | —        | List expenses      |
| POST   | /api/expenses                 | ✓    | —        | Create expense     |
| GET    | /api/expenses/{id}            | ✓    | —        | Show expense       |
| PATCH  | /api/expenses/{id}            | ✓    | owner    | Update expense     |
| DELETE | /api/expenses/{id}            | ✓    | owner    | Delete expense     |
| POST   | /api/expenses/{id}/submit     | ✓    | owner    | Submit expense     |
| POST   | /api/expenses/{id}/approve    | ✓    | manager+ | Approve expense    |
| POST   | /api/expenses/{id}/reject     | ✓    | manager+ | Reject expense     |
| GET    | /api/admin/expenses/export    | ✓    | admin    | Export CSV         |
| GET    | /api/dashboard                | ✓    | —        | Dashboard summary  |
| GET    | /api/documentation            | —    | —        | Swagger UI         |

## Running Tests

```bash
php artisan test
```

## Roadmap

- [x] Phase 1: Foundation (Laravel 13, Sanctum, PostgreSQL, Docker)
- [x] Phase 1: User model with role enum + Auth endpoints
- [x] Phase 2: Expense & ApprovalLog models + CRUD
- [ ] Phase 2: Authorization (Policy) + Approval workflow
- [ ] Phase 3: Dashboard API + Swagger docs
- [x] Phase 4: Vue 3 SPA (router, stores, views, components)
- [ ] Phase 5: Integration & Deployment

## License

MIT
