# TaskBreakdown AI - Complete Project README

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue)
![React](https://img.shields.io/badge/React-18-61dafb)
![License](https://img.shields.io/badge/license-MIT-green)

> AI-powered task management system that automatically breaks down complex tasks into actionable subtasks using Anthropic's Claude API.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Project Structure](#project-structure)
- [Development Workflow](#development-workflow)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

---

## 🎯 Overview

TaskBreakdown AI is a full-stack task management application that leverages artificial intelligence to help teams and individuals break down complex tasks into manageable subtasks. Built with Laravel 12 and React 18, it demonstrates modern software architecture patterns including service layers, event-driven design, and queue-based processing.

### Key Capabilities

- **Smart Task Management**: Create, organize, and track tasks with priorities and deadlines
- **AI-Powered Breakdown**: Automatically decompose complex tasks using Claude AI
- **Real-time Updates**: Background processing with queue workers and event broadcasting
- **Secure Authentication**: Token-based API authentication with Laravel Sanctum
- **Beautiful UI**: Modern, responsive interface built with React and Tailwind CSS

---

## ✨ Features

### Core Features

- ✅ **User Authentication & Authorization**
  - Registration and login
  - JWT token-based API authentication
  - Role-based access control with policies
  
- ✅ **Task Management**
  - CRUD operations for tasks
  - Status tracking (Pending, In Progress, Completed, Cancelled)
  - Priority levels (Low, Medium, High, Urgent)
  - Due date management
  - Soft deletes for data recovery

- ✅ **AI Integration**
  - Automatic task breakdown using Anthropic Claude API
  - Customizable complexity levels
  - Focus area specification
  - Estimated time per subtask

- ✅ **Subtask Management**
  - Create, edit, and delete subtasks
  - Drag-and-drop reordering
  - Progress tracking
  - Time estimation

- ✅ **Notifications**
  - Email notifications for task events
  - Database notifications
  - Real-time browser notifications (via broadcasting)

- ✅ **Search & Filtering**
  - Full-text search
  - Filter by status, priority
  - Pagination support

### Advanced Features

- 📊 **Analytics Dashboard** (Planned)
- 👥 **Team Collaboration** (Planned)
- 📱 **Mobile App** (Planned)
- 🔄 **Task Dependencies** (Planned)

---

## 🛠 Tech Stack

### Backend

| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.1+ | Programming Language |
| Laravel | 12.x | Web Framework |
| MySQL | 8.0+ | Primary Database |
| Redis | 7.x | Queue & Cache |
| Laravel Sanctum | 4.x | API Authentication |

### Frontend

| Technology | Version | Purpose |
|------------|---------|---------|
| React | 18.x | UI Framework |
| Vite | 5.x | Build Tool |
| TailwindCSS | 3.x | Styling |
| React Query | 5.x | Data Fetching |
| React Router | 6.x | Routing |
| Axios | 1.x | HTTP Client |

### AI & Services

| Technology | Purpose |
|------------|---------|
| Anthropic Claude API | Task Breakdown AI |
| Laravel Queue | Background Jobs |
| Laravel Events | Event-Driven Architecture |

---

## 🏗 Architecture

### Design Patterns

1. **Service Pattern**: Business logic separated from controllers
2. **Repository Pattern**: Data access abstraction via Eloquent
3. **Event-Driven Architecture**: Decoupled operations using events
4. **Observer Pattern**: Model lifecycle hooks
5. **Facade Pattern**: Simplified service access
6. **Strategy Pattern**: Flexible AI prompt building

### Key Architectural Decisions

- **API-First Design**: RESTful API with separate React frontend
- **Queue-Based Processing**: Long-running AI operations handled asynchronously
- **Comprehensive Validation**: Form Request classes for all inputs
- **Resource Transformers**: Consistent API responses
- **Exception Handling**: Custom exceptions for different error scenarios

### System Flow

```
User Request → Controller → Service Layer → Model/Repository
                                ↓
                         Event Dispatcher
                                ↓
                         Queue Job (if async)
                                ↓
                    External Services (AI API)
                                ↓
                         Database Update
                                ↓
                    Notification Dispatch
```

---

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** >= 8.1
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **npm** >= 9.x
- **MySQL** >= 8.0
- **Redis** >= 7.0
- **Git**

### Optional but Recommended

- **Docker** (for containerized development)
- **Mailpit** (for local email testing)
- **Laravel Valet** or **Homestead** (for macOS/Windows)

---

## 🚀 Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/task-breakdown-ai.git
cd task-breakdown-ai
```

### Step 2: Backend Setup

```bash
# Navigate to backend
cd task-management-api

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# DB_DATABASE=task_management
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Create database
mysql -u root -p
CREATE DATABASE task_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed

# Create notifications table
php artisan notifications:table
php artisan migrate

# Link storage (if using file uploads)
php artisan storage:link
```

### Step 3: Frontend Setup

```bash
# Navigate to frontend
cd ../task-management-frontend

# Install npm dependencies
npm install

# Copy environment file
cp .env.example .env
```

---

## ⚙️ Configuration

### Backend Configuration

#### 1. Database Configuration

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 2. Redis Configuration

```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

#### 3. Anthropic AI Configuration

Get your API key from [Anthropic Console](https://console.anthropic.com)

```env
ANTHROPIC_API_KEY=your_anthropic_api_key_here
ANTHROPIC_MODEL=claude-sonnet-4-20250514
ANTHROPIC_MAX_TOKENS=4096
```

#### 4. Mail Configuration

For local development, use Mailpit:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@taskbreakdown.local"
MAIL_FROM_NAME="TaskBreakdown AI"
```

For production, use a service like SendGrid, Mailgun, or Amazon SES.

#### 5. CORS Configuration

Edit `config/cors.php`:

```php
'allowed_origins' => [
    'http://localhost:5173',  // React dev server
    'http://localhost:3000',  // Alternative port
    env('FRONTEND_URL'),      // Production URL
],
```

### Frontend Configuration

Edit `.env`:

```env
VITE_API_URL=http://localhost:8000/api
```

---

## 🏃 Running the Application

You need **4 terminal windows** to run the complete application:

### Terminal 1: Laravel Development Server

```bash
cd task-management-api
php artisan serve

# Server will run on http://localhost:8000
```

### Terminal 2: Queue Worker

```bash
cd task-management-api
php artisan queue:work --tries=3

# Processes background jobs for AI task breakdown
```

### Terminal 3: Redis Server

```bash
# If Redis is not running as a service
redis-server

# Or start as service (Linux)
sudo systemctl start redis

# Or start as service (macOS)
brew services start redis
```

### Terminal 4: React Development Server

```bash
cd task-management-frontend
npm run dev

# Server will run on http://localhost:5173
```

### Access the Application

- **Frontend**: http://localhost:5173
- **Backend API**: http://localhost:8000/api
- **API Documentation**: http://localhost:8000/api/documentation (if installed)

### Default Test Account

After seeding the database:

- **Email**: test@example.com
- **Password**: password123

---

## 📚 API Documentation

### Base URL

```
http://localhost:8000/api
```

### Authentication Endpoints

#### Register User

```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "token": "1|AbCdEf...",
  "token_type": "Bearer"
}
```

#### Login

```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Logout

```http
POST /api/logout
Authorization: Bearer {token}
```

#### Get Current User

```http
GET /api/me
Authorization: Bearer {token}
```

### Task Endpoints

#### List Tasks

```http
GET /api/tasks?status=pending&priority=high&search=keyword&per_page=15
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (optional): pending, in_progress, completed, cancelled
- `priority` (optional): low, medium, high, urgent
- `search` (optional): Search in title and description
- `per_page` (optional): Items per page (default: 15)

#### Create Task

```http
POST /api/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Build Authentication System",
  "description": "Implement user registration and login",
  "priority": "high",
  "status": "pending",
  "due_date": "2025-02-15",
  "ai_breakdown_requested": true
}
```

#### Get Task

```http
GET /api/tasks/{id}
Authorization: Bearer {token}
```

#### Update Task

```http
PUT /api/tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Title",
  "status": "in_progress",
  "priority": "urgent"
}
```

#### Delete Task

```http
DELETE /api/tasks/{id}
Authorization: Bearer {token}
```

#### Request AI Breakdown

```http
POST /api/tasks/{id}/breakdown
Authorization: Bearer {token}
Content-Type: application/json

{
  "complexity_level": "complex",
  "focus_areas": ["backend", "frontend", "testing"]
}
```

**Response:**
```json
{
  "message": "Task breakdown request submitted. You will be notified when complete.",
  "task_id": 1
}
```

### Subtask Endpoints

#### List Subtasks

```http
GET /api/tasks/{taskId}/subtasks
Authorization: Bearer {token}
```

#### Create Subtask

```http
POST /api/tasks/{taskId}/subtasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Create user model",
  "description": "Define user schema and relationships",
  "estimated_hours": 2,
  "order": 0
}
```

#### Update Subtask

```http
PUT /api/subtasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "completed",
  "estimated_hours": 3
}
```

#### Delete Subtask

```http
DELETE /api/subtasks/{id}
Authorization: Bearer {token}
```

---

## 🧪 Testing

### Run All Tests

```bash
cd task-management-api
php artisan test
```

### Run Specific Test Suite

```bash
# Feature tests only
php artisan test --testsuite=Feature

