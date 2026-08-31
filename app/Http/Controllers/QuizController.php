<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class QuizController extends Controller
{
    public function placementTests(Request $request)
    {
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = Http::get("{$url}/quizzes", ['size' => 100]);
        $quizzesData = $response->json()['content'] ?? [];

        $quizzes = collect($quizzesData)
            ->filter(fn($q) => is_null($q['class_id']))
            ->map(function ($qData) {
                $quiz = new Quiz();
                $quiz->forceFill($qData);
                return $quiz;
            });

        // Handle showing a specific test detail based on query params
        $seq = $request->query('seq', null);
        $previewId = $request->query('id', null);
        
        $activeTest = null;
        if ($previewId !== null) {
            $testData = collect($quizzesData)->first(fn($q) => (string)($q['id'] ?? '') === (string)$previewId);
            if ($testData) {
                $activeTest = new Quiz();
                $activeTest->forceFill($testData);
            }
        }

        return view('placement_test', compact('quizzes', 'seq', 'activeTest'));
    }

    public function started(Request $request)
    {
        $classId = $request->query('id');
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = Http::get("{$url}/quizzes", ['size' => 100]);
        $quizzesData = $response->json()['content'] ?? [];

        $quizData = collect($quizzesData)
            ->first(fn($q) => (string)($q['class_id'] ?? '') === (string)$classId);

        $quiz = null;
        if ($quizData) {
            $quiz = new Quiz();
            $quiz->forceFill($quizData);
        }

        return view('started', compact('quiz'));
    }

    public function show($id)
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login');
        }

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = Http::get("{$url}/quizzes/{$id}");
        if ($response->failed()) {
            abort(404);
        }

        $quiz = new Quiz();
        $quiz->forceFill($response->json());

        return view('quiz_show', compact('quiz'));
    }

    public function submit(Request $request, $id)
    {
        if (!Auth::guard('student')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $studentId = Auth::guard('student')->id();

        $request->validate([
            'score' => 'required|integer',
            'answers' => 'required|array',
        ]);

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = Http::post("{$url}/add-quizzes", [
            'studentId' => (string) $studentId,
            'quiz_id' => (string) $id,
            'answers' => $request->input('answers'),
            'score' => $request->input('score'),
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to submit quiz result.'], 500);
        }

        $resultData = $response->json();
        return response()->json(['success' => true, 'result_id' => $resultData['id'] ?? null]);
    }
}
