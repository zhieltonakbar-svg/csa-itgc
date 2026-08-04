<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();

$ev = \App\Models\ControlEvidence::first();
print_r($ev->toArray());

echo "\n\n--- TABLE COLUMNS ---\n";
$cols = \Illuminate\Support\Facades\Schema::getColumnListing('control_evidences');
print_r($cols);
