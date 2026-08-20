<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Control;

$controls = Control::all();
$count = 0;
foreach ($controls as $c) {
    $ev = $c->evidences()->where('file_type', 'Berita Acara')->first();
    if ($ev) {
        $c->update(['berita_acara_path' => $ev->file_path]);
        $count++;
    }
}
echo "Migrated $count Berita Acara paths.\n";
