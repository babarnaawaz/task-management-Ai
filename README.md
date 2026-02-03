# TaskBreakdown AI - AI-Powered Task Management System

## 🚀 Project Overview

TaskBreakdown AI is a full-stack task management application that leverages AI to automatically break down complex tasks into actionable subtasks. Built with Laravel 12 and React 18, it demonstrates modern software architecture patterns and best practices.

### Key Features

- **Smart Task Management**: Create, update, and track tasks with priorities and deadlines
- **AI-Powered Breakdown**: Automatically decompose complex tasks using Anthropic's Claude API
- **Subtask Tracking**: Manage subtasks with progress indicators and time estimates
- **Real-time Updates**: Background job processing with queue system
- **User Authentication**: Secure authentication using Laravel Sanctum
- **Beautiful UI**: Modern, responsive interface built with React and Tailwind CSS
- **RESTful API**: Well-structured API with proper validation and error handling

## 🏗️ Architecture

### Tech Stack

**Backend:**
- PHP 8.1+
- Laravel 12
- MySQL 8.0
- Redis (Queue & Cache)
- Laravel Sanctum (API Authentication)

**Frontend:**
- React 18
- Vite
- TailwindCSS
- React Query
- React Router
- Axios

**AI Integration:**
- Anthropic Claude API (Sonnet 4)

### Architecture Decisions

1. **Service Pattern**: Business logic separated from controllers for better testability and reusability
2. **Repository Pattern**: Data access abstracted through Eloquent models with relationships
3. **Request Validation**: Dedicated Form Request classes for input validation
4. **API Resources**: Consistent JSON response formatting
5. **Event-Driven Architecture**: Decoupled operations using Laravel events
6. **Queue System**: Async processing for AI operations to prevent blocking
7. **Proper Exception Handling**: Custom exception classes with appropriate HTTP responses

## 📁 Project Structure

```
task-management-system/
├── task-management-api/          # Laravel Backend
│   ├── app/
│   │   ├── Console/
│   │   │   └── Commands/         # Custom artisan commands
│   │   ├── Events/               # Application events
│   │   ├── Exceptions/           # Custom exceptions
│   │   ├── Facades/              # Service facades
│   │   ├── Http/
│   │   │   ├── Controllers/      # API controllers
│   │   │   ├── Requests/         # Form request validators
│   │   │   └── Resources/        # API response resources
│   │   ├── Jobs/                 # Queue jobs
│   │   ├── Listeners/            # Event listeners
│   │   ├── Models/               # Eloquent models
│   │   ├── Notifications/        # User notifications
│   │   └── Services/             # Business logic services
│   ├── config/                   # Configuration files
│   ├── database/
│   │   ├── migrations/           # Database migrations
│   │   ├── factories/            # Model factories
│   │   └── seeders/              # Database seeders
│   ├── routes/
│   │   └── api.php              # API routes
│   └── tests/                    # Feature & unit tests
│
└── task-management-frontend/     # React Frontend
    ├── src/
    │   ├── api/                  # API client & endpoints
    │   ├── components/
    │   │   ├── auth/            # Authentication components
    │   │   ├── common/          # Reusable components
    │   │   └── tasks/           # Task-related components
    │   ├── contexts/            # React contexts
    │   ├── hooks/               # Custom React hooks
    │   ├── pages/               # Page components
    │   └── utils/               # Utility functions
    └── public/                  # Static assets
```

