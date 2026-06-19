# Online Quiz System

An Online Quiz System built with Laravel. This system allows teachers to create and publish quizzes, manage questions, view student results, and export reports. Students can view available quizzes, take quizzes with a timer, view scores, view correct and wrong answers, and retake quizzes.

## Developers

- Jean
- Vlad
- Jenelle

## Live Demo

https://online-quiz-system-nci4.onrender.com/

## Features

### Authentication
- Login
- Register
- Logout
- Session handling
- Password hashing

### Teacher Module
- Create quizzes
- Edit quizzes
- Publish and unpublish quizzes
- Add questions
- Edit questions and answer choices
- Delete questions
- View student attempts and results
- Export quiz reports

### Student Module
- View available published quizzes
- Take quizzes
- Timer while taking quiz
- Submit answers
- View scores
- View correct and wrong answers
- Retake quizzes

### Reports and Export
- PDF export
- XLSX export
- CSV export
- JSON export

### REST API
The system includes RESTful API endpoints for testing in Postman.

## Tech Stack

- PHP
- Laravel
- Blade Templates
- Tailwind CSS
- PostgreSQL / SQLite
- Laravel Sanctum API Token Authentication
- Render Deployment
- GitHub

## Local Installation

Clone the project:

git clone https://github.com/sabaljenelle-png/online_quiz_system.git
cd online_quiz_system

Install PHP dependencies:

composer install

Install Node dependencies:

npm install

Create `.env` file:

cp .env.example .env

Generate app key:

php artisan key:generate

Run migrations and seeders:

php artisan migrate:fresh --seed

Build frontend assets:

npm run build

Run the Laravel server:

php artisan serve

Open in browser:

http://127.0.0.1:8000

## Demo Accounts

Teacher:

Email: john.smith@gmail.com
Password: password123

Student:

Email: alice.brown@gmail.com
Password: password123

## API Token Authentication in Postman

### Login and Get Token

Endpoint:

POST /api/v1/login

Full local URL:

http://127.0.0.1:8000/api/v1/login

Headers:

Accept: application/json
Content-Type: application/json

Body:

{
  "email": "john.smith@gmail.com",
  "password": "password123"
}

Copy the returned token.

Use it in protected routes:

Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json

## API Endpoints

### Auth

POST /api/v1/login
GET /api/v1/user
POST /api/v1/logout

### Quizzes

GET /api/v1/quizzes
POST /api/v1/quizzes
GET /api/v1/quizzes/{id}
PUT /api/v1/quizzes/{id}
PATCH /api/v1/quizzes/{id}
DELETE /api/v1/quizzes/{id}

### Questions

GET /api/v1/questions
POST /api/v1/questions
GET /api/v1/questions/{id}
PUT /api/v1/questions/{id}
PATCH /api/v1/questions/{id}
DELETE /api/v1/questions/{id}

### Attempts and Results

GET /api/v1/results
POST /api/v1/attempts
GET /api/v1/reports/quiz/{id}/json

## Sample API Test Flow

1. Login using POST /api/v1/login.
2. Copy the Bearer token.
3. Test logged-in user using GET /api/v1/user.
4. Test quiz list using GET /api/v1/quizzes.
5. Create quiz using POST /api/v1/quizzes.
6. Update quiz using PATCH /api/v1/quizzes/{id}.
7. Delete quiz using DELETE /api/v1/quizzes/{id}.

### Sample Create Quiz Body

{
  "title": "Postman Sample Quiz",
  "description": "Created using Postman API",
  "duration": 10,
  "passing_score": 70,
  "is_published": false
}

## Deployment

The system is deployed using Render.

Production URL:

https://online-quiz-system-nci4.onrender.com/

Deployment setup uses:

- Dockerfile
- Render Web Service
- PostgreSQL Database
- Environment Variables

Important Render environment variables:

APP_NAME=Online Quiz System
APP_ENV=production
APP_DEBUG=false
APP_URL=https://online-quiz-system-nci4.onrender.com
DB_CONNECTION=pgsql
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
RUN_SEEDERS=false

## Project Requirement Coverage

This project includes:

- Authentication
- CRUD operations
- Controllers
- Models
- Migrations
- Resource controllers
- Blade templates
- Master layout
- Navigation components
- Middleware protection
- Database relationships
- RESTful API
- Import and export
- Auto-generated reports
- GitHub repository
- Render deployment
