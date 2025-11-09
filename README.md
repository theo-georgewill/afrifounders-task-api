# Task Sync API

A Laravel RESTful API for task management. Users can register, authenticate, and manage tasks with CRUD operations. Tasks support soft deletion, filtering, and pagination. All key actions are logged.

> **Note:** A lightweight React SPA frontend has been implemented in a separate repository for interacting with this API. [Afrifounders-task-frontend](https://github.com/theo-georgewill/afrifounders-task-frontend)

---

## Features

- User authentication with **Laravel Sanctum** (session/cookie + API tokens)  
- Task CRUD for authenticated users only  
- **Soft deletes** for tasks  
- Logging of key events: task created, read, updated, deleted; user registration, login, logout  
- Filtering tasks by status (`pending`, `in-progress`, `completed`) and pagination  
- Validation using **Form Requests**  
- Authorization: only task creators can access/update/delete their tasks  
- Proper JSON error handling  
- **Tests:** Feature tests implemented (all passing)

---

## Tech Stack

- **Backend:** Laravel 12, MySQL  
- **Authentication:** Laravel Sanctum  
- **Logging:** Laravel logging (`storage/logs/laravel.log`)  
- **Testing:** PHPUnit for backend  

---

## Setup Instructions

1. **Clone repository**
```bash
git clone <github.com/theo-georgewill/afrifounders-task-api>
cd <afrifounders-task-api>
```
2. **Install dependencies**
```bash
composer install
cp .env.example .env
```
3. **Environment setup**
```bash
edit .env DB_ settings
php artisan key:generate
php artisan migrate
php artisan serve
```


4. **Run migrations & seeders**
```bash
php artisan migrate --seed
```

5. **Serve the API**
```bash
php artisan serve
# API runs at http://127.0.0.1:8000
```

6. **API Endpoints**
```bash
Method	Endpoint	    Description	                                Auth Required
POST	/api/register	Register a new user	                            No
POST	/api/login	    Login user	                                    No
POST	/api/logout	    Logout user	                                    Yes
GET	    /api/tasks	    List tasks (filtering & pagination supported)	Yes
POST	/api/tasks	    Create a new task	                            Yes
GET	    /api/tasks/{id}	View a single task	                            Yes
PUT	    /api/tasks/{id}	Update a task	                                Yes
DELETE	/api/tasks/{id}	Soft delete a task	                            Yes
```

6. **Filtering & Pagination**

**Filter by status**
```bash
GET /api/tasks?status=pending
```
**Pagination**
```bash
GET /api/tasks?per_page=10&page=2
```


7. **Testing**
Run backend tests:
```bash
php artisan test
```

8. **Design & Approach**

Authentication: Sanctum supports session/cookie and token-based authentication

Authorization: Only task creators can access their tasks (user_id)

Validation: Backend ensures correct input; frontend handles client-side validation separately

Soft Deletes: Ensures tasks can be recovered

Logging: Tracks key user and task actions for audit

Frontend: A React SPA is implemented in a separate repository to interact with this API
