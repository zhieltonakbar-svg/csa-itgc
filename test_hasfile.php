<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$request = new \Illuminate\Http\Request();
$file = \Illuminate\Http\UploadedFile::fake()->create('test.pdf', 100);
$request->files->set('evidences', [$file]);
echo 'Original hasFile: ' . ($request->hasFile('evidences') ? 'Yes' : 'No') . PHP_EOL;

$cleanFiles = array_filter((array)$request->file('evidences'), fn($f) => $f && $f->isValid());
$request->files->set('evidences', array_values($cleanFiles));
echo 'After set hasFile: ' . ($request->hasFile('evidences') ? 'Yes' : 'No') . PHP_EOL;

echo 'After set file count: ' . count((array)$request->file('evidences')) . PHP_EOL;
