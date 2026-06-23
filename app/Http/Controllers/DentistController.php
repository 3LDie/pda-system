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
     * Display the dentist directory registry list with analytics.
     */
    public function index(Request $request)
    {
        // 1. Fetch all dentists along with their latest logged membership status
        $query = DentistProfile::with(['memberships' => function($query) {
            $query->orderBy('membership_year', 'desc'); 
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

        // 📊 4. DYNAMIC ANALYTICS CALCULATIONS (Current Fiscal Year Bracket)
        $currentYear = date('Y');
        $currentFiscalYear = $currentYear . '-' . substr($currentYear + 1, -2); // Generates "2026-27"
        
        $stats = [
            'total_dentists' => $dentists->count(),
            
            'active_members' => $dentists->filter(function($dentist) use ($currentFiscalYear) {
                $latest = $dentist->memberships->first(); // Ordered desc, so first is the latest year
                return $latest && $latest->membership_year === $currentFiscalYear && str_contains($latest->status, 'Active');
            })->count(),

            'pending_members' => $dentists->filter(function($dentist) use ($currentFiscalYear) {
                $latest = $dentist->memberships->first();
                return $latest && $latest->membership_year === $currentFiscalYear && $latest->status === 'Pending';
            })->count(),
        ];

        // Inactive/Delinquent are those who are unpaid, pending past years, or completely missing a current bracket log
        $stats['inactive_members'] = $stats['total_dentists'] - ($stats['active_members'] + $stats['pending_members']);

        // 5. Pass the collections and stats metrics straight into your blade view
        return view('dentists.index', compact('dentists', 'stats'));
    }

    /**
     * Show the form for registering a new dentist.
     */
    public function create()
    {
        return view('dentists.create');
    }

    /**
     * Store a newly created dentist profile and membership log in storage.
     */
    public function store(Request $request)
    {
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

        DB::beginTransaction();

        try {
            $dentist = DentistProfile::create([
                'full_name'      => $validated['full_name'],
                'prc_no'         => $validated['prc_no'],
                'date_of_birth'  => $validated['date_of_birth'],
                'contact_no'     => $validated['contact_no'],
                'email_address'  => $validated['email_address'],
                'home_address'   => $validated['home_address'],
                'clinic_address' => $validated['clinic_address'],
            ]);

            // ⚠️ Keeping this as membership_year_bracket if your setup treats insertion differently during standard creation
            $dentist->memberships()->create([
                'membership_year_bracket' => $validated['membership_year'],
                'payment_status'          => $validated['payment_status'],
            ]);

            DB::commit();

            return redirect()->route('dentists.index')
                             ->with('success', 'Dentist record successfully registered!');

        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()
                         ->withErrors(['error' => 'Database failure: Could not register record.']);
        }
    }

    /**
     * Show the form for editing an existing dentist profile.
     */
    public function edit($id)
    {
        $dentist = DentistProfile::findOrFail($id);
        
        return view('dentists.edit', compact('dentist'));
    }

    /**
     * Update the specified dentist profile in storage.
     */
    public function update(Request $request, $id)
    {
        $dentist = DentistProfile::findOrFail($id);

        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'prc_no'         => 'required|string|max:15|unique:dentist_profiles,prc_no,' . $dentist->id,
            'date_of_birth'  => 'required|date|before:today',
            'contact_no'     => 'required|string|max:20',
            'email_address'  => 'required|email|max:255',
            'home_address'   => 'required|string',
            'clinic_address' => 'required|string',
        ]);

        $dentist->update($validated);

        return redirect()->route('dentists.index')
                         ->with('success', 'Dentist profile updated successfully!');
    }

    /**
     * Show the form for renewing a dentist's membership.
     */
    public function renew($id)
    {
        $dentist = DentistProfile::findOrFail($id);
        
        return view('dentists.renew', compact('dentist'));
    }

    /**
     * Store a newly created membership log row in storage.
     */
    public function storeRenewal(Request $request, $id)
    {
        $dentist = DentistProfile::findOrFail($id);

        $validated = $request->validate([
            'membership_year' => 'required|string|max:20',
            'payment_status'  => 'required|string|max:50',
        ]);

        // 🛠️ Maps form data directly to true columns 'membership_year' and 'status'
        $dentist->memberships()->create([
            'membership_year' => $validated['membership_year'],
            'status'          => $validated['payment_status'],
        ]);

        return redirect()->route('dentists.index')
            ->with('success', 'Membership year successfully logged for ' . $dentist->full_name);
    }
}