<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SpaceController;
use App\Http\Controllers\Api\SpaceTypeController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Catálogo (admin, staff y client)
    Route::middleware('permission:spaces.view')->group(function () {
        Route::get('/spaces', [SpaceController::class, 'index']);
        Route::get('/spaces/{space}', [SpaceController::class, 'show']);
        Route::get('/space-types', [SpaceTypeController::class, 'index']);
    });

    // CRUD de espacios (admin)
    Route::post('/spaces', [SpaceController::class, 'store'])->middleware('permission:spaces.create');
    Route::put('/spaces/{space}', [SpaceController::class, 'update'])->middleware('permission:spaces.update');
    Route::delete('/spaces/{space}', [SpaceController::class, 'destroy'])->middleware('permission:spaces.delete');

    // CRUD de tipos de espacio (admin)
    Route::get('/space-types/{spaceType}', [SpaceTypeController::class, 'show'])->middleware('permission:space_types.view');
    Route::post('/space-types', [SpaceTypeController::class, 'store'])->middleware('permission:space_types.create');
    Route::put('/space-types/{spaceType}', [SpaceTypeController::class, 'update'])->middleware('permission:space_types.update');
    Route::delete('/space-types/{spaceType}', [SpaceTypeController::class, 'destroy'])->middleware('permission:space_types.delete');
});