<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\ValidacionRoles;
use App\Http\Requests\ValidacionUpdateRoles;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Session;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Role::all();
        return view('backend.roles.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datos = (object)['user' => Session::get('ci'), 'fecha' => date('Y-m-d')];
        return view('backend.roles.create', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionRoles $request)
    {
        $request['slug'] = Str::slug($request['slug'], '_');
        $validado = $request->validated();
        Role::create($validado);
        return redirect()->route('roles')->with('mensaje', 'Rol Creado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Role::findOrFail($id);
        $datos = (object)['user' => Session::get('ci'), 'fecha' => date('Y-m-d')];
        return view('backend.roles.edit', compact('data', 'datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdateRoles $request, $id)
    {
        $request['slug'] = Str::slug($request['slug'], '_');
        Role::findOrFail($id)->update($request->validated());
        return redirect()->route('roles')->with('mensaje', 'Rol Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);
        return redirect()->route('roles')->with('mensaje', 'Rol Eliminado');
    }
}
