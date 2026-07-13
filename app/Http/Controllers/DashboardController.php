<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // If an admin accidentally wanders here, let them see a general welcome or stats
        if ($user->role === 'admin') {
            return view('dashboard', [
                'role' => 'admin'
            ]);
        }

        // Fetch the member's current active membership year log (e.g., 2026-27)
        // Adjust the relationship name ('memberships') if it's named differently on your User model
        $currentMembership = $user->memberships()
            ->orderBy('membership_year', 'desc')
            ->first();

        return view('dashboard', [
            'role' => 'member',
            'user' => $user,
            'membership' => $currentMembership
        ]);
    }
}