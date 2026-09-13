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
            ->whereRaw('LOWER(donor_email) = ?', [strtolower($user->email)])
            ->latest()
            ->get();

        return view('donor.index', [
            'user' => $user,
            'donations' => $donations,
        ]);
    }
}
