<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Segundas\ProgramaSegundaController;
use App\Http\Controllers\Segundas\PreinscripcionSegundasController;
use App\Http\Controllers\Segundas\PostulanteSegundaController;


Route::get('/segundas', fn () => Inertia::render('Segundas/Admin/Preinscripciones/index'))->name('segundas-index');


// Route::middleware('auth')->group(function () { });

Route::prefix('segundas')->middleware('segundas')->group(function () {

    //PROGRAMAS 
    Route::post('select-programas-segundas', [ProgramaSegundaController::class, 'getSelectProgramas']);

    //PREINSCRIPCION
    Route::get('/preinscripciones', fn () => Inertia::render('Segundas/Admin/Preinscripciones/index'))->name('segundas-preinscripciones-admin');
    Route::post('get-postulantes-segundas', [PostulanteSegundaController::class, 'getPostulantes']);
    Route::get('postulante-perfil/{dni}', [PostulanteSegundaController::class, 'showPostulante']);
    
    //Route::post('actualizar-preinscripciones-segundas', [PreinscripcionSegundasController::class, 'Actualizar']);


    //POSTULANTES
    Route::get('/postulantes', fn () => Inertia::render('Segundas/Admin/Postulantes/index'))->name('segundas-postulantes-admin');
    Route::post('get-preinscripciones-segundas', [PostulanteSegundaController::class, 'getPreinscripciones']);
    // Route::get('postulante-perfil/{dni}', [DashboardController::class, 'showPostulante'])->name('segundas-postulantes-admin');
    // Route::post('actualizar-preinscripciones-segundas', [PreinscripcionSegundasController::class, 'Actualizar']);
    // Route::get('/', fn () => Inertia::render('Admin/Dashboard/Index'));
    // Route::get('dashboard', fn () => Inertia::render('Admin/Dashboard/Index'))->name('admin-dashboard');
    // Route::get('get-preinscritos', [DashboardController::class, 'preinscritos']);
    // Route::get('get-inscritos', [DashboardController::class, 'inscritos']);
    // Route::get('get-mejores-inscriptores', [DashboardController::class, 'mInscriptores']);
    // Route::post('get-mejores-inscriptores-dia', [DashboardController::class, 'mInscriptoresDia']);


});











