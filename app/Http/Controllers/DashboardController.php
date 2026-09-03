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
        // 1. Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 2. Admin-specific dashboard check
        if ($user->role === 'admin') {
            return view('admin.dashboard', [
                'role' => 'admin'
            ]);
        }

        // 3. Load the 'profile' relationship safely for members
        $user->load('profile'); 

        // 4. Fetch current membership record
        $currentMembership = $user->memberships()
            ->orderBy('membership_year', 'desc')
            ->first();

        // 5. Return view with profile passed explicitly to prevent errors
        return view('dashboard', [
            'role' => 'member',
            'user' => $user,
            'profile' => $user->profile ?? null, 
            'membership' => $currentMembership
        ]);
    }
}