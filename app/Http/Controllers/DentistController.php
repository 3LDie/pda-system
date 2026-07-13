<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\PdaMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Http; // Handles the outbound payload to n8n
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
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
        // 1. Join users table with dentist_profiles table to get all unified records
        $query = User::where('users.role', 'member')
            ->leftJoin('dentist_profiles', 'users.name', '=', 'dentist_profiles.full_name') // Matches on full name string reference
            ->select('users.*', 'dentist_profiles.full_name', 'dentist_profiles.prc_no', 'dentist_profiles.contact_no', 'dentist_profiles.clinic_address', 'dentist_profiles.profile_image');

        // 2. Add backend support for the search bar filtering
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('dentist_profiles.full_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('dentist_profiles.prc_no', 'LIKE', "%{$searchTerm}%");
            });
        }

        // 3. Get records ordered by latest creation
        $dentists = $query->latest('users.created_at')->get();

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
            'email_address'    => 'required|email|max:255|unique:users,email', 
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

            // 🔑 Automatically generate a secure 10-character temporary password string
            $temporaryPassword = Str::random(10);

            // 1. Create the Core Account Row inside the main users table
            $dentist = User::create([
                'name'     => $validated['full_name'], 
                'email'    => $validated['email_address'],
                'password' => Hash::make($temporaryPassword), 
                'role'     => 'member', 
            ]);

            // 1b. Direct insertion into dentist_profiles table (Bypasses missing user_id schema column constraints)
            DB::table('dentist_profiles')->insert([
                'full_name'      => $validated['full_name'],
                'profile_image'  => $imagePath,
                'prc_no'         => $validated['prc_no'],
                'date_of_birth'  => $validated['date_of_birth'],
                'contact_no'     => $validated['contact_no'],
                'home_address'   => $validated['home_address'],
                'clinic_address' => $validated['clinic_address'],
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 2. Create the associated structural Membership log row mapping to your real schema columns
            $dentist->memberships()->create([
                'membership_year' => $validated['membership_year'], 
                'status'          => $validated['payment_status'], 
            ]);

            // 🚀 3. Outbound Payload Hook to your live n8n orchestration flow
            Http::post(env('N8N_WELCOME_WEBHOOK_URL', 'https://your-n8n-instance.com/webhook/placeholder'), [
                'full_name'          => $validated['full_name'],
                'email'              => $dentist->email,
                'prc_no'             => $validated['prc_no'],
                'temporary_password' => $temporaryPassword,
                'app_login_url'      => url('/login'),
                'generated_at'       => now()->toIso8601String(),
            ]);

            DB::commit();

            // Log Registration Event
            $this->logAction('REGISTER', 'User', "Registered a new member account for {$validated['full_name']} (PRC: {$validated['prc_no']}) and piped automation logs downstream to n8n.");

            return redirect()->route('dentists.index')
                             ->with('success', 'Member record successfully saved and n8n webhook workflow executed!');

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
        $dentist = User::where('role', 'member')
            ->leftJoin('dentist_profiles', 'users.name', '=', 'dentist_profiles.full_name')
            ->select('users.*', 'dentist_profiles.full_name', 'dentist_profiles.prc_no', 'dentist_profiles.contact_no', 'dentist_profiles.clinic_address', 'dentist_profiles.home_address', 'dentist_profiles.date_of_birth', 'dentist_profiles.profile_image')
            ->findOrFail($id);
        
        return view('dentists.edit', compact('dentist'));
    }

    /**
     * Update the specified dentist profile in storage.
     */
    public function update(Request $request, $id)
    {
        $dentist = User::where('role', 'member')->findOrFail($id);

        // Fetch current profile using full_name to look up historical unique values safely
        $existingProfile = DB::table('dentist_profiles')->where('full_name', $dentist->name)->first();
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
        ]);

        // Manage photo asset replacements safely
        if ($request->hasFile('profile_image')) {
            if ($existingProfile && $existingProfile->profile_image && Storage::disk('public')->exists($existingProfile->profile_image)) {
                Storage::disk('public')->delete($existingProfile->profile_image);
            }

            // Store new resource asset element path parameters downstream
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        // Sync core authentication updates
        $dentist->update([
            'name'  => $validated['full_name'],
            'email' => $validated['email_address']
        ]);

        // Sync accompanying details updates directly via Direct DB query matching historical string contexts
        DB::table('dentist_profiles')->where('id', $profileId)->update([
            'full_name'      => $validated['full_name'],
            'profile_image'  => $validated['profile_image'] ?? ($existingProfile->profile_image ?? null),
            'prc_no'         => $validated['prc_no'],
            'date_of_birth'  => $validated['date_of_birth'],
            'contact_no'     => $validated['contact_no'],
            'home_address'   => $validated['home_address'],
            'clinic_address' => $validated['clinic_address'],
            'updated_at'     => now(),
        ]);

        // Log Update Event
        $this->logAction('UPDATE', 'User', "Updated personal account records for {$validated['full_name']} (PRC: {$validated['prc_no']})");

        return redirect()->route('dentists.index')
                         ->with('success', 'Dentist profile updated successfully!');
    }

    /**
     * Show the form for renewing a dentist's membership.
     */
    public function renew($id)
    {
        $dentist = User::where('role', 'member')->findOrFail($id);
        
        return view('dentists.renew', compact('dentist'));
    }

    /**
     * Store a newly created membership log row in storage.
     */
    public function storeRenewal(Request $request, $id)
    {
        $dentist = User::where('role', 'member')->findOrFail($id);

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
        $query = User::where('role', 'member')->with(['memberships' => function($q) {
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

        $fileName = 'pda_dentist_deep_export_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($dentists) {
            $file = fopen('php://output', 'w');
            
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

            $currentYear = date('Y');
            $currentFiscalYear = $currentYear . '-' . substr($currentYear + 1, -2); // Generates "2026-27"

            foreach ($dentists as $dentist) {
                $latestMembership = $dentist->memberships->first();
                $statusString = $latestMembership 
                    ? $latestMembership->membership_year . ' (' . $latestMembership->status . ')'
                    : 'No Logs';

                $currentFeeRecord = $dentist->memberships->where('membership_year', $currentFiscalYear)->first();
                $sustainingFeeStatus = $currentFeeRecord ? $currentFeeRecord->status : 'No Log for Current Year';

                $completeHistoryString = $dentist->memberships->isNotEmpty()
                    ? $dentist->memberships->map(function($membership) {
                        return "{$membership->membership_year}: {$membership->status}";
                      })->implode(' | ')
                    : 'No Logs Found';

                fputcsv($file, [
                    $dentist->full_name,
                    $dentist->prc_no,
                    $dentist->contact_no,
                    $dentist->email, 
                    $dentist->clinic_address,
                    $statusString,
                    $sustainingFeeStatus,   
                    $completeHistoryString  
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}