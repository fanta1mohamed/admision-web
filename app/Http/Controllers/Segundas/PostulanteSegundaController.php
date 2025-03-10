<?php
namespace App\Http\Controllers\Segundas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preinscripcion;
use App\Models\Postulante;
use App\Models\Inscripcion;
use App\Models\DocumentoSegunda;
use App\Models\ControlBiometrico;
use Inertia\Inertia;
use DB;

class PostulanteSegundaController extends Controller
{
  public function getPostulantes(Request $request)
  {
      $query_where = [];
  
      if (auth()->user()->programas != null) {
          $array = json_decode(auth()->user()->programas, true);
          if (!empty($array)) {
              $query_where[] = ['pre_inscripcion.id_programa', $array];
          }
      }
  
      $res = Preinscripcion::select(
          'postulante.id',
          'postulante.tipo_doc',
          'postulante.nro_doc',
          'postulante.primer_apellido',
          'postulante.segundo_apellido',
          'postulante.apellido_casada',
          'postulante.nombres',
          'postulante.sexo',
          'postulante.fec_nacimiento',
          'postulante.ubigeo_nacimiento',
          'postulante.ubigeo_residencia',
          'postulante.celular',
          'postulante.email',
          'postulante.estado_civil',
          'postulante.direccion',
          'postulante.anio_egreso',
          'postulante.correo_institucional',
          'postulante.cod_orcid',
          'postulante.observaciones',
          'postulante.id_colegio',
      )
      ->join('postulante', 'postulante.id', 'pre_inscripcion.id_postulante')
      ->when(!empty($query_where), function ($query) use ($query_where) {
          // Aplicamos la condición de filtro
          foreach ($query_where as $condition) {
              $query->whereIn($condition[0], $condition[1]);
          }
      })
      ->where('pre_inscripcion.id_proceso', auth()->user()->id_proceso)
      ->where(function ($query) use ($request) {
          return $query
              ->orWhere('postulante.nro_doc', 'LIKE', '%' . $request->term . '%')
              ->orWhere('postulante.email', 'LIKE', '%' . $request->term . '%')
              ->orWhere('postulante.celular', 'LIKE', '%' . $request->term . '%')
              ->orWhere(DB::raw("CONCAT(postulante.nombres, ' ', postulante.primer_apellido, ' ', postulante.segundo_apellido)"), 'LIKE', '%' . $request->term . '%')
              ->orWhere(DB::raw("CONCAT(postulante.primer_apellido, ' ', postulante.segundo_apellido, ' ', postulante.nombres)"), 'LIKE', '%' . $request->term . '%');
      })
      ->paginate(20);
  
      // Formateamos la respuesta
      return response()->json([
          'estado' => true,
          'datos' => $res
      ], 200);
  }


  public function showPostulante($dni) {

    $postulanteInfo = Postulante::select(
        'postulante.id AS id_postulante',
        'postulante.nombres',
        'postulante.primer_apellido',
        'postulante.segundo_apellido',
        'postulante.email',
        'postulante.celular',
        'departamento.nombre AS departamento',
        'provincia.nombre AS provincia',
        'distritos.nombre AS distrito'
    )
    ->leftJoin('ubigeo', 'ubigeo.ubigeo', '=', 'postulante.ubigeo_residencia')
    ->leftJoin('departamento', 'departamento.id', '=', 'ubigeo.id_departamento')
    ->leftJoin('provincia', 'provincia.id', '=', 'ubigeo.id_provincia')
    ->leftJoin('distritos', 'distritos.id', '=', 'ubigeo.id_distrito')
    ->where('postulante.nro_doc', '=', $dni)
    ->first();

    $procesos = Inscripcion::select('procesos.id AS id_proceso','procesos.nombre AS proceso','inscripciones.codigo')
    ->join('procesos', 'procesos.id', '=', 'inscripciones.id_proceso')
    ->where('inscripciones.id_postulante', '=', $postulanteInfo->id_postulante)
    ->orderBy('procesos.id', 'desc')
    ->get();

    $foto = DocumentoSegunda::where('id_proceso', 16)
    ->where('dni', $dni)
    ->where('id_tipo', 8)
    ->value('url');

    $titulo = DocumentoSegunda::where('id_proceso', 16)
    ->where('dni', $dni)
    ->where('id_tipo', 7)
    ->value('url');

    $countPreInscripcion = Preinscripcion::where('id_postulante', '=', $postulanteInfo->id_postulante)->count();
    $countInscripcion = Inscripcion::where('id_postulante', '=', $postulanteInfo->id_postulante)->count();
    $countControlBiometrico = ControlBiometrico::where('id_postulante', '=', $postulanteInfo->id_postulante)->count();

    //return Inertia::location('perfil-postulante');
    return Inertia::render('Segundas/Admin/Postulantes/Perfil',
      [
        'info' => $postulanteInfo, 
        'preinscripciones'=>  $countPreInscripcion,
        'inscripciones' => $countInscripcion,
        'control_biometrico' => $countControlBiometrico,
        'foto' => "../../".$foto,
        'titulo' => "../../".$titulo,
        'pro' => $procesos
      ]); 

  }
  
    
    
    


}
