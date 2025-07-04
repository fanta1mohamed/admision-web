<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramaProceso;
use App\Models\Programa;
use DB;

class ProgramaProcesoController extends Controller
{

    public function getProgramaProceso() {
        $res = DB::select('SELECT
            pro.nombre_corto AS programa,
            MAX(CASE WHEN pp.id_modalidad = 8 THEN "SI" ELSE "-" END) AS "8",
            MAX(CASE WHEN pp.id_modalidad = 7 THEN "SI" ELSE "-" END) AS "7",
            MAX(CASE WHEN pp.id_modalidad = 9 THEN "SI" ELSE "-" END) AS "9",
            MAX(CASE WHEN pp.id_modalidad = 1 THEN "SI" ELSE "-" END) AS "1",
            MAX(CASE WHEN pp.id_modalidad = 2 THEN "SI" ELSE "-" END) AS "2",
            MAX(CASE WHEN pp.id_modalidad = 3 THEN "SI" ELSE "-" END) AS "3",
            MAX(CASE WHEN pp.id_modalidad = 4 THEN "SI" ELSE "-" END) AS "4",
            MAX(CASE WHEN pp.id_modalidad = 5 THEN "SI" ELSE "-" END) AS "5"
        FROM vacantes pp
        JOIN programa pro ON pro.id = pp.id_programa
        WHERE pp.id_proceso = '. auth()->user()->id_proceso.'  and pp.estado = 1 GROUP BY pro.nombre_corto;');

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);

    }


    public function getSelectModalidadesProceso($id_proceso) {
        $res = DB::select("SELECT mo.id AS value, nombre AS label FROM (SELECT distinct id_modalidad FROM vacantes
        WHERE id_proceso = $id_proceso) AS pp
        JOIN modalidad mo ON mo.id = pp.id_modalidad");

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }


    public function getSelectProgramasProceso(Request $request) {
        $res = DB::select("SELECT programa.id AS value, programa.nombre AS label  FROM (SELECT id_programa FROM vacantes
            WHERE id_modalidad = $request->id_modalidad AND id_proceso = $request->id_proceso AND vacantes.estado = 1) AS vacantes
            JOIN programa ON programa.id = vacantes.id_programa");

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }
    

    public function getSelectProgramasProcesoAdmin() {

        $res = DB::select("SELECT id AS value, nombre AS label  FROM programa 
        WHERE id IN ( SELECT DISTINCT id_programa  FROM programas_proceso  WHERE id_proceso = ".auth()->user()->id_proceso.");");

        $this->response['estado'] = true;
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }


    public function getSelectProgramasProcesoArea(Request $request) {

      $res = DB::select("SELECT programa.id AS value, programa.nombre AS label
      FROM (
          SELECT id_programa
          FROM programas_proceso
          WHERE id_modalidad = :id_modalidad
                AND id_proceso = :id_proceso
                AND programas_proceso.estado = 1
          ) AS programas_proceso
          JOIN programa ON programa.id = programas_proceso.id_programa
          WHERE programa.area = :area", [
              'id_modalidad' => $request->id_modalidad,
              'id_proceso' => $request->id_proceso,
              'area' => $request->area,
          ]
  );

      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
  }




    public function saveProceso(Request $request ) {

        $proceso = null;
        if (!$request->id) {
            $proceso = ProgramaProceso::create([
                'id_modalidad' => $request->id_modalidad,
                'id_programa' => $request->id_programa,
                'estado' => $request->estado,
                'id_proceso' => auth()->user()->id_proceso,
                'id_usuario' => auth()->id()
            ]);
            $this->response['titulo'] = 'REGISTRO NUEVO';
            $this->response['mensaje'] = 'Proceso '.$proceso->nombre.' creado con exito';
            $this->response['estado'] = true;
            $this->response['datos'] = $proceso;

        }


        return response()->json($this->response, 200);
    }

    public function getAreaByCodigo($codigo){

      $res = DB::select("SELECT distinct pro.area FROM carreras_previas car
          JOIN programa pro ON car.cod_car = pro.programa_oti
          WHERE car.codigo = $codigo");

      $this->response['estado'] = true;
      $this->response['datos'] = $res[0];
      return response()->json($this->response, 200);

    }

}
