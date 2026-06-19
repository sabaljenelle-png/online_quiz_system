protected $routeMiddleware = [
    // ... other middleware
    'is_teacher' => \App\Http\Middleware\IsTeacher::class,
    'is_student' => \App\Http\Middleware\IsStudent::class,
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];