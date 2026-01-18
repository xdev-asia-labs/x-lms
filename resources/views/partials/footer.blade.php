<footer class="bg-gray-800 dark:bg-gray-900 text-gray-300 py-12 mt-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-white text-lg font-bold mb-4">{{ config('app.name') }}</h3>
                <p class="text-sm">
                    A modern Learning Management System built with Laravel and Filament.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/courses') }}" class="hover:text-white">Courses</a></li>
                    <li><a href="{{ url('/blog') }}" class="hover:text-white">Blog</a></li>
                    <li><a href="{{ url('/news') }}" class="hover:text-white">News</a></li>
                    <li><a href="{{ url('/showcase') }}" class="hover:text-white">Showcase</a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <h4 class="text-white font-semibold mb-4">Resources</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/about') }}" class="hover:text-white">About</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-white">Contact</a></li>
                    <li><a href="{{ url('/privacy') }}" class="hover:text-white">Privacy Policy</a></li>
                    <li><a href="{{ url('/terms') }}" class="hover:text-white">Terms of Service</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-white font-semibold mb-4">Newsletter</h4>
                <p class="text-sm mb-4">Subscribe to get the latest updates.</p>
                <form action="{{ url('/api/newsletter/subscribe') }}" method="POST" class="flex">
                    @csrf
                    <input type="email" name="email" placeholder="Your email" 
                           class="flex-1 px-3 py-2 rounded-l bg-gray-700 text-white text-sm">
                    <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-r text-sm">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
