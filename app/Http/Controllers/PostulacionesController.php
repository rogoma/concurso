<?php

namespace App\Http\Controllers;

use App\Models\Postulacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostulacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = (object)[
            'postulaciones' => Postulacione::with('concurso.cargos')
                                ->where('usuario_id', '=', session('usuario_id'))
                                ->get()
        ];
        return view('frontend.postulaciones.index', compact('datos'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validator($concurso_id)
    {
        $postulacion = Postulacione::with('concurso.cargos', 'usuario.perfil')
                        ->where('concurso_id', '=', $concurso_id)
                        ->get();
        $datos = (object)[
            'postulacion' => $postulacion
        ];
        return view('frontend.postulaciones.validator', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::select('SELECT insert_postulacion(?, ?)', [$request->concurso_id, session('usuario_id')]);
        return redirect()->route('postulacion')->with('mensaje', 'Postulación Realizada');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Postulacione  $postulacione
     * @return \Illuminate\Http\Response
     */
    public function show($concurso_id)
    {
        $datos = (object)[
            'postulacion' => Postulacione::with('concurso.cargos', 'usuario.perfil')
                                ->with('usuario')
                                ->with('postDatosAcads.tipoDoc', 'postCapacitaciones', 'postDatosPersonales.tipoDoc', 'postExpLaborales')
                                ->where('concurso_id', '=', $concurso_id)
                                ->where('usuario_id', '=', session('usuario_id'))
                                ->first()
        ];
        return view('frontend.postulaciones.show', compact('datos'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Postulacione  $postulacione
     * @return \Illuminate\Http\Response
     */
    public function print($concurso_id)
    {
        $datos = (object)[
            'postulacion' => Postulacione::with('concurso.cargos', 'usuario.perfil')
                                ->with('usuario')
                                ->with('postDatosAcads.tipoDoc', 'postCapacitaciones', 'postDatosPersonales.tipoDoc', 'postExpLaborales')
                                ->where('concurso_id', '=', $concurso_id)
                                ->where('usuario_id', '=', session('usuario_id'))
                                ->first()
        ];
        return view('frontend.postulaciones.print', compact('datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Postulacione  $postulacione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Postulacione $postulacione)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Postulacione  $postulacione
     * @return \Illuminate\Http\Response
     */
    public function destroy(Postulacione $postulacione)
    {
        //
    }
}
