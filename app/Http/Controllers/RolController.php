<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\HasPermission;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class RolController extends Controller
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
        $roles = Role::paginate(5);
        // $this->response['datos'] = $datos;
        // $this->response['total'] = $total;
        // return response()->json($this->response, 200);
        return Inertia::render('Roles/index', ['roles' => $roles]);
        
    }

    public function getRoles()
    {
        $roles = Role::paginate(10);
        $this->response['datos'] = $roles;
        return response()->json($this->response, 200);
    
    }

    public function create()
    {
        $permission = Permission::get();
        return Inertia::render('Roles/crear', ['permisos' => $permission]);

    }

    public function store(Request $request)
    {
        $this->validate($request,['name'=>'required', 'permision' => 'required']);
        $role = Role::create(['name'=>$request->name]);
        $role->syncPermission($request->permission);

        return redirect()->route('roles.index');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermission = DB::table('role_has_permissions')->where('role_has_permisisions.role.id',$id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return Inertia::render('Roles/editar', ['role' => $role, 'permission' => $permission, 'rolePermission' => $rolePermission]);    
        // $this->response['role'] = $datos;
        // $this->response['permission'] = $permission;
        // $this->response['rolePermission'] = $rolePermission;
        // return response()->json($this->response, 200);

    }


    public function update(Request $request, string $id)
    {
        $this->validate($request,['name'=>'required', 'permission'=>'required']);

        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
   
        $role->syncPermissions($request->permission);

        return redirect()->route('roles.index');
    }

    public function destroy(string $id)
    {
        DB::table('roles')->where('id',$id)->delete();
        return redirect()->route('roles.index');
    }

    public function getPermission(){
        $permisos = DB::select('SELECT id AS "key", name AS name, guard_name  from permissions') ;

        $this->response['permisos'] = $permisos;
        return response()->json($this->response, 200); 
    }

    public function saveRol(Request $request) {
        $rol = new Rol();
     
        $rol['name'] = $request->name;
        $rol['guard_name'] = 'web';
        $rol->save();

        foreach($request->permisos as $permiso){

            $this->savePermisos($rol['id'], $permiso);            
        }
        
        $this->response['rol'] = $rol['id'];
        return response()->json($this->response, 200); 
    }

    public function savePermisos($rol, $permiso) {
        $permisos = new HasPermission();     
        $permisos['permission_id'] = $permiso;
        $permisos['role_id'] = $rol;
        $permisos->save();
    }



}
