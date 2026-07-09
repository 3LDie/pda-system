<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class PromoteUserToAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find your existing login account by its email address
        $user = User::where('email', 'test@example.com')->first(); // 👈 Replace with your actual login email if different!

        if ($user) {
            $user->update(['role' => 'admin']);
            $this->command->info("Success: {$user->name} has been promoted to Super Admin!");
        } else {
            $this->command->error("Target user account not found.");
        }
    }
}