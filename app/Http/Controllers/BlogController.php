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

    public function update(Request $request, Blog $blog)
    {
        request()->validate([
            'titulo'=>'required',
            'contenido'=>'required'
        ]);

        $blog->update($request->all());
        return redirect()->route('blogs.index');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route(blogs.index);        
    }

    public function verPuntajes(){
        return Inertia::render('Resultados/index');
    }

    public function getPuntajes($dni){

        $res = DB::select('SELECT * FROM puntajes WHERE dni = '.$dni. ' ORDER BY fecha ASC;');
        $this->response['datos'] = $res;
        return response()->json($this->response, 200);
    }

}
