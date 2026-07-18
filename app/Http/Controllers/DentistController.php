<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\PdaMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; 
use App\Traits\LogsActivity;
use Exception;

class DentistController extends Controller
{
    use LogsActivity; 

    public function index(Request $request)
    {
        $query = User::where('users.role', 'member')
            ->join('dentist_profiles', 'users.id', '=', 'dentist_profiles.user_id')
            ->select('users.*', 'dentist_profiles.id as profile_id', 'dentist_profiles.full_name', 'dentist_profiles.prc_no', 'dentist_profiles.contact_no', 'dentist_profiles.clinic_address', 'dentist_profiles.profile_image');

        if (auth()->user()->role === 'member') {
            $query->where('users.id', auth()->id());
        }

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('dentist_profiles.full_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('dentist_profiles.prc_no', 'LIKE', "%{$searchTerm}%");
            });
        }

        $dentists = $query->latest('users.created_at')->get();
        
        $stats = ['total_dentists' => 0, 'active_members' => 0, 'pending_members' => 0, 'inactive_members' => 0];

        if (auth()->user()->role === 'admin') {
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
                'inactive_members' => $dentists->filter(function($dentist) use ($allMemberships, $currentFiscalYear) {
                    $hasActiveOrPending = $allMemberships->where('dentist_profile_id', $dentist->profile_id)
                        ->where('membership_year', $currentFiscalYear)
                        ->filter(function($m) {
                            return str_contains($m->status, 'Active') || $m->status === 'Pending';
                        })->isNotEmpty();
                    return !$hasActiveOrPending;
                })->count(),
            ];
        }

        return view('dentists.index', compact('dentists', 'stats'));
    }

    public function create() { return view('dentists.create'); }

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

            // TRIGGER N8N WEBHOOK
            try {
                Log::info('Attempting to trigger n8n webhook for: ' . $validated['email_address']);
                
                Http::post(env('N8N_WELCOME_WEBHOOK_URL'), [
                    'email'              => $validated['email_address'],
                    'name'               => $validated['full_name'],
                    'temporary_password' => $temporaryPassword,
                    'prc_no'             => $validated['prc_no'],
                    'app_login_url' => 'https://pda-system.up.railway.app'
                ]);
            } catch (Exception $webhookError) {
                Log::error('Webhook failed: ' . $webhookError->getMessage());
            }

            return redirect()->route('dentists.index')->with('success', 'Member record successfully saved!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Database failure: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $dentist = User::where('role', 'member')
            ->join('dentist_profiles', 'users.id', '=', 'dentist_profiles.user_id')
            ->select('users.*', 'dentist_profiles.full_name', 'dentist_profiles.prc_no', 'dentist_profiles.contact_no', 'dentist_profiles.clinic_address', 'dentist_profiles.home_address', 'dentist_profiles.date_of_birth', 'dentist_profiles.profile_image')
            ->findOrFail($id);
        return view('dentists.edit', compact('dentist'));
    }

    public function update(Request $request, $id)
    {
        $dentist = User::where('role', 'member')->findOrFail($id);
        $existingProfile = DB::table('dentist_profiles')->where('user_id', $dentist->id)->first();

        if (!$existingProfile) {
            return back()->withErrors(['error' => 'Profile record not found for this user.']);
        }

        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'profile_image'  => 'nullable|image|mimes:jpeg,png,jpg|max:10240', 
            'prc_no'         => 'required|string|max:15|unique:dentist_profiles,prc_no,' . $existingProfile->id, 
            'date_of_birth'  => 'required|date|before:today',
            'contact_no'     => 'required|string|max:20',
            'email_address'  => 'required|email|max:255|unique:users,email,' . $dentist->id, 
            'home_address'   => 'required|string',
            'clinic_address' => 'required|string',
            'membership_year'=> 'sometimes|required|string|max:50',
            'payment_status' => 'sometimes|required|string',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($existingProfile->profile_image && Storage::disk('public')->exists($existingProfile->profile_image)) {
                Storage::disk('public')->delete($existingProfile->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $dentist->update(['name' => $validated['full_name'], 'email' => $validated['email_address']]);

        DB::table('dentist_profiles')->where('id', $existingProfile->id)->update([
            'full_name'      => $validated['full_name'],
            'email_address'  => $validated['email_address'], 
            'profile_image'  => $validated['profile_image'] ?? $existingProfile->profile_image,
            'prc_no'         => $validated['prc_no'],
            'date_of_birth'  => $validated['date_of_birth'],
            'contact_no'     => $validated['contact_no'],
            'home_address'   => $validated['home_address'],
            'clinic_address' => $validated['clinic_address'],
            'updated_at'     => now(),
        ]);

        if ($request->has('membership_year') && $request->has('payment_status')) {
            DB::table('pda_memberships')
                ->where('dentist_profile_id', $existingProfile->id)
                ->update([
                    'membership_year' => $validated['membership_year'],
                    'status'          => $validated['payment_status'],
                    'updated_at'      => now(),
                ]);
        }

        return redirect()->route('dentists.index')->with('success', 'Dentist profile updated successfully!');
    }

    public function export(Request $request)
    {
        $dentists = User::where('role', 'member')
            ->join('dentist_profiles', 'users.id', '=', 'dentist_profiles.user_id')
            ->select('users.*', 'dentist_profiles.id as profile_id', 'dentist_profiles.full_name', 'dentist_profiles.prc_no', 'dentist_profiles.contact_no', 'dentist_profiles.clinic_address')
            ->get();

        $fileName = 'pda_export_' . date('Y-m-d') . '.csv';
        $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$fileName"];

        $callback = function() use ($dentists) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Full Name', 'PRC Number', 'Contact No.', 'Email Addr', 'Clinic Addr', 'Latest Membership', 'Sustaining Fee Status (Current Fiscal Yr)', 'Complete Historical Logs (Year: Status)']);
            
            foreach ($dentists as $dentist) {
                $memberships = DB::table('pda_memberships')
                    ->where('dentist_profile_id', $dentist->profile_id)
                    ->orderBy('membership_year', 'desc')
                    ->get();

                $latest = $memberships->first();
                
                $sustaining = ($latest && (str_contains($latest->status, 'Active') || str_contains($latest->status, 'Paid'))) 
                    ? $latest->membership_year . ' (' . $latest->status . ')' 
                    : 'N/A';
                
                $logString = $memberships->map(fn($m) => $m->membership_year . ': ' . $m->status)->implode(' | ');

                fputcsv($file, [
                    $dentist->full_name, 
                    $dentist->prc_no, 
                    $dentist->contact_no, 
                    $dentist->email, 
                    $dentist->clinic_address, 
                    $latest ? $latest->membership_year : 'N/A', 
                    $sustaining, 
                    $logString
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function renew($id)
    {
        $dentist = User::where('role', 'member')
            ->join('dentist_profiles', 'users.id', '=', 'dentist_profiles.user_id')
            ->select('users.*', 'dentist_profiles.id as profile_id', 'dentist_profiles.full_name')
            ->findOrFail($id);
        return view('dentists.renew', compact('dentist'));
    }

    public function storeRenewal(Request $request, $id)
    {
        $validated = $request->validate([
            'membership_year' => 'required|string|max:50',
            'payment_status'  => 'required|string',
        ]);

        $profile = DB::table('dentist_profiles')->where('user_id', $id)->first();

        DB::table('pda_memberships')->insert([
            'dentist_profile_id' => $profile->id,
            'membership_year'    => $validated['membership_year'],
            'status'             => $validated['payment_status'],
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        return redirect()->route('dentists.index')->with('success', 'Membership renewed successfully!');
    }

    public function destroyMembership($id)
    {
        DB::table('pda_memberships')->where('id', $id)->delete();
        return back()->with('success', 'Membership record deleted.');
    }
}