<?php

namespace App\Http\Controllers;

use App\Models\Capacitacione;
use Illuminate\Http\Request;
use App\Http\Requests\ValidacionCapacitacione;
use App\Http\Requests\ValidacionUpdateCapacitacione;
use App\Models\Tipodoc;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

//use App\Traits\Concursos;

class CapacitacionesController extends Controller
{
    private $fileName = '';
    private $fileDirectory = '';
    private $sotrageName = 'documentos/';
    private $sotrageDirectory = 'capacitacion';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Capacitacione::where('usuario_id', session('usuario_id'))
                    ->get();
        //dd($data);
        return view('frontend.capacitaciones.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = (object)[
                'tipos_doc' => Tipodoc::where('tipo', 'C')
                                ->orderBy('documento', 'asc')
                                ->get(),
                'user'      => session('ci'),
                'usuario_id' => session('usuario_id'),
            ];
        return view('frontend.capacitaciones.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionCapacitacione $request)
    {
        $capacitacion = (new Capacitacione)->fill( $request->all() );

        if($request->hasFile('constancia'))
        {
            $this->fileName = $request->user_crea.'-'.date('YmdHis').'capacitacion.'.$request->file('constancia')->extension();

            $this->fileDirectory = $this->sotrageName.$request->user_crea.'/'.$this->sotrageDirectory;

            $capacitacion->constancia = $request->file('constancia')->storeAs($this->fileDirectory, $this->fileName);
        }

        $capacitacion->save( $request->validated() );

        return redirect()->route('capacitacion')->with('mensaje', 'Capacitación Creada');
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
                'tipos_doc' => Tipodoc::where('tipo', 'C')
                                ->orderBy('documento', 'asc')
                                ->get(),
                'capacitacion' => Capacitacione::with('tipoDoc')
                                ->where('usuario_id', session('usuario_id'))
                                ->where('id', $id)
                                ->firstOrFail()
            ];
        return view('frontend.capacitaciones.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Capacitacione  $Capacitacione
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdateCapacitacione $request, $id)
    {
        $data = $request->validated();
        $capacitacion = Capacitacione::where('usuario_id', session('usuario_id'))
                                ->where('id', $id)
                                ->firstOrFail();

        if($request->hasFile('constancia'))
        {
            $this->fileName = $request->user_mod.'-'.date('YmdHis').'capacitacion.'.$request->file('constancia')->extension();

            $this->fileDirectory = $this->sotrageName.$request->user_mod.'/'.$this->sotrageDirectory;

            $capacitacion->constancia = $request->file('constancia')->storeAs($this->fileDirectory, $this->fileName);
        }
        $data['constancia'] = $capacitacion->constancia;
        $capacitacion->update($data);

        return redirect()->route('capacitacion')->with('mensaje', 'Capacitación Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Capacitacione  $Capacitacione
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $capacitacion = Capacitacione::where('usuario_id', session('usuario_id'))
                            ->where('id', $id)
                            ->firstOrFail();
        $capacitacion->delete();
        return redirect()->route('capacitacion')->with('mensaje', 'Capacitación Eliminada');
    }
}
