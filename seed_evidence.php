<?php

use App\Models\Control;
use App\Models\ControlEvidence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

$control = Control::where('it_control_id', 'C-IT-05')->first();

if (!$control) {
    echo "Control not found.\n";
    exit;
}

$filename = 'test_evidence_' . time() . '.docx';
$path = 'evidence/' . $filename;

// Copy the file
if (!File::exists('d:\csa-itgc\storage\app\public\evidence')) {
    File::makeDirectory('d:\csa-itgc\storage\app\public\evidence', 0755, true);
}
File::copy('d:\csa-itgc\test_evidence.docx', 'd:\csa-itgc\storage\app\public\\' . $path);

// Create record
$evidence = ControlEvidence::create([
    'control_id' => $control->id,
    'file_name' => $filename,
    'original_name' => 'test_evidence.docx',
    'file_path' => $path,
    'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'file_size' => filesize('d:\csa-itgc\test_evidence.docx'),
    'uploaded_by' => 'Zhielton Akbar',
]);

echo "Evidence created with ID: " . $evidence->id . "\n";
exit;
