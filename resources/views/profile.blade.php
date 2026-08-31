@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    
    <!-- Profile Header with Avatar Initials -->
    <div class="text-[#1F2937] text-center mb-10">
        @php
            $initials = '';
            if (isset($student->full_name)) {
                $words = explode(' ', $student->full_name);
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            }
        @endphp
        <div class="w-20 h-20 bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-black text-2xl rounded-full flex items-center justify-center mx-auto shadow-md border-4 border-white">
            {{ $initials ?: 'S' }}
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight mt-4 text-gray-900">My Profile</h1>
        <p class="text-gray-500 mt-1.5 font-medium">Personal details and enrolled courses.</p>
    </div>

    <!-- User Information Card -->
    <div class="bg-white shadow-sm border border-gray-150 rounded-2xl overflow-hidden">
        <div class="px-6 py-5 bg-slate-50/50 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <h3 class="text-lg leading-6 font-bold text-gray-900">
                User Information
            </h3>
        </div>
        <div class="border-t border-gray-50">
            <dl class="divide-y divide-gray-100">
                <div class="px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 items-center">
                    <dt class="text-sm font-bold text-gray-400 uppercase tracking-wider">
                        Full Name
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $student->full_name }}
                    </dd>
                </div>
                <div class="px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 items-center">
                    <dt class="text-sm font-bold text-gray-400 uppercase tracking-wider">
                        Email Address
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $student->email }}
                    </dd>
                </div>
                <div class="px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 items-center">
                    <dt class="text-sm font-bold text-gray-400 uppercase tracking-wider">
                        Phone
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $student->phone ?? 'Not provided' }}
                    </dd>
                </div>
                <div class="px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4 items-center">
                    <dt class="text-sm font-bold text-gray-400 uppercase tracking-wider">
                        Member Since
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $student->created_at ? $student->created_at->format('M d, Y') : '-' }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Enrolled Courses Card -->
    <div class="mt-10 bg-white shadow-sm border border-gray-150 rounded-2xl overflow-hidden">
        <div class="px-6 py-5 bg-slate-50/50 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-lg leading-6 font-bold text-gray-900">
                Enrolled Courses
            </h3>
        </div>
        <div class="border-t border-gray-50">
            @if(count($enrolledContents) === 0)
                <div class="px-6 py-12 text-center text-sm text-gray-400 italic">
                    You are not enrolled in any courses yet.
                </div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($enrolledContents as $c)
                        <li class="px-6 py-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors duration-150">
                            <div>
                                <h4 class="text-base font-bold text-gray-900">
                                    {{ $c['title'] ?? 'Course' }}
                                </h4>
                                <p class="text-xs text-gray-400 mt-1">Course ID: {{ $c['courseId'] ?? '-' }}</p>
                            </div>
                            <div>
                                <a
                                    href="{{ route('my-courses') }}"
                                    class="text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition duration-150 cursor-pointer block"
                                >
                                    Study Now &rarr;
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
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
        console.log('Profile details pre-fetched dynamically via fetch/xhr successfully.', data);
    })
    .catch(err => console.error('Dynamic fetch error:', err));
});
</script>
@endsection
