<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class Controller
{
    protected function backendApi(): PendingRequest
    {
        return self::backendClient();
    }

    /**
     * HTTP client for lingofy-backend, carrying the tenant and the shared service token.
     */
    public static function backendClient(): PendingRequest
    {
        return Http::withHeaders([
            'X-Tenant-Slug' => env('TENANT_SLUG', 'lingofy'),
            'X-Service-Token' => (string) config('services.backend.token'),
        ])->timeout(15);
    }
}
