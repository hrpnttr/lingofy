<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Lingofy') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- Navbar -->
    <div id="navbar" class="fixed w-[100%] transition-all duration-300 z-[1000] bg-white shadow-sm">
        <header class="w-full max-w-6xl mx-auto px-6 md:px-8 flex h-20 items-center justify-between">
            <!-- Logo -->
            <div class="font-logo text-lg text-black">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="/images/Gemini_Generated_Image_oqjo5poqjo5poqjo_copy-removebg-preview.png" alt="Logo"
                        class="h-12 w-auto object-contain transition-transform duration-200 hover:scale-105" />
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden items-center text-base font-semibold md:flex text-gray-700" aria-label="Primary">
                <a href="{{ route('placement-tests') }}" style="margin-right: 2rem;"
                    class="py-2 transition-colors duration-200 {{ request()->routeIs('placement-tests*') ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600' }}">Placement
                    Test</a>
                <a href="{{ route('courses.index') }}" style="margin-right: 2rem;"
                    class="py-2 transition-colors duration-200 {{ request()->routeIs('courses.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600' }}">Courses</a>
                @if (Auth::guard('student')->check())
                    <a href="{{ route('my-courses') }}"
                        class="py-2 transition-colors duration-200 {{ request()->routeIs('my-courses*') ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600' }}">My
                        Courses</a>
                @endif
            </nav>

            <!-- Desktop Auth -->
            <div class="hidden items-center md:flex text-gray-700 relative">
                @if (Auth::guard('student')->check())
                    <div class="relative" id="profile-dropdown-container">
                        <button onclick="toggleProfileDropdown()"
                            class="focus:outline-none flex items-center gap-2 py-1 px-3 rounded-full hover:bg-gray-100 transition-colors duration-200 border border-gray-200">
                            <span class="text-sm font-medium">{{ Auth::guard('student')->user()->full_name }}</span>
                            <!-- User Avatar Icon -->
                            <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown"
                            class="hidden absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1.5 z-50 border border-gray-100">
                            <a href="{{ route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition-colors duration-150">My
                                Profile</a>
                            <form action="{{ route('logout') }}" method="POST" class="logout-form block w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors duration-150">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="text-base font-semibold hover:text-blue-600 transition-colors duration-200 border border-gray-300 py-1.5 px-5 rounded-lg hover:border-blue-600">Login</a>
                @endif
            </div>

            <!-- Mobile Navigation Toggle -->
            <div class="md:hidden flex items-center">
                <button onclick="toggleMobileMenu()"
                    class="focus:outline-none p-1.5 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <!-- Hamburger Icon -->
                    <svg class="w-7 h-7 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu"
            class="hidden md:hidden bg-white/95 backdrop-blur-md w-full border-t border-gray-100 py-4 px-6 shadow-md transition-all duration-300">
            <nav class="flex flex-col gap-4 text-lg font-semibold text-gray-800">
                <a href="{{ route('placement-tests') }}"
                    class="hover:text-blue-600 transition-colors duration-200">Placement Test</a>
                <a href="{{ route('courses.index') }}"
                    class="hover:text-blue-600 transition-colors duration-200">Courses</a>
                @if (Auth::guard('student')->check())
                    <a href="{{ route('my-courses') }}" class="hover:text-blue-600 transition-colors duration-200">My
                        Courses</a>
                    <a href="{{ route('profile') }}" class="hover:text-blue-600 transition-colors duration-200">My
                        Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form inline">
                        @csrf
                        <button type="submit"
                            class="text-left font-semibold hover:text-red-600 transition-colors duration-200">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-blue-600 transition-colors duration-200">Login</a>
                @endif
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main id="main-content" style="padding-top: 80px; padding-bottom: 80px;" class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-150 text-gray-700" id="footer">
        <div class="w-[90%] xl:w-[85%] 2xl:w-[80%] max-w-7xl mx-auto pt-12 pb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">

                <!-- Brand & Mission Column -->
                <div class="lg:col-span-4 text-left">
                    <a href="{{ route('home') }}"
                        class="inline-block transition-transform duration-200 hover:scale-105">
                        <img src="/images/Gemini_Generated_Image_oqjo5poqjo5poqjo_copy-removebg-preview.png"
                            alt="Lingofy Logo" class="h-10 w-auto object-contain" />
                    </a>
                    <p class="mt-3 text-sm text-gray-500 leading-relaxed max-w-sm">
                        Empowering language learners worldwide with interactive placement tests, structured courses, and
                        real-time comprehension tracking.
                    </p>
                </div>

                <!-- Navigation Column -->
                <div class="lg:col-span-2 space-y-3 text-left">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Navigation</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li>
                            <a href="{{ route('home') }}"
                                class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('placement-tests') }}"
                                class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150">
                                Placement Test
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('courses.index') }}"
                                class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150">
                                Courses
                            </a>
                        </li>
                        @if (Auth::guard('student')->check())
                            <li>
                                <a href="{{ route('my-courses') }}"
                                    class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150">
                                    My Courses
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile') }}"
                                    class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150">
                                    My Profile
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}"
                                    class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150">
                                    Login
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Course Levels Column -->
                <div class="lg:col-span-3 space-y-3 text-left">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Course Levels</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li>
                            <a href="{{ route('courses.index', ['level' => 'beginner']) }}"
                                class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Beginner (A1 - A2)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('courses.index', ['level' => 'intermediate']) }}"
                                class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Intermediate (B1 - B2)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('courses.index', ['level' => 'advanced']) }}"
                                class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                Advanced (C1)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('placement-tests') }}"
                                class="text-gray-500 hover:text-blue-600 font-medium transition-colors duration-150 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Assessment Tests
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="lg:col-span-3 space-y-3 text-left">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Contact</h3>
                    <div class="space-y-3 pt-1">
                        <!-- Location -->
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <div
                                class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Bali, Indonesia</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <a href="mailto:adityariyan367@gmail.com"
                            class="group flex items-center gap-3 text-sm text-gray-600 hover:text-blue-600 transition-colors">
                            <div
                                class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 group-hover:text-blue-600 truncate">
                                    adityariyan367@gmail.com</p>
                            </div>
                        </a>

                        <!-- Phone -->
                        <a href="tel:+6288228163162"
                            class="group flex items-center gap-3 text-sm text-gray-600 hover:text-blue-600 transition-colors">
                            <div
                                class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 group-hover:text-blue-600">+62 8822 8163 162</p>
                            </div>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright Bar -->
            <div
                class="mt-10 pt-6 border-t border-gray-150 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500 font-medium">
                <div>
                    &copy; {{ date('Y') }} Aditya Farid Riyan Wijaya. All Rights Reserved.
                </div>
                <div class="flex items-center gap-4">
                    <span>Designed &amp; Built for Lingofy</span>
                    <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="inline-flex items-center gap-1 text-gray-400 hover:text-blue-600 transition-colors cursor-pointer border-0 bg-transparent">
                        <span>Top</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Sticky navbar scroll handling
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY >= 20) {
                navbar.classList.add('shadow-md');
                navbar.classList.remove('shadow-sm');
            } else {
                navbar.classList.add('shadow-sm');
                navbar.classList.remove('shadow-md');
            }
        });

        // Profile dropdown menu handling
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const container = document.getElementById('profile-dropdown-container');
            const dropdown = document.getElementById('profile-dropdown');
            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Mobile hamburger menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Intercept logout forms
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForms = document.querySelectorAll('.logout-form');
            logoutForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed',
                            'pointer-events-none');
                        submitBtn.innerHTML = `
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="animate-spin h-3.5 w-3.5 text-red-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Logging out...</span>
                            </span>
                        `;
                    }

                    const tokenInput = form.querySelector('input[name="_token"]');
                    const token = tokenInput ? tokenInput.value : (document.querySelector(
                        'meta[name="csrf-token"]')?.getAttribute('content') || '');

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (response.redirected) {
                                window.location.href = response.url;
                                return;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data && data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                window.location.href = "{{ route('login') }}";
                            }
                        })
                        .catch(error => {
                            console.error('Logout error:', error);
                            window.location.href = "{{ route('login') }}";
                        });
                });
            });
        });
    </script>
</body>

</html>
