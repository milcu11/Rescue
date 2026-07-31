<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DonorPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('donor.index', [
            'user' => $user,
        ]);
    }
}
