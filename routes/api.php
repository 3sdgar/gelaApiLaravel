<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\WorkExperienceController;

// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login']);

// // Grupo de rutas protegidas por Sanctum (COMENTADO PARA PRUEBAS)
// Route::middleware('auth:sanctum')->group(function () {
    
//     // Ruta para obtener el usuario autenticado
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });

Route::apiResource('articles', ArticleController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('people', PersonController::class);

// Rutas de recursos anidados
Route::apiResource('people.studies', StudyController::class);
Route::apiResource('people.work-experiences', WorkExperienceController::class);

// Ruta específica para la subida de archivos
Route::post('people/{person}/studies/{study}/upload-file', [StudyController::class, 'storeFile']);

Route::get('/login', function () {
    return response()->json(['error' => 'Unauthenticated.'], 401);
})->name('login');
