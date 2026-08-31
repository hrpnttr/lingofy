<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function show()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login');
        }

        $student = Auth::guard('student')->user();
        
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $scourseRes = Http::get("{$url}/scourses/{$student->id}");
        $enrolledContents = [];
        if ($scourseRes->successful() && $scourseRes->json()) {
            $enrolledContents = $scourseRes->json()['contents'] ?? [];
        }

        return view('profile', compact('student', 'enrolledContents'));
    }
}
