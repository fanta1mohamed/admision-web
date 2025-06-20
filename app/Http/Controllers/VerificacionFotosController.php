<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Filial;
use App\Models\Inscripcion;
use App\Models\Carpeta;
use Illuminate\Support\Facades\Storage;
use App\Models\Dataversion;

class VerificacionFotosController extends Controller
{
    public function index()
    {
        return Inertia::render('Filial/filial'); 
    }  

    public function getFotosVerificaion(Request $request) {
      $term = $request->input('term');
      $paginasize = $request->input('paginasize', 15); // Valor por defecto si no se proporciona
  
      $res = DB::table('verificacion')
          ->select('dni', 'nombres', 'primer_apellido', 'segundo_apellido','estado')
          ->where(function ($query) use ($term) {
              $query->orWhere('dni', 'LIKE', '%' . $term . '%')
                    ->orWhere(DB::raw("CONCAT(nombres, ' ', primer_apellido, ' ', segundo_apellido)"), 'LIKE', '%' . $term . '%')
                    ->orWhere(DB::raw("CONCAT(primer_apellido, ' ', segundo_apellido, ' ', nombres)"), 'LIKE', '%' . $term . '%');
          })
          ->distinct()
          ->orderBy('primer_apellido', 'asc')
          ->paginate($paginasize);
  
      return response()->json([
          'estado' => true,
          'datos' => $res
      ], 200);
    }

    public function updateEstado(Request $request) {

      $dni = $request->input('dni');
      $estado = $request->input('estado');
  
      $updated = DB::table('verificacion') ->where('dni', $dni)->update(['estado' => $estado]);
  
      if ($updated) {
          return response()->json([ 'estado' => true, 'mensaje' => 'Registro actualizado correctamente'], 200);
      } else {
          return response()->json(['estado' => false, 'mensaje' => 'No se encontró el registro para actualizar'], 404);
      }

    }


  public function deleteFilial($id){
    $filial = Filial::find($id);
    $p = $filial;
    $filial->delete();

    $this->response['titulo'] = '!REGISTRO ELIMINADO!';
    $this->response['mensaje'] = 'Filial '.$p->nombre.' eliminada con exito';
    $this->response['estado'] = true;
    $this->response['datos'] = $p;
    return response()->json($this->response, 200);
  }
  

}
