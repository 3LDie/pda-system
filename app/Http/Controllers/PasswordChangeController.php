<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    public function showForm()
    {
        return view('auth.change-password'); // Ensure you create this blade file
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false; // Flag as changed
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Password updated successfully!');
    }
}