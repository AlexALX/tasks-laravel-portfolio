# Simple Task Management System

A real-time task management application built with Laravel and Livewire,
showcasing production-oriented architecture, containerized development,
and automated testing workflows.

## 🚀 Features

- Real-time task management (no full page reload)
- RESTful API with full CRUD
- Responsive UI (Tailwind CSS)
- Server-side & client-side validation
- Paginated API and UI

## Architecture Decisions
- Livewire chosen instead of SPA to keep server-driven architecture
- API Resources used for response consistency
- FormRequest used for validation separation
- SQLite used in CI to speed up test execution
- Docker multi-stage build for separation of concerns

## Tech Stack

### Backend
- **Framework**: Laravel 12
- **Database**: MySQL 8.4 with Eloquent ORM
- **API**: RESTful endpoints with API Resource transformation
- **Validation**: Task Request validation using FormRequest

### Frontend
- **UI Framework**: Livewire 4
- **Styling**: Tailwind CSS
- **Interactivity**: Alpine.js for dynamic UI elements like completion switch
- **Real-time**: Event-driven updates between components

### DevOps
- **Docker**: Nginx + PHP-FPM + MySQL
- **CI/CD**: Automated testing pipeline with GitLab/GitHub CI
- **SQLite DB**: in-memory for fast CI execution
  
## Testing

- PHPUnit feature tests (REST API)
- Livewire component tests
- Automated tests with CI - failing tests are flagged before deployment
  
## Setup / Installation
The first run will build Docker images from sources, which may take a few minutes.

Due to slow file I/O on WSL2, this project supports two Docker Compose profiles:

| Mode | Description                     | Command                            |
| ---- | ------------------------------- | ---------------------------------- |
| dev  | Bind mount mode, slower on WSL2 | `docker compose --profile dev up`  |
| self | Self-contained, fast on WSL2    | `docker compose --profile self up` |

Running `docker compose up` without specifying a profile will result in `no service selected`.
           
## Setup steps

### Clone repository
```
git clone https://github.com/alexalx/tasks-laravel-portfolio.git
cd tasks-laravel-portfolio
```
         
### Demo Mode

#### Run setup (Windows)
```
./setup.ps1
```

#### Or Linux
```
./setup.sh
```
App will be available at: http://localhost:8080

For **Dev mode** can use `./setup.ps1 dev` (windows), `./setup.sh dev` (linux).

### What setup script does:
- Build docker images (first time only)
- Run docker compose
- Run composer to install required vendors (dev mode)
- Run migrations
- Compile Tailwind CSS (dev mode)

## API Endpoints

| Method | Endpoint         | Description               |
|--------|-----------------|---------------------------|
| GET    | /api/tasks       | List all tasks (paginated) |
| POST   | /api/tasks       | Create a new task          |
| GET    | /api/tasks/{id}  | Get a specific task        |
| PUT    | /api/tasks/{id}  | Update a specific task     |
| DELETE | /api/tasks/{id}  | Delete a specific task     |

### Request/Response Examples

**Create Task:**
`POST /api/tasks`
```json
{
  "title": "Complete project documentation",
  "description": "Write comprehensive README and API docs"
}
```
**Response:** `201 Created`
```json
{
  "data": {
    "id": 1,
    "title": "Complete project documentation",
    "description": "Write comprehensive README and API docs",
    "is_completed": false,
    "created_at": "2026-02-20 10:30:00",
    "updated_at": "2026-02-20 10:30:00"
  }
}
```
       
## Demo / Usage
- Create, update, delete tasks on the frontend
- View task list with pagination
- Interact with API endpoints for CRUD operations

## Purpose

This project demonstrates a production-oriented Laravel application
with maintainable architecture, consistent REST API design,
component-driven UI, automated testing strategy,
and performance-aware Docker configuration.

## Docker + WSL2 File I/O Performance on Windows

When using WSL2, mounting the entire Laravel project via a bind volume can result in very slow file I/O.  
This is a known limitation of WSL2 and affects projects with many files.

### This project provides two run modes

#### Dev Mode (bind mount – slower on WSL2)
- Uses standard Docker bind mounts.
- Source code is mounted into the container
- Uses Nginx + PHP-FPM
- Convenient for development
- May suffer from slow file I/O on WSL2

#### Self-Contained Mode (recommended on WSL2)
- Laravel source code is copied into the image during build
- Uses artisan serve
- No bind mount for application code
- Significantly faster file I/O on WSL2

If you are developing on Windows with WSL2, the self-contained mode is recommended for better performance.
