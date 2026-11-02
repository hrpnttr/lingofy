<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class Controller
{
    protected function backendApi(): PendingRequest
    {
        return Http::withHeaders([
            'X-Tenant-Slug' => env('TENANT_SLUG', 'lingofy'),
        ])->timeout(15);
    }
}
