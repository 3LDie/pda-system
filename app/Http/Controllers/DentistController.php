<?php

namespace App\Http\Controllers;

use App\Models\DentistProfile;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    public function index()
    {
        // Fetch all dentists from the database along with their membership history
        $dentists = DentistProfile::with('memberships')->get();

        // Pass the data to the frontend view file (which we will create next)
        return view('dentists.index', compact('dentists'));
    }
}