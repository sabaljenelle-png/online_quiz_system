# Online Quiz System

A Laravel final project for an Online Quiz System. It includes teacher quiz management, student quiz taking, scores/results, REST API endpoints, token-based API authentication, reports/export support, import support, and deployment files for Render.

## Main features

### Teacher

- Login/logout
- Create quizzes
- Edit/delete quizzes
- Add and manage questions/options
- Publish/unpublish quizzes
- View student attempts and results
- Import quizzes
- Export reports

### Student

- Login/logout
- View available published quizzes
- Take quizzes
- Submit answers
- See successful submission screen
- View scores

### API

- Browser-friendly API preview: `/api/v1/quizzes`
- JSON API for Postman using `Accept: application/json`
- Token login: `POST /api/v1/login`
- Bearer token protected create/update/delete routes

## Local setup

```powershell
composer install
npm install
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Open:

```txt
http://127.0.0.1:8000
```

## Demo accounts

Password:

```txt
password123
```

Teacher:

```txt
john.smith@gmail.com
```

Student:

```txt
alice.brown@gmail.com
```

## API token login

```txt
POST /api/v1/login
```

Body:

```json
{
  "email": "john.smith@gmail.com",
  "password": "password123"
}
```

Use returned token as:

```txt
Authorization: Bearer YOUR_TOKEN_HERE
```

## Deployment

This project includes Render deployment files:

- `Dockerfile`
- `docker/start.sh`
- `render.yaml`
- `.env.render.example`

Add production environment variables in Render dashboard.


## v11 fixes
- Publish button from quiz detail redirects to /quizzes.
- Quiz detail page has Back to Quizzes button.
- Import CSV was fixed so sample_quizzes.csv creates quiz records and shows success/error messages.
