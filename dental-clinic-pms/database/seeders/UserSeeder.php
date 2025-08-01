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
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@dentalclinic.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMINISTRATOR,
            'phone' => '(555) 123-4567',
            'address' => '123 Admin Street, City, State 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Dentist
        User::create([
            'name' => 'Dr. John Smith',
            'email' => 'dr.smith@dentalclinic.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_DENTIST,
            'phone' => '(555) 234-5678',
            'address' => '456 Dentist Avenue, City, State 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create another Dentist
        User::create([
            'name' => 'Dr. Sarah Johnson',
            'email' => 'dr.johnson@dentalclinic.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_DENTIST,
            'phone' => '(555) 345-6789',
            'address' => '789 Oral Health Blvd, City, State 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Receptionist
        User::create([
            'name' => 'Mary Wilson',
            'email' => 'receptionist@dentalclinic.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_RECEPTIONIST,
            'phone' => '(555) 456-7890',
            'address' => '321 Reception Lane, City, State 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create another Receptionist
        User::create([
            'name' => 'Lisa Brown',
            'email' => 'lisa@dentalclinic.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_RECEPTIONIST,
            'phone' => '(555) 567-8901',
            'address' => '654 Front Desk Road, City, State 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
