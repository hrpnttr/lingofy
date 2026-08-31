@extends('layouts.app')

@section('content')
<div>
    <!-- Clean Typography Header -->
    <div class="w-[80%] mx-auto pt-10 text-[#1F2937] text-left">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Courses Catalog</h1>
        <p class="mt-2 text-gray-500 text-base font-medium">Explore premium language courses designed to unlock new opportunities.</p>
    </div>

    <!-- Search & Filter Bar -->
    <div class="w-[80%] mx-auto mt-8 relative z-20 bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <form id="filter-form" action="{{ route('courses.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center gap-1.5 cursor-pointer border-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
                <a
                    id="clear-filters-btn"
                    href="{{ route('courses.index') }}"
                    class="{{ request('q') || request('level') || request('language') ? '' : 'hidden' }} bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold p-3 rounded-xl transition duration-200 text-sm flex items-center justify-center cursor-pointer"
                    title="Clear Filters"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Courses List Grid Area -->
    <div class="w-[80%] mx-auto pb-20 pt-6 text-[#1F2937]">
        <div class="flex items-center justify-between mb-8">
            <div id="total-badge" class="text-gray-500 font-semibold text-sm bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200/50">
                Total Courses: {{ $courses->total() }}
            </div>
        </div>

        <!-- Loading spinner -->
        <div id="loading-indicator" class="hidden flex justify-center items-center py-20">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
        </div>

        <!-- Hybrid Grid container (Server-rendered by default, dynamic on AJAX) -->
        <div id="courses-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($courses as $course)
                @php
                    $shortLang = strtoupper(substr($course->language ?? 'EN', 0, 2));
                    $price = $course->price && $course->price > 0 ? '$' . number_format($course->price, 2) : 'Free';
                @endphp
                <div class="group bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    <div style="position: relative;">
                        <div style="height: 160px; background-image: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(168, 85, 247, 0.1) 50%, rgba(59, 130, 246, 0.2) 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                            <span style="color: rgba(79, 70, 229, 0.7); font-size: 60px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; pointer-events: none; user-select: none;">{{ $shortLang }}</span>
                            <div style="position: absolute; top: 16px; left: 16px; display: flex; flex-direction: column; gap: 6px;">
                                <span style="background-color: #3b82f6; color: #ffffff; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: capitalize; display: inline-flex; align-items: center; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                                    {{ $course->language ?? '-' }}
                                </span>
                                <span style="background-color: #a855f7; color: #ffffff; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: capitalize; display: inline-flex; align-items: center; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
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
                                {{ $price }}
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <form class="enroll-form" action="{{ route('courses.enroll', $course->id) }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="w-full bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 py-3 shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer text-center block border-0 text-sm"
                            >
                                Enroll Course
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No courses message -->
        <div id="no-courses-view" class="hidden p-16 text-center text-gray-500 bg-white rounded-2xl border border-dashed border-gray-200 shadow-sm max-w-lg mx-auto">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-800">No Courses Found</h3>
            <p class="text-gray-400 mt-2">Try clearing your filters or using different keywords.</p>
            <button onclick="clearAllFilters()" class="mt-6 inline-block bg-blue-600 hover:bg-blue-750 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm text-sm border-0 cursor-pointer">
                Clear All Filters
            </button>
        </div>

        <!-- Pagination Container -->
        <div id="pagination-container" class="mt-12 flex justify-center">
            @if(count($courses) > 0)
                {{ $courses->appends(request()->query())->links() }}
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    const loadingIndicator = document.getElementById('loading-indicator');
    const coursesGrid = document.getElementById('courses-grid');
    const noCoursesView = document.getElementById('no-courses-view');
    const totalBadge = document.getElementById('total-badge');
    const paginationContainer = document.getElementById('pagination-container');
    const clearFiltersBtn = document.getElementById('clear-filters-btn');

    // Intercept filter submits for AJAX dynamic fetch
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value.trim() !== '') {
                params.append(key, value);
            }
        }
        
        const queryString = params.toString() ? '?' + params.toString() : '';
        history.pushState(null, '', window.location.pathname + queryString);
        fetchCourses(queryString);
    });

    // Fetch dynamic JSON data using fetch()
    function fetchCourses(queryStr = '') {
        showLoading(true);
        
        // Show/hide clear filters button
        const searchParams = new URLSearchParams(queryStr);
        if (searchParams.has('q') || searchParams.has('level') || searchParams.has('language')) {
            clearFiltersBtn.classList.remove('hidden');
        } else {
            clearFiltersBtn.classList.add('hidden');
        }

        fetch('/courses' + queryStr, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to load courses');
            return response.json();
        })
        .then(data => {
            showLoading(false);
            renderCourses(data.data || []);
            renderPagination(data);
            totalBadge.textContent = `Total Courses: ${data.total || 0}`;
        })
        .catch(err => {
            console.error(err);
            showLoading(false);
            coursesGrid.innerHTML = '';
            totalBadge.textContent = 'Failed to load courses';
        });
    }

    function showLoading(isLoading) {
        if (isLoading) {
            loadingIndicator.classList.remove('hidden');
            coursesGrid.classList.add('hidden');
            noCoursesView.classList.add('hidden');
            paginationContainer.classList.add('hidden');
        } else {
            loadingIndicator.classList.add('hidden');
        }
    }

    function renderCourses(courses) {
        coursesGrid.innerHTML = '';
        if (courses.length === 0) {
            coursesGrid.classList.add('hidden');
            noCoursesView.classList.remove('hidden');
            return;
        }

        noCoursesView.classList.add('hidden');
        coursesGrid.classList.remove('hidden');

        courses.forEach(course => {
            const shortLang = (course.language || 'EN').substring(0, 2).toUpperCase();
            const enrollUrl = `/courses/${course.id}/enroll`;
            const duration = course.duration_hours || '-';
            const price = course.price && course.price > 0 ? `$${parseFloat(course.price).toFixed(2)}` : 'Free';
            
            const cardHtml = `
                <div class="group bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    <div style="position: relative;">
                        <div style="height: 160px; background-image: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(168, 85, 247, 0.1) 50%, rgba(59, 130, 246, 0.2) 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                            <span style="color: rgba(79, 70, 229, 0.7); font-size: 60px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; pointer-events: none; user-select: none;">${shortLang}</span>
                            <div style="position: absolute; top: 16px; left: 16px; display: flex; flex-direction: column; gap: 6px;">
                                <span style="background-color: #3b82f6; color: #ffffff; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: capitalize; display: inline-flex; align-items: center; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                                    ${course.language || '-'}
                                </span>
                                <span style="background-color: #a855f7; color: #ffffff; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: capitalize; display: inline-flex; align-items: center; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                                    ${course.level || '-'}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 flex-grow flex flex-col justify-between text-left">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                                ${course.title}
                            </h3>
                            <p class="text-sm text-gray-500 leading-relaxed mt-2.5 line-clamp-3">
                                ${course.description || 'No description provided.'}
                            </p>
                        </div>

                        <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>${duration} hrs</span>
                            </div>
                            <div class="font-bold text-blue-600 text-base">
                                ${price}
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <form class="enroll-form" action="${enrollUrl}" method="POST">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <button
                                type="submit"
                                class="w-full bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 py-3 shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer text-center block border-0 text-sm"
                            >
                                Enroll Course
                            </button>
                        </form>
                    </div>
                </div>
            `;
            coursesGrid.insertAdjacentHTML('beforeend', cardHtml);
        });
    }

    function renderPagination(paginator) {
        paginationContainer.innerHTML = '';
        if (!paginator || paginator.last_page <= 1) {
            paginationContainer.classList.add('hidden');
            return;
        }

        paginationContainer.classList.remove('hidden');
        const nav = document.createElement('nav');
        nav.className = 'flex items-center gap-2';

        // Prev Button
        if (paginator.current_page > 1) {
            const prevBtn = createPageBtn('&larr; Previous', paginator.current_page - 1);
            nav.appendChild(prevBtn);
        }

        // Numbers
        for (let i = 1; i <= paginator.last_page; i++) {
            const pageBtn = createPageBtn(i, i, i === paginator.current_page);
            nav.appendChild(pageBtn);
        }

        // Next Button
        if (paginator.current_page < paginator.last_page) {
            const nextBtn = createPageBtn('Next &rarr;', paginator.current_page + 1);
            nav.appendChild(nextBtn);
        }

        paginationContainer.appendChild(nav);
    }

    function createPageBtn(label, pageNum, isActive = false) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.innerHTML = label;
        btn.className = `px-4 py-2 text-sm font-bold rounded-xl transition duration-150 border cursor-pointer ${isActive ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'}`;
        
        btn.onclick = () => {
            const params = new URLSearchParams(window.location.search);
            params.set('page', pageNum);
            const queryString = '?' + params.toString();
            history.pushState(null, '', window.location.pathname + queryString);
            fetchCourses(queryString);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
        return btn;
    }

    window.clearAllFilters = () => {
        filterForm.reset();
        history.pushState(null, '', window.location.pathname);
        fetchCourses('');
    };

    // Enroll Form dynamic intercepts (Event Delegation)
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('.enroll-form');
        if (!form) return;
        
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
            if (response.ok) return response.json();
            return response.json().then(errData => { throw errData; });
        })
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        })
        .catch(error => {
            console.error('Enrollment error:', error);
            alert(error.errors && error.errors.error ? error.errors.error[0] : 'Failed to enroll course. Please try again.');
        });
    });
});
</script>
@endsection
