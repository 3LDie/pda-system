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
        // 1. Fetch all dentists along with their logged membership statuses (ordered desc)
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

        // 📊 4. FIXED DYNAMIC ANALYTICS CALCULATIONS
        $currentYear = date('Y');
        $currentFiscalYear = $currentYear . '-' . substr($currentYear + 1, -2); // Generates "2026-27"
        
        $stats = [
            'total_dentists' => $dentists->count(),
            
            // Evaluates the entire collection to find active members for the current fiscal year
            'active_members' => $dentists->filter(function($dentist) use ($currentFiscalYear) {
                return $dentist->memberships->contains(function($membership) use ($currentFiscalYear) {
                    return $membership->membership_year === $currentFiscalYear && str_contains($membership->status, 'Active');
                });
            })->count(),

            // ✅ Fix: Counts ANY dentist who has an active 'Pending' action log across any year block
            'pending_members' => $dentists->filter(function($dentist) {
                return $dentist->memberships->contains('status', 'Pending');
            })->count(),
        ];

        // Inactive/Delinquent are those missing an Active or Pending record matching the current fiscal year completely
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
            'membership_year'  => 'required|string|max:50', 
            'payment_status'   => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create the Dentist Profile Row
            $dentist = DentistProfile::create([
                'full_name'      => $validated['full_name'],
                'prc_no'         => $validated['prc_no'],
                'date_of_birth'  => $validated['date_of_birth'],
                'contact_no'     => $validated['contact_no'],
                'email_address'  => $validated['email_address'],
                'home_address'   => $validated['home_address'],
                'clinic_address' => $validated['clinic_address'],
            ]);

            // 2. Create the associated structural Membership log row mapping to your real schema columns
            $dentist->memberships()->create([
                'membership_year' => $validated['membership_year'], 
                'status'          => $validated['payment_status'], 
            ]);

            DB::commit();

            return redirect()->route('dentists.index')
                             ->with('success', 'Dentist record successfully registered!');

        } catch (Exception $e) {
            DB::rollBack();

            // Logs the error message to your tracking console logs automatically
            logger($e->getMessage()); 

            return back()->withInput()
                         ->withErrors(['error' => 'Database failure: ' . $e->getMessage()]);
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

    /**
     * ✅ New Feature: Safely delete an individual membership record log row.
     */
    public function destroyMembership($id)
    {
        $membership = PdaMembership::findOrFail($id);
        $membership->delete();

        return back()->with('success', 'Membership log row successfully removed.');
    }

    /**
     * Export the filtered dentist registry to a CSV spreadsheet.
     */
    public function export(Request $request)
    {
        // 1. Re-use your index search logic so if they searched for something, only those results export!
        $query = DentistProfile::with(['memberships' => function($q) {
            $q->orderBy('membership_year', 'desc');
        }]);

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('full_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prc_no', 'LIKE', "%{$searchTerm}%");
            });
        }

        $dentists = $query->latest()->get();

        // 2. Define the download headers for a CSV file stream
        $fileName = 'pda_dentist_export_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // 3. Stream the raw database values straight to the browser link buffer
        $callback = function() use ($dentists) {
            $file = fopen('php://output', 'w');
            
            // Insert spreadsheet column headers
            fputcsv($file, ['Full Name', 'PRC Number', 'Contact No.', 'Email Address', 'Clinic Address', 'Latest Membership Status']);

            foreach ($dentists as $dentist) {
                $latestMembership = $dentist->memberships->first();
                $statusString = $latestMembership 
                    ? $latestMembership->membership_year . ' (' . $latestMembership->status . ')'
                    : 'No Logs';

                fputcsv($file, [
                    $dentist->full_name,
                    $dentist->prc_no,
                    $dentist->contact_no,
                    $dentist->email_address,
                    $dentist->clinic_address,
                    $statusString
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}