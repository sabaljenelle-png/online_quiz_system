@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Logo Card -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Logo Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-8 px-6 text-center">
                <!-- SVG Logo -->
                <svg class="w-24 h-24 mx-auto mb-4" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Circle -->
                    <circle cx="100" cy="100" r="95" fill="white" opacity="0.95" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                    
                    <!-- Main Logo Group -->
                    <g transform="translate(100, 100)">
                        <!-- Book/Certificate Shape -->
                        <rect x="-35" y="-45" width="70" height="90" rx="6" fill="rgb(59, 130, 246)"/>
                        
                        <!-- Pages detail -->
                        <rect x="-33" y="-43" width="66" height="4" fill="white" opacity="0.3"/>
                        <rect x="-33" y="-35" width="66" height="2" fill="white" opacity="0.2"/>
                        <rect x="-33" y="-30" width="66" height="2" fill="white" opacity="0.2"/>
                        
                        <!-- Hat (Graduation Cap) -->
                        <g transform="translate(0, -50)">
                            <line x1="-40" y1="0" x2="40" y2="0" stroke="white" stroke-width="4" stroke-linecap="round"/>
                            <polygon points="0,-15 -8,0 8,0" fill="white"/>
                            <circle cx="0" cy="2" r="3" fill="white"/>
                        </g>
                        
                        <!-- Star Badge -->
                        <g transform="translate(25, -20)">
                            <polygon points="0,-12 3,-4 12,-4 5,2 8,10 0,5 -8,10 -5,2 -12,-4 -3,-4" fill="rgb(34, 197, 94)"/>
                        </g>
                        
                        <!-- Checkmark inside -->
                        <polyline points="-5,5 0,10 10,0" stroke="white" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                </svg>

                <!-- Logo Text -->
                <h1 class="text-4xl font-bold text-white">Online Quiz System</h1>
                <p class="text-blue-100 mt-2">Create • Take • Master</p>
            </div>

            <!-- Login Form -->
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Welcome Back</h2>

                <!-- Session Status -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                            <p class="font-semibold text-red-800">Login Failed</p>
                        </div>
                        <ul class="list-disc list-inside text-red-600 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>Email Address
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            autocomplete="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition @error('email') border-red-500 @enderror"
                            placeholder="john@example.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                        </label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                name="remember"
                                class="rounded text-blue-600 focus:ring-blue-500 border-gray-300"
                            >
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold transition">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-800 text-white font-bold py-3 rounded-lg hover:from-blue-700 hover:to-blue-900 transition transform hover:scale-105 flex items-center justify-center"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">New to Online Quiz System?</span>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <a 
                        href="{{ route('register') }}"
                        class="w-full block text-center bg-gray-100 text-gray-800 font-semibold py-3 rounded-lg hover:bg-gray-200 transition flex items-center justify-center"
                    >
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </a>
                </form>

                <!-- Test Accounts Info -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-xs font-semibold text-blue-900 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>Demo Accounts:
                    </p>
                    <div class="text-xs text-gray-700 space-y-1">
                        <p><strong>Teacher:</strong> john.smith@gmail.com</p>
                        <p><strong>Student:</strong> alice.brown@gmail.com</p>
                        <p><strong>Password:</strong> password123</p>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="bg-gray-50 px-8 py-4 border-t text-center text-sm text-gray-600">
                <p><i class="fas fa-shield-alt text-green-600 mr-1"></i>Your login is secure and encrypted</p>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center text-white">
            <p class="text-sm">Having trouble? <a href="mailto:support@onlinequizsystem.com" class="underline hover:text-blue-100 transition">Contact Support</a></p>
        </div>
    </div>
</div>
@endsection
