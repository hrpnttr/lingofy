@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6">
    <div class="text-[#1F2937] text-center mb-12">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Class Schedule</h1>
        <p class="text-gray-500 mt-1.5 font-medium">Find your weekly live video classes below.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- English Beginner Class -->
        <div class="group bg-white border border-gray-150 p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                        English Beginner Class
                    </h3>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-green-50 text-green-700 border border-green-100 uppercase tracking-wider">
                        ongoing
                    </span>
                </div>
                
                <div class="mt-6 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">Mondays, 6:00 PM - 7:30 PM</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Instructor: <span class="font-medium text-gray-800">John Doe</span></span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-5 border-t border-gray-50 text-left">
                <a
                    href="https://example.com"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1 text-sm font-bold text-blue-600 hover:text-blue-700"
                >
                    Join Class &rarr;
                </a>
            </div>
        </div>

        <!-- German Beginner Class -->
        <div class="group bg-white border border-gray-150 p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 to-red-500"></div>
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors">
                        German Beginner Class
                    </h3>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-green-50 text-green-700 border border-green-100 uppercase tracking-wider">
                        ongoing
                    </span>
                </div>
                
                <div class="mt-6 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">Tuesdays, 6:00 PM - 7:30 PM</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Instructor: <span class="font-medium text-gray-800">John Doe</span></span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-5 border-t border-gray-50 text-left">
                <a
                    href="https://example.com"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1 text-sm font-bold text-blue-600 hover:text-blue-700"
                >
                    Join Class &rarr;
                </a>
            </div>
        </div>

        <!-- Turkish Beginner Class -->
        <div class="group bg-white border border-gray-150 p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-pink-500"></div>
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-red-650 transition-colors">
                        Turkish Beginner Class
                    </h3>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-green-50 text-green-700 border border-green-100 uppercase tracking-wider">
                        ongoing
                    </span>
                </div>
                
                <div class="mt-6 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">Wednesdays, 6:00 PM - 7:30 PM</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Instructor: <span class="font-medium text-gray-800">John Doe</span></span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-5 border-t border-gray-50 text-left">
                <a
                    href="https://example.com"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1 text-sm font-bold text-blue-600 hover:text-blue-700"
                >
                    Join Class &rarr;
                </a>
            </div>
        </div>

        <!-- Spanish Beginner Class -->
        <div class="group bg-white border border-gray-150 p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-orange-500"></div>
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-yellow-600 transition-colors">
                        Spanish Beginner Class
                    </h3>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-green-50 text-green-700 border border-green-100 uppercase tracking-wider">
                        ongoing
                    </span>
                </div>
                
                <div class="mt-6 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">Thursdays, 6:00 PM - 7:30 PM</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Instructor: <span class="font-medium text-gray-800">John Doe</span></span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-5 border-t border-gray-50 text-left">
                <a
                    href="https://example.com"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1 text-sm font-bold text-blue-600 hover:text-blue-700"
                >
                    Join Class &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch(window.location.pathname + window.location.search, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Schedule details pre-fetched dynamically via fetch/xhr successfully.', data);
    })
    .catch(err => console.error('Dynamic fetch error:', err));
});
</script>
@endsection
