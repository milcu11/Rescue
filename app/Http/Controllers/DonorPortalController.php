<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Donation;

class DonorPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $donations = Donation::query()
            ->where('donor_email', $user->email)
            ->latest()
            ->get();

        return view('donor.index', [
            'user' => $user,
            'donations' => $donations,
        ]);
    }
}
