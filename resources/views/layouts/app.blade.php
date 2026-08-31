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
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
    <div id="navbar" class="fixed w-[100%] transition-all duration-300 z-[1000] bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <header class="mx-auto flex h-[9vh] w-[85%] items-center justify-between">
            <!-- Logo -->
            <div class="font-logo text-lg text-black">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="/images/2_no.png" alt="Logo" width="130" height="70" class="object-contain transition-transform duration-200 hover:scale-105" />
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden items-center gap-10 text-base font-semibold md:flex text-gray-700" aria-label="Primary">
                <a href="{{ route('placement-tests') }}" class="py-2 transition-colors duration-200 {{ request()->routeIs('placement-tests*') ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600' }}">Placement Test</a>
                <a href="{{ route('courses.index') }}" class="py-2 transition-colors duration-200 {{ request()->routeIs('courses.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600' }}">Courses</a>
                @if(Auth::guard('student')->check())
                    <a href="{{ route('my-courses') }}" class="py-2 transition-colors duration-200 {{ request()->routeIs('my-courses*') ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600' }}">My Courses</a>
                @endif
            </nav>

            <!-- Desktop Auth -->
            <div class="hidden items-center md:flex text-gray-700 relative">
                @if(Auth::guard('student')->check())
                    <div class="relative" id="profile-dropdown-container">
                        <button onclick="toggleProfileDropdown()" class="focus:outline-none flex items-center gap-2 py-1 px-3 rounded-full hover:bg-gray-100 transition-colors duration-200 border border-gray-200">
                            <span class="text-sm font-medium">{{ Auth::guard('student')->user()->full_name }}</span>
                            <!-- User Avatar Icon -->
                            <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1.5 z-50 border border-gray-100">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition-colors duration-150">My Profile</a>
                            <form action="{{ route('logout') }}" method="POST" class="logout-form block w-full">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors duration-150">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-base font-semibold hover:text-blue-600 transition-colors duration-200 border border-gray-300 py-1.5 px-5 rounded-lg hover:border-blue-600">Login</a>
                @endif
            </div>

            <!-- Mobile Navigation Toggle -->
            <div class="md:hidden flex items-center">
                <button onclick="toggleMobileMenu()" class="focus:outline-none p-1.5 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <!-- Hamburger Icon -->
                    <svg class="w-7 h-7 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </header>
        
        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-md w-full border-t border-gray-100 py-4 px-6 shadow-md transition-all duration-300">
            <nav class="flex flex-col gap-4 text-lg font-semibold text-gray-800">
                <a href="{{ route('placement-tests') }}" class="hover:text-blue-600 transition-colors duration-200">Placement Test</a>
                <a href="{{ route('courses.index') }}" class="hover:text-blue-600 transition-colors duration-200">Courses</a>
                @if(Auth::guard('student')->check())
                    <a href="{{ route('my-courses') }}" class="hover:text-blue-600 transition-colors duration-200">My Courses</a>
                    <a href="{{ route('profile') }}" class="hover:text-blue-600 transition-colors duration-200">My Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form inline">
                        @csrf
                        <button type="submit" class="text-left font-semibold hover:text-red-600 transition-colors duration-200">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-blue-600 transition-colors duration-200">Login</a>
                @endif
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main id="main-content" class="flex-grow pt-[12vh]">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="pt-[2rem] pb-[1rem] justify-items-center bg-[#111827] text-white" id="footer">
        <div class="w-[80%] mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[2rem] border-b-[1.4px] pb-5 border-gray-600 border-opacity-40">
            <div>
                <img src="/images/2_no.png" alt="Footer Logo" height="80" width="150" class="object-contain mb-3" />
                <h1 class="text-[14px] opacity-70">
                    Built with Laravel, Tailwind CSS, and SQLite
                </h1>
            </div>
            <div class="md:mx-auto">
                <h1 class="text-[14px] mt-[1rem] opacity-70">Let's connect:</h1>
                <p class="mt-[0.5rem] underline font-semibold text-blue-400">
                    adityariyan367@gmail.com
                </p>
            </div>
            <div class="md:mx-auto">
                <h1 class="font-semibold mb-[1.4rem] text-[14px]">Address</h1>
                <div class="flex items-center mt-[1rem] space-x-2">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    <p class="text-[15px] font-normal">Bali, Indonesia</p>
                </div>
                <div class="flex items-center mt-[1rem] space-x-2">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                    <p class="text-[15px] font-normal">adityariyan367@gmail.com</p>
                </div>
                <div class="flex items-center mt-[1rem] space-x-2">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.3 11.3 0 005.455 5.455l.773-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                    <p class="text-[15px] font-normal">+62 8822 8163 162</p>
                </div>
            </div>
        </div>
        <div class="mt-[1.4rem] w-[80%] mx-auto opacity-80 text-center md:text-left">
            &#169; 2026 Aditya Farid Riyan Wijaya | All Rights Reserved
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
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.redirect) {
                            navigateTo(data.redirect);
                        }
                    })
                    .catch(error => console.error('Logout error:', error));
                });
            });
        });

        // PJAX Router for handling page navigations using fetch/xhr GET requests
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;
            
            // Check if internal, same origin, non-anchor, non-javascript
            if (link.origin !== window.location.origin) return;
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
            if (link.getAttribute('target') === '_blank') return;
            
            e.preventDefault();
            navigateTo(link.href);
        });

        window.addEventListener('popstate', function() {
            loadPage(window.location.href, false);
        });

        function navigateTo(url) {
            loadPage(url, true);
        }

        function loadPage(url, pushState) {
            fetch(url, {
                headers: {
                    'X-PJAX': 'true',
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    window.location.href = url;
                    return;
                }
                return response.text();
            })
            .then(html => {
                if (!html) return;
                
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                document.title = doc.title;
                
                const currentMain = document.getElementById('main-content');
                const newMain = doc.getElementById('main-content');
                if (currentMain && newMain) {
                    currentMain.innerHTML = newMain.innerHTML;
                }
                
                // Update active link classes or navigation state if needed
                // Note: since header is in layout and layout is not reloaded, 
                // we can also update active nav indicator or hamburger state here.
                const currentHeader = document.querySelector('header');
                const newHeader = doc.querySelector('header');
                if (currentHeader && newHeader) {
                    currentHeader.innerHTML = newHeader.innerHTML;
                }
                const currentMobileMenu = document.getElementById('mobile-menu');
                const newMobileMenu = doc.getElementById('mobile-menu');
                if (currentMobileMenu && newMobileMenu) {
                    currentMobileMenu.innerHTML = newMobileMenu.innerHTML;
                }

                if (pushState) {
                    history.pushState(null, doc.title, url);
                }
                
                window.scrollTo(0, 0);
                
                // Re-execute scripts in the newly loaded main element
                if (newMain) {
                    const scripts = newMain.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                        newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });
                }
            })
            .catch(err => {
                console.error('PJAX load error:', err);
                window.location.href = url;
            });
        }
    </script>
</body>
</html>
