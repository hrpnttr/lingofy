<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Support\QuizSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function placementTests(Request $request)
    {
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = $this->backendApi()->get("{$url}/quizzes", ['size' => 100]);
        $quizzesData = $response->json()['content'] ?? [];

        $quizzes = collect($quizzesData)
            ->filter(fn ($q) => is_null($q['class_id']))
            ->map(fn ($qData) => $this->studentQuiz($qData));

        // Handle showing a specific test detail based on query params
        $seq = $request->query('seq', null);
        $previewId = $request->query('id', null);

        $activeTest = null;
        if ($previewId !== null) {
            $testData = collect($quizzesData)->first(fn ($q) => (string) ($q['id'] ?? '') === (string) $previewId);
            if ($testData) {
                $activeTest = $this->studentQuiz($testData);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'quizzes' => $quizzes,
                'activeTest' => $activeTest,
            ]);
        }

        return view('placement_test', compact('quizzes', 'seq', 'activeTest'));
    }

    public function started(Request $request)
    {
        $classId = $request->query('id');
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = $this->backendApi()->get("{$url}/quizzes", ['size' => 100]);
        $quizzesData = $response->json()['content'] ?? [];

        $quizData = collect($quizzesData)
            ->first(fn ($q) => (string) ($q['class_id'] ?? '') === (string) $classId);

        $quiz = null;
        $submission = null;
        if ($quizData) {
            $quiz = $this->studentQuiz($quizData);
            $submission = $this->lockedSubmission($quiz->id);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'quiz' => $quiz,
                'submission' => $submission,
            ]);
        }

        return view('started', compact('quiz', 'submission'));
    }

    public function show(Request $request, $id)
    {
        if (! Auth::guard('student')->check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return redirect()->route('login');
        }

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = $this->backendApi()->get("{$url}/quizzes/{$id}");
        if ($response->failed()) {
            abort(404);
        }

        $quiz = $this->studentQuiz($response->json() ?? []);
        $submission = $this->lockedSubmission($quiz->id);

        if ($submission !== null) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'You have already submitted this quiz.',
                    'submission' => $submission,
                ], 409);
            }

            return view('quiz_submitted', compact('quiz', 'submission'));
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'quiz' => $quiz,
            ]);
        }

        return view('quiz_show', compact('quiz'));
    }

    public function submit(Request $request, $id)
    {
        if (! Auth::guard('student')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $studentId = Auth::guard('student')->id();

        $request->validate([
            'responses' => 'present|array|max:500',
            'responses.*' => 'array',
            'responses.*.index' => 'required|integer|min:0',
            'responses.*.value' => [
                'present',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value === null || is_string($value) || is_int($value) || is_float($value)) {
                        if (is_string($value) && mb_strlen($value) > 20000) {
                            $fail('The answer is too long.');
                        }

                        return;
                    }
                    if (is_array($value) && array_is_list($value)) {
                        foreach ($value as $item) {
                            if (! is_int($item) || $item < 0) {
                                $fail('Selected choices must be a list of choice numbers.');

                                return;
                            }
                        }

                        return;
                    }
                    $fail('The answer has an invalid format.');
                },
            ],
        ]);

        $responses = array_map(fn ($r) => [
            'index' => (int) $r['index'],
            'value' => $r['value'] ?? null,
        ], array_values($request->input('responses', [])));

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = $this->backendApi()->post("{$url}/add-quizzes", [
            'studentId' => (string) $studentId,
            'quiz_id' => (string) $id,
            'responses' => $responses,
        ]);

        if ($response->status() === 409) {
            return response()->json([
                'error' => $response->json('message') ?? 'You have already submitted this quiz.',
                'code' => 'quiz_already_submitted',
            ], 409);
        }

        if ($response->status() === 422 || $response->status() === 404) {
            $body = $response->json() ?? [];

            return response()->json([
                'error' => $body['message'] ?? 'Your answers could not be accepted.',
                'errors' => $body['errors'] ?? [],
            ], $response->status());
        }

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to submit quiz result.'], 500);
        }

        $resultData = $response->json() ?? [];

        return response()->json([
            'success' => true,
            'result_id' => $resultData['id'] ?? null,
            'score' => $resultData['score'] ?? null,
            'points_earned' => $resultData['points_earned'] ?? null,
            'points_max' => $resultData['points_max'] ?? null,
            'pending_review' => (bool) ($resultData['pending_review'] ?? false),
        ]);
    }

    /**
     * The student's submission that blocks another attempt (class quizzes only), or null.
     */
    private function lockedSubmission(mixed $quizId): ?array
    {
        $studentId = Auth::guard('student')->id();
        if ($studentId === null || $quizId === null) {
            return null;
        }

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = $this->backendApi()->get("{$url}/add-quizzes/attempt-status", [
            'studentId' => (string) $studentId,
            'quiz_id' => (string) $quizId,
        ]);

        if (! $response->successful() || ! $response->json('submitted')) {
            return null;
        }

        return $response->json('result') ?? [];
    }

    /**
     * Build a Quiz for student-facing output with answer keys removed.
     */
    private function studentQuiz(array $data): Quiz
    {
        $data['contens'] = QuizSanitizer::contents($data['contens'] ?? []);

        $quiz = new Quiz;
        $quiz->forceFill($data);

        return $quiz;
    }
}
