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
        $user = Auth::user();

        // Admin-specific dashboard view
        // Admins are directed here by the AuthenticatedSessionController 
        // to manage the system via the admin.dashboard view.
        if ($user->role === 'admin') {
            return view('admin.dashboard', [
                'role' => 'admin'
            ]);
        }

        // Clerk/Member-specific dashboard view
        // Members land here after login, restricted to their own profile/membership details.
        // Ensure the 'memberships' relationship is defined in your User model.
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