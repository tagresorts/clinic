<?php

require_once 'vendor/autoload.php';

use App\Models\Patient;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Updating patient sources...\n";

try {
    // Check if the source column exists
    if (Schema::hasColumn('patients', 'source')) {
        $sources = ['Walk-in', 'Referral', 'Online', 'Other'];
        
        $patients = Patient::whereNull('source')->get();
        
        foreach ($patients as $patient) {
            $patient->source = $sources[array_rand($sources)];
            $patient->save();
        }
        
        echo "Updated " . $patients->count() . " patients with source data.\n";
    } else {
        echo "Source column does not exist. Please run the migration first.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Update completed.\n";