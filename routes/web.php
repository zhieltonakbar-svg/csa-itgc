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

Route::get('/login', [
    LoginController::class,
    'showLoginForm'
])->name('login')->middleware('guest');

Route::post('/login', [
    LoginController::class,
    'login'
])->name('login.post')->middleware('guest');

Route::post('/logout', [
    LoginController::class,
    'logout'
])->name('logout');

Route::get('/pending-approval', function () {
    return view('auth.pending-approval');
})->name('pending.approval');

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
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');

    Route::get('/dashboard/categories', [
        DashboardController::class,
        'getCategories'
    ])->name('dashboard.categories');

    /*
    |--------------------------------------------------------------------------
    | IT Category
    |--------------------------------------------------------------------------
    */

    Route::get('/it-category/{category}/controls', [
        DashboardController::class,
        'showControls'
    ])->name('dashboard.controls');

    Route::get('/it-categories', [
        \App\Http\Controllers\ItCategoryController::class,
        'index'
    ])->name('it-categories.index');

    Route::post('/it-categories', [
        \App\Http\Controllers\ItCategoryController::class,
        'store'
    ])->name('it-categories.store');

    Route::put('/it-categories/{it_category}', [
        \App\Http\Controllers\ItCategoryController::class,
        'update'
    ])->name('it-categories.update');

    Route::delete('/it-categories/{it_category}', [
        \App\Http\Controllers\ItCategoryController::class,
        'destroy'
    ])->name('it-categories.destroy');

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    Route::get('/settings', [
        \App\Http\Controllers\SettingsController::class,
        'index'
    ])->name('settings.index');

    Route::put('/settings/profile', [
        \App\Http\Controllers\SettingsController::class,
        'updateProfile'
    ])->name('settings.updateProfile');

    /*
    |--------------------------------------------------------------------------
    | IT Risk Control
    |--------------------------------------------------------------------------
    */

    Route::get('/it-risk-control', [
        PlaceholderController::class,
        'itRiskControl'
    ])->name('it-risk-control');

    /*
    |--------------------------------------------------------------------------
    | Control CRUD
    |--------------------------------------------------------------------------
    */

    /*
     * IMPORTANT:
     * This route must be before /controls/{control}
     * so "next-ids" is not interpreted as a Control ID.
     */
    Route::get('/controls/next-ids', [
        \App\Http\Controllers\ControlController::class,
        'nextControlIds'
    ])->name('controls.nextIds');

    Route::post('/controls', [
        \App\Http\Controllers\ControlController::class,
        'store'
    ])->name('controls.store');

    Route::match(['post', 'put'], '/controls/{control}', [
        \App\Http\Controllers\ControlController::class,
        'update'
    ])->name('controls.update');

    Route::patch('/controls/{control}/status', [
        \App\Http\Controllers\ControlController::class,
        'updateStatus'
    ])->name('controls.updateStatus');

    Route::post('/controls/{control}/transition', [
        \App\Http\Controllers\ControlController::class,
        'transition'
    ])->name('controls.transition');

    Route::get('/controls/{control}/evidence', [
        \App\Http\Controllers\ControlController::class,
        'getEvidences'
    ])->name('controls.evidences');

    Route::get('/controls/{control}/berita-acara', [
        \App\Http\Controllers\ControlController::class,
        'downloadBeritaAcara'
    ])->name('controls.beritaAcara');

    Route::delete('/controls/delete-all', [
        \App\Http\Controllers\ControlController::class,
        'destroyAll'
    ])->name('controls.destroyAll');

    Route::delete('/controls/delete-period', [
        \App\Http\Controllers\ControlController::class,
        'destroyPeriod'
    ])->name('controls.destroyPeriod');

    Route::delete('/controls/{control}', [
        \App\Http\Controllers\ControlController::class,
        'destroy'
    ])->name('controls.destroy');

    /*
    |--------------------------------------------------------------------------
    | Evidence
    |--------------------------------------------------------------------------
    */

    Route::get('/evidence/{evidence}', [
        \App\Http\Controllers\EvidenceController::class,
        'show'
    ])->name('evidence.show');

    Route::match(['post', 'put', 'patch'], '/evidence/{evidence}', [
        \App\Http\Controllers\EvidenceController::class,
        'update'
    ])->name('evidence.update');

    Route::get('/evidence/{evidence}/preview', [
        \App\Http\Controllers\EvidenceController::class,
        'preview'
    ])->name('evidence.preview');

    Route::get('/evidence/{evidence}/preview-pdf', [
        \App\Http\Controllers\EvidenceController::class,
        'streamPreviewPdf'
    ])->name('evidence.preview-pdf');

    Route::delete('/evidence/{evidence}', [
        \App\Http\Controllers\EvidenceController::class,
        'destroy'
    ])->name('evidence.destroy');

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [
        \App\Http\Controllers\NotificationController::class,
        'index'
    ])->name('notifications.index');

    Route::delete('/notifications/clear', [
        \App\Http\Controllers\NotificationController::class,
        'clear'
    ])->name('notifications.clear');

    Route::post('/notifications/mark-read', [
        \App\Http\Controllers\NotificationController::class,
        'markRead'
    ])->name('notifications.markRead');

    Route::delete('/notifications/{id}', [
        \App\Http\Controllers\NotificationController::class,
        'destroy'
    ])->name('notifications.destroy');

    /*
    |--------------------------------------------------------------------------
    | Application Management
    |--------------------------------------------------------------------------
    */

    Route::get('/applications', [
        \App\Http\Controllers\ApplicationController::class,
        'index'
    ])->name('applications.index');

    Route::post('/applications', [
        \App\Http\Controllers\ApplicationController::class,
        'store'
    ])->name('applications.store');

    Route::put('/applications/{application}', [
        \App\Http\Controllers\ApplicationController::class,
        'update'
    ])->name('applications.update');

    Route::delete('/applications/{application}', [
        \App\Http\Controllers\ApplicationController::class,
        'destroy'
    ])->name('applications.destroy');

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [
        \App\Http\Controllers\UserController::class,
        'index'
    ])->name('users.index');

    Route::post('/users', [
        \App\Http\Controllers\UserController::class,
        'store'
    ])->name('users.store');

    Route::put('/users/{user}', [
        \App\Http\Controllers\UserController::class,
        'update'
    ])->name('users.update');

    Route::patch('/users/{user}/activate', [
        \App\Http\Controllers\UserController::class,
        'activate'
    ])->name('users.activate');

    Route::patch('/users/{user}/deactivate', [
        \App\Http\Controllers\UserController::class,
        'deactivate'
    ])->name('users.deactivate');

    Route::delete('/users/{user}', [
        \App\Http\Controllers\UserController::class,
        'destroy'
    ])->name('users.destroy');

    /*
    |--------------------------------------------------------------------------
    | UPTI Management
    |--------------------------------------------------------------------------
    */

    Route::post('/uptis', [
        \App\Http\Controllers\UptiController::class,
        'store'
    ])->name('uptis.store');

    Route::put('/uptis/{upti}', [
        \App\Http\Controllers\UptiController::class,
        'update'
    ])->name('uptis.update');

    Route::delete('/uptis/{upti}', [
        \App\Http\Controllers\UptiController::class,
        'destroy'
    ])->name('uptis.destroy');
});