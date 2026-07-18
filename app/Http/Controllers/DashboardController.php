<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view based on user role.
     */
    public function index()
    {
        // 1. Get the authenticated user and load the 'profile' relationship
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $user->load('profile'); 

        // 2. Admin-specific dashboard
        if ($user->role === 'admin') {
            return view('admin.dashboard', [
                'role' => 'admin'
            ]);
        }

        // 3. Fetch current membership record
        $currentMembership = $user->memberships()
            ->orderBy('membership_year', 'desc')
            ->first();

        // 4. Return view with 'profile' passed explicitly
        // Using the null-coalescing operator (?? null) ensures the variable 
        // is always defined, preventing the "Undefined variable" error.
        return view('dashboard', [
            'role' => 'member',
            'user' => $user,
            'profile' => $user->profile ?? null, 
            'membership' => $currentMembership
        ]);
    }
}