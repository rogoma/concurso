<?php

namespace App\Http\Controllers;

use App\Models\DatosAcad;
use App\Models\Tipodoc;
use Illuminate\Http\Request;
use App\Http\Requests\ValidacionDatosAcad;
use App\Http\Requests\ValidacionUpdateDatosAcad;
use App\Models\Cargo;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Session;

class DatosAcadsController extends Controller
{

    private $fileName = '';
    private $fileDirectory = '';
    private $sotrageName = 'documentos/';
    private $sotrageDirectory = 'academico';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DatosAcad::with('tipoDoc')
                    ->where('usuario_id', session('usuario_id'))
                    ->get();
        //dd($data);
        return view('frontend.academicos.index', compact('data'));
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
                'tipos_doc' => Tipodoc::where('tipo', 'A')
                                ->orderBy('documento', 'asc')
                                ->get(),
                'cargos' => Cargo::all()
            ];
        return view('frontend.academicos.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionDatosAcad $request)
    {
        $academico = (new DatosAcad)->fill( $request->all() );

        if($request->hasFile('pdf'))
        {
            $this->fileName = $request->user_crea.'-'.date('YmdHis').'academico.'.$request->file('pdf')->extension();

            $this->fileDirectory = $this->sotrageName.$request->user_crea.'/'.$this->sotrageDirectory;

            $academico->pdf = $request->file('pdf')->storeAs($this->fileDirectory, $this->fileName);
        }

        $academico->save( $request->validated() );

        return redirect()->route('academicos')->with('mensaje', 'Dato Académico Creado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DatosAcad  $DatosAcad
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = (object)[
                'user' => session('ci'),
                'usuario_id' => session('usuario_id'),
                'academico' => DatosAcad::with('tipoDoc', 'cargo')
                                ->where('usuario_id', session('usuario_id'))
                                ->where('id', $id)
                                ->firstOrFail(),
                'tipos_doc' => Tipodoc::where('tipo', 'A')
                                ->orderBy('documento', 'asc')
                                ->get(),
                'cargos' => Cargo::all()
            ];
        return view('frontend.academicos.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DatosAcad  $DatosAcad
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdateDatosAcad $request, $id)
    {
        $data = $request->validated();
        $academico = DatosAcad::where('usuario_id', session('usuario_id'))
                                ->where('id', $id)
                                ->firstOrFail();

        if($request->hasFile('pdf'))
        {
            $this->fileName = $request->user_mod.'-'.date('YmdHis').'academico.'.$request->file('pdf')->extension();

            $this->fileDirectory = $this->sotrageName.$request->user_mod.'/'.$this->sotrageDirectory;

            $academico->pdf = $request->file('pdf')->storeAs($this->fileDirectory, $this->fileName);
        }
        $data['pdf'] = $academico->pdf;
        $academico->update($data);

        return redirect()->route('academicos')->with('mensaje', 'Dato Académico Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DatosAcad  $DatosAcad
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $datosacad = DatosAcad::where('usuario_id', session('usuario_id'))
                        ->where('id', $id)
                        ->firstOrFail();
        $datosacad->delete();
        return redirect()->route('academicos')->with('mensaje', 'Dato Académico Eliminado');
    }
}
