<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();

$ev = \App\Models\ControlEvidence::find(3);
echo 'File path: ' . $ev->file_path . PHP_EOL;
$path = storage_path('app/public/' . $ev->file_path);
echo 'Full path: ' . $path . PHP_EOL;
echo 'Exists: ' . (file_exists($path) ? 'YES' : 'NO') . PHP_EOL;

$previewPath = storage_path('app/public/evidence/previews/preview_3.pdf');
echo 'Preview exists: ' . (file_exists($previewPath) ? 'YES' : 'NO') . PHP_EOL;
echo 'Preview size: ' . (file_exists($previewPath) ? filesize($previewPath) : 'N/A') . ' bytes' . PHP_EOL;

// Test conversion if preview doesn't exist
if (!file_exists($previewPath)) {
    echo 'Attempting conversion...' . PHP_EOL;
    try {
        $content = \App\Services\DocumentConverter::convertToPdf($path, 'docx');
        if ($content) {
            echo 'Conversion succeeded, PDF size: ' . strlen($content) . ' bytes' . PHP_EOL;
        } else {
            echo 'Conversion returned null' . PHP_EOL;
        }
    } catch (Throwable $e) {
        echo 'Conversion error: ' . $e->getMessage() . PHP_EOL;
    }
}
