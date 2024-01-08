<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CepreController extends Controller
{
    
    public function getParticipanteCepre($dni)
    {
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://sistemas.cepreuna.edu.pe/api/v1/'.$dni, [
                'headers' => [
                    'Authorization' => 'cepreuna_v1_api',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            if(count($data) > 0){
                return $data[0];
            }else {
                return "Estudiante no encontrado";
            }

        } catch (\Exception $e) {
            // Manejar el error, puedes imprimirlo o manejarlo de otra manera
            dd($e->getMessage());
        }
    }




}
