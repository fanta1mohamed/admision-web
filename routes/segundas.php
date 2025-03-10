<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Segundas\ProgramaSegundaController;
use App\Http\Controllers\Segundas\PreinscripcionSegundasController;
use App\Http\Controllers\Segundas\PostulanteSegundaController;
use App\Http\Controllers\Segundas\VacantesSegundaController;
use App\Http\Controllers\Segundas\ModalidadSegundaController;
use App\Http\Controllers\Segundas\ObservadosSegundaController;
use App\Http\Controllers\Segundas\ResumenesSegundaController;


Route::get('/segundas', fn () => Inertia::render('Segundas/Admin/Preinscripciones/index'))->name('segundas-index');


// Route::middleware('auth')->group(function () { });

Route::prefix('segundas')->middleware('segundas')->group(function () {

    //PROGRAMAS 
    Route::post('select-programas-segundas', [ProgramaSegundaController::class, 'getSelectProgramas']);
    Route::post('select-programas-segundas-autorizados', [ProgramaSegundaController::class, 'getSelectProgramasAutorizados']);

    //PREINSCRIPCION
    Route::get('/preinscripciones', fn () => Inertia::render('Segundas/Admin/Preinscripciones/index'))->name('segundas-preinscripciones-admin');
    Route::post('get-postulantes-segundas', [PostulanteSegundaController::class, 'getPostulantes']);
    Route::get('postulante-perfil/{dni}', [PostulanteSegundaController::class, 'showPostulante']);
    
    
    Route::post('actualizar-preinscripciones-segundas', [PreinscripcionSegundasController::class, 'Actualizar']);


    //POSTULANTES
    Route::get('/postulantes', fn () => Inertia::render('Segundas/Admin/Postulantes/index'))->name('segundas-postulantes-admin');
    Route::post('get-preinscripciones-segundas', [PreinscripcionSegundasController::class, 'getPreinscripciones']);


    //VACANTES
    Route::get('/vacantes', fn () => Inertia::render('Segundas/Admin/Vacantes/index'))->name('segundas-vacantes-admin');
    Route::post('/get-vacantes-segundas-admin', [VacantesSegundaController::class, 'getVacantes']);
    Route::post('/save-numero-vacantes-segundas', [VacantesSegundaController::class, 'saveNumeroVacantes']);
    Route::post('/delete-vacante-segundas', [VacantesSegundaController::class, 'eliminar']);

    //MODALIDADES
    Route::get('/get-modalidades-segundas-activas', [ModalidadSegundaController::class, 'getModalidadesActivas']);

    //OBSERVADOS
    Route::get('/observados', fn () => Inertia::render('Segundas/Admin/Observados/index'))->name('segundas-observados-admin');
    Route::post('/get-observados-segundas', [ObservadosSegundaController::class, 'getObservados']);
    Route::post('/save-observado-segundas', [ObservadosSegundaController::class, 'save']);



    //REPORTES
    Route::post('/get-resumen-preinscripcion-segundas', [ResumenesSegundaController::class, 'getResumenPreinscripcion']);
    Route::post('/get-detalle-preinscripcion-segundas', [ResumenesSegundaController::class, 'getPreinscripciones']);

});











