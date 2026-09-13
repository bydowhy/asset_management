<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetRelationshipController;
use App\Http\Controllers\AssetSpecificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentAssetController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FailureController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Equipment
    Route::resource('equipment', EquipmentController::class);

    // Assets
    Route::resource('assets', AssetController::class);

    // Specifications
    Route::put('assets/{asset}/specifications', [AssetSpecificationController::class, 'sync'])
        ->name('assets.specifications.sync');

    // Relationships
    Route::post('assets/{asset}/relationships', [AssetRelationshipController::class, 'store'])
        ->name('assets.relationships.store');
    Route::patch('relationships/{relationship}/end', [AssetRelationshipController::class, 'end'])
        ->name('relationships.end');
    Route::patch('relationships/{relationship}/replace', [AssetRelationshipController::class, 'replace'])
        ->name('relationships.replace');

    // Equipment assets (install / remove / replace)
    Route::post('equipment/{equipment}/assets', [EquipmentAssetController::class, 'store'])
        ->name('equipment.assets.store');
    Route::patch('assignments/{assignment}/remove', [EquipmentAssetController::class, 'remove'])
        ->name('assignments.remove');
    Route::patch('assignments/{assignment}/replace', [EquipmentAssetController::class, 'replace'])
        ->name('assignments.replace');

    // Failures
    Route::resource('failures', FailureController::class);

    // Locations
    Route::resource('locations', LocationController::class)->except(['show']);
});

require __DIR__.'/settings.php';
