<?php

namespace App\Http\Controllers;

use App\Models\Concurso;
use App\Traits\Concursos;
use App\Models\Postulante;
use App\Models\Postulacione;
use Illuminate\Http\Request;
use App\Models\TipoRechazoDoc;
use App\Models\EvalCurriculare;
use App\Models\MatrizCurriculare;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ValidacionDocumental;

class EvaluacionCurricularController extends Controller
{
    use Concursos;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function curricular()
    {
        $datos = (object)[
            'concursos' => Concurso::with('cargos')->orderBy('id')->get(),
            'concurso' => null,
            'estado' => null,
            'postulacion' => null
        ];
        return view('frontend.validar.curricular.curricular', compact('datos'));
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
            $sconcurso_id = session('concurso_id');
            $estado = session('estado');
        } else {
            return redirect()->route('curricular')->with('Error', 'imposible repetir la búsqueda');
        }
        if (session('fechas') !== null) {
            if (strstr(session('fechas'), '-')) {
                $arrDate = explode('-', session('fechas'));
                $fecha['desde'] = $this->fixFecha(trim($arrDate[0]));
                $fecha['hasta'] = $this->fixFecha(trim($arrDate[1]));
            } elseif (!strstr(session('fechas'), '-')) {
                $fecha['desde'] = $this->fixFecha(trim(session('fechas')));
                $fecha['hasta'] = $fecha['desde'];
            }
        } else {
            $fecha['desde'] = null;
            $fecha['hasta'] = null;
        }
        //dd($fecha);

