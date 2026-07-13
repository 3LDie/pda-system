<?php

namespace App\Http\Controllers;

use App\Models\DentistProfile;
use App\Models\PdaMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity;
use Exception;

class DentistController extends Controller
{
    use LogsActivity; 

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

        // 📊 4. DYNAMIC ANALYTICS CALCULATIONS (Current Fiscal Year Bracket)
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

            // Counts ANY dentist who has an active 'Pending' action log across any year block
            'pending_members' => $dentists->filter(function($dentist) {
                return $dentist->memberships->contains('status', 'Pending');
            })->count(),
        ];

        // Inactive members are those who are NEITHER active NOR pending for the current fiscal year track
        $stats['inactive_members'] = $dentists->filter(function($dentist) use ($currentFiscalYear) {
            $hasActiveOrPending = $dentist->memberships->contains(function($membership) use ($currentFiscalYear) {
                return $membership->membership_year === $currentFiscalYear && 
                       (str_contains($membership->status, 'Active') || $membership->status === 'Pending');
            });
            return !$hasActiveOrPending;
        })->count();

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
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
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

        $imagePath = null;

        try {
            // 📸 Handle file upload logic if a binary image asset is attached
            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            }

            // 1. Create the Dentist Profile Row
            $dentist = DentistProfile::create([
                'full_name'      => $validated['full_name'],
                'profile_image'  => $imagePath, // Assigning the local upload file path string
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

            // Log Registration Event
            $this->logAction('REGISTER', 'DentistProfile', "Registered a new profile row for {$dentist->full_name} (PRC: {$dentist->prc_no})");

            return redirect()->route('dentists.index')
                             ->with('success', 'Dentist record successfully registered!');

        } catch (Exception $e) {
            DB::rollBack();

            // Clean up stray uploaded file asset if database transaction fails
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

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
            'profile_image'  => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // ✅ Allows files up to 10MB
            'prc_no'         => 'required|string|max:15|unique:dentist_profiles,prc_no,' . $dentist->id,
            'date_of_birth'  => 'required|date|before:today',
            'contact_no'     => 'required|string|max:20',
            'email_address'  => 'required|email|max:255',
            'home_address'   => 'required|string',
            'clinic_address' => 'required|string',
        ]);

        // Manage photo asset replacements safely
        if ($request->hasFile('profile_image')) {
            // Delete old avatar from the public disk storage footprint if one exists
            if ($dentist->profile_image && Storage::disk('public')->exists($dentist->profile_image)) {
                Storage::disk('public')->delete($dentist->profile_image);
            }

            // Store new resource asset element path parameters downstream
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $dentist->update($validated);

        // Log Update Event
        $this->logAction('UPDATE', 'DentistProfile', "Updated personal profile records for {$dentist->full_name} (PRC: {$dentist->prc_no})");

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

        // Maps form data directly to true columns 'membership_year' and 'status'
        $dentist->memberships()->create([
            'membership_year' => $validated['membership_year'],
            'status'          => $validated['payment_status'],
        ]);

        // Log Renewal Event
        $this->logAction('RENEW', 'PdaMembership', "Logged a new fiscal year renewal bracket [{$validated['membership_year']}] with status [{$validated['payment_status']}] for {$dentist->full_name}");

        return redirect()->route('dentists.index')
            ->with('success', 'Membership year successfully logged for ' . $dentist->full_name);
    }

    /**
     * Safely delete an individual membership record log row.
     */
    public function destroyMembership($id)
    {
        $membership = PdaMembership::findOrFail($id);
        $membership->delete();

        // Log Deletion Event
        $this->logAction('DELETE', 'PdaMembership', "Permanently removed membership year log row entry ID: [{$id}] for bracket [{$membership->membership_year}]");

        return back()->with('success', 'Membership log row successfully removed.');
    }

    /**
     * Export the filtered dentist registry to a CSV spreadsheet with deep history tracking.
     */
    public function export(Request $request)
    {
        // 1. Re-use index search logic so if they searched for something, only those results export!
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
        $fileName = 'pda_dentist_deep_export_' . date('Y-m-d') . '.csv';
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
            
            // Insert advanced spreadsheet column headers (added deep metrics)
            fputcsv($file, [
                'Full Name', 
                'PRC Number', 
                'Contact No.', 
                'Email Address', 
                'Clinic Address', 
                'Latest Membership Status',
                'Sustaining Fee Status (Current Fiscal Yr)',
                'Complete Historical Logs (Year: Status)'
            ]);

            // Calculate current fiscal bracket parameters dynamically matching index logic
            $currentYear = date('Y');
            $currentFiscalYear = $currentYear . '-' . substr($currentYear + 1, -2); // Generates "2026-27"

            foreach ($dentists as $dentist) {
                // Find latest single log row entry standard metric
                $latestMembership = $dentist->memberships->first();
                $statusString = $latestMembership 
                    ? $latestMembership->membership_year . ' (' . $latestMembership->status . ')'
                    : 'No Logs';

                // Look up sustaining fee target record status block for current fiscal cycle 
                $currentFeeRecord = $dentist->memberships->where('membership_year', $currentFiscalYear)->first();
                $sustainingFeeStatus = $currentFeeRecord ? $currentFeeRecord->status : 'No Log for Current Year';

                // Flatten all structural historical year bracket elements into a clean text line string
                // Example Output: "2026-27: Active | 2025-26: Pending | 1991-92: Active"
                $completeHistoryString = $dentist->memberships->isNotEmpty()
                    ? $dentist->memberships->map(function($membership) {
                        return "{$membership->membership_year}: {$membership->status}";
                      })->implode(' | ')
                    : 'No Logs Found';

                fputcsv($file, [
                    $dentist->full_name,
                    $dentist->prc_no,
                    $dentist->contact_no,
                    $dentist->email_address,
                    $dentist->clinic_address,
                    $statusString,
                    $sustainingFeeStatus,   // Isolates payment validation step status
                    $completeHistoryString  // The complete historical archive map layout string
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}