@extends('layouts.app')

@section('content')
    <div>
        <!-- Clean Typography Header -->
        <div class="w-[90%] xl:w-[85%] 2xl:w-[80%] max-w-7xl mx-auto pt-10 text-[#1F2937] text-left">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">My Classroom</h1>
            <p class="mt-2 text-gray-500 text-base font-medium">Continue your learning path and track your enrolled courses.
            </p>
        </div>
        
        <!-- Enrolled Courses Content -->
        <div class="w-[90%] xl:w-[85%] 2xl:w-[80%] max-w-7xl mx-auto pb-20 pt-6 text-[#1F2937]">
            @if (session('success'))
                <div
                    class="p-4 mb-8 text-sm text-green-700 bg-green-50 border border-green-150 rounded-xl shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (count($enrolledContents) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- Left Sidebar: Enrolled Courses List -->
                    <div class="lg:col-span-4 xl:col-span-3 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm space-y-3 lg:sticky lg:top-28">
                        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-2">Enrolled Courses</h2>
                        @foreach ($enrolledContents as $index => $courseItem)
                            @php
                                $cId = $courseItem['courseId'] ?? '';
                                $cTitle = $courseItem['title'] ?? 'Course';
                            @endphp
                            <button onclick="switchCourseTab('{{ $cId }}')" id="tab-btn-{{ $cId }}"
                                class="course-tab-btn w-full text-left p-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-between border {{ $index === 0 ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-gray-50/50 hover:bg-gray-100 text-gray-700 border-transparent' }}">
                                <span class="truncate pr-2">{{ $cTitle }}</span>
                                <svg class="w-4 h-4 shrink-0 transition-transform duration-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </button>
                        @endforeach
                    </div>

                    <!-- Right Area: Course Classes and Materials -->
                    <div class="lg:col-span-8 xl:col-span-9">
                        @foreach ($enrolledContents as $index => $courseItem)
                            @php
                                $courseId = $courseItem['courseId'] ?? '';
                                $courseTitle = $courseItem['title'] ?? 'Course';
                                // Filter classes for this course
                                $courseClasses = $classes->filter(function ($c) use ($courseId) {
                                    return (string) $c->course_id === (string) $courseId;
                                });
                            @endphp

                            <div id="course-content-{{ $courseId }}"
                                class="course-content-panel space-y-6 {{ $index === 0 ? '' : 'hidden' }}">
                                <!-- Header Info -->
                                <div
                                    class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Active
                                            Course</span>
                                        <h2 class="text-2xl font-bold text-gray-900 mt-1 text-left">{{ $courseTitle }}</h2>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200/50 py-1.5 px-3 rounded-lg">
                                        {{ count($courseClasses) }} {{ count($courseClasses) === 1 ? 'Class' : 'Classes' }}
                                    </span>
                                </div>

                                <!-- Classes & Materials -->
                                @if (count($courseClasses) > 0)
                                    <div class="space-y-6">
                                        @foreach ($courseClasses as $classIndex => $classItem)
                                            <div
                                                class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                                                <!-- Class Header -->
                                                <div
                                                    class="p-6 bg-slate-50/50 border-b border-gray-50 flex items-center justify-between text-left">
                                                    <div>
                                                        <h3 class="text-lg font-bold text-gray-900">
                                                            Class {{ $classIndex + 1 }}: {{ $classItem->class_name }}
                                                        </h3>
                                                        <p class="text-sm text-gray-500 mt-1">
                                                            {{ $classItem->description }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Materials list -->
                                                <div class="p-6 text-left">
                                                    <div class="space-y-4">
                                                        @if (is_array($classItem->materials) && count($classItem->materials) > 0)
                                                            @foreach ($classItem->materials as $mIndex => $material)
                                                                @php
                                                                    $materialId =
                                                                        'material-' . $classItem->id . '-' . $mIndex;
                                                                @endphp
                                                                <div
                                                                    class="rounded-xl border border-gray-100 bg-white p-4 hover:border-gray-200 hover:shadow-sm transition-all duration-200">
                                                                    <div class="flex items-center justify-between gap-4">
                                                                        <div class="flex items-center gap-3 min-w-0">
                                                                            <!-- Icon based on type -->
                                                                            <div
                                                                                class="shrink-0 rounded-xl p-2.5 
                                                                            @if (($material['type'] ?? '') === 'video') bg-red-50 text-red-600
                                                                            @elseif(($material['type'] ?? '') === 'pdf') bg-blue-50 text-blue-600
                                                                            @else bg-green-50 text-green-700 @endif">
                                                                                @if (($material['type'] ?? '') === 'video')
                                                                                    <!-- Video Play Icon -->
                                                                                    <svg class="w-5 h-5" fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                                                                        </path>
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                                        </path>
                                                                                    </svg>
                                                                                @elseif(($material['type'] ?? '') === 'pdf')
                                                                                    <!-- Document Icon -->
                                                                                    <svg class="w-5 h-5" fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                                        </path>
                                                                                    </svg>
                                                                                @else
                                                                                    <!-- Quiz Icon -->
                                                                                    <svg class="w-5 h-5" fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                                                        </path>
                                                                                    </svg>
                                                                                @endif
                                                                            </div>
                                                                            <div class="min-w-0">
                                                                                <span
                                                                                    class="text-sm font-semibold text-gray-800 block truncate">
                                                                                    {{ $material['title'] ?? 'Material' }}
                                                                                </span>
                                                                                <span
                                                                                    class="text-xs text-gray-400 mt-0.5 block">
                                                                                    @if (($material['type'] ?? '') === 'video')
                                                                                        Watch video lecture
                                                                                    @elseif(($material['type'] ?? '') === 'pdf')
                                                                                        Read PDF module
                                                                                    @else
                                                                                        Solve homework test
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        </div>

                                                                        <div>
                                                                            @if (($material['type'] ?? '') === 'video' || ($material['type'] ?? '') === 'pdf')
                                                                                <button type="button"
                                                                                    onclick="toggleMaterialMedia('{{ $materialId }}')"
                                                                                    id="btn-{{ $materialId }}"
                                                                                    class="shrink-0 rounded-xl bg-blue-600 px-5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none cursor-pointer border-0">
                                                                                    Start
                                                                                </button>
                                                                            @else
                                                                                <a href="{{ route('quizzes.started', ['id' => $classItem->id]) }}"
                                                                                    class="inline-block shrink-0 rounded-xl bg-blue-600 px-5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none cursor-pointer text-center font-sans">
                                                                                    Start
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <!-- Embedded Video -->
                                                                    @if (($material['type'] ?? '') === 'video' && isset($material['fileName']))
                                                                        <div id="media-{{ $materialId }}"
                                                                            class="hidden mt-4">
                                                                            <video controls width="100%"
                                                                                class="w-full aspect-video rounded-xl shadow-md border border-gray-200 bg-black">
                                                                                <source
                                                                                    src="/videos/{{ $material['fileName'] }}"
                                                                                    type="video/mp4" />
                                                                                Your browser does not support the video tag.
                                                                            </video>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Embedded PDF -->
                                                                    @if (($material['type'] ?? '') === 'pdf' && isset($material['fileName']))
                                                                        <div id="media-{{ $materialId }}"
                                                                            class="hidden mt-4 space-y-2">
                                                                            <div class="flex items-center justify-end gap-2 text-xs">
                                                                                <a href="/documents/{{ $material['fileName'] }}" target="_blank"
                                                                                    class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                                                    </svg>
                                                                                    Open in new tab
                                                                                </a>
                                                                            </div>
                                                                            <div class="h-[75vh] min-h-[550px] rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                                                                <iframe
                                                                                    src="/documents/{{ $material['fileName'] }}#toolbar=1&navpanes=0"
                                                                                    class="h-full w-full bg-white border-0"
                                                                                    title="{{ $material['title'] ?? 'PDF Document' }}"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div
                                                                class="text-sm text-gray-400 italic py-4 text-center border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                                                                No materials uploaded for this class.
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm text-center text-gray-500">
                                        No classes available for this course yet. Check back soon!
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div
                    class="p-16 text-center text-gray-500 bg-white rounded-2xl border border-dashed border-gray-200 shadow-sm max-w-lg mx-auto">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800">No Enrolled Courses</h3>
                    <p class="text-gray-400 mt-2">You haven't enrolled in any courses yet.</p>
                    <a href="{{ route('courses.index') }}"
                        class="mt-6 inline-block bg-blue-600 hover:bg-blue-750 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm text-sm">
                        Explore Courses
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Trigger background XHR/fetch call of classroom API to log fetch/xhr in Network console
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/my-courses', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Classroom details pre-fetched dynamically via fetch/xhr successfully.', data);
                })
                .catch(err => console.error('Dynamic fetch error:', err));
        });

        function toggleMaterialMedia(mediaId) {
            const targetMedia = document.getElementById('media-' + mediaId);
            const targetBtn = document.getElementById('btn-' + mediaId);

            if (targetMedia.classList.contains('hidden')) {
                targetMedia.classList.remove('hidden');
                targetBtn.textContent = 'Hide';
                targetBtn.classList.remove('bg-blue-600', 'hover:bg-blue-750');
                targetBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
            } else {
                targetMedia.classList.add('hidden');
                targetBtn.textContent = 'Start';
                targetBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
                targetBtn.classList.add('bg-blue-600', 'hover:bg-blue-750');

                const video = targetMedia.querySelector('video');
                if (video) {
                    video.pause();
                }
            }
        }

        function switchCourseTab(courseId) {
            // Toggle Active button class
            const buttons = document.querySelectorAll('.course-tab-btn');
            buttons.forEach(btn => {
                if (btn.id === 'tab-btn-' + courseId) {
                    btn.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-100');
                    btn.classList.remove('bg-gray-50/50', 'hover:bg-gray-100', 'text-gray-700',
                        'border-transparent');
                } else {
                    btn.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-100');
                    btn.classList.add('bg-gray-50/50', 'hover:bg-gray-100', 'text-gray-700', 'border-transparent');
                }
            });

            // Toggle Content Panels
            const panels = document.querySelectorAll('.course-content-panel');
            panels.forEach(panel => {
                if (panel.id === 'course-content-' + courseId) {
                    panel.classList.remove('hidden');
                } else {
                    panel.classList.add('hidden');
                }
            });
        }
    </script>
@endsection
