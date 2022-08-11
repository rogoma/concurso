<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Usuario;
use App\Models\Concurso;
use App\Models\Postulacione;
use App\Models\Configuracione;
use App\Models\MatrizDocumental;
use App\Models\Postulante;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Session;

class ConcursosController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arrDoc = array();
        $arrPos = array();

        $concursos = Concurso::with('cargos', 'postulaciones', 'matrizdocs.tipoDoc')
                            ->where('ini_post', '<=', date('Y-m-d'))
                            ->where('fin_post', '>=', date('Y-m-d'))
                            ->get();
        $usuario = Usuario::with('datospersonales', 'datosacademicos', 'capacitaciones', 'laborales')
                            ->where('id', '=', session('usuario_id'))
                            ->get();

        if (count($usuario) == 0) {
            $arrDoc = false;
        } else {
            if (session('role_slug') == 'administrador' || session('role_slug') == 'validador') {
                $arrDoc = false;
            } else {
                foreach ($usuario as $u) {
                    if (count($u->datospersonales)>0) {
                        foreach ($u->datospersonales as $dp) {
                            $arrDoc[] = $dp->tipo_doc_id;
                        }
                    }
                    if (count($u->datosacademicos)>0) {
                        foreach ($u->datosacademicos as $da) {
                            $arrDoc[] = $da->tipo_doc_id;
                        }
                    }
                    if (count($u->capacitaciones) > 0) {
                        foreach ($u->capacitaciones as $dc) {
                            $arrDoc[] = $dc->tipo_doc_id;
                        }
                    }
                    if (count($u->laborales) > 0) {
                        foreach ($u->laborales as $dl) {
                            $arrDoc[] = $dl->tipo_doc_id;
                        }
                    }
                }
                if (empty($arrDoc) || count($arrDoc) == 0 || $arrDoc == null) {
                    $arrDoc = false;
                }
            }
        }

        $configuracion = Configuracione::find(1);

        $postulaciones = Postulacione::where('usuario_id', '=' ,session('usuario_id'))
                            ->get();

        if (count($postulaciones) > 0) {
            foreach ($postulaciones as $po) {
                $arrPos[] = $po->concurso_id;
            }
        } else {
            $arrPos = false;
        }

        if (session('role_slug') == 'postulantes') {
            $postulante = postulante::where('ci', session('ci'))
                        ->firstOrFail();
        } else {
            $postulante = false;
        }

        $datos = (object)[
                    'postulante' => $postulante,
                    'concursos' => $concursos,
                    'usuario' => $usuario,
                    'configuracion' => $configuracion,
                    'postulaciones' => $arrPos,
                    'tiposDoc' => $arrDoc
                ];
        return view('frontend.concursos.index', compact('datos'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexx()
    {
        $concursos = Concurso::with('cargos', 'postulaciones', 'matrizdocs.tipoDoc')
                            ->get();
        $datos = (object)[
                    'concursos' => $concursos,
                ];
        return view('backend.concursos.index', compact('datos'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function evaluacion()
    {
        $arrPos = array();

        $concursos = Concurso::with('cargos', 'postulaciones', 'matrizdocs.tipoDoc')
                            ->orderBy('id')
                            ->get();
        $usuario = Usuario::with('datospersonales', 'datosacademicos', 'capacitaciones', 'laborales')
                            ->where('id', '=', session('usuario_id'))
                            ->get();

        $configuracion = Configuracione::find(1);

        $postulaciones = Postulacione::where('usuario_id', '=' ,session('usuario_id'))
                            ->get();

        foreach ($postulaciones as $key => $p) {
            array_push($arrPos, array(
                    'idx' => $key,
                    'idC' => $p->concurso_id
                ));
        }

        if (session('role_slug') == 'postulantes') {
            $postulante = postulante::where('ci', session('ci'))
                        ->firstOrFail();
        } else {
            $postulante = false;
        }

        $datos = (object)[
                    'configuracion' => $configuracion,
                    'postulaciones' => $postulaciones,
                    'idPostulaciones' => $arrPos,
                    'postulante' => $postulante,
                    'concursos' => $concursos,
                    'usuario' => $usuario
                ];
        //dd($datos->postulaciones);
        return view('frontend.concursos.evaluacion', compact('datos'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cargos = Cargo::all();
        $datos = (object)[
                'cargos' => $cargos,
                'user' => session('ci')
            ];
        return view('backend.concursos.create', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Concurso  $Concurso
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $concurso = Concurso::with('cargos')->findOrFail($id);
        $cargos = Cargo::all();
        $datos = (object)[
                'concurso' => $concurso,
                'cargos' => $cargos,
                'user' => session('ci')
            ];
        return view('backend.concursos.edit', compact('datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
