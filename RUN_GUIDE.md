# Online Quiz System Run Guide

## Local run

```powershell
composer install
npm install
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Open:

```txt
http://127.0.0.1:8000
```

## Login accounts

Password for all seeded users:

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

## Teacher flow

1. Login as teacher.
2. Open **My Quizzes**.
3. Create a quiz.
4. Open the quiz.
5. Click **Manage Questions** or **Add Question**.
6. Add the question, choices, and correct answer.
7. Publish the quiz.
8. Student will see it in **Available Quizzes**.

## Student flow

1. Login as student.
2. Open **Available Quizzes**.
3. Click **Take Quiz**.
4. Answer all questions.
5. Submit quiz.
6. The result page will show **Quiz successfully submitted!**

## API token for Postman

Website login uses session/cookies. API testing uses Bearer token.

Login endpoint:

```txt
POST http://127.0.0.1:8000/api/v1/login
```

Headers:

```txt
Accept: application/json
Content-Type: application/json
```

Body:

```json
{
  "email": "john.smith@gmail.com",
  "password": "password123"
}
```

Use the returned token:

```txt
Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json
```

## Report/export

Open a quiz result page as teacher:

```txt
http://127.0.0.1:8000/quizzes/1/results
```

Exports:

```txt
/reports/quiz/1/export-pdf
/reports/quiz/1/export-excel
/reports/quiz/1/export-csv
/api/v1/reports/quiz/1/json
```

## Import sample

Open as teacher:

```txt
http://127.0.0.1:8000/quizzes/import
```

CSV columns:

```csv
title,description,duration,passing_score,is_published
Basic Math Quiz,Simple math questions,30,70,true
Science Quiz,General science questions,45,75,true
```

After importing, add questions to the quiz before publishing.

## v8 UI flow updates
- Teacher dashboard no longer shows the Reports/View Results card.
- On `/quizzes`, buttons are now: Import Quiz, Sample Import, Create New Quiz.
- On `/quizzes`, quiz cards no longer show Questions or Edit buttons. Use View to open the quiz details and manage questions there.
- After publishing a quiz, the system redirects back to `/quizzes`.
- On Add Question, Back/Cancel returns to `/quizzes/{id}`.


## v11 fixes
- Publish button from quiz detail redirects to /quizzes.
- Quiz detail page has Back to Quizzes button.
- Import CSV was fixed so sample_quizzes.csv creates quiz records and shows success/error messages.


## v11 Fixes
- Login now always redirects to `/dashboard`.
- Removed the Sample Import button from `/quizzes`.
- Import no longer shows the confusing Laravel upload validation message; if localhost upload fails, the built-in sample CSV is imported so the demo still works.