        $postulacion = Postulacione::with('concurso.cargos', 'usuario.perfil')
                        ->with('usuario')
                        ->with('postDatosAcads.tipoDoc', 'postCapacitaciones', 'postDatosPersonales.tipoDoc', 'postExpLaborales')
                        ->where('concurso_id', '=', $sconcurso_id)
                        ->where('eval_docum', 'AD')
                        ->when($estado, function($e) use($estado) {
                            $e->when($estado == 'NE', function($e) use($estado){
                                //$e->whereNull('eval_curric');
                                $e->where('eval_curric', 0);
                            });
                            $e->when($estado == 'EV', function($e) use($estado){
                                //$e->whereNotNull('eval_curric');
                                $e->where('eval_curric', '!=', 0);
                            });
                        })
                        ->when($fecha['desde'] !== null, function($f) use($fecha){
                            $f->when($fecha['desde'] && $fecha['hasta'], function($q) use($fecha){
                                $q->when($fecha['desde'] == $fecha['hasta'], function($q) use($fecha){
                                    $q->whereDate('fecha_post', '=', $fecha['desde']);
                                });
                                $q->when($fecha['desde'] !== $fecha['hasta'], function($q) use($fecha){
                                    $q->whereBetween('fecha_post', [$fecha['desde']." 00:00:00", $fecha['hasta']." 23:59:59"]);
                                });
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
            'postulacion' => $postulacion,
            'fechas' => $fechas,
            'estado' => session('estado')
        ];
        //dd($datos->postulacion);
        session()->forget(['estado', 'concurso_id', 'fechas']);
        return view('frontend.validar.curricular.curricular', compact('datos'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        //dd($request->all());
        $postulacion = Postulacione::with('concurso.cargos', 'usuario.perfil')
                        ->with('usuario')
                        ->with('postDatosAcads.tipoDoc', 'postCapacitaciones', 'postDatosPersonales.tipoDoc', 'postExpLaborales')
                        ->where('concurso_id', $request->concurso_id)
                        ->where('eval_docum', 'AD')
                        ->when($request->estado, function($e) use($request) {
                            $e->when($request->estado == 'NE', function($e) use($request){
                                //$e->whereNull('eval_curric');
                                $e->where('eval_curric', 0);
                            });
                            $e->when($request->estado == 'EV', function($e) use($request){
                                //$e->whereNotNull('eval_curric');
                                $e->where('eval_curric', '!=', 0);
                            });
                        })
                        ->when($request->fecha_desde !== null, function($f) use($request){
                            $f->when($request->fecha_desde && $request->fecha_hasta, function($q) use($request){

                                $q->when($request->fecha_desde == $request->fecha_hasta, function($q) use($request){
                                    $q->whereDate('fecha_post', '=', $request->fecha_desde);

                                });
                                $q->when($request->fecha_desde !== $request->fecha_hasta, function($q) use($request){

                                    $q->whereBetween('fecha_post', [$request->fecha_desde." 00:00:00", $request->fecha_hasta." 23:59:59"]);
                                });
                            });
                        })
                        ->orderBy('id')
                        ->get();

        if ($request->fecha_desde !== null
                && $request->fecha_hasta !== null
                && $request->fecha_desde !== $request->fecha_hasta) {
            $fechas = date('d/m/Y', strtotime($request->fecha_desde)).' - '.date('d/m/Y', strtotime($request->fecha_hasta));
        } elseif ($request->fecha_desde !== null
                && $request->fecha_desde == $request->fecha_hasta ) {
            $fechas = date('d/m/Y', strtotime($request->fecha_desde));
        } else {
            $fechas = null;
        }
        $datos = (object)[
            'concursos' => Concurso::all(),
            'concurso' => Concurso::findOrFail($request['concurso_id']),
            'postulacion' => $postulacion,
            'fechas' => $fechas,
            'estado' => $request->estado
        ];
        //dd($datos);
        session()->forget(['estado', 'concurso_id', 'fechas']);
        return view('frontend.validar.curricular.curricular', compact('datos'));
    }

    public function matriz(Request $request)
    {
        $concurso = Concurso::with('cargos')->findOrFail($request->concurso_id);
        $matriz = MatrizCurriculare::with('itemevalcurric')
                        ->where('concurso_id', '=', $request->concurso_id)
                        ->orderBy('id')
                        ->get();
        $postulacion = Postulacione::with('usuario.perfil', 'evalcurric')
                        ->with('postDatosAcads.tipoDoc', 'postCapacitaciones.tipoDoc', 'postDatosPersonales.tipoDoc', 'postExpLaborales.tipoDoc')
                        ->findOrFail($request->postulacion_id);
        if ($request->fechas) {
            $fechas = $request->fechas;
        } else {
            $fechas = null;
        }

        $postulante = Postulante::where('ci', $postulacion->usuario->ci)
                        ->firstOrFail();

        $datos = (object)[
            'concurso' => $concurso,
            'matriz' => $matriz,
            'postulacion' => $postulacion,
            'postulante' => $postulante,
            'fechas' => $fechas,
            'estado' => $request->estado
        ];
        //dd($datos->postulacion->postDatosPersonales);
        return view('frontend.validar.curricular.matriz-curricular', compact('datos'));
    }

    //ValidacionDocumental
    public function store(Request $request)
    {
        //dd($request->all());
        $chkEval = Postulacione::findOrFail($request->postulacion_id);
        //dd($chkEval);
        if ($chkEval->eval_curric == 0) {
            if (is_array($request->item_eval_curric_id) && is_array($request->puntaje)
                && (count($request->item_eval_curric_id) == count($request->puntaje))){
                $sum_puntaje = 0;
                foreach ($request->item_eval_curric_id as $key => $value) {
                    //$id = $request->get('item_eval_curric_id')[$key];
                    //$objeto = "cumple".$id;
                    EvalCurriculare::create([
                            'postulacion_id' => $request->postulacion_id,
                            'item_eval_curric_id' => $request->get('item_eval_curric_id')[$key],
                            'puntaje' => $request->get('puntaje')[$key],
                            'obs' => $request->get('obs')[$key]
                        ]);
                    $sum_puntaje = $sum_puntaje + $request->get('puntaje')[$key];
                }
            }
            $chkEval->update([
                    'eval_curric' => $sum_puntaje,
                    'eval_curric_user' => session('ci'),
                    'eval_curric_fecha' => date('Y-m-d H:i:s')
                ]);
            $object = 'mensaje';
            $message = 'Evaluación Realizada. '.$sum_puntaje;
        } else {
            $object = 'mensaje';
            $message = 'ERROR: Usuario ya Evaludado';
        }
        $request->session()->put('concurso_id', $request->concurso);
        $request->session()->put('fechas', $request->fechas);
        $request->session()->put('estado', $request->estado);
        return redirect()->route('curriculars')
                ->with($object, $message);
    }

    public function edit(Request $request)
    {
        //dd($request->all());
        $concurso = Concurso::with('cargos', 'matrizcurric')
                        ->findOrFail($request->concurso_id);
        $matriz = MatrizCurriculare::with('itemevalcurric')
                        ->where('concurso_id', '=', $request->concurso_id)
                        ->get();
        $evaluacion = EvalCurriculare::with('itemevalcurric')
                        ->where('postulacion_id', '=', $request->postulacion_id)
                        ->orderBy('id')
                        ->get();
        $postulacion = Postulacione::with('usuario.perfil', 'evaldocumental')
                        ->with('postDatosAcads.tipoDoc', 'postCapacitaciones', 'postDatosPersonales.tipoDoc', 'postExpLaborales')
                        ->orderBy('id')
                        ->findOrFail($request->postulacion_id);
        if ($request->fechas) {
            $fechas = $request->fechas;
        } else {
            $fechas = null;
        }

        $postulante = postulante::where('ci', $postulacion->usuario->ci)
                        ->firstOrFail();

        $datos = (object)[
            'concurso' => $concurso,
            'matriz' => $matriz,
            'evaluacion' => $evaluacion,
            'postulacion' => $postulacion,
            'postulante' => $postulante,
            'fechas' => $fechas,
            'estado' => $request->estado
        ];
        //dd($datos->postulacion);
        return view('frontend.validar.curricular.edit-curricular', compact('datos'));
    }


    public function update(Request $request)
    {
        //dd($request->all());
        $eval = Postulacione::findOrFail($request->postulacion_id);

        if (is_array($request->item_eval_curric_id)
                && is_array($request->puntaje)
                && is_array($request->id)
            && (count($request->item_eval_curric_id) == count($request->puntaje)
                && count($request->item_eval_curric_id) == count($request->id))
        ){
            $sum_puntaje = 0;
            $update = true;

            foreach ($request->id as $key => $value) {

                if (is_numeric($request->get('puntaje')[$key])) {
                    $puntaje = $request->get('puntaje')[$key];
                } else {
                    $update = false;
                    break;
                }

                EvalCurriculare::findOrFail($request->id[$key])
                    ->update([
                        'postulacion_id' => $request->postulacion_id,
                        'item_eval_curric_id' => $request->get('item_eval_curric_id')[$key],
                        'puntaje' => $puntaje,
                        'obs' => $request->get('obs')[$key]
                    ]);
                $sum_puntaje = $sum_puntaje + $puntaje;

            }
        } else {
            $update = false;
            $object = 'mensaje';
            $message = 'ERROR: No se pudo actualizar el registro';
        }

        if ($update == true) {
            $eval->update([
                    'eval_curric' => $sum_puntaje,
                    'eval_curric_user' => session('ci'),
                    'eval_curric_fecha' => date('Y-m-d H:i:s')
                ]);
            $object = 'mensaje';
            $message = 'Evaluación Actualizada. '.$sum_puntaje;
        } else {
            $object = 'mensaje';
            $message = 'ERROR: No se pudo actualizar el registro';
        }

        $request->session()->put('estado', $request->estado);
        $request->session()->put('concurso_id', $request->concurso);
        if ($request->fechas) {
            $request->session()->put('fechas', $request->fechas);
        } else {
            $request->session()->put('fechas', null);
        }
        return redirect()->route('curriculars')
                ->with($object, $message);
    }

}
