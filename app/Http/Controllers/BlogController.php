<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    function __construct()
    {
        #$this->middleware('permission:ver-rol | crear-rol | editar-rol | borrar-rol', ['only'=>['index']]);
        #$this->middleware('permission:crear-rol', ['only'=>['create','store']]);
        #$this->middleware('permission:editar-rol', ['only'=>['edit','update']]);
        #$this->middleware('permission:borrar-rol', ['only'=>['destroy']]);
    }

    public function index()
    {
        $blogs = Blog::paginate(5);
        return Inertia::render('Blogs/index', ['blogs' => $blogs]);
    }

    public function create()
    {
        return Inertia::render('Blogs/crear');
    }

    public function store(Request $request)
    {
        $request()->validate([
            'titulo' => 'required',
            'contenido' => 'required'
        ]);
        Blog::create($request->all());
        return redirect()->route('blogs.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Blog $blog)
    {
        return Inertia::render('Blogs/editar', ['blogs' => $blog]);
    }

    public function update(Request $request, Blog $blog) {
        request()->validate([ 'titulo'=>'required', 'contenido'=>'required']);
        $blog->update($request->all());
        return redirect()->route('blogs.index');
    }

    public function destroy(Blog $blog) {
        $blog->delete();
        return redirect()->route(blogs.index);
    }

    public function verPuntajes(){
        return Inertia::render('Resultados/index');
    }

    public function getPuntajes($dni){
        
        $res = DB::select("SELECT 
            pun.fecha, pun.dni, pos.nombres, pos.primer_apellido, pos.segundo_apellido,
            modalidad, pun.puntaje, pun.puntaje_vocacional, pun.apto as condicion, pun.programa, pro.nombre AS programa
        FROM puntajes pun
        JOIN postulante pos ON pos.nro_doc = pun.dni
        JOIN inscripciones ins ON ins.id_postulante = pos.id and ins.estado = 0
        JOIN programa pro ON pro.id = ins.id_programa
        WHERE pun.dni = $dni AND pun.id_proceso = ins.id_proceso
        AND YEAR(pun.fecha) = '2024'
        ORDER BY pun.fecha DESC;");

        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
        
    }

}