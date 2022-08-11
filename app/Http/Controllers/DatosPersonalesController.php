<?php

namespace App\Http\Controllers;

use App\Models\DatosPersonale;
use App\Models\Tipodoc;
use Illuminate\Http\Request;
use App\Http\Requests\ValidacionDatosPersonales;
use App\Http\Requests\ValidacionUpdateDatosPersonales;
use Illuminate\Support\Facades\Session;

class DatosPersonalesController extends Controller
{

    private $fileName = '';
    private $fileDirectory = '';
    private $sotrageName = 'documentos/';
    private $sotrageDirectory = 'personal';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DatosPersonale::with('tipoDoc')
                    ->where('usuario_id', session('usuario_id'))
                    ->get();
        return view('frontend.personales.index', compact('data'));
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
                'tipos_doc' => Tipodoc::where('tipo', 'P')
                                ->orderBy('documento', 'asc')
                                ->get()
            ];
        return view('frontend.personales.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionDatosPersonales $request)
    {
        $personal = (new DatosPersonale)->fill( $request->all() );

        if($request->hasFile('pdf'))
        {
            $this->fileName = $request->user_crea.'-'.date('YmdHis').'personal.'.$request->file('pdf')->extension();

            $this->fileDirectory = $this->sotrageName.$request->user_crea.'/'.$this->sotrageDirectory;

            $personal->pdf = $request->file('pdf')->storeAs($this->fileDirectory, $this->fileName);
        }

        $personal->save( $request->validated() );

        return redirect()->route('personales')->with('mensaje', 'Dato Personal Creado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DatosPersonales  $datospersonales
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = (object)[
                'user' => session('ci'),
                'usuario_id' => session('usuario_id'),
                'personal' => DatosPersonale::with('tipoDoc')
                                ->where('usuario_id', session('usuario_id'))
                                ->where('id', $id)
                                ->firstOrFail(),
                'tipos_doc' => Tipodoc::where('tipo', 'P')
                                ->orderBy('documento', 'asc')
                                ->get()
            ];
        return view('frontend.personales.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DatosPersonales  $datospersonales
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdateDatosPersonales $request, $id)
    {
        $data = $request->validated();
        $personal = DatosPersonale::findOrFail($id);

        if($request->hasFile('pdf'))
        {
            $this->fileName = $request->user_mod.'-'.date('YmdHis').'personal.'.$request->file('pdf')->extension();

            $this->fileDirectory = $this->sotrageName.$request->user_mod.'/'.$this->sotrageDirectory;

            $personal->pdf = $request->file('pdf')->storeAs($this->fileDirectory, $this->fileName);
        }
        $data['pdf'] = $personal->pdf;
        $personal->update($data);

        return redirect()->route('personales')->with('mensaje', 'Dato Personal Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DatosPersonales  $datospersonales
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $datospersonale = DatosPersonale::where('usuario_id', session('usuario_id'))
                        ->where('id', $id)
                        ->firstOrFail();
        $datospersonale->delete();
        return redirect()->route('personales')->with('mensaje', 'Dato Personal Eliminado');
    }
}
