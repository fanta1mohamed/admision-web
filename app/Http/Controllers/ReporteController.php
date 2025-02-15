<?php

namespace App\Http\Controllers;
use App\Models\Simulacro;
use App\Models\Proceso;
use App\Models\Ide;
use Illuminate\Http\Request;
use Pdf;
use DB;

class ReporteController extends Controller
{
    public function reportePrograma() {

        $sim = auth()->user()->id_proceso;
        $proceso = Proceso::find($sim);
    
            $res = DB::select("SELECT 
                pre.cod_pro AS codigo,
                COALESCE(pre.programa, ins.programa) AS programa,
                COALESCE(ins.inscripciones, 0) AS inscripciones,
                COALESCE(pre.preinscripciones, 0) AS preinscripciones,
                COALESCE(pre.preinscripciones, 0) - COALESCE(ins.inscripciones, 0) AS diferencia
            FROM (
                SELECT pro.id AS cod_pro, pro.nombre AS programa, COUNT(*) AS preinscripciones FROM pre_inscripcion pre
                JOIN programa pro ON pre.id_programa = pro.id
                WHERE pre.id_proceso = ".auth()->user()->id_proceso." AND pre.estado = 1
                GROUP BY pro.id, pro.nombre
            ) pre
            INNER JOIN (
                SELECT pro.nombre AS programa, COUNT(*) AS inscripciones
                FROM inscripciones ins
                JOIN programa pro ON ins.id_programa = pro.id
                WHERE ins.id_proceso = ".auth()->user()->id_proceso." AND ins.estado = 0
                GROUP BY pro.nombre
            ) ins ON pre.programa = ins.programa
            ORDER BY pre.cod_pro");

        $totales = DB::select("SELECT
            SUM(COALESCE(ins.inscripciones, 0)) AS total_inscripciones,
            SUM(COALESCE(pre.preinscripciones, 0)) AS total_preinscripciones,
            SUM(COALESCE(pre.preinscripciones, 0)) - SUM(COALESCE(ins.inscripciones, 0)) AS total_diferencia
        FROM (
            SELECT COUNT(*) AS preinscripciones
            FROM pre_inscripcion pre
            WHERE pre.id_proceso = ".auth()->user()->id_proceso." AND pre.estado = 1
            ) pre
        INNER JOIN (
        SELECT COUNT(*) AS inscripciones
            FROM inscripciones ins
            WHERE ins.id_proceso = ".auth()->user()->id_proceso." AND ins.estado = 0
            ) ins;");
    

        $pdf = Pdf::loadView('Reportes.programa_pre_ins', compact('res', 'proceso','totales'));
        $pdf->getDomPDF()->set_option("isPhpEnabled", true);
        $pdf->getDomPDF()->set_option("isHtml5ParserEnabled", true);
        $pdf->setPaper('A4', 'portrait');
    

        $rutaCarpeta = public_path("/documentos/$sim/reportes/");
        $rutaArchivo = $rutaCarpeta . 'ReporteProgramas_' . date('Y-m-d_H-i-s') .auth()->id(). '.pdf';
    
        if (!file_exists($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0755, true);
        }
    
        file_put_contents($rutaArchivo, $pdf->output());
    
        return $pdf->stream();
    }

}