## 🚦 Quick Start

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 18+ & npm
- MySQL 8.0
- Redis
- Anthropic API Key (get from https://console.anthropic.com)

### Installation

#### 1. Clone Repository
```bash
git clone https://github.com/babarnaawaz/task-management-Ai.git
cd task-management-system
```

#### 2. Backend Setup

```bash
# Navigate to backend
cd task-management-api

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure .env file
# Update the following:
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=your_password
ANTHROPIC_API_KEY=your_api_key_here
QUEUE_CONNECTION=redis

# Create database
mysql -u root -p
CREATE DATABASE task_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Create notifications table
php artisan notifications:table
php artisan migrate
```

#### 3. Frontend Setup

```bash
# Navigate to frontend
cd ../task-management-frontend

# Install dependencies
npm install

# Copy environment file
cp .env.example .env

# Configure if needed (default should work)
VITE_API_URL=http://localhost:8000/api
```

### Running the Application

You'll need **4 terminal windows**:

#### Terminal 1 - Laravel Server
```bash
cd task-management-api
php artisan serve
# Runs on http://localhost:8000
```

#### Terminal 2 - Queue Worker
```bash
cd task-management-api
php artisan queue:work
# Processes background jobs
```

#### Terminal 3 - Redis Server (if not running as service)
```bash
redis-server
```

#### Terminal 4 - React Dev Server
```bash
cd task-management-frontend
npm run dev
# Runs on http://localhost:5173
```

### Access the Application

- **Frontend**: http://localhost:5173
- **API**: http://localhost:8000/api

### Default Test Account (after seeding)
- Email: `test@example.com`
- Password: `password123`

## 📚 API Documentation

### Authentication Endpoints

#### Register
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
GET /api/tasks?status=pending&priority=high&search=keyword
Authorization: Bearer {token}
```

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
  "status": "completed"
}
```

#### Delete Subtask
```http
DELETE /api/subtasks/{id}
Authorization: Bearer {token}
```

## 🧪 Testing

### Run Backend Tests
```bash
cd task-management-api
php artisan test
```

### Run Specific Test
```bash
php artisan test --filter TaskTest
```

### Run with Coverage
```bash
php artisan test --coverage
```

## 🛠️ Artisan Commands

### Custom Commands

#### Clean Up Old Tasks
```bash
php artisan tasks:cleanup --days=90
# Removes completed/cancelled tasks older than 90 days
```

#### Generate Task Statistics
```bash
php artisan tasks:stats
# Shows global statistics

php artisan tasks:stats --user=1
# Shows statistics for specific user
```

### Useful Laravel Commands

```bash
# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration with seeding
php artisan migrate:fresh --seed

# List all routes
php artisan route:list

# Tinker (REPL)
php artisan tinker
```

## 📊 Database Schema

### Users Table
- id (primary key)
- name
- email (unique)
- password
- timestamps
- soft deletes

### Tasks Table
- id (primary key)
- user_id (foreign key → users)
- title
- description
- status (enum)
- priority (enum)
- due_date
- ai_breakdown_requested (boolean)
- ai_breakdown_completed_at
- timestamps
- soft deletes

### Subtasks Table
- id (primary key)
- task_id (foreign key → tasks)
- title
- description
- status (enum)
- order
- estimated_hours
- generated_by_ai (boolean)
- timestamps
- soft deletes

### Task Breakdowns Table
- id (primary key)
- task_id (foreign key → tasks)
- status (enum)
- ai_prompt
- ai_response (json)
- error_message
- started_at
- completed_at
- timestamps

## 🔒 Security Features

- Password hashing with bcrypt
- CSRF protection
- SQL injection prevention (via Eloquent ORM)
- XSS protection
- API token authentication
- Request rate limiting
- Input validation and sanitization
- Authorized access control

## 🎯 Laravel Standards & Best Practices

### ✅ Implemented Standards

1. **Request Classes**: All form validation in dedicated request classes
2. **API Resources**: Consistent response formatting
3. **Service Pattern**: Business logic separated from controllers
4. **Proper Relationships**: Eloquent relationships properly defined
5. **Events & Listeners**: Decoupled event-driven architecture
6. **Notifications**: Email and database notifications
7. **Queue Jobs**: Background processing for long tasks
8. **Console Commands**: Custom artisan commands
9. **Exception Handling**: Custom exception classes
10. **Facades**: Service facades for clean access
11. **Middleware**: Authentication and authorization
12. **Database Migrations**: Version-controlled schema changes
13. **Model Factories**: Test data generation
14. **Seeders**: Database population
15. **Soft Deletes**: Preserve deleted records
16. **Type Hints**: Full PHP 8.1+ type declarations
17. **Eloquent Scopes**: Reusable query logic
18. **Feature Tests**: Comprehensive test coverage

## 🎨 Frontend Features

- **Modern React**: Hooks, Context API, Functional components
- **State Management**: React Query for server state
- **Routing**: React Router with protected routes
- **Styling**: TailwindCSS utility-first approach
- **Icons**: Lucide React icon library
- **Notifications**: React Hot Toast
- **Date Handling**: date-fns library
- **API Integration**: Axios with interceptors
- **Form Handling**: Controlled components
- **Loading States**: Proper loading & error states
- **Responsive Design**: Mobile-first approach

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Verify MySQL is running
sudo service mysql status

# Check credentials in .env
# Reset migrations if needed
php artisan migrate:fresh
```

### Queue Not Processing
```bash
# Ensure Redis is running
redis-cli ping

# Clear failed jobs
php artisan queue:flush

# Restart queue worker
php artisan queue:restart
```

### CORS Issues
```bash
# Clear config cache
php artisan config:clear

# Verify frontend URL in config/cors.php
# Should include: http://localhost:5173
```

### AI Breakdown Failing
```bash
# Check API key in .env
# Verify queue worker is running
# Check logs
tail -f storage/logs/laravel.log
```

### Frontend Build Issues
```bash
# Clear and reinstall
rm -rf node_modules package-lock.json
npm install

# Clear Vite cache
rm -rf node_modules/.vite
```

## 📈 Performance Optimization

- Database indexing on frequently queried columns
- Eager loading to prevent N+1 queries
- Redis caching for sessions and queues
- API response pagination
- Database query optimization
- Frontend code splitting (lazy loading ready)
- Image optimization ready

## 🔮 Future Enhancements

- [ ] Real-time updates with WebSockets
- [ ] Team collaboration features
- [ ] Task templates
- [ ] File attachments
- [ ] Time tracking
- [ ] Kanban board view
- [ ] Calendar integration
- [ ] Mobile app (React Native)
- [ ] Advanced reporting & analytics
- [ ] Email reminders
- [ ] Task dependencies
- [ ] Dark mode

## 📝 License

This project is created for educational and demonstration purposes.

## 👨‍💻 Development

### Code Style

**Backend (PHP):**
- PSR-12 coding standard
- Laravel conventions
- Type hints everywhere
- DocBlocks for complex methods

**Frontend (JavaScript):**
- ESLint + Prettier
- React best practices
- Functional components
- Custom hooks for reusability

### Git Workflow

```bash
# Feature development
git checkout -b feature/task-filtering
# ... make changes
git commit -m "Add task filtering functionality"
git push origin feature/task-filtering
```

## 🙏 Acknowledgments

- Laravel Framework
- React Team
- Anthropic (Claude API)
- TailwindCSS
- All open-source contributors

---

**Built with ❤️ using Laravel 12, React 18, and Claude AI**

For detailed implementation steps, see:
- `IMPLEMENTATION_GUIDE.md` - Complete backend setup
- `IMPLEMENTATION_GUIDE_PART2.md` - Frontend and final configuration
