<!-- Navigation Bar -->
<nav class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 hover:opacity-90 transition">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                    <span class="text-2xl font-bold">Online Quiz System</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-1">
                @if (Route::has('login'))
                    @auth
                    


                        <!-- Authenticated Menu -->
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                            <i class="fas fa-chart-line mr-1"></i>Dashboard
                        </a>

                        @if (auth()->user()->isTeacher())
                            <a href="{{ route('quizzes.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                                <i class="fas fa-book mr-1"></i>My Quizzes
                            </a>
                        @elseif (auth()->user()->isStudent())
                            <a href="{{ route('quizzes.available') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                                <i class="fas fa-list mr-1"></i>Available Quizzes
                            </a>
                            <a href="{{ route('scores.my-scores') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                                <i class="fas fa-star mr-1"></i>My Scores
                            </a>
                        @endif

                        <!-- User Dropdown -->
                        <div class="relative group ml-4 pl-4 border-l border-blue-400">
                            <button class="px-3 py-2 rounded-md text-sm font-medium flex items-center hover:bg-blue-700 transition">
                                <i class="fas fa-user-circle mr-2"></i>
                                {{ auth()->user()->name }}
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-t-lg">
                                    <i class="fas fa-user mr-2"></i>My Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-b-lg border-t">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                    @endauth
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-md hover:bg-blue-700 focus:outline-none transition" id="mobile-menu-btn">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="hidden md:hidden pb-4" id="mobile-menu">
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition">
                        <i class="fas fa-chart-line mr-2"></i>Dashboard
                    </a>

                    @if (auth()->user()->isTeacher())
                        <a href="{{ route('quizzes.index') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition">
                            <i class="fas fa-book mr-2"></i>My Quizzes
                        </a>
                    @elseif (auth()->user()->isStudent())
                        <a href="{{ route('quizzes.available') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition">
                            <i class="fas fa-list mr-2"></i>Available Quizzes
                        </a>
                        <a href="{{ route('scores.my-scores') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition">
                            <i class="fas fa-star mr-2"></i>My Scores
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition">
                        <i class="fas fa-user mr-2"></i>My Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
