<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaceholderController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Root Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes  (Phase 1 — Dashboard + IT Risk Control only)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // JSON endpoint: fetch IT categories for a selected application
    Route::get('/dashboard/categories', [DashboardController::class, 'getCategories'])->name('dashboard.categories');

    // IT Category detail page (controls table UI template)
    Route::get('/it-category/{application}/{category}', [DashboardController::class, 'showCategory'])->name('it-category.show');

    Route::get('/it-risk-control', [PlaceholderController::class, 'itRiskControl'])->name('it-risk-control');

    // Control CRUD and evidence routes
    Route::post('/controls', [\App\Http\Controllers\ControlController::class, 'store'])->name('controls.store');
    Route::match(['post', 'put'], '/controls/{control}', [\App\Http\Controllers\ControlController::class, 'update'])->name('controls.update');
    Route::patch('/controls/{control}/status', [\App\Http\Controllers\ControlController::class, 'updateStatus'])->name('controls.updateStatus');
    Route::get('/controls/{control}/evidence', [\App\Http\Controllers\ControlController::class, 'getEvidences'])->name('controls.evidences');
    Route::delete('/controls/delete-all', [\App\Http\Controllers\ControlController::class, 'destroyAll'])->name('controls.destroyAll');
    Route::delete('/controls/{control}', [\App\Http\Controllers\ControlController::class, 'destroy'])->name('controls.destroy');
    Route::get('/evidence/{evidence}', [\App\Http\Controllers\EvidenceController::class, 'show'])->name('evidence.show');
    Route::match(['post', 'put', 'patch'], '/evidence/{evidence}', [\App\Http\Controllers\EvidenceController::class, 'update'])->name('evidence.update');
    Route::get('/evidence/{evidence}/preview', [\App\Http\Controllers\EvidenceController::class, 'preview'])->name('evidence.preview');
    Route::get('/evidence/{evidence}/preview-pdf', [\App\Http\Controllers\EvidenceController::class, 'streamPreviewPdf'])->name('evidence.preview-pdf');
    Route::delete('/evidence/{evidence}', [\App\Http\Controllers\EvidenceController::class, 'destroy'])->name('evidence.destroy');
});
