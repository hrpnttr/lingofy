<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::guard('student')->check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return view('schedule');
    }
}