# Unit tests only
php artisan test --testsuite=Unit

# Specific test file
php artisan test tests/Feature/TaskTest.php

# Specific test method
php artisan test --filter test_user_can_create_task
```

### Run Tests with Coverage

```bash
php artisan test --coverage
```

### Test Database

Tests use SQLite in-memory database by default. Configure in `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

---

## 📁 Project Structure

```
task-management-system/
├── task-management-api/              # Laravel Backend
│   ├── app/
│   │   ├── Console/
│   │   │   └── Commands/             # Custom artisan commands
│   │   ├── Events/                   # Event classes
│   │   ├── Exceptions/               # Custom exceptions
│   │   ├── Facades/                  # Service facades
│   │   ├── Helpers/                  # Helper functions
│   │   ├── Http/
│   │   │   ├── Controllers/Api/      # API controllers
│   │   │   ├── Middleware/           # Custom middleware
│   │   │   ├── Requests/             # Form request validators
│   │   │   └── Resources/            # API resources
│   │   ├── Jobs/                     # Queue jobs
│   │   ├── Listeners/                # Event listeners
│   │   ├── Mail/                     # Mailable classes
│   │   ├── Models/                   # Eloquent models
│   │   ├── Notifications/            # Notification classes
│   │   ├── Observers/                # Model observers
│   │   ├── Policies/                 # Authorization policies
│   │   ├── Rules/                    # Custom validation rules
│   │   └── Services/                 # Business logic services
│   ├── config/                       # Configuration files
│   ├── database/
│   │   ├── factories/                # Model factories
│   │   ├── migrations/               # Database migrations
│   │   └── seeders/                  # Database seeders
│   ├── resources/
│   │   └── views/                    # Blade templates (emails)
│   ├── routes/
│   │   └── api.php                   # API routes
│   ├── storage/                      # File storage & logs
│   └── tests/                        # PHPUnit tests
│
└── task-management-frontend/         # React Frontend
    ├── public/                       # Static assets
    └── src/
        ├── api/                      # API client & endpoints
        ├── components/
        │   ├── auth/                 # Authentication components
        │   ├── common/               # Reusable components
        │   └── tasks/                # Task-related components
        ├── contexts/                 # React contexts
        ├── hooks/                    # Custom React hooks
        ├── pages/                    # Page components
        ├── utils/                    # Utility functions
        ├── App.jsx                   # Main app component
        └── main.jsx                  # Entry point
```

