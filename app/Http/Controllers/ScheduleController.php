<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login');
        }

        return view('schedule');
    }
}
