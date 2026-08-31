<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\Auth::provider('backend_students', function ($app, array $config) {
            return new class implements \Illuminate\Contracts\Auth\UserProvider {
                public function retrieveById($identifier) {
                    $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
                    $response = \Illuminate\Support\Facades\Http::get("{$url}/students/{$identifier}");
                    if ($response->successful() && $response->json()) {
                        $data = $response->json();
                        $student = new \App\Models\Student();
                        $student->forceFill($data);
                        $student->exists = true;
                        return $student;
                    }
                    return null;
                }

                public function retrieveByToken($identifier, $token) {
                    return null;
                }

                public function updateRememberToken(\Illuminate\Contracts\Auth\Authenticatable $user, $token) {}

                public function retrieveByCredentials(array $credentials) {
                    if (empty($credentials) || (count($credentials) === 1 && array_key_exists('password', $credentials))) {
                        return null;
                    }
                    
                    $email = $credentials['email'] ?? null;
                    if (!$email) {
                        return null;
                    }

                    $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
                    $response = \Illuminate\Support\Facades\Http::post("{$url}/students/by-email", ['email' => $email]);
                    if ($response->successful() && $response->json()) {
                        $data = $response->json();
                        $student = new \App\Models\Student();
                        $student->forceFill($data);
                        $student->exists = true;
                        return $student;
                    }
                    return null;
                }

                public function validateCredentials(\Illuminate\Contracts\Auth\Authenticatable $user, array $credentials) {
                    $email = $user->email;
                    $password = $credentials['password'];
                    $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
                    $response = \Illuminate\Support\Facades\Http::post("{$url}/students/login", [
                        'email' => $email,
                        'password' => $password
                    ]);
                    if ($response->successful()) {
                        $data = $response->json();
                        if (isset($data['accessToken'])) {
                            session(['backend_access_token' => $data['accessToken']]);
                            return true;
                        }
                    }
                    return false;
                }
                
                public function rehashPasswordIfRequired(\Illuminate\Contracts\Auth\Authenticatable $user, array $credentials, bool $force = false) {
                    return false;
                }
            };
        });
    }
}
