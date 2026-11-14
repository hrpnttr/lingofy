<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Auth::provider('backend_students', function ($app, array $config) {
            return new class implements UserProvider
            {
                public function retrieveById($identifier)
                {
                    $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
                    $response = Controller::backendClient()->get("{$url}/students/{$identifier}");
                    if ($response->successful() && $response->json()) {
                        $data = $response->json();
                        // Deactivated accounts lose access on their next request.
                        if (array_key_exists('is_active', $data) && ! $data['is_active']) {
                            return null;
                        }
                        $student = new Student;
                        $student->forceFill($data);
                        $student->exists = true;

                        return $student;
                    }

                    return null;
                }

                public function retrieveByToken($identifier, $token)
                {
                    return null;
                }

                public function updateRememberToken(Authenticatable $user, $token) {}

                public function retrieveByCredentials(array $credentials)
                {
                    if (empty($credentials) || (count($credentials) === 1 && array_key_exists('password', $credentials))) {
                        return null;
                    }

                    $email = $credentials['email'] ?? null;
                    if (! $email) {
                        return null;
                    }

                    $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
                    $response = Controller::backendClient()->post("{$url}/students/by-email", ['email' => $email]);
                    if ($response->successful() && $response->json()) {
                        $data = $response->json();
                        $student = new Student;
                        $student->forceFill($data);
                        $student->exists = true;

                        return $student;
                    }

                    return null;
                }

                public function validateCredentials(Authenticatable $user, array $credentials)
                {
                    $email = $user->email;
                    $password = $credentials['password'];
                    $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
                    $response = Controller::backendClient()->post("{$url}/students/login", [
                        'email' => $email,
                        'password' => $password,
                    ]);
                    if ($response->status() === 403 && $response->json('code') === 'account_inactive') {
                        throw ValidationException::withMessages([
                            'error' => 'Your account is inactive. Please contact your teacher to reactivate it.',
                        ]);
                    }
                    if ($response->successful()) {
                        $data = $response->json();
                        if (isset($data['accessToken'])) {
                            session(['backend_access_token' => $data['accessToken']]);

                            return true;
                        }
                    }

                    return false;
                }

                public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
                {
                    return false;
                }
            };
        });
    }
}
