@extends('layouts.app')

@section('content')
<div>
    <!-- Hero Gradient Banner -->
    <div class="bg-gradient-to-r from-blue-700 via-blue-800 to-indigo-900 text-white py-16 px-8 relative overflow-hidden shadow-sm">
        <!-- Decorator Circles -->
        <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 bg-blue-500/10 rounded-full blur-2xl -ml-16 -mb-16 pointer-events-none"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            <div class="text-left">
                <h1 class="text-4xl font-extrabold tracking-tight">Expand Your Horizons</h1>
                <p class="mt-2 text-blue-100 text-lg font-medium">Explore premium language courses designed to unlock new opportunities.</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="w-[80%] mx-auto mt-[-2.5rem] relative z-20 bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <form action="{{ route('courses.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Search Courses</label>
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="e.g. English, Beginner..."
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-700 px-4 py-3 border text-sm"
                    />
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Level</label>
                <select
                    name="level"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-700 px-4 py-3 border text-sm capitalize"
                >
                    <option value="">All Levels</option>
                    <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Language</label>
                <input
                    type="text"
                    name="language"
                    value="{{ request('language') }}"
                    placeholder="e.g. Spanish, English"
                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-700 px-4 py-3 border text-sm"
                />
            </div>
            <div class="flex gap-2">
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
                @if(request('q') || request('level') || request('language'))
                    <a
                        href="{{ route('courses.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold p-3 rounded-xl transition duration-200 text-sm flex items-center justify-center cursor-pointer"
                        title="Clear Filters"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Courses List Grid -->
    <div class="w-[80%] mx-auto pb-20 pt-10 text-[#1F2937]">
        @if(session('success'))
            <div class="p-4 mb-8 text-sm text-green-700 bg-green-50 border border-green-150 rounded-xl shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-8">
            <div class="text-gray-500 font-semibold text-sm bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200/50">
                Total Courses: {{ $courses->total() }}
            </div>
        </div>

        @if(count($courses) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($courses as $course)
                    <div class="group bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                        <div class="relative">
                            <!-- Card Banner placeholder -->
                            <div class="h-40 bg-gradient-to-br from-indigo-500/20 via-purple-500/10 to-blue-500/20 flex items-center justify-center relative overflow-hidden">
                                <span class="text-indigo-600/70 font-black text-6xl uppercase tracking-widest select-none pointer-events-none">{{ substr($course->language ?? 'EN', 0, 2) }}</span>
                                <!-- Badges -->
                                <div class="absolute top-4 left-4 flex flex-col gap-1.5">
                                    <span class="inline-flex items-center rounded-full bg-blue-500 text-white px-2.5 py-0.5 text-xs font-semibold shadow-sm capitalize">
                                        {{ $course->language ?? '-' }}
                                    </span>
                                    <span class="inline-flex items-center rounded-full bg-purple-500 text-white px-2.5 py-0.5 text-xs font-semibold shadow-sm capitalize">
                                        {{ $course->level ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col justify-between text-left">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                                    {{ $course->title }}
                                </h3>
                                <p class="text-sm text-gray-500 leading-relaxed mt-2.5 line-clamp-3">
                                    {{ $course->description ?? 'No description provided.' }}
                                </p>
                            </div>

                            <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between text-sm text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $course->duration_hours ?? '-' }} hrs</span>
                                </div>
                                <div class="font-bold text-blue-600 text-base">
                                    {{ $course->price && $course->price > 0 ? '$' . number_format($course->price, 2) : 'Free' }}
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <form class="enroll-form" action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 py-3 shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer text-center block"
                                >
                                    Enroll Course
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-16 text-center text-gray-500 bg-white rounded-2xl border border-dashed border-gray-200 shadow-sm max-w-lg mx-auto">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-800">No Courses Found</h3>
                <p class="text-gray-400 mt-2">Try clearing your filters or using different keywords.</p>
                <a href="{{ route('courses.index') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-750 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm text-sm">
                    Clear All Filters
                </a>
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-12">
            {{ $courses->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.enroll-form');
    forms.forEach(form => {
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
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                return response.json().then(errData => {
                    throw errData;
                });
            })
            .then(data => {
                if (data.redirect) {
                    if (typeof navigateTo === 'function') {
                        navigateTo(data.redirect);
                    } else {
                        window.location.href = data.redirect;
                    }
                }
            })
            .catch(error => {
                console.error('Enrollment error:', error);
                alert(error.errors && error.errors.error ? error.errors.error[0] : 'Failed to enroll course. Please try again.');
            });
        });
    });
});
</script>
@endsection
