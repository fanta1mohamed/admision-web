<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ApixController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResCepreController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-ingresante/{dni}/{anio}/{ciclo}', [ApixController::class, 'getIngresante']);
    Route::get('/get-postulante-pago/{dni}/{proceso}', [ApixController::class, 'getPostulantePago']);
});

Route::get('/get-ingresante-pago/{dni}/{anio}/{ciclo}', [ApixController::class, 'getIngresantePago']);
Route::get('/get-postulante-biometrico/{codigo}', [ApixController::class, 'getBiometrico']); 

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-puntaje/{dni}', [BlogController::class, 'getPuntajes']);

Route::get('/v1/resultados_simulacro/{dni}', [ResCepreController::class, 'obtenerInformacionEstudiante']);