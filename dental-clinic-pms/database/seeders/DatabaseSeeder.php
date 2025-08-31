<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. First create roles and permissions (foundation)
            RolesAndPermissionsSeeder::class,
            
            // 2. Then create users (depends on roles)
            UserSeeder::class,
            
            // 3. Then create basic data (procedures, settings)
            ProcedureSeeder::class,
            SettingsSeeder::class,
            EmailTemplatesSeeder::class,
            
            // 4. Then create patient data
            PatientSeeder::class,
            
            // 5. Then create appointment data (depends on patients and users)
            AppointmentSeeder::class,
            
            // 6. Then create treatment data (depends on patients and appointments)
            TreatmentPlanSeeder::class,
            TreatmentRecordSeeder::class,
            
            // 7. Then create invoice data (depends on patients, appointments, and treatment plans)
            InvoiceSeeder::class,
            
            // 8. Then create payment data (depends on invoices)
            PaymentSeeder::class,
            
            // 9. Finally create inventory data (can be independent)
            StockDemoSeeder::class,
        ]);
    }
}
