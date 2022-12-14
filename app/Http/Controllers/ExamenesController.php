<?php

namespace App\Http\Controllers;

use App\Models\Examene;
use App\Models\Concurso;
use App\Models\EvalExamene;
use App\Models\Postulacione;
use App\Models\Configuracione;
use Illuminate\Http\Request;

class ExamenesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(json_encode($array));
        //return response()->json($array, 200);
        return view('frontend.examenes.index');
    }

    public function rendir(Request $request)
    {

        $postulacion = Postulacione::where('concurso_id', $request->concurso_id)
                            ->where('usuario_id', session()->get('usuario_id'))
                            ->firstOrFail();
        $configuracion = Configuracione::find(1);

        if($postulacion->act_intento_examen < $configuracion->max_intento_examen) {
            $examen = Examene::where('concurso_id', $request->concurso_id)
                        ->firstOrFail();
            $concurso = Concurso::with('cargos')
                        ->where('id', $request->concurso_id)
                        ->firstOrFail();
            if(strtotime(date('H:i:s')) >= strtotime($examen->hora_ini) && strtotime(date('H:i:s')) <= strtotime($examen->hora_fin)) {
                $cantPregxExa = $examen->cant_preg_examen;
                $cantPreg = count($examen->preguntas['preguntas']);

                $idxPreg = array();
                $i=0;
                while($i<$cantPregxExa){
                    $aleatNum = rand(0, $cantPreg-1);
                    if (!in_array($aleatNum,$idxPreg)) {
                        array_push($idxPreg, $aleatNum);
                        $i++;
                    }
                }

                $preguntas = array();
                foreach ($idxPreg as $id) {
                    array_push($preguntas, $examen->preguntas['preguntas'][$id]);
                }

                $datos = (object)[
                        'examen' => $examen,
                        'concurso' => $concurso,
                        'postulacion' => $postulacion,
                        'preguntas' => $preguntas,
                    ];
                $new_intento_examen = $postulacion->act_intento_examen + 1;
                //dd($new_intento_examen);
                $postulacion->update(['act_intento_examen' => $new_intento_examen]);
                return view('frontend.examenes.rendir', compact('datos'));
            } else {
                $object = 'mensaje';
                $message = 'ERROR: Horario no Permitido';
                //return view('frontend.examenes.denegado'); //compact('datos')
                return redirect()->route('examen')
                    ->with($object, $message);
            }
        } else {
            $object = 'mensaje';
            $message = 'ERROR: Intentos agotados';
            //return view('frontend.examenes.denegado'); //compact('datos')
            return redirect()->route('examen')
                ->with($object, $message);
        }

    }


    public function list()
    {
        $examenes = Examene::with('concurso')
                        ->orderBy('id')
                        ->get();
        $datos = (object)[
            'examenes' => $examenes,
        ];
        //dd($datos->examenes[0]->preguntas);
        return view('backend.examenes.index', compact('datos'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $examen = Examene::with('concurso')
                    ->where('id', $id)
                    ->firstOrFail();
        $preguntas = $examen->preguntas;
        $datos = (object)[
            'examen' => $examen,
            'preguntas' => $preguntas
        ];
        //dd($datos->preguntas['preguntas'][0]);
        return view('backend.examenes.questions', compact('datos'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $examenes = Examene::all();
        if (count($examenes) > 0) {
            foreach($examenes as $e) {
                $idConcursosExam[] = $e->concurso_id;
                $idConcursosExam = implode(',', $idConcursosExam);
            }
        } else {
            $idConcursosExam = 0;
        }

        $concursos = Concurso::with('cargos')
                        ->when($idConcursosExam, function($idx) use($idConcursosExam) {
                            $idx->when($idConcursosExam != 0, function($idx) use($idConcursosExam){
                                $idx->whereNotIn('id', [$idConcursosExam]);
                            });
                        })
                        ->orderBy('id')
                        ->get();
        $datos = (object)[
            'concursos' => $concursos,
        ];
        //dd($datos);
        return view('backend.examenes.create', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $preguntas = array(
            'preguntas'=>
            array(
                array(
                    'pregunta_id'=>1,
                    'pregunta'=>'Los valores en los cuales descansa la Pol??tica Nacional de Salud son esenciales para establecer las prioridades nacionales y para evaluar si los pactos sociales responden o no a las necesidades y expectativas de la poblaci??n, cu??l de las opciones describe dichos valores.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Derecho a la Salud, Equidad, Solidaridad, Respeto por la Diversidad.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Derecho a la Vida, Salud y Educaci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Derecho a la diversidad y respeto al pr??jimo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Derechos para el Ni??o, la Mujer y el Adulto Mayor.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>2,
                    'pregunta'=>'Los marcos conceptuales que orientan la actual propuesta de la PNS ponen ??nfasis en:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Fuerte apuesta a la tercerizaci??n de Servicios de Salud y Asociaci??n Publico Privada',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El fortalecimiento de "la estrategia de acceso universal a la salud y cobertura universal de salud".',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La implementaci??n de un Programa Nacional de Vacunas adecuado.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Capacitaci??n de los personales encargados de referencia y contra referencia en el sistema p??blico.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>3,
                    'pregunta'=>'En el enfoque de determinantes sociales la mirada Ambiental, eval??a.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'El H??bitat espec??fico de las comunidades donde importan la seguridad y la disponibilidad en el acceso a agua, la ocurrencia de fen??menos naturales riesgosos, la interrelaci??n con otras especies, la contaminaci??n de suelo y aire. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El ciclo biol??gico del ecosistema.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La abundancia de enfermedades transmitidas por vectores.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'La distancia de las comunidades a sus respectivos servicios de Salud.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>4,
                    'pregunta'=>'El enfoque de determinantes sociales se??ala al menos cuatro categor??as que son.',
                    'puntaje'=>1,
                    'respuestas'=> array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Culturales, Religiosos, Epidemiol??gicos, acceso a aguas mejoradas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Epidemiol??gicos, ??tnicos, h??bitos saludables, sedentarios.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Ambientales, Socioecon??micos, Servicios de atenci??n en salud, Biol??gicos.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Religioso, pol??tico, educativo, agr??colas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>5,
                    'pregunta'=>'De los siguientes enunciados se??ala cual es correcto:',
                    'puntaje'=>1,
                    'respuestas'=> array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La PNS de Paraguay, est?? en consonancia con las metas conocidas en el marco de los ODS de las Naciones Unidas.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los servicios de tercer y cuarto nivel son la puerta de acceso en la atenci??n primaria de la salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Las Unidades de Salud Familiar funcionan las 24 hs todos los d??as. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Los consejos locales de salud tienen como encargado a los lideres comunitarios ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>6,
                    'pregunta'=>'La Equidad en Salud:',
                    'puntaje'=>1,
                    'respuestas'=> array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Consiste en la creaci??n de filtros de acceso por genero a los servicios de salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Tiene que ver con el acceso y cobertura a servicios de urgencia las 24hs ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Depende del tipo de patolog??as que padece una poblaci??n en una regi??n. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Se refiere a la ausencia de diferencias injustas en el estado de salud, en el acceso a servicios de salud integrales, oportunos, y de calidad, en la contribuci??n financiera, y en el acceso a entornos saludables.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>7,
                    'pregunta'=>'Son elementos en la formulaci??n de la Pol??tica Nacional de salud:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Situaci??n de la Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Marco conceptual y compromisos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Valores y Principios.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>8,
                    'pregunta'=>'En la PNS son considerados indicadores importantes y que se monitorean anualmente los siguientes:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La mortalidad neonatal, mortalidad materna, enfermedades transmitidas por vectores.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Tasa de accidentes de tr??nsito y sus secuelas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Enfermedades cr??nicas ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Numero de camas de terapia intensiva polivalente. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>9,
                    'pregunta'=>'En lo relativo al acceso a saneamiento b??sico, pese a los importantes avances, persisten d??ficits en acceso a agua potable segura, disposici??n de excretas y de residuos s??lidos. El grupo m??s afectado por esta carencia:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Las poblaciones m??s vulnerables situadas en los quintiles de ingresos m??s bajos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Sectores sociales de trabajadores informales y artesanos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Complejos habitacionales de viviendas sociales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Poblaciones residentes en los alrededores de la ciudad capital.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>10,
                    'pregunta'=>'Persisten varios retos para optimizar la capacidad de respuesta de los servicios sanitarios ante la creciente demanda de atenci??n. ??Cu??l de lo siguientes se encuentra dentro de la PNS de nuestro pa??s?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Desarrollo e implementaci??n de una Red Integrada e integral de Servicios de Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Reducir ostensiblemente los gastos de bolsillo y otros desembolsos directos de los usuarios.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Garantizar beneficios similares para todos los grupos de poblaci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>11,
                    'pregunta'=>'Le atentamente cada enunciado y marca la respuesta correcta.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Promoci??n de la Salud es un proceso desarrollado por el Ministerio de Salud tendientes a lograr el uso adecuado de medicamentos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La Promoci??n de la Salud es una obligaci??n exclusiva del ministerio de salud P??blica.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La Promoci??n de la Salud es realizada por la gente y con la gente, en los diversos ??mbitos de su vida cotidiana, en donde vive, trabaja, estudia o se recrea.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'La Promoci??n de la salud se debe realizar entre las personas enfermas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>12,
                    'pregunta'=>'El prop??sito de la Promoci??n de la Salud es: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Lograr cambios favorables en la situaci??n de salud de las personas, la preservaci??n del ambiente, y la calidad de vida de la poblaci??n.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Creaci??n e intercambio de conocimientos, informaci??n y experiencias en educaci??n a nivel nacional e internacional.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Mejorar el acceso a la salud con caminos de todo tiempo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Dotar de tecnolog??a, conectividad y transmisi??n de datos a los Hospitales.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>13,
                    'pregunta'=>'La salud de las personas y de las comunidades configura una situaci??n din??mica, cambiante, cuyo equilibrio depende de m??ltiples factores entre los que se destacan por su importancia.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'El Estado y el gobierno, la sociedad en su conjunto, los grupos humanos, las familias y cada persona',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los programas de ayuda agropecuarios del MAG, para peque??os agricultores.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La cantidad de hospitales de tercer y cuarto nivel.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Medicina emp??rica y terapias alternativas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>14,
                    'pregunta'=>'De los siguientes son determinantes sociales de la Salud.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Falta de Pol??ticas de protecci??n a sectores vulnerables.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Uso de agro t??xicos en plantaciones.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Nivel educativo, Ingresos, ocupaci??n, acceso a agua potable',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Canasta de medicamentos insuficiente.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>15,
                    'pregunta'=>'Son Funciones del Agente comunitario de salud.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Fortalecer el v??nculo entre el equipo de salud de la familia y la comunidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Realizar acciones educativas, de promoci??n, de prevenci??n, y vigilancia de acuerdo a la planificaci??n del equipo de salud de la familia.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Tienen un ??rea geogr??fica de acci??n definido o micro territorio que deben cubrir.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores son funciones de los agentes comunitarios.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>16,
                    'pregunta'=>'En el acceso universal a la salud se consideran limitantes de acceso:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Barreras de tipo geogr??fico, econ??mico, socio cultural, administrativo y de g??nero.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El retraso en la atenci??n oportuna impide un tratamiento adecuado en la mayor??a de los casos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Las comunidades con limitaciones en el acceso al agua potable.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'El acceso universal significa que cualquiera puede ser atendido en cualquier nivel de atenci??n sin importar la gravedad.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>17,
                    'pregunta'=>'??Cu??l es la definici??n correcta de Modelo de Atenci??n en salud?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es el conjunto de Normas y procedimientos establecidos que define un esquema de servicios de salud estableciendo niveles de atenci??n, orientados a entregar servicios en funci??n de las expectativas y necesidades de la poblaci??n.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Es la definici??n entre un modelo preventivo y uno curativo o paliativo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Es la distribuci??n de la cartera de servicios ofertada por un sistema de salud teniendo en cuenta solo las ofertas  del sistema p??blico.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Modelo que tiene en cuenta las estad??sticas de los atendidos por regi??n y su tipificaci??n.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>18,
                    'pregunta'=>'??C??mo se mide la calidad de atenci??n en Salud?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Por el grado de satisfacci??n del usuario con la atenci??n recibida, sin medir la resolutividad',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Grado en que los servicios de salud prestados a personas y poblaciones aumentan la probabilidad de lograr los resultados sanitarios deseados y en coherencia con los conocimientos cient??ficos del momento.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Por el tiempo de espera en las consultas y en las urgencias.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Por la tecnolog??a implementada en equipos de diagnostico.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>19,
                    'pregunta'=>'Con relaci??n a Las Unidades de Salud Familiar todo lo siguiente es correcto excepto:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es la unidad efectora local, operacionalizada por un equipo de salud de la familiar responsable de territorios de 3000 a 5000 habitantes.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Opera como puerta de entrada al sistema de salud en el primer nivel de atenci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Su objetivo es prestar cartera de servicios definida para el primer nivel de atenci??n lo mas cerca posible del lugar donde viven y trabajan las personas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Proveer asistencia m??dica intensiva de vanguardia al paciente cr??tico.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>20,
                    'pregunta'=>'El abordaje o la puerta de entrada de la Promoci??n de la salud puede darse:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'En ??mbitos espec??ficos de la vida cotidiana (hogares, escuelas, lugares de trabajo, de recreaci??n y de intercambio social, servicios de salud, etc).',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Seg??n necesidades del ciclo de vida, edades y funciones (salud infantil, adolescencia y juventud, salud sexual y reproductiva, maternidad segura, salud de la mujer, en la nutrici??n, el crecimiento y desarrollo psicol??gico, f??sico, mental, salud de las personas adultas mayores).',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Por temas espec??ficos que hacen a los comportamientos, los estilos de vida y la calidad de ambientes f??sicos y sociales sanos, seguros y de su convivencia arm??nica.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las opciones son correctas.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>21,
                    'pregunta'=>'El c??digo de buen gobierno define las pol??ticas para la administraci??n de la Instituci??n, seg??n dicho c??digo una gesti??n p??blica se debe caracterizar por:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Un c??digo de buen Gobierno rige solo para empresas privadas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Protocolos estrictos, tr??mites burocr??ticos, planillas de control din??micas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Manejo pol??tico seg??n criterio del gobernante de turno.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'La Integridad, la Transparencia, la Eficiencia y la Eficacia.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>22,
                    'pregunta'=>'Se entiende por Gobernabilidad:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Conjunto de condiciones que hacen factible a un gerente o administrador el ejercicio real del poder que formalmente se le ha entregado para el cumplimiento de los objetivos y fines bajo la responsabilidad de su cargo.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Pacto Social entre los grupos de inter??s donde se negocia el ejercicio del poder estableciendo la fortaleza del partido pol??tico mayoritario.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Equilibrio arm??nico que se establece en una mesa de trabajo intersectorial.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Administraci??n de las pol??ticas de gesti??n de gobierno de manera transversal.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>23,
                    'pregunta'=>'El C??digo de Buen Gobierno tiene por finalidad',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Establecer un nomenclador universal asignando a cada instituci??n un c??digo identificador para los problemas comunes.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Elaborar un diagnostico situacional y un plan de mejora institucional.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Buscar  eficiencia, transparencia e integridad para responder adecuadamente ante sus grupos de inter??s, asegurando un comportamiento ??tico institucional.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Sancionara las instituciones o funcionarios que incurran en faltas administrativas de acuerdo a su gravedad.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>24,
                    'pregunta'=>'El concepto de Gobierno Abierto es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Conjunto de pr??cticas a trav??s de las cuales los gobiernos democr??ticos se relacionan con la ciudadan??a, aplicando los principios de conversaci??n permanente, en el sentido doble: escuchar y responder.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Un Modelo Democr??tico con nula o muy baja inversi??n de su fuerza de seguridad p??blica con alto nivel de educaci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Un gobierno Abierto es transparente en el sentido de que rinde cuentas y pone a disposici??n de la ciudadan??a la informaci??n p??blica.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'La opci??n a y c son correctas.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>25,
                    'pregunta'=>'En lo que respecta a Rendici??n de Cuentas:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es Deber de todo funcionario p??blico que administre bienes p??blicos de responder e informar por la administraci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Deber?? declarar el manejo, rendimiento de fondos, bienes y/o recursos p??blicos asignados y los respectivos resultados, en el cumplimiento del mandato que le ha sido conferido.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El funcionario deber?? manejarse asegurando un comportamiento ??tico institucional.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas son correctas.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>26,
                    'pregunta'=>'El C??digo de Buen Gobierno vigente establece Pol??ticas de buen gobierno para la Gesti??n de la Instituci??n entre las que se destacan.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Pol??ticas para la gesti??n ??tica, para la gesti??n de talento humano, de informaci??n y comunicaci??n, de responsabilidad social, de gesti??n de calidad.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Pol??ticas asociadas a Partidos pol??ticos, grupos sindicales, gremios de la educaci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La aplicaci??n de Modelos de gesti??n de Calidad como los ISO 9001 ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'La planificaci??n y monitoreo de proyectos ligados a la Salud hasta su implementaci??n.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>27,
                    'pregunta'=>'El Comit?? de ??tica y Buen Gobierno tendr?? las siguientes funciones:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Asegurarse de la difusi??n de los C??digos de ??tica y Buen Gobierno y de una adecuada capacitaci??n en los mismos a los funcionarios y grupos de inter??s de la instituci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Monitorear el desarrollo y cumplimiento de las pol??ticas contenidas en los C??digos de ??tica y de Buen Gobierno.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Vigilar el comportamiento de los que conforman la Instituci??n para asegurar el cumplimiento de los C??digos de ??tica y Buen Gobierno.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Resolver controversias sobre interpretaci??n de los art??culos del C??digo de ??tica y Buen Gobierno.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas las anteriores son funciones del comit?? de ??tica.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>28,
                    'pregunta'=>'Seg??n el c??digo de buen Gobierno, al hablar de Riesgos nos referimos: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Posibilidad de ocurrencia de eventos tanto internos como externos que puedan afectar o impedir el logro de los objetivos de la instituci??n, entorpeciendo el desarrollo normal de sus funciones.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Evento aleatorio e impredecible que se presenta sorpresivamente atentando contra el buen desarrollo institucional de los objetivos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Para una gesti??n de gobierno las tomas de decisiones acertadas se encuentran libres de riesgos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna es correcta.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>29,
                    'pregunta'=>'Podemos decir de la Transparencia en gesti??n de Gobierno.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es ??tico el uso Campa??as publicitarias donde se trata de mostrar el trabajo realizado y se destacan solo lo positivo de una gesti??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Forma de hacer visible la funci??n p??blica, ejecut??ndola de acuerdo con las normas constitucionales y legales aceptando y facilitando que la gesti??n sea observada en forma directa por los grupos de inter??s.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La transparencia implica el deber de rendir cuentas de la gesti??n encomendada.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Las opciones b y c son correctas.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>30,
                    'pregunta'=>'??Cu??l es las siguientes definiciones es la correcta?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Grupos de Inter??s: Partes interesadas, persona, grupos o instituciones sobre las cuales el ente p??blico tiene influencia o son influenciadas por ella.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Administrar: Hacer diligencias o acciones que garanticen la administraci??n eficiente de un negocio o proyecto, y que conduzcan al logro de los objetivos propuestos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Gestionar: Gobernar, ejercer la autoridad o mando sobre un territorio y sobre personas que lo habitan, dirigir una instituci??n. Ordenar, disponer, organizar, en especial la hacienda y los bienes.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>31,
                    'pregunta'=>'El objetivo general de la Pol??tica Nacional de Recursos Humanos en Salud del Paraguay 2020-2030:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Se centra en la definici??n de estrategias y l??neas de acci??n que permitan fortalecer la rector??a y gobernanza de la gesti??n de los RHS',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Es impulsar el desarrollo planificado e integral de los RHS.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Es disponer de los datos sobre RHS.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Incentivar el desarrollo y la incorporaci??n del personal de salud.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>32,
                    'pregunta'=>'La Estrategia 1 de la Pol??tica Nacional del Recursos Humanos en Salud del Paraguay 2020-2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Planificaci??n Integral de los Recursos Humanos en Salud',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de la Rector??a y Gesti??n de la Autoridad Sanitaria en relaci??n a los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementaci??n del Sistema Nacional de Informaci??n Estrat??gica de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementaci??n de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>33,
                    'pregunta'=>'La Estrategia 2 de la Pol??tica Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Planificaci??n Integral de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de la Rector??a y Gesti??n de la Autoridad Sanitaria en relaci??n a los Recursos Humanos en Salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementaci??n del Sistema Nacional de Informaci??n Estrat??gica de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementaci??n de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>34,
                    'pregunta'=>'La Estrategia 3 de la Pol??tica Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Planificaci??n Integral de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de la Rector??a y Gesti??n de la Autoridad Sanitaria en relaci??n a los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementaci??n del Sistema Nacional de Informaci??n Estrat??gica de los Recursos Humanos en Salud',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementaci??n de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>35,
                    'pregunta'=>'La Estrategia 4 de la Pol??tica Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Planificaci??n Integral de los Recursos Humanos en Salud',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de la Rector??a y Gesti??n de la Autoridad Sanitaria en relaci??n a los Recursos Humanos en Salud',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementaci??n del Sistema Nacional de Informaci??n Estrat??gica de los Recursos Humanos en Salud',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementaci??n de la Carrera Sanitaria.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>36,
                    'pregunta'=>'La Estrategia 5 de la Pol??tica Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Gesti??n y Desarrollo Equitativo de los Recursos Humanos en Salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Formaci??n y Capacitaci??n de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementaci??n del Sistema Nacional de Informaci??n Estrat??gica de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementaci??n de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>37,
                    'pregunta'=>'La Estrategia 6 de la Pol??tica Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Gesti??n y Desarrollo Equitativo de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Formaci??n y Capacitaci??n de los Recursos Humanos en Salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementaci??n del Sistema Nacional de Informaci??n Estrat??gica de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementaci??n de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>38,
                    'pregunta'=>'La l??nea de acci??n que propone la estrategia 6 de la Pol??tica Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030, es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Elaborar el Plan Nacional de Capacitaci??n de los RHS',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Planificar las necesidades de los distintos prestadores de servicios de salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Establecer el marco legal que regir?? la carrera sanitaria.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Definir pol??ticas de gesti??n de los RHS.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>39,
                    'pregunta'=>'Los Valores orientadores de la Pol??tica Nacional de Recursos Humanos en Salud del Paraguay 2020-2030, son:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Servicio y Responsabilidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Imparcialidad y Honestidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Compromiso y ??tica.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>40,
                    'pregunta'=>'El Marco de referencia para la construcci??n de la Pol??tica Nacional de Recursos Humanos en Salud del Paraguay 2020-2030, est?? constituido por:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Estrategia de Recursos Humanos para el Acceso Universal a La Salud y Cobertura Universal de Salud de la Organizaci??n Panamericana de la Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El Plan Nacional De Desarrollo. Construyendo El Paraguay del 2030.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La Pol??tica Nacional de Salud del Paraguay - PNS 2015-2030.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>41,
                    'pregunta'=>'Con respecto a la Pol??tica Nacional de Salud 2015/2030, marcar la respuesta correcta:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es un conjunto de enunciados que orientan el rumbo de las determinaciones y acciones que el Estado Paraguayo est?? decidido a realizar para garantiza r el ejercicio pleno del derecho a la salud de todos los paraguayos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fue construida en un proceso participativo de reflexi??n y an??lisis entre actores diversos con enfoque intersectorial e interinstitucional con el fin de establecer consensos pol??ticos y t??cnicos con amplia participaci??n de la ciudadan??a',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Esta pol??tica se emana del compromiso asumido por el Estado ante el Consejo Directivo de la OPS con el fin de la elaboraci??n de una Estrategia para el acceso universal y la cobertura en salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Solo A y B.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas son correctas.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>42,
                    'pregunta'=>'El enfoque de determinantes sociales se??ala las siguientes categor??as, excepto:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Ambientales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Socio econ??micas y culturales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Servicios de atenci??n en salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Biol??gicos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Bioseguridad sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>43,
                    'pregunta'=>'El acceso universal a la salud se entiende como la ausencia de barreras del tipo geogr??fico, econ??mico, sociocultural, de organizaci??n o de g??nero y se logra a trav??s de la eliminaci??n inmediata de las barreras que impiden que algunas personas utilicen servicios no esenciales de salud, determinados a nivel Internacional y de manera no equitativa',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Falso',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Verdadero',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>44,
                    'pregunta'=>'La cobertura de salud es definida como la capacidad del sistema de salud para responder a las necesidades de la poblaci??n, lo cual incluye la disponibilidad de infraestructura, recursos humanos, tecnolog??as de la salud, y financiamiento.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Falso',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Verdadero',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>45,
                    'pregunta'=>'Sobre la Pol??tica Nacional de Salud 2015 - 2030 la opci??n correcta es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Responde a los Objetivos de Desarrollo Sostenible.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Se encuentra sujeta a compromisos internacionales asumidos por el Estado  paraguayo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Es aplicada en todo el territorio paraguayo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Son correctas (a, b y c).',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Son correctas a y b.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>46,
                    'pregunta'=>'La Pol??tica Nacional de Salud se rige a trav??s de los siguientes principios excepto:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Universalidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Calidad y humanizaci??n de los servicios.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'lntegralidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Sostenibilidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Capacidad.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>47,
                    'pregunta'=>'La Pol??tica Nacional de Salud se rige a trav??s de los siguientes valores excepto:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Derecho a la salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Equidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Solidaridad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Respeto a la diversidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Organizaci??n',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>48,
                    'pregunta'=>'Sobre la Pol??tica Nacional de Salud 2015-2030:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Tiene 7 Estrategias de l??nea de acci??n.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Tiene 5 Estrategias de l??nea de acci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Tiene 6 Estrategias de l??nea de acci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Tiene 4 Estrategias de l??nea de acci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Tiene 3 Estrategias de l??nea de acci??n.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>49,
                    'pregunta'=>'Sobre la Pol??tica Nacional de Salud 2015-2030, es correcto todo excepto:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los Objetivos del PNS se transforman en estrategias, y luego estos son bajados a trav??s de una l??nea de acci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Avanzar hacia el acceso universal a la salud y lograr la cobertura universal de salud al mayor nivel posible.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Entre las estrategias de acci??n se encuentra fortalecer la Rector??a y Gobernanza.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas son correctas (a, b y c).',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Ninguna es correcta.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>50,
                    'pregunta'=>'Sobre las 7 estrategias de la Pol??tica Nacional de Salud 2015 - 2030:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La estrategia N?? 3 refiere al Fortalecimiento de las acciones intersectoriales e  interinstitucionales para el abordaje de los determinantes sociales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La Estrategia N?? 5 refiere al Fortalecimiento de la gesti??n del talento humano en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La estrategia N?? 6 refiere Desarrollo y Fortalecimiento de la Salud P??blica en las Fronteras.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas son correctas.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Solo B y C son correctas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>51,
                    'pregunta'=>'El concepto correcto del C??digo de Buen Gobierno es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Disposiciones voluntarias de autorregulaci??n de quienes ejercen el gobierno de las Instituciones, que a manera de compromiso ??tico buscan garantizar una gesti??n eficiente, ??ntegra y transparente en la administraci??n p??blica.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Documento de referencia para gestionar la ??tica en el d??a a d??a de la Instituci??n, y est?? conformado por los Principios y Valores.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Conjunto de condiciones que hacen factible a un gobernante o director el ejercicio real del poder que formalmente se le ha entregado para el cumplimiento de los objetivos y fines bajo la responsabilidad de su cargo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Manera en que las Instituciones son dirigidas, mejorando su funcionamiento interna y externamente, buscando eficiencia, transparencia e integridad, para responder adecuadamente ante sus grupos de inter??s, asegurando un comportamiento ??tico institucional.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Ninguna de las anteriores.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>52,
                    'pregunta'=>'Los Valores ??ticos que inspiran y soportan la gesti??n del Ministerio de Salud P??blica y Bienestar Social son los siguientes, EXCEPTO:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Servicio.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Responsabilidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Imparcialidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Participaci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Calidad.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>53,
                    'pregunta'=>'Se consideran Directivos con responsabilidad especial en la aplicaci??n del presente C??digo de Buen Gobierno, el Ministro de Salud P??blica y Bienestar Social:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Viceministro.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Directores Generales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Jefes de departamentos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Funcionarios administrativos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>54,
                    'pregunta'=>'En el marco del cumplimiento con el Art. 7 ???Compromiso con los Fines del Estado??? del C??digo de Buen Gobierno, el MSPBS se compromete a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Establecer las pol??ticas necesarias para cumplir los fines misionales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Asegurar que las pol??ticas trazadas se cumplan.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cumplir las disposiciones constitucionales y legales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ejecutar eficientemente su plan estrat??gico.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>55,
                    'pregunta'=>'En cuanto al Art. 18 ???Compromiso con la Comunicaci??n P??blica???, El Misterio de Salud P??blica y Bienestar Social se compromete a asumir la comunicaci??n y la informaci??n comobienes p??blicos, a conferirles un car??cter estrat??gico y orientarlas hacia el fortalecimiento de la identidad institucional y a la expansi??n de la capacidad productiva de los miembros de la Instituci??n, para lo cual las acciones comunicativas se efectuar??n de acuerdo con las pol??ticas de comunicaci??n que se establezcan:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Verdadero',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Falso',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>56,
                    'pregunta'=>'En cuanto al Art. 32: ???Deberes del Equipo Humano Relacionados con los Conflictos de Intereses??? el/los deber/es de los funcionarios p??blicos del Ministerio de Salud P??blica y Bienestar Social, es/son:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Revelar a tiempo y por escrito a los entes competentes cualquier posible conflicto de inter??s que crea tener.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Contribuir a que se permita la adecuada realizaci??n de las funciones encomendadas a los ??rganos de control interno y externo de la Instituci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Guardar y proteger la informaci??n que la normatividad legal haya definido como de car??cter reservado.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Contribuir a que se le otorgue a todos los ciudadanos y habitantes del territorio nacional un trato equitativo, ya que se le garanticen sus derechos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>57,
                    'pregunta'=>'En el Ministerio de Salud P??blica y Bienestar Social se instaurar?? un Comit?? de Buen Gobierno, compuesto por ciudadanos que ostenten la calidad de externos y/o independientes de la Instituci??n. Sus miembros se seleccionar??n de los siguientes colectivos: (1) miembro de la comunidad empresarial, nominado por las asociaciones empresariales; un (1) miembro de la sociedad civil, nominado por grupos comunitarios organizados u organizaciones no gubernamentales del sector salud; un (1) miembro de la comunidad acad??mica, nominado por rectores de universidades; un (1) miembro nominado por el Consejo Nacional de Salud; y un (1) miembro delegado por el Ministro en representaci??n del Ministerio de Salud:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Verdadero',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Falso',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>58,
                    'pregunta'=>'La definici??n correcta de Valores ??ticos es: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Formas de ser y de actuar de las personas que son altamente deseables como atributos o cualidades propias y de los dem??s, por cuanto posibilitan la construcci??n de una convivencia gratificante en el marco de la dignidad humana.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Forma de hacer visible la funci??n p??blica, ejecut??ndola de acuerdo con las normas constitucionales y legales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Manera en que las Instituciones son dirigidas, mejorando su funcionamiento interna y externamente, buscando eficiencia.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Instancia encargada de promover y liderar el proceso de implantaci??n de la gesti??n ??tica para Instituciones del Estado.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>59,
                    'pregunta'=>'El Ministerio de Salud P??blica y Bienestar Social fue creado por el Decreto Ley N?? 2000 del 15 de junio de 1936, con el marco legal preceptuado en la Constituci??n Nacional, la Ley N?? 836/80 ???C??digo Sanitario???, el Decreto N?? 21.376/1998 ???Por el cual se establece la nueva organizaci??n funcional del Ministerio de Salud P??blica y Bienestar Social???, y las dem??s disposiciones legales y reglamentarias vigentes que regulan el funcionamiento de la administraci??n p??blica del Paraguay.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Verdadero',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Falso',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>60,
                    'pregunta'=>'En cuanto al Segundo Capitulo: ???Pol??tica de Gesti??n del Recurso Humano???, el art. 17 reza: ???El Ministerio de Salud P??blica y Bienestar Social se compromete con el desarrollo de las competencias, habilidades, aptitudes e idoneidad de sus funcionarios p??blicos, determinando pol??ticas y pr??cticas de gesti??n humana que deben incorporar los derechos y deberes constitucionales para garantizar la justicia, equidad, imparcialidad y transparencia al realizar los procesos de selecci??n, inducci??n, formaci??n, capacitaci??n, promoci??n y evaluaci??n del desempe??o. En este sentido, la Instituci??n propender?? por la vinculaci??n de los m??s capaces e id??neos a la Administraci??n, bien sea como funcionarios p??blicos o como contratados.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Verdadero',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Falso',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>61,
                    'pregunta'=>'Algunos de los ejes de la Pol??tica Nacional de Promoci??n de la Salud son:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Fortalecimiento de entornos claves; Participaci??n y empoderamiento de la comunidad; Fortalecimiento de la rector??a y gobernanza.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de entornos claves; Participaci??n y empoderamiento de la comunidad; Integraci??n efectiva de la promoci??n de la salud en los servicios de salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Coordinaci??n intersectorial para abordar los determinantes sociales de la salud; Fortalecimiento de la gesti??n del talento humano.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Fortalecimiento de la Atenci??n Primaria de la Salud; Fortalecimiento de la Salud P??blica en las fronteras.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Ninguna es correcta.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>62,
                    'pregunta'=>'Concepto de Promoci??n de la Salud.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es una estrategia para promocionar la salud y prevenir enfermedades.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Es el estado de total bienestar f??sico, social y mental.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Es una estrategia que se realiza de manera anticipada para prevenir enfermedades.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Es el proceso que permite a las personas incrementar el control sobre su salud para mejorarla.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Es la intervenci??n que se realiza para reducir o eliminar la consecuencias de las enfermedades.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>63,
                    'pregunta'=>'Dimensiones para el desarrollo de competencias en promoci??n de la salud.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Econ??mico y tecnol??gico.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Social, cultural, ambiental, econ??mico y pol??tico.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Ambiental, cultural y religioso.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Cient??fico, cultural y tecnol??gico.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas son correctas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>64,
                    'pregunta'=>'Las estrategias utilizadas para la participaci??n y empoderamiento de la comunidad y compromiso de la sociedad civil es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Desarrollo de espacios y mecanismos de deliberaci??n.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Educaci??n permanente.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Construcci??n de alianzas y rendici??n de cuentas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Desarrollo de di??logo por la salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'a y d son correctos.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>65,
                    'pregunta'=>'Uno de los resultados del fortalecimiento de los entornos claves es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Procesos educativos que promueven la salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Indicadores crecientes de utilizaci??n de los servicios de salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Alianzas a nivel nacional, regional y local sostenibles sobre entornos saludables.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Incorporaci??n de la promoci??n de la salud en leyes y pol??ticas p??blicas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'a y d son correctas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>66,
                    'pregunta'=>'Uno de los resultados de la integraci??n efectiva de la promoci??n de la salud en los servicios de salud:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Indicadores de promoci??n de la salud incorporados en la base de datos de la informaci??n estrat??gica en salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Involucramiento de otros sectores en el abordaje de los determinantes sociales de la salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Procesos educativos que promueven la salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Procesos comunicativos generados desde la comunidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Incorporaci??n de promoci??n de la salud en los sectores gubernamentales y no gubernamentales.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>67,
                    'pregunta'=>'El abordaje o puerta de entrada de la promoci??n de la salud puede darse en:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Seg??n necesidades del ciclo de vida.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Por temas espec??ficos que hacen a los comportamientos, las oportunidades y condiciones de vida.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'En ??mbitos espec??ficos de la vida cotidiana.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'a y b son correctas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'a, b y c son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>68,
                    'pregunta'=>'El prop??sito de la Pol??tica Nacional de Promoci??n de la Salud es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Empoderar a la ciudadan??a sobre el cuidado de su salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Indicar el camino a seguir para prevenir enfermedades.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Renovar la promoci??n de la salud por medio de acciones sociales, pol??ticas y t??cnicas, que aborden los determinantes sociales de la salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Preservar y sostener ambientes sanos y seguros.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Intervenir en los estilos de vida individuales y colectivos. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>69,
                    'pregunta'=>'Uno de los principios b??sicos de la Pol??tica Nacional de Promoci??n de la Salud es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Dignidad y trascendencia dela vida.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Derecho a la autonom??a, la intimidad y la privacidad de las personas.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Equidad territorial, social, de g??nero y ??tico cultural.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Honestidad y responsabilidad.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Ciudadan??a, democracia y liderazgos participativos.  ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>70,
                    'pregunta'=>'Uno de los valores b??sicos de la Pol??tica Nacional de Promoci??n de la Salud es: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Interculturalidad, pluralismo, respeto a las diversidades.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Derecho a la libertad de pensamiento, de expresi??n y a la participaci??n en el desarrollo.  ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Enfoque de calidad t??cnica y humana en los servicios.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Inversi??n social y en salud.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Sostenibilidad de las acciones que construyan pol??ticas de estado. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>71,
                    'pregunta'=>'LA LEY 1626/2000 DE LA FUNCI??N P??BLICA TIENE POR OBJETO REGULAR LA SITUACI??N JUR??DICA DE:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los funcionarios y de los empleados p??blicos, el personal de confianza, el contratado y el auxiliar que presten servicio en la Administraci??n Central. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los entes descentralizados, los gobiernos departamentales y municipales, la Defensor??a del Pueblo, la Contralor??a, la Banca P??blica y los dem??s Organismos y Entidades del Estado. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna es correcta. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>72,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, ES CONSIDERADO FUNCIONARIO P??BLICO AQUELLA PERSONA QUE:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es contratada por un Organismo del Estado. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Es nombrada mediante acto administrativo para ocupar de manera permanente un cargo previsto en el Presupuesto General de la Naci??n. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Es nombrada mediante acto administrativo para ocupar de manera permanente un cargo previsto en la Funci??n P??blica. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>73,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, LA PERSONA QUE EN VIRTUD DE UN CONTRATO Y POR TIEMPO DETERMINADO EJECUTA UNA OBRA O PRESTA UN SERVICIO AL ESTADO, SE DENOMINA:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Personal Contratado. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Personal del Servicio Auxiliar. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Funcionario P??blico. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna es correcta. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>74,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, EST??N INHABILITADOS PARA INGRESAR A LA FUNCI??N P??BLICA:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los condenados por sentencia firme o pena privativa de libertad, mientras dure la condena. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los condenados por la comisi??n de delitos electorales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Los ex funcionarios y empleados que hubiesen terminado su relaci??n jur??dica con el Estado por causa justificada no imputable al empleador, salvo que hayan transcurrido m??s de cinco a??os de destituci??n. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>75,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, EL NOMBRAMIENTO DE UN FUNCIONARIO TENDR?? CAR??CTER PROVISORIO (PLAZO DE PRUEBA) DURANTE UN PERIODO DE:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'6 meses. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'1 a??o. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'2 a??os. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ningunas son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>76,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, ??EN QUE OCASIONES SE DAR?? LA CONTRATACI??N TEMPORARIA EN LAS ENTIDADES P??BLICA?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Combatir brotes epid??micos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Realizar censos, encuestas o eventos electorales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Ejecutar servicios profesionales especializados. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>77,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, LA RELACI??N JUR??DICA ENTRE UN ORGANISMO O ENTIDAD DEL ESTADO Y SUS FUNCIONARIOS TERMINAR?? POR:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Renuncia. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Supresi??n o fusi??n del cargo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cesant??a por inhabilidad f??sica o mental debidamente comprobado. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>78,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, LA ESTABILIDAD EN LA FUNCI??N P??BLICA SE ADQUIERE A LOS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'A los 6 meses, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'A los 1 a??o, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'A los 2 a??os. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'A los 3 a??os. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>79,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, LOS FUNCIONARIOS P??BLICOS TENDR??N DERECHO A:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Vacaciones anuales remuneradas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Percibir el aguinaldo anual. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Ser promovido de conformidad a los procedimientos establecidos en esta ley. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>80,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, SON OBLIGACIONES DEL FUNCIONARIO P??BLICO:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Acatar las instrucciones de los superiores jer??rquicos relativas al trabajo que realiza cuando ellas no sean manifiestamente contrarias a las leyes y reglamentos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Permanecer en el cargo en caso de renuncia, por el plazo m??ximo de treinta d??as, si antes no fuese reemplazado. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Concurrir a la citaci??n por la instrucci??n de un sumario administrativo o prestar declaraci??n en calidad de testigo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores son correctas ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>81,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, QUE ACCIONES SER??N CONSIDERADAS FALTAS LEVES:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Abandono de cargo, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Ausencias injustificadas, ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Violaci??n del secreto profesional, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>82,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, SER??N APLICADAS A LAS FALTAS LEVES LAS SIGUIENTES SANCIONES DISCIPLINARIAS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Amonestaci??n verbal, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Apercibimiento por escrito, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Multa equivalente al importe de uno a cinco d??as de salario, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>83,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, SERAN FALTAS GRAVES LAS SIGUIENTES:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Incumplimiento de las obligaciones o transgresi??n de las prohibiciones establecidas en la Ley 1626/2000,  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Ausencia injustificada por m??s de tres d??as continuos o cinco alternos en el mismo trimestre, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Abandono de cargo, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas, ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>84,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, LAS SANCIONES DISCIPLINARIAS CORRESPONDIENTES A LAS FALTAS GRAVES SER??N APLICADAS POR:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La M??xima Autoridad del Organismo o Entidad del Estado en que el afectado preste sus servicios, sin sumario administrativo previo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La M??xima Autoridad del Organismo o Entidad del Estado en que el afectado preste sus servicios, previo sumario administrativo. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El jefe de la repartici??n publica donde el afectado presta servicios. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna es correcta. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>85,
                    'pregunta'=>'SEG??N LA LEY N?? 1626/2000, LA RESPONSABILIDAD ADMINISTRATIVA DEL FUNCIONARIO P??BLICO SE EXTINGUE:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Por muerte. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Por cumplimiento de la sanci??n. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Prescripci??n de la acci??n disciplinaria. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>86,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, LOS FUNCIONARIOS QUE TIENEN EXCEPTUADO EL CONTROL DE ASISTENCIA SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los Directores. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los Directores Generales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Los Coordinadores. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>87,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, LOS CERTIFICADOS M??DICOS QUE AVALEN EL REPOSO DE UN FUNCIONARIO DEBER?? SER VISADO POR LA DIRECCI??N DE CONTROL DE PROFESIONES Y ESTABLECIMIENTOS DE SALUD CUANDO:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'El plazo del reposo sea mayor a 2 (dos) d??as corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El plazo del reposo sea mayor a 3 (tres) d??as h??biles. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El plazo del reposo sea mayor a 3 (tres) d??as corridos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'El plazo del reposo sea mayor a 4 (cuatro) d??as corridos. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>88,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, LAS LLEGADAS TARD??AS REITERADAS REGISTRADAS EN EL LAPSO DE UN MES SER??N PASIBLES DE LAS SIGUIENTES SANCIONES:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Tres llegadas tard??as en el mes, multa equivalente a dos d??as de salario. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Cuatro llegadas tard??as en el mes, multa equivalente a dos d??as de salario. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cinco llegadas tard??as en el mes, multa equivalente a cuatro d??as de salario. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>89,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, LAS SALIDAS INJUSTIFICADAS DE LA INSTITUCI??N REGISTRADAS EN EL LAPSO DE UN MES SER??N PASIBLES DE LAS SIGUIENTES SANCIONES:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Dos salidas injustificadas de la Instituci??n en el mes, multa equivalente a un d??a de salario. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Cuatro salidas injustificadas de la Instituci??n en el mes, multa equivalente a un d??a de salario. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cinco salidas injustificadas de la Instituci??n en el mes, multa equivalente a tres d??as de salario. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Seis salidas injustificadas de la Instituci??n en el emes, multa equivalente a tres d??as de salario. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>90,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, EN LOS CASOS DE REITERACI??N O REINCIDENCIA DE LAS FALTAS LEVES, SER??N CONSIDERADAS COMO FALTAS GRAVES SEG??N LO DISPUESTO EN EL ART. 68?? DE LA LEY N?? 1626/2000 Y SER??N PASIBLES DE LAS SIGUIENTES MEDIDAS DISCIPLINARIAS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Suspensi??n del Derecho a promoci??n por el periodo de dos a??os. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Suspensi??n en el cargo sin goce de sueldo de hasta cuarenta d??as. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Destituci??n o despido, con inhabilitaci??n para ocupar cargos p??blicos por dos a cinco a??os. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Destituci??n o despido, con inhabilitaci??n para ocupar cargos p??blicos por dos a diez a??os. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>91,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, LOS D??AS DE PERMISOS CONTEMPLADOS EN EL REGLAMENTO INTERNO DEL MSPBS, EN LO REFERENTE A FALLECIMIENTO DEL C??NYUGE, HIJOS Y PADRES SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'5 (cinco) d??as corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'5 (cinco) d??as h??biles. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'10 (diez) d??as corridos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'10 (diez) d??as h??biles. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>92,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, LOS D??AS DE PERMISOS CONTEMPLADOS EN EL REGLAMENTO INTERNO DEL MSPBS, EN LO REFERENTE A FALLECIMIENTO DE ABUELOS O HERMANOS SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'3 (tres) d??as corridos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'3 (tres) d??as h??biles. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'5 (cinco) d??as corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'5 (cinco) d??as h??biles. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>93,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, LOS D??AS DE PERMISOS CONTEMPLADOS EN EL REGLAMENTO INTERNO DEL MSPBS, EN LO REFERENTE A MATRIMONIO DEL FUNCIONARIO SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'3 (tres) d??as corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'3 (tres) d??as h??biles. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'5 (cinco) d??as corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'5 (cinco) d??as h??biles. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>94,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, LOS D??AS DE PERMISOS CONTEMPLADOS EN EL REGLAMENTO INTERNO DEL MSPBS, EN LO REFERENTE A SALUD NO PODR?? EXCEDER EN EL A??O:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'80 (ochenta) d??as. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'90 (noventa) d??as. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'100 (cien) d??as. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'120 (ciento veinte) d??as. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>95,
                    'pregunta'=>'SEG??N REGLAMENTO INTERNO DEL MSPBS, LOS FUNCIONARIOS Y PERSONAL CONTRATADO TIENEN DERECHO AL GOCE DE UN PERIODO DE VACACIONES REMUNERADAS DESPUES DE CADA A??O DE TRABAJO CONTINUO, CUYA DURACI??N SER??:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Antig??edad de hasta cinco a??os = 12 (doce) d??as corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Antig??edad de hasta cinco a??os = 12 (doce) d??as h??biles corridos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Antig??edad con m??s de cinco a??os y hasta diez a??os= 18 (dieciocho) d??as corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Antig??edad con m??s de diez a??os= 18 (treinta) d??as h??biles corridos. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>96,
                    'pregunta'=>'EL C??DIGO DE ??TICA INSTITUCIONAL:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Resume los pensamientos de los que desarrollamos nuestras tareas en la Instituci??n en lo que respecta a compromisos personales asumidos, valores que destacamos y responsabilidad en nuestras acciones. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Son reglas que debemos cumplir estrictamente. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Se refiere a normas establecidas de car??cter punitivo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>97,
                    'pregunta'=>'QUE SON LOS PRINCIPIOS ??TICOS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Son reglas que debemos cumplir estrictamente. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Son normas del fuero interno y creencias b??sicas de las personas sobre las formas correctas como debemos relacionarnos con los otros y con el mundo. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Son formas de ser y de actuar de las personas que consideramos altamente deseables como atributos o cualidades nuestras y de los dem??s. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>98,
                    'pregunta'=>'LOS PRINCIPIOS ??TICOS SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los recursos p??blicos son para el desarrollo social y humano de la poblaci??n.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La esencia de la funci??n p??blica es servir a la ciudadan??a con excelencia. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El inter??s general prevalece sobre el inter??s particular. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>99,
                    'pregunta'=>'QUE SON LOS VALORES ??TICOS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Son normas del fuero interno y creencias b??sicas de las personas sobre las formas correctas como debemos relacionarnos con los otros y con el mundo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Son formas de ser y de actuar de las personas que consideramos altamente deseables como atributos o cualidades nuestras y de los dem??s. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Son orientaciones acerca de c??mo debe relacionarse la entidad y los funcionarios p??blicos con cada uno de sus grupos de inter??s para poner en pr??ctica los principios y valores ??ticos adoptados. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>100,
                    'pregunta'=>'LOS VALORES ??TICOS DEL C??DIGO DE ??TICA INSTITUCIONAL SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Servicio, Respeto, Imparcialidad y Equidad. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Honestidad, Responsabilidad y Compromiso. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Transparencia, Participaci??n, Coherencia y Solidaridad. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>101,
                    'pregunta'=>'LAS DIRECTRICES ??TICAS SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Son formas de ser y de actuar de las personas que consideramos altamente deseables como atributos o cualidades nuestras y de los dem??s. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Son orientaciones acerca de c??mo debe relacionarse la entidad y los funcionarios p??blicos con cada uno de sus grupos de inter??s, para poner en pr??ctica los valores y principios ??ticos adoptados. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Son normas del fuero interno y creencias b??sicas de las personas sobre las formas correctas como debemos relacionarnos con los otros y con el mundo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna es correcta. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>102,
                    'pregunta'=>'LAS DIRECTRICES ??TICAS CONTEMPLADAS EN EL C??DIGO DE ??TICA SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Con la ciudadan??a y con los Extranjeros. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Con los ??rganos de Control y con las Instituciones Privadas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Con las Organizaciones Sociales y con los Funcionarios P??blicos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>103,
                    'pregunta'=>'LA DIRECTRIZ CON LOS FUNCIONARIOS P??BLICOS SE REFIERE A:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Suministramos informaci??n oportuna y peri??dica a los gremios econ??micos sobre la pol??tica y desarrollo de la gesti??n del Ministerio. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Damos cumplimiento formal y real a la Ley de Contrataciones P??blicas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Reconocemos y aceptamos las distintas formas de pensar, sentir y actuar de los funcionarios dentro de las normativas vigentes de la funci??n p??blica. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Suministramos en tiempo, forma y de manera veraz los datos que nos fueren solicitados por los ??rganos de control. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>104,
                    'pregunta'=>'LA DIRECTRIZ CON LOS ??RGANOS DE CONTROL SE REFIERE A:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Reconocemos y aceptamos las distintas formas de pensar, sentir y actuar de los funcionarios dentro de las normativas vigentes de la funci??n p??blica. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Suministramos en tiempo, forma y de manera veraz los datos que nos fueren solicitados por los ??rganos de control para la auditor??a y verificaci??n de nuestras gestiones. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Establecemos alianzas con otras instituciones p??blicas para promover y coordinar programas y proyectos de inter??s com??n en beneficio de la ciudadan??a. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Cumplimos cabalmente loas compromisos adquiridos con los ??rganos internacionales ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>105,
                    'pregunta'=>'LA DIRECTRIZ CON LA CIUDADN??A SE REFIERE A:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Reconocemos la necesidad de un medio ambiente saludable de modo que orientamos las pol??ticas de salud dentro de marco de conservaci??n del ecosistema. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Establecemos alianzas con otras instituciones p??blicas para promover y coordinar programas y proyectos de inter??s com??n en beneficio de la ciudadan??a. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cumplimos cabalmente los compromisos adquiridos con los ??rganos internacionales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Prestamos servicios con excelencia, brindamos atenci??n humanizada a cada usuario seg??n sus necesidades, reconociendo y aceptando sus diferencias. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>106,
                    'pregunta'=>'EL MINISTERIO DE SALUD P??BLICA SE CREO:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'En el a??o 1936, bajo el gobierno del Coronel Rafael Franco, mediante la promulgaci??n del Decreto Ley N?? 2000/36 del 15 de junio de 1936. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'En el a??o 1936, bajo el gobierno del Coronel Rafael Franco, mediante la promulgaci??n del Decreto Ley N?? 2001/36 del 15 de junio de 1936. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'En el a??o 1992, bajo el gobierno del General Alfredo Stroessner, mediante la promulgaci??n del Decreto Ley N?? 21376/1992 del 15 de junio de 1992. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'En el a??o 1998, bajo el gobierno de Juan Carlos Wasmosy, mediante la promulgaci??n del Decreto Ley N?? 21376/98 del 15 de junio de 1998. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>107,
                    'pregunta'=>'DESDE LA CREACI??N DEL MINISTERIO DE SALUD P??BLICA HASTA EL 2013 FUERON NOMBRADOS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'25 (veinticinco) Ministros. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'28 (veintiocho) Ministros. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'30 (treinta) Ministros. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'31 (treinta y uno) Ministros. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>108,
                    'pregunta'=>'ANTES DE LA CREACI??N DEL MINISTERIO DE SALUD P??BLICA, LOS SERVICIOS DE SALUD ERAN PROVE??DOS Y ADMINISTRADOS POR:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'El Ministerio de Desarrollo Social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El Ministerio del Interior. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El Ministerio de Defensa. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'El Ministerio de la Mujer. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>109,
                    'pregunta'=>'EL MINISTERIO DE SALUD P??BLICA Y BIENESTAR SOCIAL TIENE COMO MISI??N:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rector??a, conducci??n, financiamiento y provisi??n de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de prevenci??n social, en el marco del Sistema de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de conducci??n y provisi??n de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de prevenci??n social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rector??a, conducci??n, financiamiento y provisi??n de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de protecci??n social, en el marco del Sistema de Salud. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Garantizar, oportuna y eficientemente, las prestaciones del Seguros Social, con calidad y calidez en el servicio, a nuestros asegurados. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>110,
                    'pregunta'=>'EL MINISTERIO DE SALUD P??BLICA Y BIENESTAR SOCIAL TIENE COMO MISI??N: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rector??a, conducci??n, financiamiento y provisi??n de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de prevenci??n social, en el marco del Sistema de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de conducci??n y provisi??n de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de prevenci??n social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rector??a, conducci??n, financiamiento y provisi??n de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de protecci??n social, en el marco del Sistema de Salud. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Garantizar, oportuna y eficientemente, las prestaciones del Seguros Social, con calidad y calidez en el servicio, a nuestros asegurados. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>111,
                    'pregunta'=>'EL MINISTERIO DE SALUD P??BLICA Y BIENESTAR SOCIAL TIENE COMO VISI??N: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rector??a, conducci??n, financiamiento y provisi??n de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de protecci??n social, en el marco del Sistema de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Ser un Ministerio eficaz, eficiente y transparente que garantiza el acceso efectivo de la poblaci??n, para la asistencia sanitaria integral y equitativa, promoviendo la participaci??n activa de la ciudadan??a en funci??n al ejercicio de sus derechos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Ser la instituci??n que administra el Seguro Social, con amplia cobertura, garantizando la sostenibilidad del sistema, en base al continuo perfeccionamiento de la gesti??n, contribuyendo al desarrollo del pa??s. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ser una Instituci??n promotora y garante de los Derechos Humanos de las mujeres, con mayor nivel de incidencia en los ??mbitos p??blico y privado, para la igualdad real y efectiva entre mujeres y hombres. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>112,
                    'pregunta'=>'El derecho a la vida es inherente a la persona humana, adem??s que se garantiza su protecci??n, en general, desde la concepci??n y que toda persona ser?? protegida por el Estado: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'En su integridad f??sica y ps??quica. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'En su integridad f??sica, ps??quica y en su salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'En su honor y en su reputaci??n.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'b) y c), son correctas. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'a) y c) son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>113,
                    'pregunta'=>'La Constituci??n de la Rep??blica del Paraguay, dispone que la calidad de vida ser?? promovida por el Estado mediante planes y pol??ticas que reconozcan factores condicionantes, tales como: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La pobreza, los impedimentos de la discapacidad y de la educaci??n.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La extrema pobreza y los factores sociales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La extrema pobreza, y los impedimentos de la edad o de la discapacidad. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Los factores de poblaci??n. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'b) y c) son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>114,
                    'pregunta'=>'Seg??n el Derecho a Peticionar a las Autoridades previsto en la Constituci??n de la Rep??blica del Paraguay, ??Qui??nes tienen derecho a peticionar a las autoridades, por escrito?: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Todo ciudadano paraguayo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Toda persona mayor de edad, residente en la Rep??blica. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Toda persona, individual o colectivamente y sin requisitos especiales. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todo ciudadano paraguayo o extranjero. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Las personas f??sicas y jur??dicas, sin requisitos especiales. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>115,
                    'pregunta'=>'La Constituci??n de la Rep??blica del Paraguay, establece que la familia, la sociedad y los poderes p??blicos promover??n el bienestar de toda persona en la tercera edad mediante servicios sociales que se ocupen de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Sus necesidades de alimentaci??n, salud, vivienda, cultura y ocio. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Sus necesidades de alimentaci??n, salud, vivienda y  cultura.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Sus necesidades fundamentales de alimentaci??n y salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Sus necesidades fundamentales de alimentaci??n, salud, y vivienda. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Sus necesidades esenciales y culturales. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>116,
                    'pregunta'=>'Seg??n los Derechos de las Personas Excepcionales. Se garantizar?? a las personas excepcionales la atenci??n de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'De su salud.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'De su educaci??n. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'De su recreaci??n y de su formaci??n profesional. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'a) y b). ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>117,
                    'pregunta'=>'La Constituci??n de la Republica del Paraguay en cuanto los Derechos de las Personas Excepcionales dispone que el Estado organizar?? una pol??tica de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Prevenci??n, tratamiento, y rehabilitaci??n de los discapacitados f??sicos, ps??quicos y sensoriales, a quienes prestar?? el cuidado especializado que requieran.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Prevenci??n, tratamiento, rehabilitaci??n e integraci??n de los discapacitados f??sicos, ps??quicos y sensoriales, a quienes prestar?? el cuidado especializado que requieran.  ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Prevenci??n, tratamiento, rehabilitaci??n e integraci??n de los discapacitados f??sicos y sensoriales, a quienes prestar?? el cuidado especializado que requieran. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Tratamiento, rehabilitaci??n e integraci??n de los discapacitados f??sicos, ps??quicos y sensoriales, a quienes prestar?? el cuidado especializado que requieran. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Rehabilitaci??n e integraci??n de los discapacitados f??sicos, a quienes prestar?? el cuidado especializado que requieran. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>118,
                    'pregunta'=>'La Constituci??n de la Rep??blica del Paraguay prev?? que se establecer??n planes especiales de salud reproductiva y salud materno infantil: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Para la poblaci??n en general. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Para la poblaci??n nacional. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Para las familias de escasos recurso. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Para la poblaci??n de escasos recurso. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Para la poblaci??n de todos los estratos sociales. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>119,
                    'pregunta'=>'Selecciona la opci??n que completa el enunciado. ???Se promover?? ?????????????????? que ejecute acciones sanitarias integradas, con pol??ticas que posibiliten la concertaci??n, la coordinaci??n y la complementaci??n de programas y recursos del sector p??blico y privado???. ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Una Pol??tica Nacional de Salud ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Un Sistema Nacional de Salud. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Un Programa de Bienestar Social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Un Sistema de Seguro Social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Un Plan Nacional de Salud. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>120,
                    'pregunta'=>'La ley establecer?? programas de bienestar social mediante estrategias basadas en: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La educaci??n sanitaria. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La participaci??n comunitaria.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La salud p??blica y la econom??a social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'a) y b) son correctas. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'La educaci??n y la salud. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>121,
                    'pregunta'=>'La Constituci??n de la Rep??blica del Paraguay  dispone que los funcionarios y los empleados p??blicos est??n al servicio: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'De la sociedad. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Del pueblo paraguayo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Del pa??s.  ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'De la comunidad. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'De los ciudadanos. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>122,
                    'pregunta'=>'El derecho de todas las personas a un nivel adecuado y digno de vida en lo respecta a la salud y al bienestar, no s??lo descansa en la atenci??n m??dica y sanitaria, sino que comprende: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Al derecho a un nivel adecuado de alimentos, vivienda, educaci??n y servicios sociales desde una perspectiva de g??nero. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Al derecho a un nivel adecuado de alimentos, agua, vivienda, educaci??n, trabajo y servicios sociales desde una perspectiva de g??nero. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Al derecho a un nivel adecuado de alimentos, agua, vivienda, educaci??n y de trabajo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Al derecho a un nivel adecuado de vida y la provisi??n de servicios sociales desde una perspectiva de g??nero. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Al derecho a un nivel adecuado de alimentos, agua, vivienda, educaci??n sanitaria, trabajo y servicios sociales desde una perspectiva de g??nero. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>123,
                    'pregunta'=>'Salud Publica, como instituci??n se constituye en el ente rector de la pol??tica sanitaria nacional y con tal tiene la responsabilidad de apoyar la conducci??n???: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'De los Establecimientos de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'De la Pol??tica Nacional de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Del Plan Nacional de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Del Sistema Nacional de Salud. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'De la Descentralizaci??n de los servicios. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>124,
                    'pregunta'=>'Constituyen ??mbitos de responsabilidad, control y ejecuci??n del Ministerio de Salud P??blica y Bienestar Social, las siguientes ??reas espec??ficas de acci??n: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Conducci??n de la Pol??tica de Salud y del Ambiente Humano. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Vigilancia de la Salud y del Ambiente. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Desarrollo de la investigaci??n cient??fica; regulaci??n y atenci??n sanitaria. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'a, b, y c son correctas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'a y b son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>125,
                    'pregunta'=>'Son funciones espec??ficas en el ??rea de Conducci??n de la Salud y del Ambiente Humano: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Formular y conducir la ejecuci??n de la Pol??tica de Salud y del Ambiente humano coordinada con otros Establecimientos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Dise??ar y conducir el sistema de informaci??n sanitaria. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Asesorar a los organismos nacionales, regionales y locales de salud, en la elaboraci??n y seguimiento de  planes, proyectos y programas de salud y del ambiente humano. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna de las anteriores. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'b y c son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>126,
                    'pregunta'=>'Definir normas generales y especificas de atenci??n en salud, y  establecer normas que regulen participaci??n de los subsectores publico y privados en el desarrollo de sistemas de abastecimiento de agua potable y de saneamiento ambiental, son algunas de las funciones espec??ficas en el ??rea de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Bienestar Social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Conducci??n de la Salud y del Ambiente Humano. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Promoci??n de la Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Regulaci??n y atenci??n sanitaria. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Ninguna de las anteriores. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>127,
                    'pregunta'=>'Fijar aranceles por servicios prestados, y ejercer la administraci??n general de la instituci??n como Ordenador de Gastos y responsable de los recursos humanos, f??sicos y financieros, son funciones espec??ficas de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'El Ministro de Salud P??blica y Bienestar Social. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La Direcci??n General de Administraci??n y Finanzas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La Direcci??n General de Recursos Humanos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'a y b son correctas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Superintendencia de Salud. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>128,
                    'pregunta'=>'Son funciones espec??ficas del Ministro de Salud P??blica y Bienestar Social representar al Ministerio de Salud P??blica y Bienestar Social ante: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los organismos nacionales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los organismos del exterior del ??rea de la salud.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Los pa??ses  y otras instituciones que el Poder Ejecutivo disponga. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores son correctas. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'a y b son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>129,
                    'pregunta'=>'Un de las funciones espec??ficas del Ministro de Salud P??blica y Bienestar Social, es promover la coordinaci??n y complementaci??n inter-institucional de los servicios p??blicos y privados de la salud???: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Que permita la extensi??n de la cobertura sanitaria.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El mejor control de las crisis sanitarias. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El mejor control de enfermedades end??micas y emergentes. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'a y b son correctas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'a y c son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>130,
                    'pregunta'=>'Desarrollar y fortalecer los organismos sectoriales establecidos en la Ley N?? 103296, que crea el Sistema Nacional de Salud como instrumento de participaci??n  social en las instancias???: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Nacionales e Internacionales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Locales e Internacionales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Nacionales, departamentales y locales. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Nacionales, p??blicas y privadas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Sectoriales e Extrasectoriales. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>131,
                    'pregunta'=>'La estructura Institucional existente se ajuara a los cambios generados por la: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Crisis sanitaria.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Reforma del Estado. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Necesidades emergentes del Sistema Sanitario. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Reforma sanitaria. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Pol??tica y el Plan Nacional de Salud. ',
                            'correcto'=>'false'
                        )
                ))
            )
        );
        //dd($request);

        $datos = Examene::create([
            'concurso_id' => 49,
            'hora_ini' => '10:00:00',
            'hora_fin' =>  '12:00:00',
            'puntaje' => 45,
            'cant_preg_examen' => 45,
            'confirmado' => true,
            'user_crea' => 'admin',
            'preguntas' => $preguntas
        ]);

        if($datos) {
            return response()->json([
                    'status' => 'success',
                    'data' => $datos]);
        }
        return response()->json([
                'status' => 'fail',
                'message' => 'failed to create content_arr record']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function finish(Request $request)
    {
        /*
        {
            "respuesta": [
                {
                    "pregunta_id": "1",
                    "respuesta_id": "4",
                    "correcto": "true",
                    "puntaje": "5"
                },
                {
                    "pregunta_id": "3",
                    "respuesta_id": "2",
                    "correcto": "false",
                    "puntaje": "0"
                }
            ]
        }
         */
        $examen = Examene::where('id', $request->examen_id)
                        ->firstOrFail();
        //dd(strtotime($examen->hora_fin) > strtotime(date('H:i:s')));
        if (strtotime($examen->hora_fin) > strtotime(date('H:i:s'))) {
            $postulacion = Postulacione::where('id', $request->postulacion_id)
                            ->firstOrFail();

            $preguntas = $examen->preguntas;
            $id_preg_examen = $request->pregunta_id;
            $arrResp = array();
            $totalPuntaje = 0;

            foreach ($preguntas['preguntas'] as $p) {

                if(in_array($p["pregunta_id"], $id_preg_examen)) {
                    $objstr = "resp_preg".$p["pregunta_id"];
                    foreach ($p['respuestas'] as $r) {
                        unset($correcto);
                        unset($puntaje);
                        if ($r['respuesta_id'] == $request->$objstr) {
                            $correcto = $r['correcto'];
                            if ($correcto == "true") {
                                $puntaje = $p['puntaje'];
                            } else {
                                $puntaje = 0;
                            }
                            break;
                        }
                    }

                    array_push($arrResp, array(
                        'pregunta_id' => $p["pregunta_id"],
                        'respuesta_id' => $request->$objstr,
                        'correcto' => $correcto,
                        'puntaje' => $puntaje
                    ));

                    $totalPuntaje = $totalPuntaje + $puntaje;
                }
            }

            $momentTime = date('Y-m-d H:i:s');
            $respuestas = array('respuesta' => $arrResp);
            //dd($respuestas);
            EvalExamene::create([
                'postulacion_id' => $request->postulacion_id,
                'examen_id' => $request->examen_id,
                'hora_examen' => $momentTime,
                'puntaje' => $totalPuntaje,
                'respuestas' => $respuestas
            ]);

            $postulacion->update(['examen' => $totalPuntaje, 'examen_user' => session('ci'), 'examen_fecha' => $momentTime]);
            $object = 'mensaje';
            $message = 'Examen Finalizado';
            //return view('frontend.examenes.denegado'); //compact('datos')
            return redirect()->route('examen')->with($object, $message);
        } else {
            $object = 'mensaje';
            $message = 'ERROR: Exedi?? el horario establecido';
            //return view('frontend.examenes.denegado'); //compact('datos')
            return redirect()->route('examen')->with($object, $message);
        }

        //dd($preguntas['preguntas']);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
