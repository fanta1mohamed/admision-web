<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UsuarioController extends Controller
{

    public function index()
    {
        $usuarios = User::paginate(5);
        return Inertia::render('Usuarios/index', ['usuarios' => $usuarios]);
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return Inertia::render('Usuarios/crear', ['usuarios' => $usuarios]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->roles);

        return redirect()->route('usuarios.index');

    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return Inertia::render('Usuarios/editar', [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users,email'.$id,
            'password'=>'same:confirm-password',
            'roles'=>'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::excep($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->roles);
        return redirect()->route('usuarios.index');

    }

    public function destroy(string $id)
    {
        User::find($id)->delete();
        return redirect()->route('usuarios.index');
    }
    
    public function getRoles()
    {
        $roles = DB::select('SELECT id, name AS value FROM roles;');
        
        $this->response['datos'] = $roles;
        return response()->json($this->response, 200);
    
    }

    public function getUsuarios(){
        $res = DB::select('SELECT users.id, users.name, users.email, roles.name AS role_name, roles.id AS rol_id  FROM users
        JOIN roles ON users.id_rol = roles.id;
        ');
        
        $this->response['usuarios'] = $res;
        return response()->json($this->response, 200); 
    }


    public function saveUsuario(Request $request) {
        $user = new User();
     
        $user['name'] = $request->name;
        $user['paterno'] = $request->paterno;
        $user['materno'] = $request->materno;
        $user['email'] = $request->email;
        $user['password'] = Hash::make($request->password);
        $user['id_rol'] = $request->rol;
        $user['id_usuario'] = auth()->id();
        $user->save();
        
        $this->response['user'] = $user;
        return response()->json($this->response, 200); 
    }

    public function getPermisos(){
        $res = DB::select('SELECT permissions.id, permissions.name FROM users
        JOIN roles ON users.id_rol = roles.id
        JOIN role_has_permissions ON role_has_permissions.role_id = roles.id
        JOIN permissions ON role_has_permissions.permission_id = permissions.id 
        WHERE users.id = '. auth()->id());
        $this->response['permisos'] = $res;
        return response()->json($this->response, 200);
    }



}
