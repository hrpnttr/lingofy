<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Scourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseController extends Controller
{
    public function home()
    {
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = Http::get("{$url}/courses", ['size' => 100]);
        $coursesData = $response->json()['content'] ?? [];

        $courses = collect($coursesData)
            ->filter(fn($c) => $c['published'] ?? false)
            ->take(3)
            ->map(function ($cData) {
                $cData['duration_hours'] = $cData['durationHours'] ?? 0;
                $course = new Course();
                $course->forceFill($cData);
                return $course;
            });

        return view('home', compact('courses'));
    }

    public function index(Request $request)
    {
        $page = (int) $request->input('page', 1);
        $backendPage = $page - 1;
        $size = 10;

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        
        $params = [
            'page' => $backendPage,
            'size' => $size,
        ];
        if ($request->filled('q')) {
            $params['q'] = $request->input('q');
        }
        if ($request->filled('level')) {
            $params['level'] = $request->input('level');
        }
        if ($request->filled('language')) {
            $params['language'] = $request->input('language');
        }

        $response = Http::get("{$url}/courses", $params);

        $data = $response->json();
        $items = collect($data['content'] ?? [])->map(function ($itemData) {
            $itemData['duration_hours'] = $itemData['durationHours'] ?? 0;
            $course = new Course();
            $course->forceFill($itemData);
            return $course;
        });

        $filteredItems = $items->filter(fn($c) => $c->published);

        $courses = new LengthAwarePaginator(
            $filteredItems,
            $data['totalElements'] ?? 0,
            $size,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('courses', compact('courses'));
    }

    public function enroll(Request $request, $id)
    {
        if (!Auth::guard('student')->check()) {
            if ($request->wantsJson()) {
                return response()->json(['redirect' => route('login')], 401);
            }
            return redirect()->route('login');
        }

        $studentId = Auth::guard('student')->id();
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');

        // Fetch course from backend
        $courseRes = Http::get("{$url}/courses/{$id}");
        if ($courseRes->failed()) {
            abort(404);
        }
        $courseData = $courseRes->json();

        // Call enroll (POST api/scourses)
        $enrollRes = Http::post("{$url}/scourses", [
            'studentId' => (string) $studentId,
            'contents' => [
                [
                    'courseId' => (string) $id,
                    'title' => $courseData['title'] ?? '',
                ]
            ]
        ]);

        // 409 means already enrolled, which is fine, we just proceed
        if ($enrollRes->failed() && $enrollRes->status() !== 409) {
            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => ['error' => ['Failed to enroll course. Please try again.']]
                ], 422);
            }
            return back()->withErrors(['error' => 'Failed to enroll course. Please try again.']);
        }

        if ($request->wantsJson()) {
            return response()->json(['redirect' => route('my-courses')]);
        }

        return redirect()->route('my-courses')->with('success', 'Course has been enrolled successfully!');
    }

    public function myCourses()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login');
        }

        $studentId = Auth::guard('student')->id();
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');

        // Get enrolled courses
        $scourseRes = Http::get("{$url}/scourses/{$studentId}");
        $enrolledContents = [];
        if ($scourseRes->successful() && $scourseRes->json()) {
            $enrolledContents = $scourseRes->json()['contents'] ?? [];
        }

        $enrolledCourseIds = collect($enrolledContents)->pluck('courseId')->toArray();

        // Fetch all classes
        $classesRes = Http::get("{$url}/classes", ['size' => 100]);
        $classesData = $classesRes->json()['content'] ?? [];

        // Filter and map to SchoolClass models
        $classes = collect($classesData)
            ->filter(function ($cData) use ($enrolledCourseIds) {
                return in_array((string)($cData['course_id'] ?? ''), $enrolledCourseIds);
            })
            ->map(function ($cData) {
                $class = new SchoolClass();
                $class->forceFill($cData);
                return $class;
            });

        return view('my_courses', compact('enrolledContents', 'classes'));
    }
}
