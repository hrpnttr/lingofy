@extends('layouts.app')

@section('content')
<div id="home">
    <!-- Hero Section -->
    <section class="relative w-full overflow-hidden">
        <div class="w-full relative h-[70vh] md:h-[80vh]">
            <img
                src="/images/upscalemedia-transformed (3).png"
                alt="Students studying together"
                class="absolute inset-0 w-full h-full object-cover animate-fade-in"
                style="z-index: 1;"
            />
            <!-- Centered Text Overlay -->
            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-b from-black/40 via-black/25 to-black/60 z-10" style="z-index: 2;">
                <div class="mx-auto w-[80%] text-center">
                    <h1 class="font-extrabold leading-tight text-white text-[clamp(2.0rem,6vw,4.5rem)] drop-shadow-md">
                        Let's Explore the Most Exciting
                    </h1>
                    <p class="mt-2 font-semibold text-white text-[clamp(1.3rem,3.5vw,2.6rem)] drop-shadow-md">
                        Learning Method
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Intro Text Section -->
    <div class="text-[#1F2937] flex justify-center flex-col w-[80%] h-[100%] mx-auto py-12">
        <div class="text-4xl font-bold tracking-tight text-gray-900">
            Learn Language Online
        </div>
        <div class="mt-4 text-[#6B7280] leading-relaxed text-lg font-normal">
            With this platform's courses, you can learn languages easily and free of
            charge. Whether you are a beginner or highly proficient, this is where
            you will find language courses. You can also learn with news
            or music - from level A1 to C1. For language teachers, there are
            teaching materials and the latest on language teaching resources.
        </div>
    </div>

    <!-- Course Levels Section (Services block) -->
    <div class="bg-blue-50/50 border-t border-b border-blue-100/50 py-12">
        <div class="w-[80%] h-[100%] mx-auto">
            <div class="text-[#1F2937] mb-8">
                <h2 class="font-bold text-3xl text-gray-900 tracking-tight">
                    Find the right course for you
                </h2>
                <p class="text-gray-500 mt-2 font-medium">Select your level to filter our curated language courses.</p>
            </div>
            
            <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-center">
                <!-- A1 | A2 Card -->
                <a href="{{ route('courses.index', ['level' => 'beginner']) }}" class="group bg-white border border-gray-100 relative rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-[230px] overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                    <div class="bg-blue-50 text-blue-600 rounded-2xl w-14 h-14 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414V7a1 1 0 00.293.707l5.414 5.414a1 1 0 001.414 0l1.414-1.414a1 1 0 000-1.414L10 4.828V3a1 1 0 00-1-1H7zm1 6V5.414L10.586 8H8zm-3 6a3 3 0 100-6 3 3 0 000 6zm9 3a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-left mt-4">
                        <h2 class="text-2xl font-bold text-gray-800">A1 | A2</h2>
                        <p class="text-sm text-gray-500 mt-1 font-medium group-hover:text-blue-600 transition-colors">Beginner Courses &rarr;</p>
                    </div>
                    <p class="text-gray-100 text-6xl font-black absolute bottom-2 right-4 group-hover:text-blue-50/50 transition-colors pointer-events-none select-none">01</p>
                </a>

                <!-- B1 | B2 Card -->
                <a href="{{ route('courses.index', ['level' => 'intermediate']) }}" class="group bg-white border border-gray-100 relative rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-[230px] overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                    <div class="bg-indigo-50 text-indigo-600 rounded-2xl w-14 h-14 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414V7a1 1 0 00.293.707l5.414 5.414a1 1 0 001.414 0l1.414-1.414a1 1 0 000-1.414L10 4.828V3a1 1 0 00-1-1H7zm1 6V5.414L10.586 8H8zm-3 6a3 3 0 100-6 3 3 0 000 6zm9 3a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-left mt-4">
                        <h2 class="text-2xl font-bold text-gray-800">B1 | B2</h2>
                        <p class="text-sm text-gray-500 mt-1 font-medium group-hover:text-indigo-600 transition-colors">Intermediate Courses &rarr;</p>
                    </div>
                    <p class="text-gray-100 text-6xl font-black absolute bottom-2 right-4 group-hover:text-indigo-50/50 transition-colors pointer-events-none select-none">02</p>
                </a>

                <!-- C1 | C2 Card -->
                <a href="{{ route('courses.index', ['level' => 'advanced']) }}" class="group bg-white border border-gray-100 relative rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-[230px] overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                    <div class="bg-purple-50 text-purple-600 rounded-2xl w-14 h-14 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414V7a1 1 0 00.293.707l5.414 5.414a1 1 0 001.414 0l1.414-1.414a1 1 0 000-1.414L10 4.828V3a1 1 0 00-1-1H7zm1 6V5.414L10.586 8H8zm-3 6a3 3 0 100-6 3 3 0 000 6zm9 3a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-left mt-4">
                        <h2 class="text-2xl font-bold text-gray-800">C1 | C2</h2>
                        <p class="text-sm text-gray-500 mt-1 font-medium group-hover:text-purple-600 transition-colors">Advanced Courses &rarr;</p>
                    </div>
                    <p class="text-gray-100 text-6xl font-black absolute bottom-2 right-4 group-hover:text-purple-50/50 transition-colors pointer-events-none select-none">03</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Science & Fun Benefits Section -->
    <div class="py-16 w-[80%] mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="text-left">
            <h2 class="font-extrabold text-3xl text-gray-900 tracking-tight">
                Fun and Effective
            </h2>
            <p class="mt-4 text-gray-500 leading-relaxed font-medium">
                Learning with us is fun! You will earn points and unlock new levels
                while gaining real-world communication skills.
            </p>
        </div>
        <div>
            <img
                src="/images/upscalemedia-transformed.png"
                alt="Benefit 1"
                class="w-full h-auto object-cover rounded-2xl shadow-lg border border-gray-150"
            />
        </div>
    </div>

    <div class="py-12 w-[80%] mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center bg-blue-50/60 border border-blue-100/50 px-8 rounded-3xl my-12 shadow-sm">
        <div class="order-2 md:order-1">
            <img
                src="/images/upscalemedia-transformed (1).png"
                alt="Benefit 2"
                class="w-full h-auto object-cover rounded-2xl shadow-lg border border-gray-150"
            />
        </div>
        <div class="order-1 md:order-2 text-left">
            <h2 class="font-extrabold text-3xl text-gray-900 tracking-tight">
                Backed by Science
            </h2>
            <p class="mt-4 text-gray-500 leading-relaxed font-medium">
                We use a combination of research-backed teaching methods and
                delightful content to create courses that effectively teach reading,
                writing, listening, and speaking skills!
            </p>
        </div>
    </div>
</div>
@endsection
