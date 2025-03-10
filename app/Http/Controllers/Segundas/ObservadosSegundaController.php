<?php
namespace App\Http\Controllers\Segundas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sancionado;
use Inertia\Inertia;
use DB;

class ObservadosSegundaController extends Controller
{
  
    public function getObservados(Request $request)
    {
      $query_where = [];
      $res = Sancionado::select('sancionados.id','sancionados.dni','sancionados.nombres','sancionados.paterno','sancionados.materno','sancionados.motivo','procesos.nombre as nombre_proceso','procesos.id as id_proceso')
        ->join ('procesos', 'procesos.id', '=','sancionados.id_proceso')
        ->where($query_where)
        ->where('procesos.id','=',auth()->user()->id_proceso)
        ->where(function ($query) use ($request) {
            return $query
                ->orWhere('sancionados.dni', 'LIKE', '%' . $request->term . '%')
                ->orWhere('sancionados.nombres', 'LIKE', '%' . $request->term . '%')
                ->orWhere('sancionados.paterno', 'LIKE', '%' . $request->term . '%')
                ->orWhere('sancionados.materno', 'LIKE', '%' . $request->term . '%')
                ->orWhere(DB::raw("CONCAT(sancionados.paterno,' ',sancionados.materno,' ',sancionados.nombres)"), 'LIKE', '%' . $request->term . '%')
                ->orWhere(DB::raw("CONCAT(sancionados.nombres,' ',sancionados.paterno, ' ', sancionados.materno)"), 'LIKE', '%' . $request->term . '%')
                ->orWhere('procesos.nombre', 'LIKE', '%' . $request->term . '%');
        })->orderBy('sancionados.id', 'DESC')
        ->paginate(50);

      $this->response['estado'] = true;
      $this->response['datos'] = $res;
      return response()->json($this->response, 200);
    }


    public function save(Request $request ) {

      $request->validate([
          'dni' => 'required|string|max:20', 
          'nombres' => 'required|string|max:255',
          'paterno' => 'required|string|max:255',
          'materno' => 'required|string|max:255',
          'motivo' => 'required|string|max:255',
          'observacion' => 'nullable|string|max:500',
      ]);

      $observado = null;
      if (!$request->id) {
          $observado = Sancionado::create([
              'dni' => $request->dni,
              'nombres' => $request->nombres,
              'paterno' => $request->paterno,
              'materno' => $request->materno,
              'motivo' => $request->motivo,
              'observacion' => $request->observacion,
              'id_proceso' => auth()->user()->id_proceso,
          ]);

          $this->response['tipo'] = 'success';
          $this->response['titulo'] = '!REGISTRO CREADO CON EXITO!';
          $this->response['mensaje'] = 'Proceso '.$observado->nombres.' modificado con exito';
          $this->response['estado'] = true;
          $this->response['datos'] = $observado;

      } else {

          $observado = Sancionado::find($request->id);
          $observado->dni = $request->dni;
          $observado->nombres = $request->nombres;
          $observado->paterno = $request->paterno;
          $observado->materno = $request->materno;
          $observado->motivo = $request->motivo;
          $observado->observacion = $request->observacion;
          $observado->id_proceso = auth()->user()->id_proceso;
          $observado->save();

          $this->response['tipo'] = 'info';
          $this->response['titulo'] = '!REGISTRO MODIFICADO!';
          $this->response['mensaje'] = 'Proceso '.$observado->nombres.' modificado con exito';
          $this->response['estado'] = true;
          $this->response['datos'] = $observado;

        }

        return response()->json($this->response, 200);
    }


}
