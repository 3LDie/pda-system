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

    public function index(Request $request)
    {
        $query = User::where('users.role', 'member')
            ->leftJoin('dentist_profiles', 'users.name', '=', 'dentist_profiles.full_name')
            ->select('users.*', 'dentist_profiles.id as profile_id', 'dentist_profiles.full_name', 'dentist_profiles.prc_no', 'dentist_profiles.contact_no', 'dentist_profiles.clinic_address', 'dentist_profiles.profile_image');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('dentist_profiles.full_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('dentist_profiles.prc_no', 'LIKE', "%{$searchTerm}%");
            });
        }

        $dentists = $query->latest('users.created_at')->get();
        $currentYear = date('Y');
        $currentFiscalYear = $currentYear . '-' . substr($currentYear + 1, -2);
        
        $profileIds = $dentists->pluck('profile_id')->filter()->toArray();
        $allMemberships = DB::table('pda_memberships')
            ->whereIn('dentist_profile_id', $profileIds)
            ->get();

        $stats = [
            'total_dentists' => $dentists->count(),
            'active_members' => $dentists->filter(function($dentist) use ($allMemberships, $currentFiscalYear) {
                return $allMemberships->where('dentist_profile_id', $dentist->profile_id)
                                      ->where('membership_year', $currentFiscalYear)
                                      ->filter(function($m) { return str_contains($m->status, 'Active'); })
                                      ->isNotEmpty();
            })->count(),
            'pending_members' => $dentists->filter(function($dentist) use ($allMemberships) {
                return $allMemberships->where('dentist_profile_id', $dentist->profile_id)
                                      ->where('status', 'Pending')
                                      ->isNotEmpty();
            })->count(),
        ];

        $stats['inactive_members'] = $dentists->filter(function($dentist) use ($allMemberships, $currentFiscalYear) {
            $hasActiveOrPending = $allMemberships->where('dentist_profile_id', $dentist->profile_id)
                ->where('membership_year', $currentFiscalYear)
                ->filter(function($m) {
                    return str_contains($m->status, 'Active') || $m->status === 'Pending';
                })->isNotEmpty();
            return !$hasActiveOrPending;
        })->count();

        return view('dentists.index', compact('dentists', 'stats'));
    }

    public function create() { return view('dentists.create'); }

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
            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            }

            $temporaryPassword = Str::random(10);
            $dentist = User::create([
                'name'     => $validated['full_name'], 
                'email'    => $validated['email_address'],
                'password' => Hash::make($temporaryPassword), 
                'role'     => 'member', 
            ]);

            $profileId = DB::table('dentist_profiles')->insertGetId([
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

            Http::post('https://n8n-production-385ae.up.railway.app/webhook/pda-member-welcome', [
                'full_name'          => $validated['full_name'],
                'email'              => $dentist->email,
                'prc_no'             => $validated['prc_no'],
                'temporary_password' => $temporaryPassword,
                'app_login_url'      => url('/login'),
                'generated_at'       => now()->toIso8601String(),
            ]);

            $this->logAction('REGISTER', 'User', "Registered a new member account for {$validated['full_name']} (PRC: {$validated['prc_no']}) and piped automation logs downstream to n8n.");

            return redirect()->route('dentists.index')->with('success', 'Member record successfully saved and n8n webhook workflow executed!');

        } catch (Exception $e) {
            DB::rollBack();
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            logger($e->getMessage()); 
            return back()->withInput()->withErrors(['error' => 'Database failure: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $dentist = User::where('role', 'member')
            ->leftJoin('dentist_profiles', 'users.name', '=', 'dentist_profiles.full_name')
            ->select('users.*', 'dentist_profiles.full_name', 'dentist_profiles.prc_no', 'dentist_profiles.contact_no', 'dentist_profiles.clinic_address', 'dentist_profiles.home_address', 'dentist_profiles.date_of_birth', 'dentist_profiles.profile_image')
            ->findOrFail($id);
        return view('dentists.edit', compact('dentist'));
    }

    public function update(Request $request, $id)
    {
        $dentist = User::where('role', 'member')->findOrFail($id);
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

        Http::post('https://n8n-production-385ae.up.railway.app/webhook/pda-member-welcome', [
            'full_name'          => $validated['full_name'],
            'email'              => $validated['email_address'],
            'prc_no'             => $validated['prc_no'],
            'temporary_password' => 'CHANGED_BY_ADMIN',
            'app_login_url'      => url('/login'),
            'generated_at'       => now()->toIso8601String(),
        ]);

        $this->logAction('UPDATE', 'User', "Updated personal account records for {$validated['full_name']} (PRC: {$validated['prc_no']})");

        return redirect()->route('dentists.index')->with('success', 'Dentist profile updated successfully!');
    }

    public function renew($id)
    {
        $dentist = User::where('role', 'member')->findOrFail($id);
        return view('dentists.renew', compact('dentist'));
    }

    public function storeRenewal(Request $request, $id)
    {
        $dentist = User::where('role', 'member')->findOrFail($id);
        $validated = $request->validate([
            'membership_year' => 'required|string|max:20',
            'payment_status'  => 'required|string|max:50',
        ]);

        $existingProfile = DB::table('dentist_profiles')->where('full_name', $dentist->name)->first();
        $profileId = $existingProfile ? $existingProfile->id : null;

        DB::table('pda_memberships')->insert([
            'dentist_profile_id' => $profileId, 
            'membership_year'    => $validated['membership_year'],
            'status'             => $validated['payment_status'],
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        $this->logAction('RENEW', 'PdaMembership', "Logged a new fiscal year renewal bracket [{$validated['membership_year']}] with status [{$validated['payment_status']}] for {$dentist->full_name}");

        return redirect()->route('dentists.index')->with('success', 'Membership year successfully logged for ' . $dentist->full_name);
    }

    public function destroyMembership($id)
    {
        $membership = PdaMembership::findOrFail($id);
        $membership->delete();
        $this->logAction('DELETE', 'PdaMembership', "Permanently removed membership year log row entry ID: [{$id}] for bracket [{$membership->membership_year}]");
        return back()->with('success', 'Membership log row successfully removed.');
    }

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
            fputcsv($file, ['Full Name', 'PRC Number', 'Contact No.', 'Email Address', 'Clinic Address', 'Latest Membership Status', 'Sustaining Fee Status (Current Fiscal Yr)', 'Complete Historical Logs (Year: Status)']);

            $currentYear = date('Y');
            $currentFiscalYear = $currentYear . '-' . substr($currentYear + 1, -2);

            foreach ($dentists as $dentist) {
                $latestMembership = $dentist->memberships->first();
                $statusString = $latestMembership ? $latestMembership->membership_year . ' (' . $latestMembership->status . ')' : 'No Logs';
                $currentFeeRecord = $dentist->memberships->where('membership_year', $currentFiscalYear)->first();
                $sustainingFeeStatus = $currentFeeRecord ? $currentFeeRecord->status : 'No Log for Current Year';
                $completeHistoryString = $dentist->memberships->isNotEmpty() ? $dentist->memberships->map(function($m) { return "{$m->membership_year}: {$m->status}"; })->implode(' | ') : 'No Logs Found';

                fputcsv($file, [$dentist->full_name, $dentist->prc_no, $dentist->contact_no, $dentist->email, $dentist->clinic_address, $statusString, $sustainingFeeStatus, $completeHistoryString]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}