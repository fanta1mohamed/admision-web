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
            if(count($data) > 0 && $data[0]['habilitado'] == 1){
                $this->response['estado'] = true;
                $this->response['datos'] = $data[0];
                return response()->json($this->response, 200);
            }else { 
                $this->response['estado'] = false;
                return response()->json($this->response, 200);
            }

        } catch (\Exception $e) {
            // Manejar el error, puedes imprimirlo o manejarlo de otra manera
            dd($e->getMessage());
        }
    }




}
