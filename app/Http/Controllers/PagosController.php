<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PagoBanco;
use DB;

class PagosController extends Controller
{
    

    public function getPagosBN_OTI($dni)
    {
        $res = [];      
        // $pagos = PagoBanco::select('imp_pag as amount', 'nom_cli as client', 'num_mat as code', 'fch_pag as date', 'num_doc as document',
        //                     'secuencia as paymentId', \DB::raw("'0' as status"), \DB::raw("'000000000000000' as universityId"))
        //     ->where('fch_pag', '>=', '2024-07-01')
        //     ->whereIn('concepto', ['00000026', '00000039', '00000028', '00000027'])
        //     ->whereRaw("$dni = SUBSTRING(num_doc, 9, 8)")
        //     ->get();

        $res = DB::select("SELECT imp_pag as amount, nom_cli AS 'client', num_mat AS 'code',
            fch_pag AS 'date', num_doc AS document, secuencia AS paymentId,
            '0' AS 'status',
            '000000000000000' AS universityId
            FROM banco_pagos
            WHERE fch_pag >= '2024-07-01'
            and concepto IN ('00000026', '00000039', '00000028', '00000027')
            AND 60201772 = substr(num_doc, 8,8)");
  
      return $res;
    }





}
