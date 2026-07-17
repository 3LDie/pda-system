<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\PdaMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use App\Traits\LogsActivity;
use Exception;

class DentistController extends Controller
{
    use LogsActivity; 

    // ... [keep index, create methods as they are] ...

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'profile_image'  => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'prc_no'         => 'required|string|max:15|unique:dentist_profiles,prc_no', 
            'date_of_birth'  => 'required|date|before:today',
            'contact_no'     => 'required|string|max:20',
            'email_address'  => 'required|email|max:255|unique:users,email', 
            'home_address'   => 'required|string',
            'clinic_address' => 'required|string',
            'membership_year'=> 'required|string|max:50', 
            'payment_status' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = $request->hasFile('profile_image') ? $request->file('profile_image')->store('profile_images', 'public') : null;
            $temporaryPassword = Str::random(10);
            
            $dentist = User::create([
                'name'     => $validated['full_name'], 
                'email'    => $validated['email_address'],
                'password' => Hash::make($temporaryPassword), 
                'role'     => 'member', 
            ]);

            $profileId = DB::table('dentist_profiles')->insertGetId([
                'user_id'        => $dentist->id,
                'full_name'      => $validated['full_name'],
                'email_address'  => $validated['email_address'],
                'profile_image'  => $imagePath,
                'prc_no'         => $validated['prc_no'],
                'date_of_birth'  => $validated['date_of_birth'],
                'contact_no'     => $validated['contact_no'],
                'home_address'   => $validated['home_address'],
                'clinic_address' => $validated['clinic_address'],
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            DB::table('pda_memberships')->insert([
                'dentist_profile_id' => $profileId, 
                'membership_year'    => $validated['membership_year'], 
                'status'             => $validated['payment_status'], 
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            DB::commit();
            return redirect()->route('dentists.index')->with('success', 'Member record successfully saved!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Database failure: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $dentist = User::where('role', 'member')->findOrFail($id);
        $existingProfile = DB::table('dentist_profiles')->where('user_id', $dentist->id)->first();
        $profileId = $existingProfile ? $existingProfile->id : $id;

        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'profile_image'  => 'nullable|image|mimes:jpeg,png,jpg|max:10240', 
            'prc_no'         => 'required|string|max:15|unique:dentist_profiles,prc_no,' . $profileId, 
            'date_of_birth'  => 'required|date|before:today',
            'contact_no'     => 'required|string|max:20',
            'email_address'  => 'required|email|max:255|unique:users,email,' . $dentist->id, 
            'home_address'   => 'required|string',
            'clinic_address' => 'required|string',
            'membership_year'=> 'sometimes|required|string|max:50',
            'payment_status' => 'sometimes|required|string',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($existingProfile && $existingProfile->profile_image && Storage::disk('public')->exists($existingProfile->profile_image)) {
                Storage::disk('public')->delete($existingProfile->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $dentist->update(['name' => $validated['full_name'], 'email' => $validated['email_address']]);

        DB::table('dentist_profiles')->where('id', $profileId)->update([
            'full_name'      => $validated['full_name'],
            'email_address'  => $validated['email_address'], 
            'profile_image'  => $validated['profile_image'] ?? ($existingProfile->profile_image ?? null),
            'prc_no'         => $validated['prc_no'],
            'date_of_birth'  => $validated['date_of_birth'],
            'contact_no'     => $validated['contact_no'],
            'home_address'   => $validated['home_address'],
            'clinic_address' => $validated['clinic_address'],
            'updated_at'     => now(),
        ]);

        // Updated to sync membership details
        DB::table('pda_memberships')
            ->where('dentist_profile_id', $profileId)
            ->update([
                'membership_year' => $validated['membership_year'],
                'status'          => $validated['payment_status'],
                'updated_at'      => now(),
            ]);

        return redirect()->route('dentists.index')->with('success', 'Dentist profile updated successfully!');
    }
}