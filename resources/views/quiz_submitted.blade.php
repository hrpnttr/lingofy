@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="bg-white border border-gray-150 rounded-2xl shadow-sm p-8 text-center max-w-xl mx-auto relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 mx-auto">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Quiz already submitted</h1>
        <p class="text-sm font-semibold text-emerald-600 mt-2">{{ $quiz->title ?? 'Classroom Quiz' }}</p>
        <p class="text-sm mt-4 text-gray-500 max-w-sm mx-auto leading-relaxed">
            You submitted this quiz{{ !empty($submission['submitted_at']) ? ' on ' . \Illuminate\Support\Carbon::parse($submission['submitted_at'])->format('M j, Y') : '' }}.
            Each class quiz can be taken once. If you need another attempt, ask your teacher to reopen it for you.
        </p>
        <div class="mt-8">
            <a href="{{ route('my-courses') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-xl shadow-md font-bold transition text-sm cursor-pointer">
                Back to my courses
            </a>
        </div>
    </div>
</div>
@endsection
