<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->bootstrap();

// Simulate a logged-in user
$user = \App\Models\User::first();
if ($user) {
    auth()->login($user);
    echo 'Logged in as: ' . $user->email . PHP_EOL;
}

// Simulate the request
$request = \Illuminate\Http\Request::create('/evidence/3/preview-pdf', 'GET');
$response = $kernel->handle($request);

echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
echo 'Content-Type: ' . $response->headers->get('Content-Type') . PHP_EOL;
echo 'Content-Length: ' . strlen($response->getContent()) . ' bytes' . PHP_EOL;

// Check if it's a valid PDF
$content = $response->getContent();
if (str_starts_with($content, '%PDF')) {
    echo 'Response IS a valid PDF' . PHP_EOL;
} else {
    echo 'Response is NOT a PDF: ' . substr($content, 0, 200) . PHP_EOL;
}
