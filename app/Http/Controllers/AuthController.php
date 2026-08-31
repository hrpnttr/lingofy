<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('profile');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();
            if ($request->wantsJson()) {
                return response()->json(['redirect' => route('profile')]);
            }
            return redirect()->route('profile');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'errors' => ['error' => ['Login failed. Please check your credentials and try again.']]
            ], 422);
        }

        return back()->withErrors([
            'error' => 'Login failed. Please check your credentials and try again.',
        ])->withInput($request->only('email'));
    }

    public function showRegisterForm()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('profile');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');

        // Check if email already exists
        $check = Http::post("{$url}/students/by-email", ['email' => $data['email']]);
        if ($check->successful()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => ['email' => ['The email has already been taken.']]
                ], 422);
            }
            return back()->withErrors(['email' => 'The email has already been taken.'])->withInput();
        }

        $response = Http::post("{$url}/students", [
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if ($response->failed()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => ['error' => ['Registration failed. Please try again.']]
                ], 422);
            }
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }

        $studentData = $response->json();
        $student = new Student();
        $student->forceFill($studentData);
        $student->exists = true;

        // Perform login to get token
        $loginRes = Http::post("{$url}/students/login", [
            'email' => $data['email'],
            'password' => $data['password']
        ]);
        if ($loginRes->successful() && isset($loginRes->json()['accessToken'])) {
            session(['backend_access_token' => $loginRes->json()['accessToken']]);
        }

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json(['redirect' => route('profile')]);
        }

        return redirect()->route('profile');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = Http::post("{$url}/students/forgot-password", ['email' => $request->email]);

        if ($response->successful()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', true);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'errors' => ['error' => ['Email not found. Please try again.']]
            ], 422);
        }

        return back()->withErrors(['error' => 'Email not found. Please try again.']);
    }

    public function showResetPasswordForm()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = Http::post("{$url}/students/reset-password", [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            if ($request->wantsJson()) {
                return response()->json(['redirect' => route('login'), 'status' => 'Password reset successfully!']);
            }
            return redirect()->route('login')->with('status', 'Password reset successfully!');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'errors' => ['email' => ['Email not found.']]
            ], 422);
        }

        return back()->withErrors(['email' => 'Email not found.']);
    }
}
