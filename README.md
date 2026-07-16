# laravel-mysql-restaurant-manager

Laravel MySQL restaurant management system with dining tables, reservations, menu, orders, staff shifts, users, roles, dashboard, and activity logs.

## Tech Stack

- PHP 8.2+
- Laravel 11
- MySQL / MariaDB
- Blade + Bootstrap 5 + Font Awesome

## Features

- Dashboard with table availability, today's reservations, open orders, and shifts
- Dining table management with capacity, zone, and status
- Menu categories and items with vegetarian flag and prep time
- Reservation booking with table assignment and status workflow
- Restaurant orders with multi-item lines and status updates (open/served/paid)
- Staff shift scheduling
- Role-based access: owner, manager, waiter, host, viewer
- Activity logs

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Create database:

```sql
CREATE DATABASE restaurant_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Set `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_manager
DB_USERNAME=root
DB_PASSWORD=
```

Run:

```bash
php artisan migrate --seed
php artisan serve
```

## Demo Login

| Email | Role | Password |
|-------|------|----------|
| owner@example.com | Owner | password |
| host@example.com | Host | password |
| waiter@example.com | Waiter | password |
