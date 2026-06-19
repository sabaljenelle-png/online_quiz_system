@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div>
                    <h1 class="text-5xl md:text-6xl font-bold mb-6">Welcome to Online Quiz System</h1>
                    <p class="text-xl md:text-2xl mb-8 text-blue-100">Create, manage, and take quizzes online with ease</p>
                    
                    @if (Route::has('login'))
                        @auth
                            <div class="flex flex-col sm:flex-row gap-4">
                                @if (auth()->user()->isTeacher())
                                    <a href="{{ route('quizzes.index') }}" class="px-8 py-3 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition inline-flex items-center justify-center">
                                        <i class="fas fa-book mr-2"></i>My Quizzes
                                    </a>
                                    <a href="{{ route('quizzes.create') }}" class="px-8 py-3 bg-blue-500 text-white rounded-lg font-bold hover:bg-blue-400 transition inline-flex items-center justify-center">
                                        <i class="fas fa-plus-circle mr-2"></i>Create Quiz
                                    </a>
                                @else
                                    <a href="{{ route('quizzes.available') }}" class="px-8 py-3 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition inline-flex items-center justify-center">
                                        <i class="fas fa-list mr-2"></i>Available Quizzes
                                    </a>
                                    <a href="{{ route('scores.my-scores') }}" class="px-8 py-3 bg-blue-500 text-white rounded-lg font-bold hover:bg-blue-400 transition inline-flex items-center justify-center">
                                        <i class="fas fa-star mr-2"></i>My Scores
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition inline-flex items-center justify-center">
                                    <i class="fas fa-user-plus mr-2"></i>Register Now
                                </a>
                                <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-500 text-white rounded-lg font-bold hover:bg-blue-400 transition inline-flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                                </a>
                            </div>
                        @endauth
                    @endif
                </div>

                <!-- Right Image -->
                <div class="flex justify-center">
                    <div class="w-full max-w-md">
                        <svg class="w-full h-auto" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background Circle -->
                            <circle cx="200" cy="200" r="180" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                            
                            <!-- Quiz Icon -->
                            <g transform="translate(200, 200)">
                                <!-- Main Book/Quiz Shape -->
                                <rect x="-60" y="-70" width="120" height="140" rx="8" fill="white" opacity="0.95"/>
                                
                                <!-- Book spine effect -->
                                <rect x="-62" y="-70" width="4" height="140" rx="2" fill="rgba(59, 130, 246, 0.3)"/>
                                
                                <!-- Header bar -->
                                <rect x="-60" y="-70" width="120" height="25" rx="8" fill="rgb(59, 130, 246)"/>
                                
                                <!-- Header text -->
                                <text x="0" y="-50" text-anchor="middle" font-size="14" font-weight="bold" fill="white">Quiz Master</text>
                                
                                <!-- Question marks -->
                                <circle cx="-35" cy="-20" r="18" fill="rgb(59, 130, 246)" opacity="0.2"/>
                                <text x="-35" y="-12" text-anchor="middle" font-size="28" font-weight="bold" fill="rgb(59, 130, 246)">?</text>
                                
                                <circle cx="35" cy="-20" r="18" fill="rgb(59, 130, 246)" opacity="0.2"/>
                                <text x="35" y="-12" text-anchor="middle" font-size="28" font-weight="bold" fill="rgb(59, 130, 246)">?</text>
                                
                                <!-- Lines (questions) -->
                                <line x1="-50" y1="10" x2="50" y2="10" stroke="rgb(200, 200, 200)" stroke-width="2" stroke-linecap="round"/>
                                <line x1="-50" y1="25" x2="50" y2="25" stroke="rgb(200, 200, 200)" stroke-width="2" stroke-linecap="round"/>
                                <line x1="-50" y1="40" x2="30" y2="40" stroke="rgb(200, 200, 200)" stroke-width="2" stroke-linecap="round"/>
                                
                                <!-- Checkmark -->
                                <circle cx="35" cy="32" r="12" fill="rgb(34, 197, 94)"/>
                                <polyline points="30,32 33,35 40,28" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                
                                <!-- Bottom decoration -->
                                <line x1="-50" y1="60" x2="50" y2="60" stroke="rgb(59, 130, 246)" stroke-width="2" opacity="0.3" stroke-linecap="round"/>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-gray-900 mb-12">Features</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Easy Quiz Creation</h3>
                    <p class="text-gray-600">Create quizzes with multiple question types including multiple choice, true/false, and short answer questions.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Real-time Assessment</h3>
                    <p class="text-gray-600">Take quizzes with timer functionality and get instant feedback on your performance and scores.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-file-export"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Export Reports</h3>
                    <p class="text-gray-600">Export quiz results in PDF, Excel, CSV, and JSON formats for easy sharing and analysis.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Analytics Dashboard</h3>
                    <p class="text-gray-600">View comprehensive analytics including pass rates, average scores, and student performance metrics.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Secure & Reliable</h3>
                    <p class="text-gray-600">Secure authentication, role-based access control, and reliable data storage for your peace of mind.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Mobile Responsive</h3>
                    <p class="text-gray-600">Access quizzes from any device - desktop, tablet, or mobile phone with full responsiveness.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    @if (Route::has('login'))
        @guest
            <div class="bg-blue-600 text-white py-16">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-4xl font-bold mb-4">Ready to Get Started?</h2>
                    <p class="text-xl mb-8">Join thousands of students and teachers using Online Quiz System</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition inline-flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i>Create Account
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-500 text-white rounded-lg font-bold hover:bg-blue-400 transition border-2 border-white inline-flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login Now
                        </a>
                    </div>
                </div>
            </div>
        @endguest
    @endif
</div>
@endsection
