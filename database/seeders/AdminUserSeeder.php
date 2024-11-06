<?php

namespace Database\Seeders;

use App\Models\User;
use Lunar\Hub\Models\Staff;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'admin@metalsdepot.com')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@metalsdepot.com',
                'password' => bcrypt('Testing123!'),
                'email_verified_at' => now()
            ]);
        }

        $staff = Staff::where('email', 'admin@metalsdepot.com')->first();

        if (!$staff) {
            Staff::create([
                'admin' => true,
                'firstname' => 'Admin',
                'lastname' => 'User',
                'email' => 'admin@metalsdepot.com',
                'password' => bcrypt('Testing123!'),
                'email_verified_at' => now()
            ]);
        }
    }
}
