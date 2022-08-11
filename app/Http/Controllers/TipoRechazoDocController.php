<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidacionRechazodoc;
use App\Http\Requests\ValidacionUpdateRechazodoc;
use App\Models\Tipodoc;
use App\Models\TipoRechazoDoc;
use DateTime;
use Illuminate\Http\Request;

class TipoRechazoDocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TipoRechazoDoc::with('tipoDoc')->get();
        return view('backend.rechazodoc.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = (object)[
            'user'      => session('ci'),
            'usuario_id' => session('usuario_id'),
            'tipos_doc' => Tipodoc::orderBy('documento', 'asc')
                            ->get()
        ];
        return view('backend.rechazodoc.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionRechazodoc $request)
    {
        $validado = $request->validated();
        TipoRechazoDoc::create($validado);
        return redirect()->route('rechazodoc')->with('mensaje', 'Tipo de Rechazo de Documento Creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoRechazoDoc  $tipoRechazoDoc
     * @return \Illuminate\Http\Response
     */
    public function show(TipoRechazoDoc $tipoRechazoDoc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = (object)[
            'user'      => session('ci'),
            'usuario_id' => session('usuario_id'),
            'tipos_doc' => Tipodoc::orderBy('documento', 'asc')
                            ->get(),
            'rechazo' => TipoRechazoDoc::with('tipoDoc')
                            ->findOrFail($id)
        ];
        return view('backend.rechazodoc.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoRechazoDoc  $tipoRechazoDoc
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdateRechazodoc $request, $id)
    {
        $validado = $request->validated();
        $validado['fecha_mod'] = date('Y-m-d H:i:s');
        TipoRechazoDoc::findOrFail($id)->update($validado);
        return redirect()->route('rechazodoc')->with('mensaje', 'Tipo de Rechazo de Documento Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoRechazoDoc  $tipoRechazoDoc
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TipoRechazoDoc::destroy($id);
        return redirect()->route('rechazodoc')->with('mensaje', 'Tipo de Rechazo de Documento Eliminado');
    }
}
