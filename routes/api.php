<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ApixController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResCepreController;
use App\Http\Controllers\SancionadoController;


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
//Route::get('/v1/get-observados/{p}/{dni}', [SancionadoController::class, 'getObservados']);

Route::get('/v1/observados-cepre/{dni}', [SancionadoController::class, 'getSancionadoCepre'])->middleware('cepre');
Route::get('/v1/observados-cepre-libre/{dni}', [SancionadoController::class, 'getSancionadoCepre']);

Route::get('/obtener-origin', function (Request $request) {
    $respuesta = Http::get('https://inscripciones.admision.unap.edu.pe/api/v1/observados-cepre/70757838');
    $contenido = $respuesta->getBody()->getContents();
    return response()->json($contenido);
});

Route::get('/obtener-origin2', function (Request $request) {    
    $origin = $request->header('Origin');
    return response()->json(['origin' => $origin]);
});
