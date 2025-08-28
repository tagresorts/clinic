<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Administrator
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@dentalclinic.com',
            'password' => Hash::make('password'),
            'phone' => '(555) 123-4567',
            'address' => '123 Admin Street, City, State 12345',

            'email_verified_at' => now(),
        ]);
        $admin->assignRole('administrator');

        // Create Dentist
        $dentist1 = User::create([
            'name' => 'Dr. John Smith',
            'email' => 'dr.smith@dentalclinic.com',
            'password' => Hash::make('password'),
            'phone' => '(555) 234-5678',
            'address' => '456 Dentist Avenue, City, State 12345',

            'email_verified_at' => now(),
        ]);
        $dentist1->assignRole('dentist');

        // Create another Dentist
        $dentist2 = User::create([
            'name' => 'Dr. Sarah Johnson',
            'email' => 'dr.johnson@dentalclinic.com',
            'password' => Hash::make('password'),
            'phone' => '(555) 345-6789',
            'address' => '789 Oral Health Blvd, City, State 12345',

            'email_verified_at' => now(),
        ]);
        $dentist2->assignRole('dentist');

        // Create Receptionist
        $receptionist1 = User::create([
            'name' => 'Mary Wilson',
            'email' => 'receptionist@dentalclinic.com',
            'password' => Hash::make('password'),
            'phone' => '(555) 456-7890',
            'address' => '321 Reception Lane, City, State 12345',

            'email_verified_at' => now(),
        ]);
        $receptionist1->assignRole('receptionist');

        // Create another Receptionist
        $receptionist2 = User::create([
            'name' => 'Lisa Brown',
            'email' => 'lisa@dentalclinic.com',
            'password' => Hash::make('password'),
            'phone' => '(555) 567-8901',
            'address' => '654 Front Desk Road, City, State 12345',

            'email_verified_at' => now(),
        ]);
        $receptionist2->assignRole('receptionist');
    }
}
