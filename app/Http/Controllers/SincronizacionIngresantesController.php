<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulante;
use App\Models\RegistroSincronizacion;
use App\Models\Proceso;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SincronizacionIngresantesController extends Controller {

    public function getUltimoIngresanteSincronizacion(){

      $datos = null;
      $procesos = Procesos::orderBy('id')->pluck('id');
      $ultimoRegistro = RegistroSincronizacion::where('servicio', 'SERVICIO_1')
        ->orderBy('ultima_fecha_sincronizacion', 'desc')
        ->first();

      if($ultimo_registro){


      }else{

          $registro = ControlBiometrico::select([
          'control_biometrico.id as id_control_biometrico',
          'control_biometrico.codigo_ingreso as codigo',
          'postulante.celular',
          'postulante.email',
          'resultados.fecha as f_examen',
          'modalidad.nombre as modalidad',
          'programa.nombre as programa',
          'postulante.nro_doc as DNI',
          'postulante.nombres',
          'postulante.primer_apellido',
          'postulante.segundo_apellido',
          DB::raw('IF(postulante.sexo = 1, "M", "F") as sexo'),
          'postulante.fec_nacimiento',
          'postulante.ubigeo_nacimiento',
          'departamento.nombre as departamento',
          'provincia.nombre as provincia',
          'distritos.nombre as distrito',
          'postulante.estado_civil',
          'postulante.anio_egreso',
          'colegios.nombre as colegio',
          'colegios.gestion',
          'colegios.ubigeo as c_ubigeo',
          'postulante.direccion'
      ])
      ->join('postulante', 'postulante.id', '=', 'control_biometrico.id_postulante')
      ->join('inscripciones', function($join) {
          $join->on('inscripciones.id_postulante', '=', 'postulante.id')
              ->where('inscripciones.id_proceso', 4);
      })
      ->join('resultados', function($join) {
          $join->on('resultados.dni_postulante', '=', 'postulante.nro_doc')
              ->where('resultados.id_proceso', 4);
      })
      ->join('ubigeo', 'postulante.ubigeo_nacimiento', '=', 'ubigeo.ubigeo')
      ->join('departamento', 'ubigeo.id_departamento', '=', 'departamento.id')
      ->join('provincia', 'ubigeo.id_provincia', '=', 'provincia.id')
      ->join('distritos', 'ubigeo.id_distrito', '=', 'distritos.id')
      ->join('modalidad', 'inscripciones.id_modalidad', '=', 'modalidad.id')
      ->join('programa', 'programa.id', '=', 'inscripciones.id_programa')
      ->join('colegios', 'colegios.id', '=', 'postulante.id_colegio')
      ->where('inscripciones.estado', 0)
      ->where('control_biometrico.id_proceso', 4)
      ->orderBy('control_biometrico.id')
      ->first();

      return response()->json([ 'status' => true, 'data' => $registro ],200);

      }








      return response()->json([ 'status' => true, 'data' => $datos ],200);
    }

    private function contarDatosPendientes($id_proceso){



    }





}
