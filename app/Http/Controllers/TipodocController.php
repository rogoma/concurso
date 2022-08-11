<?php

namespace App\Http\Controllers;

use App\Models\Tipodoc;
use Illuminate\Http\Request;
use App\Http\Requests\ValidacionTipodoc;
use App\Http\Requests\ValidacionUpdateTipodoc;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Session;

class TipodocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Tipodoc::all();
        return view('backend.tipodoc.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datos = (object)['user' => Session::get('ci'), 'fecha' => date('Y-m-d')];
        return view('backend.tipodoc.create', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionTipodoc $request)
    {
        $validado = $request->validated();
        Tipodoc::create($validado);
        return redirect()->route('tipodoc')->with('mensaje', 'Tipo de Documento Creado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tipodoc  $tipodoc
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Tipodoc::findOrFail($id);
        $datos = (object)['user' => Session::get('ci'), 'fecha' => date('Y-m-d')];
        return view('backend.tipodoc.edit', compact('data', 'datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipodoc  $tipodoc
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdateTipodoc $request, $id)
    {
        $validado = $request->validated();
        Tipodoc::findOrFail($id)->update($validado);
        return redirect()->route('tipodoc')->with('mensaje', 'Tipo de Documento Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tipodoc  $tipodoc
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tipodoc::destroy($id);
        return redirect()->route('tipodoc')->with('mensaje', 'Tipo de Documento Eliminado');
    }
}