---

## 🔧 Development Workflow

### Artisan Commands

```bash
# List all routes
php artisan route:list

# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Create new controller
php artisan make:controller Api/NewController

# Create new model with migration
php artisan make:model NewModel -m

# Create new service
touch app/Services/NewService.php

# Run queue worker
php artisan queue:work

# Cleanup old tasks (custom command)
php artisan tasks:cleanup --days=90

# Generate task statistics
php artisan tasks:stats
```

### Git Workflow

```bash
# Create feature branch
git checkout -b feature/new-feature

# Make changes and commit
git add .
git commit -m "Add new feature"

# Push to remote
git push origin feature/new-feature

# Create pull request on GitHub/GitLab
```

### Code Quality

```bash
# Run PHP CS Fixer (if installed)
./vendor/bin/php-cs-fixer fix

# Run PHPStan (if installed)
./vendor/bin/phpstan analyse

# Run tests before committing
php artisan test
```

---

## 🚢 Deployment

### Production Checklist

#### Backend

1. **Environment Configuration**
```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use production database
DB_CONNECTION=mysql
DB_DATABASE=production_db

# Configure production cache/queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

2. **Optimize Application**
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

3. **Set Permissions**
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

4. **Setup Supervisor for Queue Worker**

Create `/etc/supervisor/conf.d/task-management-worker.conf`:

```ini
[program:task-management-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/task-management-api/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/task-management-api/storage/logs/worker.log
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start task-management-worker:*
```

#### Frontend

1. **Build for Production**
```bash
cd task-management-frontend
npm run build
```

2. **Deploy to Web Server**

Upload the `dist/` folder to your web server or CDN.

3. **Configure Nginx**

```nginx
server {
    listen 80;
    server_name yourdomain.com;

    root /var/www/task-management-frontend/dist;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    location /api {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

---

## 🐛 Troubleshooting

### Common Issues

#### Database Connection Error

```bash
# Check MySQL is running
sudo systemctl status mysql

# Verify credentials in .env
# Reset migrations
php artisan migrate:fresh
```

#### Queue Not Processing

```bash
# Check Redis is running
redis-cli ping

# Clear failed jobs
php artisan queue:flush

# Restart queue worker
php artisan queue:restart
```

#### CORS Errors

```bash
# Clear config cache
php artisan config:clear

# Verify frontend URL in config/cors.php
# Check CORS headers in response
```

#### AI Breakdown Not Working

```bash
# Verify API key in .env
ANTHROPIC_API_KEY=sk-ant-...

# Check queue worker is running
ps aux | grep "queue:work"

# Check logs
tail -f storage/logs/laravel.log
```

#### Frontend Build Issues

```bash
# Clear node modules
rm -rf node_modules package-lock.json

# Reinstall dependencies
npm install

# Clear Vite cache
rm -rf node_modules/.vite
```

---

## 👥 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 for PHP code
- Use ESLint + Prettier for JavaScript/React
- Write tests for new features
- Update documentation

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com)
- [React](https://react.dev)
- [Anthropic Claude API](https://www.anthropic.com)
- [TailwindCSS](https://tailwindcss.com)
- All open-source contributors

---

## 📞 Support

- **Documentation**: [Full Documentation](./docs)
- **Issues**: [GitHub Issues](https://github.com/yourusername/task-breakdown-ai/issues)
- **Email**: support@taskbreakdown.com

---

**Built with ❤️ using Laravel 12, React 18, and Claude AI**

*TaskBreakdown AI - Making complex tasks manageable*
