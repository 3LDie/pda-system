<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DentistProfile;
use App\Models\PdaMembership;

class DentistTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Dr. Juan dela Cruz
        $dentist1 = DentistProfile::create([
            'full_name' => 'Dr. Juan dela Cruz',
            'prc_no' => '0012345',
            'date_of_birth' => '1965-05-12',
            'home_address' => 'Session Road, Baguio City',
            'clinic_address' => 'Room 204, Laperal Building, Baguio City',
            'email_address' => 'juandelacruz.dds@gmail.com',
            'contact_no' => '09171234567',
        ]);

        $dentist1->memberships()->createMany([
            ['membership_year' => '1989-90', 'status' => 'Active'],
            ['membership_year' => '1990-91', 'status' => 'Active'],
            ['membership_year' => '1991-92', 'status' => 'Active'],
        ]);

        // 2. Seed Dr. Maria Santos
        $dentist2 = DentistProfile::create([
            'full_name' => 'Dr. Maria Clara Santos',
            'prc_no' => '0056789',
            'date_of_birth' => '1978-10-24',
            'home_address' => 'Km. 5, La Trinidad, Benguet',
            'clinic_address' => 'Pico Retail Center, La Trinidad',
            'email_address' => 'mariasantos.dental@yahoo.com',
            'contact_no' => '09209876543',
        ]);

        $dentist2->memberships()->createMany([
            ['membership_year' => '2024-25', 'status' => 'Active'],
            ['membership_year' => '2025-26', 'status' => 'Pending'],
        ]);

        // 3. Seed Dr. Alan Turing
        $dentist3 = DentistProfile::create([
            'full_name' => 'Dr. Alan Alcantara',
            'prc_no' => '0098765',
            'date_of_birth' => '1985-12-01',
            'home_address' => 'Magsaysay Avenue, Baguio City',
            'clinic_address' => 'SM City Baguio Cyberzone Complex',
            'email_address' => 'alan.alcantara.dds@outlook.com',
            'contact_no' => '09991112222',
        ]);

        $dentist3->memberships()->create(['membership_year' => '2025-26', 'status' => 'Active']);
    }
}