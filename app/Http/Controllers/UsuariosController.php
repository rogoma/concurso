<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Usuario;
use App\Models\Perfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ValidacionUsuarios;
use App\Http\Requests\ValidacionUpdateUsuarios;



class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = (object)[ 'usuarios' => Usuario::with('rol')->get() ];
        return view('backend.usuarios.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datos = (object)[
                    'user' => session('ci'),
                    'roles' => Role::where('slug', 'not like', 'postulantes')->get()
                ];
        return view('backend.usuarios.create', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionUsuarios $request)
    {
        $validado = $request->validated();
        $validado['password'] = Hash::make($validado['password']);
        $validado['activo'] = true;
        $validado['email_verified_at'] = date('Y-m-d H:i:s');
        $usuario = Usuario::create($validado);
        $usuario->rol()->attach(Role::where('id', $request['rol'])->first());
        Perfile::create([
            'usuario_id' => $usuario->id,
            'nombres' => 0,
            'apellidos' => 0,
            'postulante' => 'I',
            'user_crea'  => $usuario->ci,
            'fecha_crea' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->route('usuarios')->with('mensaje', 'Usuario Creado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $datos = (object)[
                    'user' => session('ci'),
                    'edit' => true,
                    'usuario' => $usuario,
                    'rol' => $usuario->rol,
                    'roles' => Role::where('slug', 'not like', 'postulantes')->get()
                ];
        return view('backend.usuarios.edit', compact('datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdateUsuarios $request, $id)
    {
        $user = Usuario::findOrFail($id);
        $user->update($request->validated());
        $user->rol()->sync($request->rol);
        return redirect()->route('usuarios')->with('mensaje', 'Usuario Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Usuario::destroy($id);
        return redirect()->route('usuarios')->with('mensaje', 'Usuario Eliminado');
    }
}
