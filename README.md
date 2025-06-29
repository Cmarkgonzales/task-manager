# 🗂️ Task Manager Monorepo

A full-stack Task Management System built with:

- **Backend:** Laravel 11 (PHP 8.2+, Dockerized with MySQL 8)
- **Frontend:** Vue 3 (Vite, Composition API, Pinia, TailwindCSS)

---

## 📸 Screenshot

![Task Manager Screenshot](./frontend/public/screenshot.png)

---

## API Documentation

This project includes a full-featured Postman collection to test and document the backend API.

[Download Postman Collection](./backend/TaskManager.postman_collection.json)

This collection includes endpoints for:

- **Authentication**
  - POST `/api/register`
  - POST `/api/login`
  - GET `/api/user`
- **Task Management**
  - GET `/api/tasks`
  - POST `/api/tasks`
  - PUT `/api/tasks/{id}`
  - DELETE `/api/tasks/{id}`
  - PATCH `/api/tasks/reorder`
  - PATCH `/api/tasks/{id}/status`
- **Admin & Stats**
  - GET `/api/tasks/stats` (for logged-in users)

> To use:  
> 1. Import the collection into [Postman](https://www.postman.com/).  
> 2. Set `{{base_url}}` to your local or deployed API URL.  
> 3. Authenticate via `/api/login` to get your token.  
> 4. Set `Authorization: Bearer {{auth_token}}` for protected routes.

---

## Project Structure

This monorepo contains:
    task-manager/
    ├── backend/ # Laravel backend code (Dockerized)
    ├── frontend/ # Vue 3 frontend code (Vite)
    └── README.md # Setup instructions + API documentation

---

##  Quick Start

###  Prerequisites

- [Docker (version 26.1.3) + Docker Compose (version v2.35.1)](https://docs.docker.com/get-docker/)
- Node.js v20.19.3
- NPM or Yarn


##  Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/Cmarkgonzales/task-manager.git
cd task-manager
```

## Backend Setup (Laravel 11 + Docker)
### Configure Environment

```bash
    cd backend
    cp .env.example .env
```

Edit .env to match Docker services:

```bash
    # .env
    #     DB_CONNECTION=mysql
    #     DB_HOST=db
    #     DB_PORT=3306
    #     DB_DATABASE=task_manager
    #     DB_USERNAME=laravel
    #     DB_PASSWORD=secret

    #     SANCTUM_STATEFUL_DOMAINS=localhost:5173
    #     APP_URL=http://localhost:8000


    # Start Docker Containers
    docker compose up -d --build

    docker exec -it task-manager-app bash

    # inside container
    composer install
    php artisan key:generate
    php artisan migrate
    php artisan db:seed  # Optional
    exit
```

---

### Frontend
## Navigate to the backend directory:

```bash
    # Make sure to use Node.js (v20.19.3)
    cd frontend

    # Install dependencies
    npm install

    # Run the development server
    npm run dev
