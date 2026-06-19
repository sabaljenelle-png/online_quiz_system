<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 py-12 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- About -->
            <div>
                <h3 class="text-white font-bold text-lg mb-4">
                    <i class="fas fa-graduation-cap mr-2"></i>Online Quiz System
                </h3>
                <p class="text-sm">An interactive platform for creating and taking quizzes online.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Dashboard</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="hover:text-white transition">Profile</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Features -->
            <div>
                <h4 class="text-white font-bold mb-4">Features</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">Create Quizzes</a></li>
                    <li><a href="#" class="hover:text-white transition">Take Quizzes</a></li>
                    <li><a href="#" class="hover:text-white transition">View Results</a></li>
                    <li><a href="#" class="hover:text-white transition">Export Reports</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-bold mb-4">Contact</h4>
                <ul class="space-y-2 text-sm">
                    <li><i class="fas fa-envelope mr-2"></i>quiz-system@example.com</li>
                    <li><i class="fas fa-phone mr-2"></i>+1 (555) 123-4567</li>
                    <li class="pt-2">
                        <a href="#" class="text-blue-400 hover:text-blue-300 mr-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-blue-400 hover:text-blue-300 mr-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-blue-400 hover:text-blue-300"><i class="fab fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-700 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm">&copy; 2024 Online Quiz System. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0 text-sm">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                    <a href="#" class="hover:text-white transition">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>
