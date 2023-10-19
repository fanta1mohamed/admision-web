<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SimulacroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\FilialController;
use App\Http\Controllers\ModalidadController;
use App\Http\Controllers\ApoderadoController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\PreinscripcionController;
use App\Http\Controllers\SeleccionDataController;
use App\Http\Controllers\ColegioController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\DetalleExamenVocacionalController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\DashboardController;


Route::middleware('auth')->get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified','revisor','admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/about', fn () => Inertia::render('About'))->name('about');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::prefix('admin')->middleware('auth','admin')->group(function () {

    Route::get('/', fn () => Inertia::render('Admin/Dashboard/Index'));
    Route::get('dashboard', fn () => Inertia::render('Admin/Dashboard/Index'))->name('admin-dashboard');
    Route::get('get-preinscritos', [DashboardController::class, 'preinscritos']);
    Route::get('get-inscritos', [DashboardController::class, 'inscritos']);
    Route::get('get-mejores-inscriptores', [DashboardController::class, 'mInscriptores']);
    Route::post('get-mejores-inscriptores-dia', [DashboardController::class, 'mInscriptoresDia']);

    Route::resource('roles',RolController::class);
    Route::get('roles', fn () => Inertia::render('Roles/index'))->name('roles-index');
    Route::resource('usuarios',UsuarioController::class);
    Route::get('usuarios', fn () => Inertia::render('Usuarios/index'))->name('usuarios-index');

    Route::get('/get-permission', [RolController::class, 'getPermission'])->name('get');
    Route::get('/get-roles', [RolController::class, 'getRoles']);
    Route::post('/save-rol', [RolController::class, 'saveRol']);

    Route::get('/get-usuarios', [UsuarioController::class, 'getUsuarios']);
    Route::get('/get-roles-u', [UsuarioController::class, 'getRoles']);
    Route::post('/save-user',[UsuarioController::class, 'saveUsuario']);

    Route::get('/get-permisos', [UsuarioController::class, 'getPermisos']);

    Route::post('/inscripciones/get-postulantes', [InscripcionController::class, 'getPostulantes']);

    Route::get('/inscripciones/impresion', [InscripcionController::class, 'index'])->name('impresion-cepre');
    Route::get('/inscripciones/get-postulante-dni/{dni}', [InscripcionController::class, 'getPostulanteByDni']);
    Route::get('/inscripciones/get-apoderados-postulante/{dni}', [InscripcionController::class, 'getApoderados']);
    Route::get('/inscripciones/get-vouchers-postulante/{dni}', [InscripcionController::class, 'getVouchers']);
    Route::get('/inscripciones/get-documentos-postulante/{dni}', [InscripcionController::class, 'getDocumentos']);
    Route::get('/inscripciones/get-preinscripciones-postulante/{dni}', [InscripcionController::class, 'getPreinscipciones']);
    Route::get('/inscripciones/get-inscripciones-postulante/{dni}', [InscripcionController::class, 'getInscripciones']);
    Route::get('/pdf-inscripción/{dni}', [InscripcionController::class, 'pdfInscripcion']);
    Route::post('/inscripciones/inscribir', [InscripcionController::class, 'Inscribir']);

    Route::post('/get-inscripciones-admin', [InscripcionController::class, 'getInscripcionesAdmin']);
    Route::post('/get-preinscripciones-admin', [PreinscripcionController::class, 'getPreinscripcionesAdmin']);
    Route::post('/actualizar-sexo-postulante', [PreinscripcionController::class, 'actualizarSexo']);
    Route::post('/actualizar-preinscripcion', [PreinscripcionController::class, 'Actualizar']);    


    Route::get('/procesos', [ProcesoController::class, 'index'])->name('proceso-index');
    Route::get('/eliminar-proceso/{id}', [ProcesoController::class, 'deleteProceso']);
    Route::get('/procesos/get-tipos', [ProcesoController::class, 'getTipoProceso']);
    Route::get('/procesos/get-modalidades', [ProcesoController::class, 'getModalidades']);
    Route::post('/procesos/get-procesos', [ProcesoController::class, 'getProcesos']);
    Route::post('/save-proceso', [ProcesoController::class, 'saveProceso']);
    //Route::get('/get-has-permission/{rol}', [BlogController::class, 'getPermission']);

    //PREINSCRIPCION
    Route::post('/get-postulante-datos-personales', [PostulanteController::class, 'getPostulanteXDni']);
    Route::get('/inscripciones', fn () => Inertia::render('Admin/Inscripcion/index'))->name('admin-inscripciones');

    Route::post('/actualizar-inscripcion', [InscripcionController::class, 'Actualizar']);
    
    Route::get('/preinscripciones', fn () => Inertia::render('Admin/Preinscripcion/index'))->name('admin-preinscripciones');

    // Route::post('/save-programa', [ProgramaController::class, 'savePrograma']);
    // Route::post('/programas/get-programas', [ProgramaController::class, 'getProgramas']);
    // Route::get('/eliminar-programa/{id}', [ProgramaController::class, 'deletePrograma']);

    //fILIAL 
    Route::get('/filial', [FilialController::class, 'index'])->name('filial-index');
    Route::post('/filiales/get-filiales', [FilialController::class, 'getFiliales']);
    Route::post('/save-filial', [FilialController::class, 'saveFilial']);
    Route::get('/eliminar-filial/{id}', [FilialController::class, 'deleteFilial']);
    
    //PROGRAMA
    Route::get('/programa', [ProgramaController::class, 'index'])->name('programa-index');
    Route::post('/save-programa', [ProgramaController::class, 'savePrograma']);
    Route::post('/programas/get-programas', [ProgramaController::class, 'getProgramas']);
    Route::get('/eliminar-programa/{id}', [ProgramaController::class, 'deletePrograma']);

    //APODERADOS    
    Route::post('/get-apoderados-admin', [ApoderadoController::class, 'getApoderadoAdmin']);
    Route::post('/save-apoderados-admin', [ApoderadoController::class, 'saveApoderadoAdmin']);

    //MODALIDAD
    Route::get('/modalidad', [ModalidadController::class, 'index'])->name('modalidad-index');
    Route::post('/save-modalidad', [ModalidadController::class, 'saveModalidad']);
    Route::post('/modalidad/get-modalidades', [ModalidadController::class, 'getModalidades']);
    Route::get('/eliminar-modalidad/{id}', [ModalidadController::class, 'deleteModalidad']);    

    //PRE-INSCRIPCIONES
    Route::get('/pre-inscripcion', [PreinscripcionController::class, 'index'])->name('preincripcion-index');
    // Route::get('/pre-inscripcion', [PreinscripcionController::class, 'index'])->name('preincripcion-index');
    // Route::get('/pre-inscripcion', [PreinscripcionController::class, 'index'])->name('preincripcion-index');
    // Route::get('/pre-inscripcion', [PreinscripcionController::class, 'index'])->name('preincripcion-index');

    //GET DATA 
    Route::get('/get-facultades', [SeleccionDataController::class, 'getFacultades']);
    Route::post('/procesos/get-sedes', [SeleccionDataController::class, 'getSedes']);
    Route::post('/get-departamentos', [SeleccionDataController::class, 'getDepartamento']);
    Route::get('/get-provincia-x-departamento/{cod}', [SeleccionDataController::class, 'getProvinciasPorDepartamento']);
    Route::post('/pre-inscripcion/get-comprobantes', [SeleccionDataController::class, 'getComprobanteByDni']);

    Route::post('/get-departamentos-codigo', [SeleccionDataController::class, 'getDepartamentoCodigo']);
    Route::post('/get-provincias-codigo', [SeleccionDataController::class, 'getProvinciasCodigo']);
    
    Route::post('/get-distritos-codigo', [SeleccionDataController::class, 'getDistritosCodigo']);

    Route::get('/foto-inscripcion', fn () => Inertia::render('Foto/foto'))->name('admin-foto-inscripcion');
    Route::get('/foto-biometrico', fn () => Inertia::render('Foto/fotobiometrico'))->name('admin-foto-biometrico');    
    Route::post('/guardar-foto-inscripcion', [FotoController::class, 'guardarFotoInscripcion']);
    Route::post('/guardar-foto-biometrico', [FotoController::class, 'guardarFotoBiometrico']);

    Route::get('/reporte', fn () => Inertia::render('Admin/Vocacional/index'))->name('admin-reporte');
    Route::get('/resultados-vocacional', [PreguntaController::class, 'getResultado']);


    Route::get('/apoderados', fn () => Inertia::render('Admin/Apoderados/index'))->name('admin-apoderado-index');
    Route::get('/postulante', fn () => Inertia::render('Admin/Postulante/index'))->name('admin-postulante-index');
    Route::get('/colegio', fn () => Inertia::render('Admin/Colegio/index'))->name('admin-colegio-index');
    Route::get('/documento', fn () => Inertia::render('Admin/Documentos/index'))->name('admin-documento-index');
    Route::post('/get-colegios-admin', [ColegioController::class, 'getColegiosAdmin']);
    Route::post('/get-documentos-admin', [DocumentoController::class, 'getDocumentosAdmin']);
    Route::post('/save-documento', [DocumentoController::class, 'saveDocumentoAdmin']);
    
    Route::post('/get-postulantes-admin', [PostulanteController::class, 'getPostulantesAdmin']);
    Route::post('/save-postulante-admin', [PostulanteController::class, 'savePostulanteAdmin']);
    // Route::post('/modalidad/get-modalidades', [ModalidadController::class, 'getModalidades']);
    // Route::get('/eliminar-modalidad/{id}', [ModalidadController::class, 'deleteModalidad']);    


    Route::get('/component', fn () => Inertia::render('Admin/Dashboard/components/reportes'));

    //REPORTES VARIOS 
    Route::get('get-inscritos-genero-reporte', [DashboardController::class, 'reporteInscritosGenero']);
    Route::get('get-inscritos-edad-reporte', [DashboardController::class, 'reporteInscritosEdad']);
    Route::get('get-inscritos-residencia-reporte', [DashboardController::class, 'reporteInscritosResidencia']);
    Route::get('get-inscritos-procedencia-reporte', [DashboardController::class, 'reporteInscritosProcedencia']);
    Route::get('get-inscritos-egreso-reporte', [DashboardController::class, 'reporteInscritosEgreso']);
    Route::get('get-inscritos-discapacidad-reporte', [DashboardController::class, 'reporteInscritosDiscapacidad']);
    Route::get('get-inscritos-tipo-documento-reporte', [DashboardController::class, 'reporteInscritosTipoDocumento']);
    Route::get('get-inscritos-colegio-reporte', [DashboardController::class, 'reporteInscritosColegio']);
    Route::get('get-inscritos-procedencia-colegio-reporte', [DashboardController::class, 'reporteInscritosProcedenciaColegio']);
    Route::get('get-inscritos-tipo-colegio-reporte', [DashboardController::class, 'reporteInscritosTipoColegio']);


    //POSTULANTE
    Route::get('/perfil-postulante', fn () => Inertia::render('Admin/Postulante/Perfil'));
    Route::get('postulante-perfil/{dni}', [DashboardController::class, 'showPostulante']);
    Route::post('get-procesos', [DashboardController::class, 'getInsPostulante']);

    
    //PARTICIPANTES
    Route::get('/participante-docente', fn () => Inertia::render('Admin/Participante/Docente'))->name('admin-participante-docente');
    Route::get('/participante-administrativo', fn () => Inertia::render('Admin/Participante/Administrativo'))->name('admin-participante-administrativo');
    Route::get('/participante-sorteo', fn () => Inertia::render('Admin/Participante/Sorteo'))->name('admin-participante-sorteo');

    Route::post('/save-docente', [DocenteController::class, 'saveDocente']);   
    Route::post('/get-docentes', [DocenteController::class, 'getDocentes']);
    Route::post('/eliminar-docente', [DocenteController::class, 'deleteDocente']);
    Route::post('/actualizar-sexo-docente', [DocenteController::class, 'actualizarSexo']);

    Route::post('/save-administrativo', [AdministrativoController::class, 'saveAdministrativo']);   
    Route::post('/get-administrativos', [AdministrativoController::class, 'getAdministrativos']);   
    Route::post('/eliminar-administrativo', [AdministrativoController::class, 'deleteAdministrativo']);
    Route::post('/actualizar-sexo-administrativo', [AdministrativoController::class, 'actualizarSexo']);

});

