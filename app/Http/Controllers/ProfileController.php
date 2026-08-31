<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        if (!Auth::guard('student')->check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        $student = Auth::guard('student')->user();
        
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $scourseRes = Http::get("{$url}/scourses/{$student->id}");
        $enrolledContents = [];
        if ($scourseRes->successful() && $scourseRes->json()) {
            $enrolledContents = $scourseRes->json()['contents'] ?? [];
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'student' => $student,
                'enrolledContents' => $enrolledContents
            ]);
        }

        return view('profile', compact('student', 'enrolledContents'));
    }
}
