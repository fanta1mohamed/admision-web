<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pagos;
use App\Models\PagosUnap;
use DB;

class SyncController extends Controller
{
    public function syncTables()
    {
        $perPage = 1000;
        $existingSequences = Pagos::pluck('secuencia')->toArray();
        $totalRecords = PagosUnap::whereNotIn('secuencia', $existingSequences)->count();

        return $totalRecords;

        $totalPages = ceil($totalRecords / $perPage);


        for ($page = 1; $page <= $totalPages; $page++) {
            $sourceData = PagosUnap::whereNotIn('secuencia', $existingSequences)
                                    ->skip(($page - 1) * $perPage)
                                    ->take($perPage)
                                    ->get();

            if ($sourceData->isEmpty()) {
                continue;
            }

            DB::transaction(function () use ($sourceData) {
                foreach ($sourceData as $data) {
                    Pagos::updateOrCreate(
                        ['cod_uni' => $data->cod_uni, 'num_mat' => $data->num_mat, 'secuencia' => $data->secuencia],
                        [
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
                            'cod_age' => $data->cod_age,
                            'cod_caj' => $data->cod_caj,
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
                        ]
                    );
                }
            });

            // Actualizar la lista de secuencias ya registradas
            $existingSequences = array_merge($existingSequences, $sourceData->pluck('secuencia')->toArray());
        }
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