Route::prefix('revisor')->middleware('auth','revisor')->group(function () {

    Route::get('/', fn () => Inertia::render('Revisor/revisor'))->name('revisor');
    Route::get('/validacion', fn () => Inertia::render('Revisor/validacion'))->name('revisor-validacion');
    Route::get('/documentos', fn () => Inertia::render('Revisor/documentos'))->name('revisor-documentos');
    Route::get('/imprimir', fn () => Inertia::render('Revisor/imprimir'))->name('revisor-imprimir');
    Route::get('/postulantes', fn () => Inertia::render('Revisor/postulantes'))->name('revisor-postulantes');
    
    Route::post('/get-certificados-revision', [DocumentoController::class, 'getCertificadosRevision']);
    Route::post('/cambiar-estado', [DocumentoController::class, 'cambiarEstado']);
    Route::post('/get-comprobantes', [SeleccionDataController::class, 'getComprobantesDNI']);
    Route::post('/verificar-comprobante', [SeleccionDataController::class, 'verificarComprobante']);

    Route::get('/get-requisitos', [SeleccionDataController::class, 'getRequisitos']);    
    Route::post('/save-requisito', [SeleccionDataController::class, 'saveReq']);    
        
    Route::post('/get-postulantes', [SeleccionDataController::class, 'getPostulantes']);
    Route::post('/get-postulante-dni', [SeleccionDataController::class, 'getPostulanteByDni']);

    Route::post('/get-postulante-requisitos', [SeleccionDataController::class, 'getPostulanteRequisitos']);
    Route::post('/get-postulantes-requisitos', [SeleccionDataController::class, 'getRequisitoPostulantes']);

    Route::get('/avance', [TestController::class, 'saveAvance']);

    Route::get('/foto-inscripcion', fn () => Inertia::render('Foto/foto'))->name('foto-inscripcion');
    Route::get('/foto-biometrico', fn () => Inertia::render('Foto/fotobiometrico'))->name('foto-biometrico');    
    Route::post('/guardar-foto-inscripcion', [FotoController::class, 'guardarFotoInscripcion']);
    Route::post('/guardar-foto-biometrico', [FotoController::class, 'guardarFotoBiometrico']);

    Route::post('/control-biometrico', [IngresoController::class, 'biometrico']);

    Route::get('/impresion', fn () => Inertia::render('Revisor/impresion'))->name('revisor-impresion-inscripcion');
    Route::get('/get-postulante-dni/{dni}', [InscripcionController::class, 'getPostulanteByDni']);
    Route::get('/get-apoderados-postulante/{dni}', [InscripcionController::class, 'getApoderados']);
    Route::get('/get-vouchers-postulante/{dni}', [InscripcionController::class, 'getVouchers']);
    Route::get('/get-documentos-postulante/{dni}', [InscripcionController::class, 'getDocumentos']);
    Route::get('/get-preinscripciones-postulante/{dni}', [InscripcionController::class, 'getPreinscipciones']);
    Route::get('/get-inscripciones-postulante/{dni}', [InscripcionController::class, 'getInscripciones']);
    Route::get('/pdf-inscripción/{dni}', [InscripcionController::class, 'pdfInscripcion']);
    Route::post('/inscribir', [InscripcionController::class, 'Inscribir']);

    Route::get('/seguimiento', fn () => Inertia::render('Revisor/seguimiento'))->name('revisor-seguimiento');

    Route::post('/actualizar-postulante', [PostulanteController::class, 'actualizarDatos']);
    Route::post('/actualizar-ingresante', [PostulanteController::class, 'actualizarDatosIngresante']);

    Route::get('/get-ingresante/{dni}', [IngresoController::class, 'getDatosIngreso']);
    Route::get('/get-ingresante-general/{dni}', [IngresoController::class, 'getDatosIngresoGeneral']);

    Route::get('/get-codigo/{dni}', [IngresoController::class, 'getCodigo']);


    Route::get('/api-pagos/{parametro}', function ($parametro) {
        try {
            $response = Http::get('http://unap.scielodigital.net.pe/caja/pago_admision/server/CHECK_PAYMENT/?w=' . $parametro);
            return response($response->body(), $response->status())->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la solicitud'], 500);
        }
    });

});
Route::get('/pdf-datos-biometrico/{dni}', [IngresoController::class, 'pdfbiometrico2']);


