<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        // Phil
        $user = User::create([
            'id_number' => '00001',
            'super' => true,
            'email' => 'admin@philcharitou.com',
            'password' => Hash::make('!1Temp4now'),
            'blocked' => 0,
            'pass_reset' => false,
            'first_name' => 'Phil',
            'last_name' => 'Charitou',
            'notification_preference' => 'mail',
            'company' => 'Charitou Multimedia Solutions Inc.',
            'branch' => 'north_america',
            'department' => 'admin',
            'address' => '',
            'phone' => '',
            ]);
        $user->assignRole('super_admin');
    }
}
