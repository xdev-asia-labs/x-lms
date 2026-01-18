<header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                {{ config('app.name', 'LMS') }}
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ url('/courses') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition">
                    Courses
                </a>
                <a href="{{ url('/blog') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition">
                    Blog
                </a>
                <a href="{{ url('/news') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition">
                    News
                </a>
                <a href="{{ url('/showcase') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition">
                    Showcase
                </a>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="toggle()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- Search -->
                <a href="{{ url('/search') }}" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenu" x-data="{ mobileMenu: false }" class="md:hidden mt-4 space-y-2">
            <a href="{{ url('/courses') }}" class="block py-2 hover:text-primary-600">Courses</a>
            <a href="{{ url('/blog') }}" class="block py-2 hover:text-primary-600">Blog</a>
            <a href="{{ url('/news') }}" class="block py-2 hover:text-primary-600">News</a>
            <a href="{{ url('/showcase') }}" class="block py-2 hover:text-primary-600">Showcase</a>
        </div>
    </nav>
</header>
