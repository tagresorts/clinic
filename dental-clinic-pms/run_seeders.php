<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Bootstrap Laravel
$app = Application::configure(basePath: dirname(__FILE__))
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Run the seeders
$app->make('db')->beginTransaction();

try {
    echo "Running seeders...\n";
    
    // Run seeders in order
    $seeders = [
        'Database\Seeders\RolesAndPermissionsSeeder',
        'Database\Seeders\UserSeeder',
        'Database\Seeders\ProcedureSeeder',
        'Database\Seeders\SettingsSeeder',
        'Database\Seeders\EmailTemplatesSeeder',
        'Database\Seeders\PatientSeeder',
        'Database\Seeders\AppointmentSeeder',
        'Database\Seeders\TreatmentPlanSeeder',
        'Database\Seeders\TreatmentRecordSeeder',
        'Database\Seeders\InvoiceSeeder',
        'Database\Seeders\PaymentSeeder',
        'Database\Seeders\StockDemoSeeder',
    ];
    
    foreach ($seeders as $seederClass) {
        echo "Running {$seederClass}...\n";
        $seeder = new $seederClass();
        $seeder->run();
        echo "Completed {$seederClass}\n";
    }
    
    $app->make('db')->commit();
    echo "All seeders completed successfully!\n";
    
} catch (Exception $e) {
    $app->make('db')->rollBack();
    echo "Error running seeders: " . $e->getMessage() . "\n";
    exit(1);
}