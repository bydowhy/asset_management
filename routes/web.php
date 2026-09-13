<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetRelationshipController;
use App\Http\Controllers\AssetSpecificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Equipment
    Route::resource('equipment', EquipmentController::class)->only(['index', 'show']);

    // Assets
    Route::resource('assets', AssetController::class)->only(['index', 'show']);

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
});

require __DIR__.'/settings.php';
