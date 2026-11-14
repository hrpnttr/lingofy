<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Start a payment for a paid course.
     *
     * Integration point: when a provider is added, create the payment/session
     * here using the price fetched from the backend (never the client), return
     * its redirect URL as `payment_url`, and enroll the student only after the
     * provider confirms payment (webhook or return URL), not in this request.
     */
    public function store(Request $request, string $id): JsonResponse
    {
        if (! Auth::guard('student')->check()) {
            return response()->json([
                'message' => 'Please login first to enroll courses',
                'redirect' => route('login'),
            ], 401);
        }

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $courseRes = $this->backendApi()->get("{$url}/courses/{$id}");
        if ($courseRes->failed()) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $price = (float) ($courseRes->json('price') ?? 0);
        if ($price <= 0) {
            return response()->json(['message' => 'This course is free. Enroll directly.'], 422);
        }

        if (! config('services.payments.enabled')) {
            return response()->json([
                'code' => 'payments_unavailable',
                'message' => 'Online payment is not available yet. Please contact us to enroll in this course.',
            ], 503);
        }

        return response()->json([
            'code' => 'payments_not_configured',
            'message' => 'The payment provider is not configured yet.',
        ], 501);
    }
}
