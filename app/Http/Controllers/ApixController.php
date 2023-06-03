<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulante;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ApixController extends Controller {

    public function getPostulante($dni){
        $res = Postulante::select(
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
            'resultados.ingreso AS es_ingresante', 
            'facultad.codigo AS codigo_facultad_unidad_ingreso',
            'programa.codigo AS codigo_programa_ingreso', 'resultados.fecha AS fecha_ingreso'
        )
        ->join('paises','paises.id','postulante.id_pais')
        ->join('inscripciones','inscripciones.id_postulante','postulante.id')
        ->join('programa','inscripciones.id_programa','programa.id')
        ->join('facultad','programa.id_facultad','facultad.id')
        ->join('resultados','resultados.id_inscripcion','inscripciones.id')
        ->join('modalidad','inscripciones.id_modalidad','modalidad.id')
        ->join('procesos','procesos.id','inscripciones.id_proceso')
        ->join('filial','filial.id','procesos.id_sede_filial')
        ->join('tipo_proceso','tipo_proceso.id','procesos.id_tipo_proceso')
        ->where('nro_doc','=',$dni)->get();

        if (count($res) > 0 ){
            return response()->json(['status' => true, 'mensaje'=>'-', 'data' => $res[0]], 200);
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
