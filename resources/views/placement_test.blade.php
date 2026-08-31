@extends('layouts.app')

@section('content')
    <div>
        <!-- Clean Typography Header -->
        <div class="max-w-4xl mx-auto pt-10 px-6 text-[#1F2937] text-left">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Placement Tests</h1>
            <p class="mt-2 text-gray-500 text-base font-medium">Find out your level instantly and enroll in the ideal course
                for you.</p>
        </div>
        <br>
        <div class="max-w-4xl mx-auto pt-6 pb-12 px-6">
            @if (!$activeTest)
                <!-- List Mode -->
                <div class="pb-[2rem]">
                    <div class="text-[#1F2937] flex flex-col">
                        <p class="text-gray-500 text-base leading-relaxed text-center max-w-2xl mx-auto mb-10 font-medium">
                            How good is your language? Not sure which course is right for you? Take one of our quick tests
                            below to evaluate your proficiency.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                            @forelse($quizzes as $quiz)
                                <div
                                    class="group bg-white border border-gray-150 rounded-2xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden relative">
                                    <div
                                        class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-indigo-600">
                                    </div>

                                    <div>
                                        <div
                                            class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3
                                            class="text-xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors duration-200">
                                            {{ $quiz->title }}
                                        </h3>
                                        <p class="mt-3 text-gray-500 leading-relaxed text-sm">
                                            {{ $quiz->description ?? 'Determine your grammar, listening, and speaking capabilities.' }}
                                        </p>
                                    </div>

                                    <div class="mt-8 pt-5 border-t border-gray-50 flex items-center justify-between">
                                        <span class="text-xs font-semibold text-gray-400">
                                            {{ count($quiz->contens ?? []) }} questions
                                        </span>
                                        <a href="{{ route('placement-tests', ['id' => $quiz->id]) }}"
                                            class="inline-block text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 px-4 py-2.5 rounded-xl transition duration-150 cursor-pointer text-center">
                                            Take Test
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="col-span-2 text-center py-16 text-gray-500 bg-white border border-dashed border-gray-200 rounded-2xl shadow-sm">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m16 0V5a2 2 0 00-2-2H6a2 2 0 00-2 2v3m16 0v4M4 15v4m0 0a2 2 0 002 2h12a2 2 0 002-2v-4M4 19h16">
                                        </path>
                                    </svg>
                                    <span class="font-bold text-gray-800">No Tests Available</span>
                                    <p class="text-gray-400 mt-1 text-sm">Check back later for newly added proficiency
                                        tests.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <!-- Preview / Start Mode -->
                <div
                    class="bg-white border border-gray-150 rounded-2xl shadow-sm p-8 text-center max-w-xl mx-auto relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-500 to-indigo-600"></div>

                    <div
                        class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Ready to start?</h1>
                    <h3 class="mt-2 text-lg font-semibold text-purple-600">
                        {{ $activeTest->title }}
                    </h3>

                    <p class="mt-4 text-gray-500 leading-relaxed text-sm px-4">
                        {{ $activeTest->description }} This test contains exactly **{{ count($activeTest->contens ?? []) }}
                        multiple-choice questions**. It takes around **15 minutes** to complete. Do not refresh or exit the
                        page once you begin.
                    </p>

                    <div class="flex items-center justify-center gap-3 mt-8">
                        <a href="{{ route('placement-tests') }}"
                            class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 font-bold transition text-sm cursor-pointer">
                            Go Back
                        </a>
                        <a href="{{ route('quizzes.show', $activeTest->id) }}"
                            class="bg-purple-600 text-white px-8 py-3.5 rounded-xl shadow-md hover:bg-purple-750 font-bold transition text-sm cursor-pointer">
                            Start Test &rarr;
                        </a>
                    </div>
                </div>
            @endif
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
                    console.log('Placement tests details pre-fetched dynamically via fetch/xhr successfully.',
                        data);
                })
                .catch(err => console.error('Dynamic fetch error:', err));
        });
    </script>
@endsection
