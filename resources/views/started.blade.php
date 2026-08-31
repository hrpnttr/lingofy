@extends('layouts.app')

@section('content')
<div>
    <!-- Hero Gradient Banner -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-700 to-blue-800 text-white py-16 px-8 relative overflow-hidden shadow-sm">
        <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 bg-emerald-500/10 rounded-full blur-2xl -ml-16 -mb-16 pointer-events-none"></div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            <div class="text-left">
                <h1 class="text-4xl font-extrabold tracking-tight">Practice Quiz</h1>
                <p class="mt-2 text-emerald-100 text-lg font-medium">Test your knowledge and level up your comprehension.</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-12 px-6">
        @if($quiz)
            <div class="bg-white border border-gray-155 rounded-2xl shadow-sm p-8 text-center max-w-xl mx-auto relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 mx-auto">
                    <!-- Homework/Clipboard Icon -->
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Ready to begin?</h1>
                <h3 class="mt-2 text-lg font-semibold text-emerald-600">
                    {{ $quiz->title ?? 'Classroom Quiz' }}
                </h3>
                
                <p class="mt-4 text-gray-500 leading-relaxed text-sm px-4">
                    {{ $quiz->description ?? 'Determine your course progress and practice key components.' }}
                    This practice test consists of **{{ count($quiz->contens ?? []) }} multiple-choice questions** and takes roughly **10-15 minutes**. Take your time to answer carefully.
                </p>

                <div class="flex items-center justify-center gap-3 mt-8">
                    <a
                        href="{{ route('my-courses') }}"
                        class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 font-bold transition text-sm cursor-pointer"
                    >
                        Cancel
                    </a>
                    <a
                        href="{{ route('quizzes.show', $quiz->id) }}"
                        class="bg-emerald-600 text-white px-8 py-3.5 rounded-xl shadow-md hover:bg-emerald-700 font-bold transition text-sm cursor-pointer"
                    >
                        Start Quiz &rarr;
                    </a>
                </div>
            </div>
        @else
            <div class="p-8 text-center text-red-700 bg-red-50 border border-dashed border-red-200 rounded-2xl max-w-md mx-auto shadow-sm">
                <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="text-lg font-bold text-red-950">Quiz Unavailable</h3>
                <p class="text-sm text-red-650 mt-1">No quiz is currently associated with this class.</p>
                <a href="{{ route('my-courses') }}" class="mt-4 inline-block bg-white text-red-700 border border-red-200 hover:bg-red-50 px-4 py-2 rounded-xl text-xs font-semibold shadow-sm">
                    Back to Classroom
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
