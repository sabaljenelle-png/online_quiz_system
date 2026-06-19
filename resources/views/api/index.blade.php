<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz System API</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900">
    <main class="max-w-5xl mx-auto px-6 py-10">
        <div class="bg-white rounded-2xl shadow p-8 border border-slate-200">
            <h1 class="text-3xl font-bold mb-2">Online Quiz System API v1</h1>
            <p class="text-slate-600 mb-6">Website pages use normal login/session. Postman or VS Code API testing uses Bearer token.</p>

            <div class="grid md:grid-cols-2 gap-6">
                <section class="border rounded-xl p-5">
                    <h2 class="font-bold text-xl mb-3">Public / Browser-friendly</h2>
                    <ul class="space-y-2 text-sm">
                        <li><code>GET /api/v1/quizzes</code></li>
                        <li><code>GET /api/v1/quizzes/{id}</code></li>
                        <li><code>GET /api/v1/quizzes/{id}/questions</code></li>
                        <li><code>GET /api/v1/reports/quiz/{id}/json</code></li>
                    </ul>
                </section>

                <section class="border rounded-xl p-5">
                    <h2 class="font-bold text-xl mb-3">Postman Token Required</h2>
                    <ul class="space-y-2 text-sm">
                        <li><code>POST /api/v1/login</code> to get token</li>
                        <li><code>GET /api/v1/user</code></li>
                        <li><code>POST /api/v1/quizzes</code></li>
                        <li><code>PUT/PATCH /api/v1/quizzes/{id}</code></li>
                        <li><code>DELETE /api/v1/quizzes/{id}</code></li>
                    </ul>
                </section>
            </div>

            <div class="mt-8 bg-slate-50 border rounded-xl p-5">
                <h2 class="font-bold mb-2">Normal Website Pages</h2>
                <p class="text-sm text-slate-600 mb-3">Use these in the browser after logging in:</p>
                <div class="flex flex-wrap gap-3 text-sm">
                    <a class="text-blue-700 underline" href="/login">Login</a>
                    <a class="text-blue-700 underline" href="/dashboard">Dashboard</a>
                    <a class="text-blue-700 underline" href="/quizzes">Teacher Quizzes</a>
                    <a class="text-blue-700 underline" href="/quizzes/available">Student Available Quizzes</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
