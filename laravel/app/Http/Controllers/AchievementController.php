<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $achievements = $request->user()
                                ->achievements()
                                ->with('achievement')
                                ->get();

        return response()->json($achievements);
    }
}
