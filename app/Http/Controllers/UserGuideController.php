<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserGuideController extends Controller
{
    public function index()
    {
        return view('system.user-guide', [
            'role' => Auth::user()->role?->slug,
        ]);
    }
}
