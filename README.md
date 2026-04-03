# Qistas

Qistas is a Laravel-based legal practice management platform for law firms. It provides firm-scoped case management, clients, court sessions, tasks, team management, subscriptions, payments, notifications, audit logs, and an admin subscription panel.

---

## Table of Contents

- [Overview](#overview)
- [Core Features](#core-features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Domain Model](#domain-model)
- [Access Control & Tenancy](#access-control--tenancy)
- [Routing Map](#routing-map)
- [Scheduled Jobs](#scheduled-jobs)
- [Requirements](#requirements)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
- [Database & Seed Data](#database--seed-data)
- [Running the App](#running-the-app)
- [Testing](#testing)
- [Subscription & Billing Flows](#subscription--billing-flows)
- [API](#api)
- [Operational Notes](#operational-notes)
- [Troubleshooting](#troubleshooting)
- [Deployment Checklist](#deployment-checklist)
- [License](#license)

---

## Overview

Qistas is designed for law-office workflows with a strong emphasis on:

- Firm-level data ownership
- Team collaboration (owners, lawyers, assistants)
- Lifecycle-aware subscriptions (including enterprise contracts)
- Billing review and renewal flows
- Auditability and notifications

The app uses Laravel Jetstream + Livewire for authentication/profile/team foundations, then builds custom legal and billing modules on top.

---

## Core Features

### Legal Operations

- Case management (`cases` resource)
- Client management (`clients` resource)
- Court session scheduling (`sessions` resource)
- Task tracking with status updates (`tasks` + `tasks/{task}/status`)
- Dashboard analytics for cases, tasks, fees, and upcoming sessions
- Global search endpoint for cross-module querying

### Team & Identity

- Authenticated + verified user access
- Role model: `admin`, `owner`, `lawyer`, `assistant`
- Team member invitation flow (7-day activation window)
- Invitation resend support
- Automatic purge for expired, unactivated invitations

### Subscription & Billing

- Firm subscriptions: `basic`, `office`, `premium`, `enterprise`
- Subscription statuses: `trial`, `active`, `expired`, `suspended`, `cancelled`
- Renewal requests via payment submissions (pending review)
- Admin billing panel for subscription and payment status management
- Enterprise contract fields and lifecycle enforcement

### Monitoring & Traceability

- In-app notifications + unread counters
- Audit logs for sensitive actions
- Scheduled contract reminders and lifecycle transitions

---

## Tech Stack

### Backend

- PHP `^8.3`
- Laravel Framework `^13.0`
- Laravel Jetstream `^5.5`
- Laravel Sanctum `^4.0`
- Livewire `^3.6.4`

### Frontend / Build

- Vite `^8`
- Tailwind CSS `^3.4`
- PostCSS + Autoprefixer
- Axios

### Tooling

- PHPUnit `^12.5`
- Laravel Pint
- Laravel Pail
- Faker

---

## Project Structure

Key directories:

- `app/Http/Controllers`: Feature and admin controllers
- `app/Http/Middleware`: Access middleware (`EnsureSubscriptionAccess`)
- `app/Models`: Domain entities (case, client, session, subscription, etc.)
- `app/Services`: Notification and template services
- `app/Console/Commands`: Scheduled business automation
- `database/migrations`: Schema evolution
- `database/factories`: Rich fake data builders
- `database/seeders`: Environment seed orchestration
- `resources/views`: Blade UI layer
- `routes/web.php`: Main web routes
- `routes/api.php`: API endpoints (Sanctum protected user endpoint)
- `routes/console.php`: Schedule + artisan console routes

---

## Domain Model

Major models in this project:

- `LawFirm`
- `User`
- `CaseModel`
- `Client`
- `CourtSession`
- `Task`
- `Subscription`
- `Payment`
- `Notification`
- `AuditLog`

Typical relationships:

- A law firm has many users, cases, clients, sessions, tasks, subscriptions, payments, and audit logs.
- Cases can be linked to multiple clients.
- Subscriptions belong to law firms and have many payments.

---

## Access Control & Tenancy

### Authentication Layer

- Web access is guarded by `auth` and `verified` middleware.
- API `/api/user` uses `auth:sanctum`.

### Role Helpers

`User` model helper methods include:

- `isAdmin()`
- `isOwner()`
- `isLawyer()`
- `isAssistant()`

### Subscription Access Lock

Custom middleware alias: `subscription.access` (registered in `bootstrap/app.php`).

Behavior:

- Non-admin users are restricted when the latest firm subscription is `expired`/`suspended` or logically expired.
- During lock, only subscription/settings/profile routes remain accessible.

### Data Scoping

The project includes scope-based firm filtering patterns (for example, `Subscription` uses a global law-firm scope). Admin flows intentionally bypass scopes with `withoutGlobalScopes()` where required.

---

## Routing Map

Main web routes (`routes/web.php`):

- `/` landing page
- `/dashboard`
- `/search`
- `/calendar`
- Resource routes:
	- `/cases`
	- `/clients`
	- `/tasks`
	- `/sessions`
	- `/team`
- Team invitation resend:
	- `POST /team/{team}/resend-invitation`
- Notifications:
	- `GET /notifications`
	- `POST /notifications/mark-read`
- Audit logs:
	- `GET /logs`
- Subscription:
	- `GET /subscription`
	- `POST /subscription/renew`
- Admin subscriptions panel:
	- `GET /admin/subscriptions`
	- `GET /admin/subscriptions/enterprise`
	- `POST /admin/subscriptions`
	- `PATCH /admin/subscriptions/{subscription}/status`
	- `PATCH /admin/subscriptions/{subscription}/enterprise`
	- `PATCH /admin/subscriptions/payments/{payment}/status`
- Settings:
	- `GET /settings`
	- `POST /settings/firm`
	- `POST /settings/profile`

---

## Scheduled Jobs

Defined in `routes/console.php`:

- `reminders:enterprise-contracts` daily at `08:00`
- `subscriptions:enforce-enterprise-lifecycle` daily at `08:30`
- `team-invitations:purge-expired` hourly

### Command Responsibilities

- `SendEnterpriseContractReminders`: sends reminders at 30/15/7 days before enterprise contract end.
- `EnforceEnterpriseSubscriptionLifecycle`: marks enterprise subscriptions as expired at contract end, then suspended after grace period.
- `PurgeExpiredTeamInvitations`: removes pending invited users whose activation window elapsed.

---

## Requirements

- PHP `8.3+`
- Composer `2+`
- Node.js `20+` (recommended for modern Vite)
- npm `10+`
- A database engine (SQLite/MySQL/PostgreSQL)
- Mail driver configured for invitation and notification email delivery

---

## Installation

From project root:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Or use the predefined Composer setup script:

```bash
composer run setup
```

---

## Environment Configuration

Start from `.env.example` and configure at minimum:

- `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_URL`
- `DB_*` connection variables
- `MAIL_*` variables
- `QUEUE_CONNECTION` (default is `database`)
- `SESSION_DRIVER`, `CACHE_STORE`

Important notes:

- Default sample config uses SQLite.
- If using queue `database`, generate tables if missing:

```bash
php artisan queue:table
php artisan migrate
```

---

## Database & Seed Data

Run migrations and seeders:

```bash
php artisan migrate --seed
```

`DatabaseSeeder` provisions multiple law firms with users, clients, cases, sessions, tasks, subscriptions, payments, notifications, and audit logs.

Demo account seeded:

- Email: `demo@qistas.test`
- Password: `password`

Use this account for quick local walkthroughs.

---

## Running the App

### Option A: Full Dev Stack via Composer Script

```bash
composer run dev
```

This runs concurrently:

- Laravel web server
- Queue listener
- Log tailing (Pail)
- Vite dev server

### Option B: Manual Processes

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Terminal 3:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

Terminal 4 (optional logs):

```bash
php artisan pail --timeout=0
```

---

## Testing

Run all tests:

```bash
composer run test
```

or:

```bash
php artisan test
```

Test configuration (`phpunit.xml`) uses:

- `APP_ENV=testing`
- In-memory SQLite database (`DB_DATABASE=:memory:`)
- Array drivers for cache/session/mail

---

## Subscription & Billing Flows

### End-User Flow

- Firm owner visits subscription page.
- Owner submits renewal request with payment method (`ccp`, `bank_transfer`, `cash`).
- A pending payment record is created for admin review.

### Admin Flow

- Admin reviews subscriptions and pending payments.
- Admin updates payment status (`pending`, `completed`, `failed`).
- On completed renewal payment, subscription is reactivated and extended.
- Enterprise subscriptions can be managed via dedicated enterprise panel fields (contract number, dates, user limit, billing cycle).

### Locking Logic

- Locked when subscription status is expired/suspended or end date has passed.
- Non-admin locked users can still access subscription/settings/profile routes to recover account state.

---

## API

Current API route:

- `GET /api/user` (requires Sanctum authentication)

This project is primarily web-first and Blade/Livewire-driven; API surface can be extended as needed.

---

## Operational Notes

### Scheduler

In production, ensure Laravel scheduler runs every minute:

```bash
php artisan schedule:run
```

Configure via system cron / task scheduler accordingly.

### Queue Workers

If asynchronous features are used heavily, run persistent workers (e.g., Supervisor/systemd on Linux or equivalent process manager).

### Health Check

Framework health endpoint is enabled at:

- `/up`

---

## Troubleshooting

- **White page / 500 error**: check `storage/logs/laravel.log` and `APP_DEBUG`.
- **Assets missing**: run `npm install` then `npm run build` or `npm run dev`.
- **Auth/session issues**: confirm `APP_URL`, session driver, and cookies domain config.
- **Email not arriving**: verify `MAIL_*` settings; local `log` mailer writes emails to logs.
- **Queue not processing**: start `php artisan queue:listen` or worker process.
- **No scheduled effects**: verify scheduler is configured and running.

---

## Deployment Checklist

- Set production `.env` values
- Disable debug: `APP_DEBUG=false`
- Configure real DB and run migrations
- Build assets: `npm ci && npm run build`
- Cache framework config/routes/views:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- Configure queue workers
- Configure scheduler (`schedule:run` every minute)
- Set writable permissions for `storage` and `bootstrap/cache`

---

## License

This project is built on Laravel and follows the repository license declared in `composer.json`.
