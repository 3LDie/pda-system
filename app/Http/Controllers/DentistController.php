<?php

namespace App\Http\Controllers;

use App\Models\DentistProfile;
use App\Models\PdaMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class DentistController extends Controller
{
    /**
     * Display the dentist directory registry list.
     */
    public function index(Request $request)
    {
        // 1. Fetch all dentists along with their latest logged membership status
        $query = DentistProfile::with(['memberships' => function($query) {
            $query->orderBy('membership_year', 'desc'); // Fixed column name from error
        }]);

        // 2. Add backend support for the search bar filtering
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('full_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prc_no', 'LIKE', "%{$searchTerm}%");
            });
        }

        // 3. Get records ordered by latest creation
        $dentists = $query->latest()->get();

        // 4. Pass the collections straight into your blade view
        return view('dentists.index', compact('dentists'));
    }

    /**
     * Show the form for registering a new dentist.
     */
    public function create()
    {
        // Added missing method to resolve Call to Undefined Method error
        return view('dentists.create');
    }

    /**
     * Store a newly created dentist profile and membership log in storage.
     */
    public function store(Request $request)
    {
        // 1. Enforce strict data rules (Stops massive inputs and duplicates)
        $validated = $request->validate([
            'full_name'        => 'required|string|max:255',
            'prc_no'           => 'required|string|max:15|unique:dentist_profiles,prc_no',
            'date_of_birth'    => 'required|date|before:today',
            'contact_no'       => 'required|string|max:20',
            'email_address'    => 'required|email|max:255',
            'home_address'     => 'required|string',
            'clinic_address'   => 'required|string',
            'membership_year'  => 'required|integer|min:1900|max:' . date('Y'),
            'payment_status'   => 'required|string|in:Active (Paid),Inactive (Unpaid)',
        ]);

        // 2. Transactional Database Safety Net
        DB::beginTransaction();

        try {
            // Step A: Save the Core Profile
            $dentist = DentistProfile::create([
                'full_name'      => $validated['full_name'],
                'prc_no'         => $validated['prc_no'],
                'date_of_birth'  => $validated['date_of_birth'],
                'contact_no'     => $validated['contact_no'],
                'email_address'  => $validated['email_address'],
                'home_address'   => $validated['home_address'],
                'clinic_address' => $validated['clinic_address'],
            ]);

            // Step B: Link the Initial Membership Log
            $dentist->memberships()->create([
                'membership_year_bracket' => $validated['membership_year'],
                'payment_status'          => $validated['payment_status'],
            ]);

            // Commit changes if everything succeeded
            DB::commit();

            return redirect()->route('dentists.index')
                             ->with('success', 'Dentist record successfully registered!');

        } catch (Exception $e) {
            // Roll back the entire operation if anything crashes midway
            DB::rollBack();

            return back()->withInput()
                         ->withErrors(['error' => 'Database failure: Could not register record.']);
        }
    }
}