Route::get('/examen-vocacional2', fn () => Inertia::render('Publico/exvocacional2'))->name('ex-vocacional2');
Route::post('/get-avance-postulante', [TestController::class, 'getAvancePostulante']);
Route::post('/get-avance-postulante2', [TestController::class, 'getAvancePostulante2']);

Route::post('/get-preguntas2', [PreguntaController::class, 'getPreguntas2']);
Route::post('/get-alternativas2', [PreguntaController::class, 'getAlternativas2']);

Route::get('/get-pre', [PreguntaController::class, 'getPreguntasPerfiles2']);

Route::prefix('simulacro')->middleware('auth','simulacro')->group(function () {
    Route::get('/', fn () => Inertia::render('Simulacro/index'))->name('simulacros');
    Route::get('/simulacros', fn () => Inertia::render('Simulacro/Simulacros'))->name('simulacro-simulacros');
    Route::get('/calificacion', fn () => Inertia::render('Simulacro/Ficha'))->name('simulacro-calificacion');

    Route::post('/save-simulacro', [SimulacroController::class, 'saveSimulacro']);
    Route::post('/get-simulacros', [SimulacroController::class, 'getSimulacros']);
    Route::post('/get-participantes', [SeleccionDataController::class, 'getParticipantes']);    
    Route::post('/get-participantes-simulacro', [SimulacroController::class, 'getParticipantesSimulacro']);    
    Route::post('/save-respuestas', [SimulacroController::class, 'saveRespuestas']);

});

