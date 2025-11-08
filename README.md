# Afrifounders - Laravel Task API

A simple Laravel REST API for a task management assessment.

## Features
- User registration & login using Laravel Sanctum (token-based auth)
- CRUD operations for tasks (owned by authenticated user)
- Filtering by status (`?status=pending|in-progress|completed`)
- Pagination (`?per_page=10`)
- Basic feature tests for auth and tasks

## Requirements
- PHP 8.1+ 
- Composer
- MySQL 
- Laravel 12

## Installation

```bash
git clone <github.com/theo-georgewill/afrifounders-task-api>
cd afrifounders-task-api
composer install
cp .env.example .env
edit .env DB_ settings
php artisan key:generate
php artisan migrate
php artisan serve
```
