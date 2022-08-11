<?php

namespace App\Http\Controllers;

use App\Models\Concurso;
use App\Traits\Concursos;
use App\Models\Postulante;
use App\Models\Postulacione;
use Illuminate\Http\Request;
use App\Models\TipoRechazoDoc;
use App\Models\EvalDocumentale;
use App\Models\MatrizDocumentale;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ValidacionDocumental;

class EvaluacionDocumentalController extends Controller
{
    use Concursos;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documental()
    {
        $datos = (object)[
            'concursos' => Concurso::with('cargos')->orderBy('id')->get(),
            'concurso' => null,
            'estado' => null,
            'postulacion' => null
        ];
        return view('frontend.validar.documental.documental', compact('datos'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchs()
    {
        //dd(session()->all());
        if (session('estado') && session('concurso_id')) {
            $sestado = session('estado');
            $sconcurso_id = session('concurso_id');
        } else {
            return redirect()->route('documental')->with('Error', 'imposible repetir la búsqueda');
        }
        if (session('fechas') !== null) {
            if (strstr(session('fechas'), '-')) {
                $arrDate = explode('-', session('fechas'));
                $fecha['desde'] = $this->fixFecha(trim($arrDate[0]));
                $fecha['hasta'] = $this->fixFecha(trim($arrDate[1]));
            } elseif (!strstr(session('fechas'), '-')) {
                $fecha['desde'] = $this->fixFecha(trim(session('fechas')));
            }
        } else {
            $fecha['desde'] = '';
            $fecha['hasta'] = '';
        }
        //dd($fecha);

        switch ($sestado) {
            case 'NE':
                $estado['dscrp'] = "No Evaluado";
                $estado['code'] = "NE";
                break;
            case 'AD':
                $estado['dscrp'] = "Admitido";
                $estado['code'] = "AD";
                break;
            case 'NA':
                $estado['dscrp'] = "No Admitido";
                $estado['code'] = "NA";
                break;
            default:
                $estado['dscrp'] = null;
                $estado['code'] = null;
                break;
        }
        $postulacion = Postulacione::with('concurso.cargos', 'usuario.perfil')
                        ->with('usuario')
                        ->with('postDatosAcads.tipoDoc', 'postCapacitaciones', 'postDatosPersonales.tipoDoc', 'postExpLaborales')
                        ->where('concurso_id', '=', $sconcurso_id)
                        ->where('eval_docum', '=', $sestado)
                        ->when($fecha['desde'] && $fecha['hasta'], function($q) use($fecha){
                            $q->when($fecha['desde'] == $fecha['hasta'], function($q) use($fecha){
                                $q->whereDate('fecha_post', '=', $fecha['desde']);
                            });
                            $q->when($fecha['desde'] !== $fecha['hasta'], function($q) use($fecha){
                                $q->whereBetween('fecha_post', [$fecha['desde']." 00:00:00", $fecha['hasta']." 23:59:59"]);
                            });
                        })
                        ->orderBy('id')
                        ->get();
        if ($fecha['desde'] && $fecha['hasta']) {
            $fechas = date('d/m/Y', strtotime($fecha['desde'])).' - '.date('d/m/Y', strtotime($fecha['hasta']));
        } elseif ($fecha['desde'] !== null && $fecha['desde'] == $fecha['hasta'] ) {
            $fechas = date('d/m/Y', strtotime($fecha['desde']));
        } else {
            $fechas = null;
        }
        $datos = (object)[
            'concursos' => Concurso::all(),
            'concurso' => Concurso::findOrFail($sconcurso_id),
            'estado' => $estado,
            'postulacion' => $postulacion,
            'fechas' => $fechas
        ];
        //dd($datos->concurso);
        session()->forget(['estado', 'concurso_id', 'fechas']);
        return view('frontend.validar.documental.documental', compact('datos'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        switch ($request->estado) {
            case 'NE':
                $estado['dscrp'] = "No Evaluado";
                $estado['code'] = "NE";
                break;
            case 'AD':
                $estado['dscrp'] = "Admitido";
                $estado['code'] = "AD";
                break;
            case 'NA':
                $estado['dscrp'] = "No Admitido";
                $estado['code'] = "NA";
                break;
            default:
                $stado['dscrp'] = null;
                $estado['code'] = null;
                break;
        }
        $postulacion = Postulacione::with('concurso.cargos', 'usuario.perfil')
                        ->with('usuario')
                        ->with('postDatosAcads.tipoDoc', 'postCapacitaciones', 'postDatosPersonales.tipoDoc', 'postExpLaborales')
                        ->where('concurso_id', '=', $request->concurso_id)
                        ->where('eval_docum', '=', $request->estado)
                        ->when($request->fecha_desde && $request->fecha_hasta, function($q) use($request){
                            $q->when($request->fecha_desde == $request->fecha_hasta, function($q) use($request){
                                $q->whereDate('fecha_post', '=', $request->fecha_desde);
                            });
                            $q->when($request->fecha_desde !== $request->fecha_hasta, function($q) use($request){
                                $q->whereBetween('fecha_post', [$request->fecha_desde." 00:00:00", $request->fecha_hasta." 23:59:59"]);
                            });
                        })
                        ->get();
        if ($request->fecha_desde && $request->fecha_hasta) {
            $fechas = date('d/m/Y', strtotime($request->fecha_desde)).' - '.date('d/m/Y', strtotime($request->fecha_hasta));
        } elseif ($request->fecha_desde !== null && $request->fecha_desde == $request->fecha_hasta ) {
            $fechas = date('d/m/Y', strtotime($request->fecha_desde));
        } else {
            $fechas = null;
        }
        $datos = (object)[
            'concursos' => Concurso::all(),
            'concurso' => Concurso::findOrFail($request['concurso_id']),
            'estado' => $estado,
            'postulacion' => $postulacion,
            'fechas' => $fechas
        ];
        //dd($datos->concurso);
        session()->forget(['estado', 'concurso_id']);
        return view('frontend.validar.documental.documental', compact('datos'));
    }

    public function matriz(Request $request)
    {
        $estado = $request->estado;
        $concurso = Concurso::with('cargos')->findOrFail($request->concurso_id);
        $matriz = MatrizDocumentale::with('tipoDoc')
                        ->where('concurso_id', '=', $request->concurso_id)
                        ->get();
        $postulacion = Postulacione::with('usuario.perfil', 'evaldocumental')
                        ->with('postDatosAcads.tipoDoc', 'postCapacitaciones', 'postDatosPersonales.tipoDoc', 'postExpLaborales')
                        ->findOrFail($request->postulacion_id);
        $rechazos = TipoRechazoDoc::all();
        if ($request->fechas) {
            $fechas = $request->fechas;
        } else {
            $fechas = null;
        }

        $postulante = postulante::where('ci', $postulacion->usuario->ci)
                        ->firstOrFail();

        $datos = (object)[
            'concurso' => $concurso,
            'estado' => $estado,
            'matriz' => $matriz,
            'postulacion' => $postulacion,
            'postulante' => $postulante,
            'rechazos' => $rechazos,
            'fechas' => $fechas
        ];
        //dd($datos->concurso->salario);
        return view('frontend.validar.documental.matriz-documental', compact('datos'));
    }

    //ValidacionDocumental
    public function store(Request $request)
    {
        $chkEval = Postulacione::findOrFail($request->postulacion_id);

        if ($chkEval->eval_docum == 'NE') {
            if (is_array($request->tipo_doc_id) && is_array($request->requerido)
                && ( count($request->tipo_doc_id) == count($request->requerido) ) ){
                foreach ($request->tipo_doc_id as $key => $value) {
                    $id = $request->get('tipo_doc_id')[$key];
                    $objeto = "cumple".$id;
                    if($request->get('requerido')[$key] == 1 && $request->get($objeto) == 0) {
                        $aprove[] = false;
                    } else {
                        $aprove[] = true;
                    }

                    EvalDocumentale::create([
                            'postulacion_id' => $request->postulacion_id,
                            'tipo_doc_id' => $request->get('tipo_doc_id')[$key],
                            'cumple' => $request->get($objeto),
                            'motivo_rechazo_id' => $request->get('motivo_rechazo_id')[$key],
                            'obs' => $request->get('obs')[$key]
                        ]);
                }
            }
            if (in_array(false, $aprove)) {
                Postulacione::findOrFail($request->postulacion_id)
                    ->update([
                        'eval_docum' => 'NA',
                        'eval_docum_user' => session('ci'),
                        'eval_docum_fecha' => date('Y-m-d H:i:s')
                    ]);
                    $evaluado = 'No Admitido';
            } else {
                Postulacione::findOrFail($request->postulacion_id)
                    ->update([
                        'eval_docum' => 'AD',
                        'eval_docum_user' => session('ci'),
                        'eval_docum_fecha' => date('Y-m-d H:i:s')
                    ]);
                    $evaluado = 'Admitido';
            }
            $object = 'mensaje';
            $message = 'Evaluación Realizada. '.$evaluado;
        } else {
            $object = 'mensaje';
            $message = 'ERROR: Usuario ya Evaludado';
        }
        $request->session()->put('estado', $request->estado);
        $request->session()->put('concurso_id', $request->concurso);
        $request->session()->put('fechas', $request->fechas);
        return redirect()->route('documentals')
                ->with($object, $message);
    }

    public function edit(Request $request)
    {
        $estado = $request->estado;
        $concurso = Concurso::with('cargos', 'matrizdocs')
                        ->findOrFail($request->concurso_id);
        $evaluacion = EvalDocumentale::with('tipoDoc', 'rechazos')
                        ->where('postulacion_id', '=', $request->postulacion_id)
                        ->orderBy('id')
                        ->get();
        $postulacion = Postulacione::with('usuario.perfil', 'evaldocumental')
                        ->with('postDatosAcads.tipoDoc', 'postCapacitaciones', 'postDatosPersonales.tipoDoc', 'postExpLaborales')
                        ->orderBy('id')
                        ->findOrFail($request->postulacion_id);
        $rechazos = TipoRechazoDoc::all();
        if ($request->fechas) {
            $fechas = $request->fechas;
        } else {
            $fechas = null;
        }

        $postulante = postulante::where('ci', $postulacion->usuario->ci)
                        ->firstOrFail();

        foreach ($concurso->matrizdocs as $m) {
            $idMatriz[] = array(
                                'id' => $m->tipo_doc_id,
                                'req' => $m->requerido
                            );
        }

        $datos = (object)[
            'concurso' => $concurso,
            'matriz' => $idMatriz,
            'estado' => $estado,
            'evaluacion' => $evaluacion,
            'postulacion' => $postulacion,
            'postulante' => $postulante,
            'rechazos' => $rechazos,
            'fechas' => $fechas
        ];
        //dd($datos->postulacion->postCapacitaciones);
        return view('frontend.validar.documental.edit-documental', compact('datos'));
    }


    public function update(Request $request)
    {
        //dd($request->all());
        if (is_array($request->tipo_doc_id) && is_array($request->requerido) && (count($request->tipo_doc_id) == count($request->requerido))){
            foreach ($request->id as $key => $value) {
                $idDoc = $request->get('tipo_doc_id')[$key];
                $objeto = "cumple".$idDoc;
                if($request->get('requerido')[$key] == 1 && $request->get($objeto) == 0) {
                    $aprove[] = false;
                } else {
                    $aprove[] = true;
                }
                EvalDocumentale::findOrFail($request->id[$key])
                    ->update([
                        'cumple' => $request->get($objeto),
                        'motivo_rechazo_id' => $request->get('motivo_rechazo_id')[$key],
                        'obs' => $request->get('obs')[$key]
                    ]);
            }
        }
        if (in_array(false, $aprove)) {
            Postulacione::findOrFail($request->postulacion_id)
                ->update([
                    'eval_docum' => 'NA',
                    'eval_docum_user' => session('ci'),
                    'eval_docum_fecha' => date('Y-m-d H:i:s')
                ]);
                $evaluado = 'No Admitido';
        } else {
            Postulacione::findOrFail($request->postulacion_id)
                ->update([
                    'eval_docum' => 'AD',
                    'eval_docum_user' => session('ci'),
                    'eval_docum_fecha' => date('Y-m-d H:i:s')
                ]);
                $evaluado = 'Admitido';
        }
        $request->session()->put('estado', $request->estado);
        $request->session()->put('concurso_id', $request->concurso);
        $request->session()->put('fechas', $request->fechas);
        return redirect()->route('documentals')
                ->with('mensaje', 'Evaluación Actualizada. '.$evaluado);
    }

}
