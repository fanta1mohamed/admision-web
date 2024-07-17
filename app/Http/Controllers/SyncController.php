<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pagos;
use App\Models\PagosUnap;
use DB;

class SyncController extends Controller
{
    public function syncTables(){
        $chunkSize = 1000;
    
        $sourceData = DB::connection('mysql_secondary')
        ->table('banco_pagos_admision')
        ->select(
            'cod_uni', 'num_mat', 'secuencia', 'tip_doc', 'situacion', 'concepto', 'tip_per', 'sede', 'num_doc', 'imp_pag',
            'tip_pag', 'for_pag',
            DB::raw("CASE WHEN fch_pag = '0000-00-00' OR fch_pag = '0000-00-00 00:00:00' THEN NULL ELSE fch_pag END AS fch_pag"),
            'hra_pag', 'cod_caj', 'cod_age', 'num_che', 'cod_ban', 'con_pag',
            DB::raw("CASE WHEN fch_env = '0000-00-00' OR fch_env = '0000-00-00 00:00:00' THEN NULL ELSE fch_env END AS fch_env"),
            'nom_cli', 'cuenta', 'ano_aca', 'per_aca', 'estado', 'fecha_usado'
        )
        ->whereIn('concepto', ['00000026', '00000039', '00000028', '00000027'])
        ->where(function ($query) {
            $query->where('fch_pag', '>', '2024-06-30')
                  ->orWhere('fch_env', '>', '2024-06-30');
        })
        ->get();
    
        if ($sourceData->isEmpty()) {
            return response()->json(['message' => 'No data found to insert.']);
        }
    
        $insertData = [];
    
        foreach ($sourceData as $data) {
            $insertData[] = [
                'cod_uni' => $data->cod_uni,
                'num_mat' => $data->num_mat,
                'secuencia' => $data->secuencia,
                'tip_doc' => $data->tip_doc,
                'situacion' => $data->situacion,
                'concepto' => $data->concepto,
                'tip_per' => $data->tip_per,
                'sede' => $data->sede,
                'num_doc' => $data->num_doc,
                'imp_pag' => $data->imp_pag,
                'tip_pag' => $data->tip_pag,
                'for_pag' => $data->for_pag,
                'fch_pag' => $this->validateDate($data->fch_pag),
                'hra_pag' => $data->hra_pag,
                'cod_caj' => $data->cod_caj,
                'cod_age' => $data->cod_age,
                'num_che' => $data->num_che,
                'cod_ban' => $data->cod_ban,
                'con_pag' => $data->con_pag,
                'fch_env' => $this->validateDate($data->fch_env),
                'nom_cli' => $data->nom_cli,
                'cuenta' => $data->cuenta,
                'ano_aca' => $data->ano_aca,
                'per_aca' => $data->per_aca,
                'estado' => ($data->estado === '1') ? 1 : 0,
                'fecha_usado' => $this->validateDateTime($data->fecha_usado),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
    
        // Insertar en lotes usando Eloquent
        if (!empty($insertData)) {
            Pagos::insert($insertData);
        }
    
        return response()->json(['message' => 'Batch insertion completed!']);
    }

private function validateDate($date)
{
    return ($date === '0000-00-00' || $date === null) ? null : $date;
}

private function validateDateTime($datetime)
{
    return ($datetime === '0000-00-00 00:00:00' || $datetime === null) ? null : $datetime;
}
    
}
