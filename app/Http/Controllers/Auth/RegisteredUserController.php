<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeMemberMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password made optional so admin-created accounts can auto-generate
        ]);

        // Generate a temporary secure password if none is explicitly provided
        $plainPassword = $request->password ?? Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
        ]);

        // Send the welcome email with the temporary password directly via Brevo SMTP
        Mail::to($user->email)->send(new WelcomeMemberMail($user, $plainPassword));

        event(new Registered($user));

        // If registered from public view, log them in. If created by admin panel, handle redirection accordingly.
        if (!Auth::check()) {
            Auth::login($user);
            return redirect(route('dashboard', absolute: false));
        }

        return redirect()->back()->with('success', 'Member registered successfully and temporary credentials sent via email.');
    }
}