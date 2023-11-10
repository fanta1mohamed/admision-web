<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Collection;

class PruebasController extends Controller
{
    private function aleatorizarUbicacion($datos, $combinaciones)
    {
        foreach ($combinaciones as $columna => $posiciones) {
            foreach ($posiciones as $posicion) {
                // Solo necesitas embarajar el array basado en las posiciones
                $datos = $this->shuffleColumn($datos, $columna, $posicion);
            }
        }

        return $datos;
    }

    private function shuffleColumn($datos, $columna, $posicion)
    {
        $datos = collect($datos);

        $datos = $datos->sortBy(function ($fila) use ($columna, $posicion) {
            $valor = $fila->$columna;

            if ($posicion >= 0 && abs($posicion) < strlen($valor)) {
                $indice = ($posicion >= 0) ? $posicion : strlen($valor) + $posicion;
                return $valor[$indice];
            }

            return $valor;
        })->values()->toArray();

        return $datos;
    }

    public function aleatorizar()
    {
        $datos = DB::select("SELECT * FROM participantes_simulacro limit 5");
        $combinaciones = [
            'nro_doc' => [0, 3],
            'ubi_residencia' => [-1, 1],
            'nombres' => [-1, 3],
            'paterno' => [-1, 1],
            'materno' => [-1, 1],
        ];

        $datosAleatorizados = $this->aleatorizarUbicacion($datos, $combinaciones);

        return $datosAleatorizados;
    }
}