Route::prefix('simulacros')->group(function () {
    Route::get('/formulario-inscripcion', fn () => Inertia::render('Simulacro/formulario'));
});


//PREINSCRIPCION
Route::get('/preinscripcion-adicional', fn () => Inertia::render('Publico/preinscripcion'))->name('preinscripcion');
//Route::get('/preinscripcion', fn () => Inertia::render('Publico/preinscripcion'))->name('preinscripcion');
Route::get('/preinscripcion-general', fn () => Inertia::render('Publico/preinscripciongeneral'))->name('preinscripcion-general');
Route::get('/examen-vocacional', fn () => Inertia::render('Publico/exvocacional'))->name('ex-vocacional');

Route::post('/save-respuesta', [DetalleExamenVocacionalController::class, 'saveRespuesta']);


Route::post('save-pasos-preinscripcion', [PreinscripcionController::class, 'savePasos']);
Route::post('/get-postulante-datos-personales', [PostulanteController::class, 'getPostulanteXDni']);
Route::post('/save-postulante-dni', [PostulanteController::class, 'saveDniPostulante']);
Route::post('/save-postulante', [PostulanteController::class, 'savePostulante']);
Route::post('/save-postulante-residencia', [PostulanteController::class, 'saveResidencia']);
Route::post('/save-postulante-colegio', [PostulanteController::class, 'saveColegio']);
Route::post('/save-postulante-apoderado', [ApoderadoController::class, 'saveApoderado']);
Route::post('save-pre-inscripcion', [PreinscripcionController::class, 'preinscribir']);
Route::get('pdf', [PreinscripcionController::class, 'pdf']);

