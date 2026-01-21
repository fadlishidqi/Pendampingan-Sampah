<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\HouseholdController;
use App\Http\Controllers\Api\BioporiSiteController;
use App\Http\Controllers\Api\FurnacePlanController;
use App\Http\Controllers\Api\MapPointController;

Route::apiResource('households', HouseholdController::class);
Route::apiResource('biopori-sites', BioporiSiteController::class);
Route::apiResource('furnace-plans', FurnacePlanController::class);

// Catatan:
// Endpoint peta admin (/api/map-points, /api/proker-points, /api/admin/clear-all-points)
// dipindah ke routes/web.php supaya bisa memakai session auth + CSRF.
