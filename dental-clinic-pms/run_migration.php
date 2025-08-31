<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Running migration to add source column to patients table...\n";

try {
    // Check if the column already exists
    if (!Schema::hasColumn('patients', 'source')) {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('source')->nullable()->after('gender');
        });
        echo "Successfully added 'source' column to patients table.\n";
    } else {
        echo "Source column already exists in patients table.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Migration completed.\n";