Route::post('/get-departamentos-codigo', [SeleccionDataController::class, 'getDepartamentoCodigo']);
Route::post('/get-provincias-codigo', [SeleccionDataController::class, 'getProvinciasCodigo']);
Route::post('/get-distritos-codigo', [SeleccionDataController::class, 'getDistritosCodigo']);
Route::post('/get-ubigeo-colegio', [ColegioController::class, 'getUbigeoColegio']);
Route::post('/get-colegio-distrito', [ColegioController::class, 'getColegiosDistrito']);

Route::post('/get-colegio-distrito', [ColegioController::class, 'getColegiosDistrito']);
Route::post('/get-apoderado', [ApoderadoController::class, 'getApoderado']);
Route::post('/get-pasos-proceso', [SeleccionDataController::class, 'getPasos']);

Route::post('/get-preguntas', [PreguntaController::class, 'getPreguntasPrograma']);
Route::post('/get-preguntas-perfiles', [PreguntaController::class, 'getPreguntasPerfiles']);

Route::post('/get-datos-examen', [PreguntaController::class, 'getDatosExamen']);
Route::post('/get-datos-examen2', [PreguntaController::class, 'getDatosExamen2']);

Route::post('/save-vocacional', [DetalleExamenVocacionalController::class, 'saveVocacional']);

