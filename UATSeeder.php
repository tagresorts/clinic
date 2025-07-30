<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\TreatmentPlan;
use App\Models\Supplier;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UATSeeder extends Seeder
{
    /**
     * Run the UAT database seeds.
     */
    public function run(): void
    {
        // Create UAT Users
        $this->createUsers();
        
        // Create Test Patients
        $this->createPatients();
        
        // Create Sample Appointments
        $this->createAppointments();
        
        // Create Treatment Plans
        $this->createTreatmentPlans();
        
        // Create Suppliers and Inventory
        $this->createInventory();
    }

    private function createUsers(): void
    {
        // UAT Administrator
        User::create([
            'name' => 'UAT Administrator',
            'email' => 'admin@dentaluat.com',
            'password' => Hash::make('UATAdmin2024!'),
            'role' => User::ROLE_ADMINISTRATOR,
            'phone' => '(555) 100-0001',
            'address' => 'UAT Admin Office, Test City, TC 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // UAT Dentists
        User::create([
            'name' => 'Dr. Emily Rodriguez',
            'email' => 'dr.rodriguez@dentaluat.com',
            'password' => Hash::make('UATDentist2024!'),
            'role' => User::ROLE_DENTIST,
            'phone' => '(555) 100-0002',
            'address' => 'UAT Dental Office, Test City, TC 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Dr. Michael Chen',
            'email' => 'dr.chen@dentaluat.com',
            'password' => Hash::make('UATDentist2024!'),
            'role' => User::ROLE_DENTIST,
            'phone' => '(555) 100-0003',
            'address' => 'UAT Dental Office, Test City, TC 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // UAT Receptionists
        User::create([
            'name' => 'Sarah Thompson',
            'email' => 'sarah@dentaluat.com',
            'password' => Hash::make('UATReception2024!'),
            'role' => User::ROLE_RECEPTIONIST,
            'phone' => '(555) 100-0004',
            'address' => 'UAT Reception Desk, Test City, TC 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jennifer Martinez',
            'email' => 'jennifer@dentaluat.com',
            'password' => Hash::make('UATReception2024!'),
            'role' => User::ROLE_RECEPTIONIST,
            'phone' => '(555) 100-0005',
            'address' => 'UAT Reception Desk, Test City, TC 12345',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    private function createPatients(): void
    {
        $patients = [
            [
                'first_name' => 'John',
                'last_name' => 'Anderson',
                'date_of_birth' => '1985-03-15',
                'gender' => 'male',
                'phone' => '(555) 200-0001',
                'email' => 'john.anderson@email.com',
                'allergies' => 'Penicillin',
                'insurance_provider' => 'Delta Dental',
                'insurance_policy_number' => 'DD123456789',
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'date_of_birth' => '1990-07-22',
                'gender' => 'female',
                'phone' => '(555) 200-0002',
                'email' => 'maria.garcia@email.com',
                'allergies' => null,
                'insurance_provider' => 'MetLife',
                'insurance_policy_number' => 'ML987654321',
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Johnson',
                'date_of_birth' => '1978-11-08',
                'gender' => 'male',
                'phone' => '(555) 200-0003',
                'email' => 'robert.johnson@email.com',
                'allergies' => 'Latex, Ibuprofen',
                'insurance_provider' => 'Cigna',
                'insurance_policy_number' => 'CG456789123',
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Wilson',
                'date_of_birth' => '1995-01-30',
                'gender' => 'female',
                'phone' => '(555) 200-0004',
                'email' => 'lisa.wilson@email.com',
                'allergies' => null,
                'insurance_provider' => null,
                'insurance_policy_number' => null,
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Brown',
                'date_of_birth' => '1982-09-12',
                'gender' => 'male',
                'phone' => '(555) 200-0005',
                'email' => 'david.brown@email.com',
                'allergies' => 'Codeine',
                'insurance_provider' => 'Aetna',
                'insurance_policy_number' => 'AE789123456',
            ],
        ];

        foreach ($patients as $patientData) {
            Patient::create(array_merge($patientData, [
                'address' => '123 Test Street, Test City, TC 12345',
                'emergency_contact_name' => 'Emergency Contact',
                'emergency_contact_phone' => '(555) 999-0000',
                'emergency_contact_relationship' => 'Spouse',
                'medical_conditions' => 'None reported',
                'current_medications' => 'None',
                'dental_history' => 'Regular checkups',
                'insurance_expiry_date' => Carbon::now()->addYear(),
                'is_active' => true,
            ]));
        }
    }

    private function createAppointments(): void
    {
        $patients = Patient::all();
        $dentists = User::dentists()->get();

        foreach ($patients->take(3) as $index => $patient) {
            // Create upcoming appointments
            Appointment::create([
                'patient_id' => $patient->id,
                'dentist_id' => $dentists[$index % $dentists->count()]->id,
                'appointment_datetime' => Carbon::now()->addDays($index + 1)->setTime(9 + $index, 0),
                'duration_minutes' => 60,
                'appointment_type' => 'Routine Checkup',
                'status' => 'scheduled',
                'reason_for_visit' => 'Regular dental examination and cleaning',
            ]);

            // Create past appointments
            Appointment::create([
                'patient_id' => $patient->id,
                'dentist_id' => $dentists[$index % $dentists->count()]->id,
                'appointment_datetime' => Carbon::now()->subMonths(6)->setTime(10 + $index, 30),
                'duration_minutes' => 45,
                'appointment_type' => 'Cleaning',
                'status' => 'completed',
                'reason_for_visit' => 'Dental cleaning and examination',
            ]);
        }
    }

    private function createTreatmentPlans(): void
    {
        $patients = Patient::take(2)->get();
        $dentists = User::dentists()->get();

        foreach ($patients as $index => $patient) {
            TreatmentPlan::create([
                'patient_id' => $patient->id,
                'dentist_id' => $dentists[$index]->id,
                'plan_title' => 'Comprehensive Dental Treatment',
                'diagnosis' => 'Multiple caries, gingivitis',
                'proposed_procedures' => json_encode([
                    'Dental cleaning',
                    'Cavity fillings (3 teeth)',
                    'Fluoride treatment'
                ]),
                'estimated_cost' => 850.00,
                'estimated_duration_sessions' => 3,
                'priority' => 'medium',
                'status' => 'proposed',
                'treatment_notes' => 'Patient requires comprehensive treatment for optimal oral health',
                'insurance_covered' => true,
                'insurance_coverage_amount' => 600.00,
            ]);
        }
    }

    private function createInventory(): void
    {
        // Create suppliers
        $suppliers = [
            [
                'supplier_name' => 'Dental Supply Co.',
                'contact_person_name' => 'Sales Manager',
                'phone' => '(555) 300-0001',
                'email' => 'sales@dentalsupply.com',
                'address' => '456 Supply Street',
                'city' => 'Supply City',
                'country' => 'USA',
                'is_active' => true,
            ],
            [
                'supplier_name' => 'Medical Equipment Ltd.',
                'contact_person_name' => 'Account Manager',
                'phone' => '(555) 300-0002',
                'email' => 'accounts@medequip.com',
                'address' => '789 Equipment Ave',
                'city' => 'Equipment City',
                'country' => 'USA',
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }

        // Create inventory items
        $items = [
            [
                'item_code' => 'GLOVES-001',
                'item_name' => 'Latex Examination Gloves',
                'category' => 'consumables',
                'unit_of_measure' => 'box',
                'quantity_in_stock' => 50,
                'reorder_level' => 10,
                'unit_cost' => 15.99,
            ],
            [
                'item_code' => 'MASK-001',
                'item_name' => 'Surgical Face Masks',
                'category' => 'consumables',
                'unit_of_measure' => 'box',
                'quantity_in_stock' => 5,
                'reorder_level' => 15,
                'unit_cost' => 12.50,
            ],
            [
                'item_code' => 'PROBE-001',
                'item_name' => 'Dental Probe Set',
                'category' => 'instruments',
                'unit_of_measure' => 'set',
                'quantity_in_stock' => 8,
                'reorder_level' => 3,
                'unit_cost' => 45.00,
            ],
        ];

        $supplier = Supplier::first();
        foreach ($items as $itemData) {
            InventoryItem::create(array_merge($itemData, [
                'supplier_id' => $supplier->id,
                'description' => 'UAT test item',
                'is_active' => true,
            ]));
        }
    }
}