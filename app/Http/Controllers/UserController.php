<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');
        $response = Http::get("{$url}/users");
        $usersData = $response->json() ?? [];

        $users = collect($usersData)->map(function ($data) {
            $user = new User();
            $user->forceFill($data);
            return $user;
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'users' => $users
            ]);
        }

        return view('users', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced',
        ]);

        $url = env('BACKEND_API_URL', 'http://localhost:8000/api');

        // Check if email already exists
        $check = Http::get("{$url}/users/by-email", ['email' => $data['email']]);
        if ($check->successful()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => ['email' => ['The email has already been taken.']]
                ], 422);
            }
            return back()->withErrors(['email' => 'The email has already been taken.'])->withInput();
        }

        $response = Http::post("{$url}/users", [
            'name' => $data['name'],
            'email' => $data['email'],
            'level' => $data['level'],
        ]);

        if ($response->failed()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => ['error' => ['Failed to create user. Please try again.']]
                ], 422);
            }
            return back()->withErrors(['error' => 'Failed to create user. Please try again.'])->withInput();
        }

        if ($request->wantsJson()) {
            return response()->json(['redirect' => route('users.index')]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }
}
