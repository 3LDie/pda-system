<?php

namespace App\Http\Controllers;

use App\Models\DentistProfile;
use App\Models\PdaMembership; // Added this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Added this line

class DentistController extends Controller
{
    public function index()
    {
        // Fetch all dentists from the database along with their membership history
        $dentists = DentistProfile::with('memberships')->get();

        // Pass the data to the frontend view file
        return view('dentists.index', compact('dentists'));
    }

    // 1. Display the creation form view
    public function create()
    {
        return view('dentists.create');
    }

    // 2. Handle the incoming form data submission
    public function store(Request $request)
    {
        // Validate the incoming inputs strictly
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'prc_no' => 'required|string|unique:dentist_profiles,prc_no|max:50',
            'date_of_birth' => 'required|date',
            'home_address' => 'required|string',
            'clinic_address' => 'required|string',
            'email_address' => 'required|email|unique:dentist_profiles,email_address|max:255',
            'contact_no' => 'required|string|max:20',
            'membership_year' => 'required|string|max:10',
            'status' => 'required|string|in:Active,Pending',
        ]);

        // Use a Database Transaction to guarantee both records save together, or neither does
        DB::transaction(function () use ($validated) {
            // Create the primary dentist profile row
            $dentist = DentistProfile::create([
                'full_name' => $validated['full_name'],
                'prc_no' => $validated['prc_no'],
                'date_of_birth' => $validated['date_of_birth'],
                'home_address' => $validated['home_address'],
                'clinic_address' => $validated['clinic_address'],
                'email_address' => $validated['email_address'],
                'contact_no' => $validated['contact_no'],
            ]);

            // Create the linked membership row using the new dentist ID
            $dentist->memberships()->create([
                'membership_year' => $validated['membership_year'],
                'status' => $validated['status'],
            ]);
        });

        // Send them back to our directory with a success alert banner
        return redirect()->route('dentists.index')->with('success', 'Dentist successfully registered!');
    }
}