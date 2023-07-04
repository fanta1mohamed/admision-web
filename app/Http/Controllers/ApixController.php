<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulante;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ApixController extends Controller {

    public function getIngresante($dni, $proceso){
        $res = Postulante::select(
            'control_biometrico.codigo_ingreso',
            'filial.codigo as codigo_sede_filial', 'tipo_proceso.id AS tipo_proceso',
            DB::raw("CONCAT( procesos.anio,'-',procesos.ciclo) as proceso_admision"),
            'procesos.nro_convocatoria AS numero_convocatoria', 
            'postulante.tipo_doc AS tipo_documento', 'postulante.nro_doc AS nro_documento', 'postulante.nombres', 'postulante.primer_apellido',
            'postulante.segundo_apellido', 'postulante.apellido_casada', 'postulante.solo_un_apellido',  'postulante.sexo', 
            'postulante.fec_nacimiento AS fecha_nacimiento', 'paises.codigo AS pais_nacimiento', 'paises.nacionalidad',
            'postulante.ubigeo_residencia', 'postulante.ubigeo_nacimiento', 'postulante.discapacidad', 
            'postulante.tipo_discapacidad', 'postulante.celular', 'postulante.email', 
            'facultad.codigo AS codigo_facultad', 'programa.codigo AS codigo_programa', 'inscripciones.fecha AS fecha_postulacion',
            'resultados.puntaje', 
            'modalidad.codigo AS modalidad_admision',
            'procesos.id_modalidad_estudio AS modalidad_estudio',
            'resultados.apto AS es_ingresante', 
            'facultad.codigo AS codigo_facultad_unidad_ingreso',
            'programa.codigo AS codigo_programa_ingreso', 'resultados.fecha AS fecha_ingreso'
        )
        ->join('paises','paises.id','postulante.id_pais')
        ->join('inscripciones','inscripciones.id_postulante','postulante.id')
        ->join('programa','inscripciones.id_programa','programa.id')
        ->join('facultad','programa.id_facultad','facultad.id')
        ->join('resultados','resultados.dni_postulante','postulante.nro_doc')
        ->join('modalidad','inscripciones.id_modalidad','modalidad.id')
        ->join('procesos','procesos.id','inscripciones.id_proceso')
        ->join('filial','filial.id','procesos.id_sede_filial')
        ->join('tipo_proceso','tipo_proceso.id','procesos.id_tipo_proceso')
        ->join('control_bimetrico','control_biometrico.id_postulante','postulante.id')
        ->where('resultados.apto', '=','SI')
        ->where('procesos.id', '=',$proceso)
        ->where('nro_doc','=',$dni)->get();

        if (count($res) > 0 ){
            return response()->json(['status' => true, 'mensaje'=>'-', 'data' => $res[0]], 200);
        }else {
            return response()->json(['status' => false, 'mensaje'=>'Postulante no encontrado'], 203);
        }

    }


    public function getPostulantePago($dni, $proceso){
        $res = [];
        if($proceso == 4 ){
            $res = Postulante::select(
                'postulante.nro_doc', 'postulante.primer_apellido', 'postulante.segundo_apellido',
                'postulante.nombres', 'colegios.id_gestion',
                DB::raw("IF(colegios.id_gestion = 1, 21, IF((colegios.id_gestion = 2 OR colegios.id_gestion = 3), 21, IF(colegios.id_gestion = 4, 21, 0))) AS Monto")  
            )
            ->join('colegios','colegios.id','postulante.id_colegio')
            ->where('nro_doc','=',$dni)->get();
        }
        if($proceso == 5){
            $res = Postulante::select(
                'postulante.nro_doc', 'postulante.primer_apellido', 'postulante.segundo_apellido',
                'postulante.nombres', 'colegios.id_gestion',
                DB::raw("IF(colegios.id_gestion = 1, 200, IF((colegios.id_gestion = 2 OR colegios.id_gestion = 3), 350, IF(colegios.id_gestion = 4, 450, 0))) AS Monto")  
            )
            ->join('colegios','colegios.id','postulante.id_colegio')
            ->where('nro_doc','=',$dni)->get();
        }

        if (count($res) > 0 ){
            return response()->json(['status' => true, 'mensaje'=>'-', 'data' => $res[0]], 200);
        }else {
            return response()->json(['status' => false, 'mensaje'=>'Postulante no encontrado'], 203);
        }
    }

    public function getBiometrico($codigo){
        $res = DB::select("SELECT estado FROM control_biometrico
        WHERE codigo_ingreso = ".$codigo);

        if (count($res) > 0 ){
            return response()->json(['status' => true, 'data' => $res[0]], 200);
        }else {
            return response()->json(['status' => false, 'mensaje'=>'Postulante no encontrado'], 203);
        }

    }
    

    public function update(Request $request, Postulante $postulante){
        $rules = [
            'matricula'=>'required|string|max:6'];
            $validator = \Validator::make($request->input(), $rules);
            if($validator->fails()){
                return response()->json([
                    'status'=> false,
                    'errors'=> $validator->errors()->all()
                ],400);
            }
            $departament->update($request->input());
            return response()->json([
                'status' => true,
                'message' => 'Postulante actualizado'
            ],200);
    }
    public function destroy(Postulante $postulante){

    }









}
