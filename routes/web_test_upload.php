<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/test-upload', function(Request $request) {
    \Log::info('Test Upload Request', $request->all());
    $cleanFiles = array_filter(
        (array) $request->file('evidences'),
        fn($f) => $f && $f->isValid()
    );
    $request->files->set('evidences', array_values($cleanFiles));

    $filesToSave = $request->file('evidences');
    \Log::info('Files to save', ['count' => is_array($filesToSave) ? count($filesToSave) : 0]);

    return response()->json(['success' => true]);
});
