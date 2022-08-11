<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;
use App\Http\Requests\ValidacionCargos;
use App\Http\Requests\ValidacionUpdateCargos;

class CargosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Cargo::all();
        return view('backend.cargos.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datos = (object)['user' => session('ci')];
        return view('backend.cargos.create', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionCargos $request)
    {
        $validado = $request->validated();
        Cargo::create($validado);
        return redirect()->route('cargos')->with('mensaje', 'Cargo Creado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = (object)[
            'cargo' => Cargo::findOrFail($id),
            'user' => session('ci'),
        ];
        return view('backend.cargos.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdateCargos $request, $id)
    {
        $validado = $request->validated();
        Cargo::findOrFail($id)->update($validado);
        return redirect()->route('cargos')->with('mensaje', 'Cargo Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cargo::destroy($id);
        return redirect()->route('cargos')->with('mensaje', 'Cargo Eliminado');
    }
}