Route::get('/pdf-vocacional/{dni}', [PreinscripcionController::class, 'pdfvocacional']);
Route::get('/pdf-solicitud/{dni}', [PreinscripcionController::class, 'pdfsolicitud']);

Route::post('/control-biometrico', [IngresoController::class, 'biometrico']);

Route::get('/documentos-pdfs/{dni}', [PreinscripcionController::class, 'UnirPDF']);
Route::get('/siguiendo-mi-postulacion', fn () => Inertia::render('Publico/estado'));
#Route::get('/get-expediente/{programa}/{dni}', [TestController::class, 'getNroConstancia']);

Route::get('/participa/{dni}', [PostulanteController::class, 'participa']);
//Editor

Route::get('/ver-puntaje', fn () => Inertia::render('Publico/puntaje'));
Route::get('/aleatorio', fn () => Inertia::render('Publico/aleatorio'));

Route::get('/resultados', fn () => Inertia::render('Resultados/index'))->name('resultados');

//Seguimiento
Route::get('/test', fn () => Inertia::render('Prueba/test'));
//Route::get('/', [BlogController::class, 'verPuntajes']);
Route::get('/get-puntajes/{dni}', [BlogController::class, 'getPuntajes']);
Route::get('/constancias-ingreso', [IngresoController::class, 'constanciasIngreso']);


Route::get('/pago-banco-nacion',[PagoController::class,'getPagosBancoNacion']);
Route::get('/get-pagos',[PagoController::class,'getPagos']);
Route::get('/get-pagos-caja',[PagoController::class,'getPagosCaja']);


//SIMULACROS
Route::post('/get-ubigeo', [SeleccionDataController::class, 'getUbigeos']);
Route::post('/get-colegios-ubigeo',[SeleccionDataController::class,'getColegiosUbigeo']);
Route::post('/save-simulacro-participante', [SimulacroController::class, 'saveParticipante']);

Route::get('/get-e-oti', [IngresoController::class, 'getEstudianteOTI']);



Route::get('/get-biometricos-extraordinarios', function () {
    $documentos = [
        '60836477','73749385','76766782','75148837','76619018','74410191','71938666','74210522','76650758','74057961','73647476','76316812','60174501','75773845','75828188','74695346','71476905','72220072',
        '73318957','71868361','73809219','73385750','74695063','74984922','73953518','73821195','77030707','76651131','62271229','73266575','77073195','73380990','75793924','42951068','73113874','73811240',
        '71955896','75784783','73532565','75486134','76776151','77130071','73333888','71892811','71442782','76646804','76582589','75842724','73648584','73818926','74237991','76797370','75670999','71439193',
        '46925867','75257148','74868267','74057031','75997933','77818316','60443077','71505574','74601033','74938634','75498340','75690091','73523846','75090568','74527219','75937257','73525046','71534521',
        '74624479','75449951','74568475','75573642','72169334','75317017','76970313','73764067','60422495','70760840','73811586','74575054','73747948','75981167','74704631','74822823','71449272','70439234',
        '77473209','70094794','71868472','72430115','72542571','72816673','73576735','74396959','72011705','75554389','75787096','81274424','77215512','75055641','73818929','74120280','73385652','73525048',
        '75654456','60417080','74808838','60758689',
    ];

    foreach ($documentos as $documento) {
        $requestBody = [
            'doc_' => $documento,
            'nom_' => 'NAYE',
            'app_' => 'AEDO',
            'apm_' => '',
        ];
        $response = Http::post('https://service2.unap.edu.pe/TieneCarrerasPrevias/' . $documento, $requestBody);
        $responseData = $response->json();

        if ($response != null && !empty($response->json())) {
            $responseData = $response->json();

            // Supongamos que deseas guardar la respuesta en una tabla ficticia en la base de datos
            DB::table('biometricos')->insert([
                'dni' => $documento,
                'name' => $responseData[0]['name'],
                'code' => $responseData[0]['code'],
                'career' => $responseData[0]['career'],
                'cond1tion' => $responseData[0]['cond1tion'],
            ]);
        }

    }

    return 'Solicitudes POST enviadas y respuestas guardadas en la base de datos ficticia.';
});







require __DIR__.'/auth.php';



