<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reglamento;


class ReglamentoController extends Controller
{

  public function getSelectReglamentos(){
    $res = Reglamento::select('id as value','nombre as label')->where('estado',1)->get();
    $this->response['estado'] = true;
    $this->response['datos'] = $res;
    return response()->json($this->response, 200);
  }

  public function getReglamentos(Request $request)
  {
    $res = Reglamento::select('reglamento.*')
    ->when($request->term, fn($q, $term) => $q->where('reglamento.nombre', 'LIKE', "%{$term}%"))
    ->orderByDesc('reglamento.id')
    ->paginate(50);

    return response()->json([ 'estado' => true, 'datos' => $res ], 200);
  }

  public function saveReglamento(Request $request)
  {
      $request->validate([
          'nombre' => 'required|string|max:255',
          'estado' => 'required|boolean',
          'inicio_vigencia' => 'nullable|date',
          'fin_vigencia' => 'nullable|date|after_or_equal:inicio_vigencia',
          'file' => 'required|file|mimes:pdf|max:2048'
      ]);

      if ($request->hasFile('file')) {
          $path = $request->file('file')->store('reglamentos', 'public');

          $reglamento = Reglamento::create([
              'nombre' => $request->nombre,
              'estado' => $request->estado,
              'inicio_vigencia' => $request->inicio_vigencia,
              'fin_vigencia' => $request->fin_vigencia,
              'url' => 'storage/'.$path,
              'id_usuario' => auth()->id(),
              'version' => 1.0 // Ejemplo de versión inicial
          ]);

          return response()->json([
              'success' => 'Reglamento creado exitosamente',
              'data' => $reglamento
          ], 201);
      }

      return response()->json(['error' => 'No se subió ningún archivo'], 400);
  }



}
