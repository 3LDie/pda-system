<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'extension' => ['nullable', 'string', 'max:50'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Automatically convert middle name to middle initial and format name (Surname-first)
        $rawName = trim($request->full_name);
        $parts = array_map('trim', explode(',', $rawName));
        
        $formattedName = $rawName; 
        if (count($parts) >= 2) {
            $surname = $parts[0];
            $rest = array_values(array_filter(explode(' ', $parts[1])));
            $firstName = $rest[0] ?? '';
            $middleName = $rest[1] ?? '';

            $formattedName = $surname . ', ' . $firstName;
            if (!empty($middleName)) {
                $initial = strtoupper(substr($middleName, 0, 1)) . '.';
                $formattedName .= ' ' . $initial;
            }
        }

        if (!empty($request->extension)) {
            $formattedName .= ' ' . trim($request->extension);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $formattedName,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'member',
            ]);

            // Create a matching profile entry for the new member
            DB::table('dentist_profiles')->insert([
                'user_id'       => $user->id,
                'full_name'     => $formattedName,
                'email_address' => $request->email,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::commit();

            event(new Registered($user));

            auth()->login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}