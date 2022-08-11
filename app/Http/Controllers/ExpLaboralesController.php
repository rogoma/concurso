<?php

namespace App\Http\Controllers;

use App\Models\ExpLaborale;
use App\Models\Tipodoc;
use Illuminate\Http\Request;
use App\Http\Requests\ValidacionExpLaborales;
use App\Http\Requests\ValidacionUpdateExpLaborales;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;

class ExpLaboralesController extends Controller
{

    private $fileName = '';
    private $fileDirectory = '';
    private $sotrageName = 'documentos/';
    private $sotrageDirectory = 'laboral';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ExpLaborale::with('tipoDoc')
                            ->where('usuario_id', session('usuario_id'))->get();
        return view('frontend.laborales.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = (object)[
                'tipos_doc' => Tipodoc::where('tipo', 'L')
                                ->orderBy('documento', 'asc')
                                ->get(),
                'user'      => session('ci'),
                'usuario_id' => session('usuario_id')
            ];
        return view('frontend.laborales.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionExpLaborales $request)
    {
        $laboral = (new ExpLaborale)->fill( $request->all() );

        if($request->hasFile('constancia'))
        {
            $this->fileName = $request->user_crea.'-'.date('YmdHis').'laboral.'.$request->file('constancia')->extension();

            $this->fileDirectory = $this->sotrageName.$request->user_crea.'/'.$this->sotrageDirectory;

            $laboral->constancia = $request->file('constancia')->storeAs($this->fileDirectory, $this->fileName);
        }

        $laboral->save( $request->validated() );

        return redirect()->route('laborales')->with('mensaje', 'Experiencia Laboral Creada');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpLaborale  $ExpLaborale
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $laboral = ExpLaborale::with('tipoDoc')
                                ->where('usuario_id', session('usuario_id'))
                                ->where('id', $id)
                                ->firstOrFail();
        $data = (object)[
                'user' => session('ci'),
                'usuario_id' => session('usuario_id'),
                'tipos_doc' => Tipodoc::where('tipo', 'L')
                                ->orderBy('documento', 'asc')
                                ->get(),
                'laboral' => $laboral
            ];
        return view('frontend.laborales.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpLaborale  $ExpLaborale
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdateExpLaborales $request, $id)
    {
        $data = $request->validated();
        $laboral = ExpLaborale::where('usuario_id', session('usuario_id'))
                                ->where('id', $id)
                                ->firstOrFail();

        if($request->hasFile('constancia'))
        {
            $this->fileName = $request->user_mod.'-'.date('YmdHis').'laboral.'.$request->file('constancia')->extension();

            $this->fileDirectory = $this->sotrageName.$request->user_mod.'/'.$this->sotrageDirectory;

            $laboral->constancia = $request->file('constancia')->storeAs($this->fileDirectory, $this->fileName);
        }
        $data['constancia'] = $laboral->constancia;
        $laboral->update($data);
        return redirect()->route('laborales')->with('mensaje', 'Experiencia Laboral Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpLaborale  $ExpLaborale
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $explaboral = ExpLaborale::where('usuario_id', session('usuario_id'))
                        ->where('id', $id)
                        ->firstOrFail();
        $explaboral->delete();
        return redirect()->route('laborales')->with('mensaje', 'Experiencia Laboral Eliminada');
    }
}
