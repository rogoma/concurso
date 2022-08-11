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
                    'pregunta'=>'Los valores en los cuales descansa la Política Nacional de Salud son esenciales para establecer las prioridades nacionales y para evaluar si los pactos sociales responden o no a las necesidades y expectativas de la población, cuál de las opciones describe dichos valores.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Derecho a la Salud, Equidad, Solidaridad, Respeto por la Diversidad.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Derecho a la Vida, Salud y Educación.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Derecho a la diversidad y respeto al prójimo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Derechos para el Niño, la Mujer y el Adulto Mayor.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>2,
                    'pregunta'=>'Los marcos conceptuales que orientan la actual propuesta de la PNS ponen énfasis en:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Fuerte apuesta a la tercerización de Servicios de Salud y Asociación Publico Privada',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El fortalecimiento de "la estrategia de acceso universal a la salud y cobertura universal de salud".',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La implementación de un Programa Nacional de Vacunas adecuado.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Capacitación de los personales encargados de referencia y contra referencia en el sistema público.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>3,
                    'pregunta'=>'En el enfoque de determinantes sociales la mirada Ambiental, evalúa.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'El Hábitat específico de las comunidades donde importan la seguridad y la disponibilidad en el acceso a agua, la ocurrencia de fenómenos naturales riesgosos, la interrelación con otras especies, la contaminación de suelo y aire. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El ciclo biológico del ecosistema.',
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
                    'pregunta'=>'El enfoque de determinantes sociales señala al menos cuatro categorías que son.',
                    'puntaje'=>1,
                    'respuestas'=> array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Culturales, Religiosos, Epidemiológicos, acceso a aguas mejoradas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Epidemiológicos, Étnicos, hábitos saludables, sedentarios.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Ambientales, Socioeconómicos, Servicios de atención en salud, Biológicos.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Religioso, político, educativo, agrícolas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>5,
                    'pregunta'=>'De los siguientes enunciados señala cual es correcto:',
                    'puntaje'=>1,
                    'respuestas'=> array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La PNS de Paraguay, está en consonancia con las metas conocidas en el marco de los ODS de las Naciones Unidas.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los servicios de tercer y cuarto nivel son la puerta de acceso en la atención primaria de la salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Las Unidades de Salud Familiar funcionan las 24 hs todos los días. ',
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
                            'respuesta'=>'Consiste en la creación de filtros de acceso por genero a los servicios de salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Tiene que ver con el acceso y cobertura a servicios de urgencia las 24hs ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Depende del tipo de patologías que padece una población en una región. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Se refiere a la ausencia de diferencias injustas en el estado de salud, en el acceso a servicios de salud integrales, oportunos, y de calidad, en la contribución financiera, y en el acceso a entornos saludables.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>7,
                    'pregunta'=>'Son elementos en la formulación de la Política Nacional de salud:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Situación de la Salud.',
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
                            'respuesta'=>'Tasa de accidentes de tránsito y sus secuelas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Enfermedades crónicas ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Numero de camas de terapia intensiva polivalente. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>9,
                    'pregunta'=>'En lo relativo al acceso a saneamiento básico, pese a los importantes avances, persisten déficits en acceso a agua potable segura, disposición de excretas y de residuos sólidos. El grupo más afectado por esta carencia:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Las poblaciones más vulnerables situadas en los quintiles de ingresos más bajos. ',
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
                    'pregunta'=>'Persisten varios retos para optimizar la capacidad de respuesta de los servicios sanitarios ante la creciente demanda de atención. ¿Cuál de lo siguientes se encuentra dentro de la PNS de nuestro país?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Desarrollo e implementación de una Red Integrada e integral de Servicios de Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Reducir ostensiblemente los gastos de bolsillo y otros desembolsos directos de los usuarios.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Garantizar beneficios similares para todos los grupos de población.',
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
                            'respuesta'=>'La Promoción de la Salud es un proceso desarrollado por el Ministerio de Salud tendientes a lograr el uso adecuado de medicamentos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La Promoción de la Salud es una obligación exclusiva del ministerio de salud Pública.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La Promoción de la Salud es realizada por la gente y con la gente, en los diversos ámbitos de su vida cotidiana, en donde vive, trabaja, estudia o se recrea.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'La Promoción de la salud se debe realizar entre las personas enfermas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>12,
                    'pregunta'=>'El propósito de la Promoción de la Salud es: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Lograr cambios favorables en la situación de salud de las personas, la preservación del ambiente, y la calidad de vida de la población.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Creación e intercambio de conocimientos, información y experiencias en educación a nivel nacional e internacional.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Mejorar el acceso a la salud con caminos de todo tiempo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Dotar de tecnología, conectividad y transmisión de datos a los Hospitales.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>13,
                    'pregunta'=>'La salud de las personas y de las comunidades configura una situación dinámica, cambiante, cuyo equilibrio depende de múltiples factores entre los que se destacan por su importancia.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'El Estado y el gobierno, la sociedad en su conjunto, los grupos humanos, las familias y cada persona',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los programas de ayuda agropecuarios del MAG, para pequeños agricultores.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La cantidad de hospitales de tercer y cuarto nivel.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Medicina empírica y terapias alternativas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>14,
                    'pregunta'=>'De los siguientes son determinantes sociales de la Salud.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Falta de Políticas de protección a sectores vulnerables.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Uso de agro tóxicos en plantaciones.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Nivel educativo, Ingresos, ocupación, acceso a agua potable',
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
                            'respuesta'=>'Fortalecer el vínculo entre el equipo de salud de la familia y la comunidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Realizar acciones educativas, de promoción, de prevención, y vigilancia de acuerdo a la planificación del equipo de salud de la familia.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Tienen un área geográfica de acción definido o micro territorio que deben cubrir.',
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
                            'respuesta'=>'Barreras de tipo geográfico, económico, socio cultural, administrativo y de género.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El retraso en la atención oportuna impide un tratamiento adecuado en la mayoría de los casos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Las comunidades con limitaciones en el acceso al agua potable.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'El acceso universal significa que cualquiera puede ser atendido en cualquier nivel de atención sin importar la gravedad.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>17,
                    'pregunta'=>'¿Cuál es la definición correcta de Modelo de Atención en salud?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es el conjunto de Normas y procedimientos establecidos que define un esquema de servicios de salud estableciendo niveles de atención, orientados a entregar servicios en función de las expectativas y necesidades de la población.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Es la definición entre un modelo preventivo y uno curativo o paliativo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Es la distribución de la cartera de servicios ofertada por un sistema de salud teniendo en cuenta solo las ofertas  del sistema público.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Modelo que tiene en cuenta las estadísticas de los atendidos por región y su tipificación.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>18,
                    'pregunta'=>'¿Cómo se mide la calidad de atención en Salud?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Por el grado de satisfacción del usuario con la atención recibida, sin medir la resolutividad',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Grado en que los servicios de salud prestados a personas y poblaciones aumentan la probabilidad de lograr los resultados sanitarios deseados y en coherencia con los conocimientos científicos del momento.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Por el tiempo de espera en las consultas y en las urgencias.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Por la tecnología implementada en equipos de diagnostico.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>19,
                    'pregunta'=>'Con relación a Las Unidades de Salud Familiar todo lo siguiente es correcto excepto:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es la unidad efectora local, operacionalizada por un equipo de salud de la familiar responsable de territorios de 3000 a 5000 habitantes.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Opera como puerta de entrada al sistema de salud en el primer nivel de atención.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Su objetivo es prestar cartera de servicios definida para el primer nivel de atención lo mas cerca posible del lugar donde viven y trabajan las personas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Proveer asistencia médica intensiva de vanguardia al paciente crítico.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>20,
                    'pregunta'=>'El abordaje o la puerta de entrada de la Promoción de la salud puede darse:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'En ámbitos específicos de la vida cotidiana (hogares, escuelas, lugares de trabajo, de recreación y de intercambio social, servicios de salud, etc).',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Según necesidades del ciclo de vida, edades y funciones (salud infantil, adolescencia y juventud, salud sexual y reproductiva, maternidad segura, salud de la mujer, en la nutrición, el crecimiento y desarrollo psicológico, físico, mental, salud de las personas adultas mayores).',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Por temas específicos que hacen a los comportamientos, los estilos de vida y la calidad de ambientes físicos y sociales sanos, seguros y de su convivencia armónica.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las opciones son correctas.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>21,
                    'pregunta'=>'El código de buen gobierno define las políticas para la administración de la Institución, según dicho código una gestión pública se debe caracterizar por:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Un código de buen Gobierno rige solo para empresas privadas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Protocolos estrictos, trámites burocráticos, planillas de control dinámicas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Manejo político según criterio del gobernante de turno.',
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
                            'respuesta'=>'Pacto Social entre los grupos de interés donde se negocia el ejercicio del poder estableciendo la fortaleza del partido político mayoritario.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Equilibrio armónico que se establece en una mesa de trabajo intersectorial.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Administración de las políticas de gestión de gobierno de manera transversal.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>23,
                    'pregunta'=>'El Código de Buen Gobierno tiene por finalidad',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Establecer un nomenclador universal asignando a cada institución un código identificador para los problemas comunes.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Elaborar un diagnostico situacional y un plan de mejora institucional.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Buscar  eficiencia, transparencia e integridad para responder adecuadamente ante sus grupos de interés, asegurando un comportamiento ético institucional.',
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
                            'respuesta'=>'Conjunto de prácticas a través de las cuales los gobiernos democráticos se relacionan con la ciudadanía, aplicando los principios de conversación permanente, en el sentido doble: escuchar y responder.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Un Modelo Democrático con nula o muy baja inversión de su fuerza de seguridad pública con alto nivel de educación.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Un gobierno Abierto es transparente en el sentido de que rinde cuentas y pone a disposición de la ciudadanía la información pública.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'La opción a y c son correctas.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>25,
                    'pregunta'=>'En lo que respecta a Rendición de Cuentas:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es Deber de todo funcionario público que administre bienes públicos de responder e informar por la administración.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Deberá declarar el manejo, rendimiento de fondos, bienes y/o recursos públicos asignados y los respectivos resultados, en el cumplimiento del mandato que le ha sido conferido.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El funcionario deberá manejarse asegurando un comportamiento ético institucional.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas son correctas.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>26,
                    'pregunta'=>'El Código de Buen Gobierno vigente establece Políticas de buen gobierno para la Gestión de la Institución entre las que se destacan.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Políticas para la gestión Ética, para la gestión de talento humano, de información y comunicación, de responsabilidad social, de gestión de calidad.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Políticas asociadas a Partidos políticos, grupos sindicales, gremios de la educación.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La aplicación de Modelos de gestión de Calidad como los ISO 9001 ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'La planificación y monitoreo de proyectos ligados a la Salud hasta su implementación.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>27,
                    'pregunta'=>'El Comité de Ética y Buen Gobierno tendrá las siguientes funciones:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Asegurarse de la difusión de los Códigos de Ética y Buen Gobierno y de una adecuada capacitación en los mismos a los funcionarios y grupos de interés de la institución.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Monitorear el desarrollo y cumplimiento de las políticas contenidas en los Códigos de Ética y de Buen Gobierno.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Vigilar el comportamiento de los que conforman la Institución para asegurar el cumplimiento de los Códigos de Ética y Buen Gobierno.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Resolver controversias sobre interpretación de los artículos del Código de Ética y Buen Gobierno.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas las anteriores son funciones del comité de Ética.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>28,
                    'pregunta'=>'Según el código de buen Gobierno, al hablar de Riesgos nos referimos: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Posibilidad de ocurrencia de eventos tanto internos como externos que puedan afectar o impedir el logro de los objetivos de la institución, entorpeciendo el desarrollo normal de sus funciones.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Evento aleatorio e impredecible que se presenta sorpresivamente atentando contra el buen desarrollo institucional de los objetivos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Para una gestión de gobierno las tomas de decisiones acertadas se encuentran libres de riesgos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna es correcta.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>29,
                    'pregunta'=>'Podemos decir de la Transparencia en gestión de Gobierno.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es Ético el uso Campañas publicitarias donde se trata de mostrar el trabajo realizado y se destacan solo lo positivo de una gestión.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Forma de hacer visible la función pública, ejecutándola de acuerdo con las normas constitucionales y legales aceptando y facilitando que la gestión sea observada en forma directa por los grupos de interés.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La transparencia implica el deber de rendir cuentas de la gestión encomendada.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Las opciones b y c son correctas.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>30,
                    'pregunta'=>'¿Cuál es las siguientes definiciones es la correcta?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Grupos de Interés: Partes interesadas, persona, grupos o instituciones sobre las cuales el ente público tiene influencia o son influenciadas por ella.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Administrar: Hacer diligencias o acciones que garanticen la administración eficiente de un negocio o proyecto, y que conduzcan al logro de los objetivos propuestos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Gestionar: Gobernar, ejercer la autoridad o mando sobre un territorio y sobre personas que lo habitan, dirigir una institución. Ordenar, disponer, organizar, en especial la hacienda y los bienes.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>31,
                    'pregunta'=>'El objetivo general de la Política Nacional de Recursos Humanos en Salud del Paraguay 2020-2030:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Se centra en la definición de estrategias y líneas de acción que permitan fortalecer la rectoría y gobernanza de la gestión de los RHS',
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
                            'respuesta'=>'Incentivar el desarrollo y la incorporación del personal de salud.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>32,
                    'pregunta'=>'La Estrategia 1 de la Política Nacional del Recursos Humanos en Salud del Paraguay 2020-2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Planificación Integral de los Recursos Humanos en Salud',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de la Rectoría y Gestión de la Autoridad Sanitaria en relación a los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementación del Sistema Nacional de Información Estratégica de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementación de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>33,
                    'pregunta'=>'La Estrategia 2 de la Política Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Planificación Integral de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de la Rectoría y Gestión de la Autoridad Sanitaria en relación a los Recursos Humanos en Salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementación del Sistema Nacional de Información Estratégica de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementación de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>34,
                    'pregunta'=>'La Estrategia 3 de la Política Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Planificación Integral de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de la Rectoría y Gestión de la Autoridad Sanitaria en relación a los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementación del Sistema Nacional de Información Estratégica de los Recursos Humanos en Salud',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementación de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>35,
                    'pregunta'=>'La Estrategia 4 de la Política Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Planificación Integral de los Recursos Humanos en Salud',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de la Rectoría y Gestión de la Autoridad Sanitaria en relación a los Recursos Humanos en Salud',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementación del Sistema Nacional de Información Estratégica de los Recursos Humanos en Salud',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementación de la Carrera Sanitaria.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>36,
                    'pregunta'=>'La Estrategia 5 de la Política Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Gestión y Desarrollo Equitativo de los Recursos Humanos en Salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Formación y Capacitación de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementación del Sistema Nacional de Información Estratégica de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementación de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>37,
                    'pregunta'=>'La Estrategia 6 de la Política Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030 se refiere a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Gestión y Desarrollo Equitativo de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Formación y Capacitación de los Recursos Humanos en Salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Implementación del Sistema Nacional de Información Estratégica de los Recursos Humanos en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Implementación de la Carrera Sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>38,
                    'pregunta'=>'La línea de acción que propone la estrategia 6 de la Política Nacional del Recursos Humanos en Salud del Paraguay 2020 -2030, es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Elaborar el Plan Nacional de Capacitación de los RHS',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Planificar las necesidades de los distintos prestadores de servicios de salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Establecer el marco legal que regirá la carrera sanitaria.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Definir políticas de gestión de los RHS.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>39,
                    'pregunta'=>'Los Valores orientadores de la Política Nacional de Recursos Humanos en Salud del Paraguay 2020-2030, son:',
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
                            'respuesta'=>'Compromiso y Ética.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>40,
                    'pregunta'=>'El Marco de referencia para la construcción de la Política Nacional de Recursos Humanos en Salud del Paraguay 2020-2030, está constituido por:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Estrategia de Recursos Humanos para el Acceso Universal a La Salud y Cobertura Universal de Salud de la Organización Panamericana de la Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El Plan Nacional De Desarrollo. Construyendo El Paraguay del 2030.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La Política Nacional de Salud del Paraguay - PNS 2015-2030.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>41,
                    'pregunta'=>'Con respecto a la Política Nacional de Salud 2015/2030, marcar la respuesta correcta:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es un conjunto de enunciados que orientan el rumbo de las determinaciones y acciones que el Estado Paraguayo está decidido a realizar para garantiza r el ejercicio pleno del derecho a la salud de todos los paraguayos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fue construida en un proceso participativo de reflexión y análisis entre actores diversos con enfoque intersectorial e interinstitucional con el fin de establecer consensos políticos y técnicos con amplia participación de la ciudadanía',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Esta política se emana del compromiso asumido por el Estado ante el Consejo Directivo de la OPS con el fin de la elaboración de una Estrategia para el acceso universal y la cobertura en salud.',
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
                    'pregunta'=>'El enfoque de determinantes sociales señala las siguientes categorías, excepto:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Ambientales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Socio económicas y culturales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Servicios de atención en salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Biológicos.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Bioseguridad sanitaria.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>43,
                    'pregunta'=>'El acceso universal a la salud se entiende como la ausencia de barreras del tipo geográfico, económico, sociocultural, de organización o de género y se logra a través de la eliminación inmediata de las barreras que impiden que algunas personas utilicen servicios no esenciales de salud, determinados a nivel Internacional y de manera no equitativa',
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
                    'pregunta'=>'La cobertura de salud es definida como la capacidad del sistema de salud para responder a las necesidades de la población, lo cual incluye la disponibilidad de infraestructura, recursos humanos, tecnologías de la salud, y financiamiento.',
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
                    'pregunta'=>'Sobre la Política Nacional de Salud 2015 - 2030 la opción correcta es:',
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
                    'pregunta'=>'La Política Nacional de Salud se rige a través de los siguientes principios excepto:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Universalidad.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Calidad y humanización de los servicios.',
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
                    'pregunta'=>'La Política Nacional de Salud se rige a través de los siguientes valores excepto:',
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
                            'respuesta'=>'Organización',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>48,
                    'pregunta'=>'Sobre la Política Nacional de Salud 2015-2030:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Tiene 7 Estrategias de línea de acción.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Tiene 5 Estrategias de línea de acción.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Tiene 6 Estrategias de línea de acción.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Tiene 4 Estrategias de línea de acción.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Tiene 3 Estrategias de línea de acción.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>49,
                    'pregunta'=>'Sobre la Política Nacional de Salud 2015-2030, es correcto todo excepto:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los Objetivos del PNS se transforman en estrategias, y luego estos son bajados a través de una línea de acción.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Avanzar hacia el acceso universal a la salud y lograr la cobertura universal de salud al mayor nivel posible.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Entre las estrategias de acción se encuentra fortalecer la Rectoría y Gobernanza.',
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
                    'pregunta'=>'Sobre las 7 estrategias de la Política Nacional de Salud 2015 - 2030:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La estrategia Nº 3 refiere al Fortalecimiento de las acciones intersectoriales e  interinstitucionales para el abordaje de los determinantes sociales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La Estrategia Nº 5 refiere al Fortalecimiento de la gestión del talento humano en Salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La estrategia Nº 6 refiere Desarrollo y Fortalecimiento de la Salud Pública en las Fronteras.',
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
                    'pregunta'=>'El concepto correcto del Código de Buen Gobierno es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Disposiciones voluntarias de autorregulación de quienes ejercen el gobierno de las Instituciones, que a manera de compromiso ético buscan garantizar una gestión eficiente, íntegra y transparente en la administración pública.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Documento de referencia para gestionar la ética en el día a día de la Institución, y está conformado por los Principios y Valores.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Conjunto de condiciones que hacen factible a un gobernante o director el ejercicio real del poder que formalmente se le ha entregado para el cumplimiento de los objetivos y fines bajo la responsabilidad de su cargo.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Manera en que las Instituciones son dirigidas, mejorando su funcionamiento interna y externamente, buscando eficiencia, transparencia e integridad, para responder adecuadamente ante sus grupos de interés, asegurando un comportamiento ético institucional.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Ninguna de las anteriores.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>52,
                    'pregunta'=>'Los Valores éticos que inspiran y soportan la gestión del Ministerio de Salud Pública y Bienestar Social son los siguientes, EXCEPTO:',
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
                            'respuesta'=>'Participación.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Calidad.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>53,
                    'pregunta'=>'Se consideran Directivos con responsabilidad especial en la aplicación del presente Código de Buen Gobierno, el Ministro de Salud Pública y Bienestar Social:',
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
                    'pregunta'=>'En el marco del cumplimiento con el Art. 7 “Compromiso con los Fines del Estado” del Código de Buen Gobierno, el MSPBS se compromete a:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Establecer las políticas necesarias para cumplir los fines misionales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Asegurar que las políticas trazadas se cumplan.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cumplir las disposiciones constitucionales y legales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ejecutar eficientemente su plan estratégico.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>55,
                    'pregunta'=>'En cuanto al Art. 18 “Compromiso con la Comunicación Pública”, El Misterio de Salud Pública y Bienestar Social se compromete a asumir la comunicación y la información comobienes públicos, a conferirles un carácter estratégico y orientarlas hacia el fortalecimiento de la identidad institucional y a la expansión de la capacidad productiva de los miembros de la Institución, para lo cual las acciones comunicativas se efectuarán de acuerdo con las políticas de comunicación que se establezcan:',
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
                    'pregunta'=>'En cuanto al Art. 32: “Deberes del Equipo Humano Relacionados con los Conflictos de Intereses” el/los deber/es de los funcionarios públicos del Ministerio de Salud Pública y Bienestar Social, es/son:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Revelar a tiempo y por escrito a los entes competentes cualquier posible conflicto de interés que crea tener.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Contribuir a que se permita la adecuada realización de las funciones encomendadas a los órganos de control interno y externo de la Institución.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Guardar y proteger la información que la normatividad legal haya definido como de carácter reservado.',
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
                    'pregunta'=>'En el Ministerio de Salud Pública y Bienestar Social se instaurará un Comité de Buen Gobierno, compuesto por ciudadanos que ostenten la calidad de externos y/o independientes de la Institución. Sus miembros se seleccionarán de los siguientes colectivos: (1) miembro de la comunidad empresarial, nominado por las asociaciones empresariales; un (1) miembro de la sociedad civil, nominado por grupos comunitarios organizados u organizaciones no gubernamentales del sector salud; un (1) miembro de la comunidad académica, nominado por rectores de universidades; un (1) miembro nominado por el Consejo Nacional de Salud; y un (1) miembro delegado por el Ministro en representación del Ministerio de Salud:',
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
                    'pregunta'=>'La definición correcta de Valores Éticos es: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Formas de ser y de actuar de las personas que son altamente deseables como atributos o cualidades propias y de los demás, por cuanto posibilitan la construcción de una convivencia gratificante en el marco de la dignidad humana.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Forma de hacer visible la función pública, ejecutándola de acuerdo con las normas constitucionales y legales.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Manera en que las Instituciones son dirigidas, mejorando su funcionamiento interna y externamente, buscando eficiencia.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Instancia encargada de promover y liderar el proceso de implantación de la gestión ética para Instituciones del Estado.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas las anteriores.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>59,
                    'pregunta'=>'El Ministerio de Salud Pública y Bienestar Social fue creado por el Decreto Ley Nº 2000 del 15 de junio de 1936, con el marco legal preceptuado en la Constitución Nacional, la Ley Nº 836/80 “Código Sanitario”, el Decreto N° 21.376/1998 “Por el cual se establece la nueva organización funcional del Ministerio de Salud Pública y Bienestar Social”, y las demás disposiciones legales y reglamentarias vigentes que regulan el funcionamiento de la administración pública del Paraguay.',
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
                    'pregunta'=>'En cuanto al Segundo Capitulo: “Política de Gestión del Recurso Humano”, el art. 17 reza: “El Ministerio de Salud Pública y Bienestar Social se compromete con el desarrollo de las competencias, habilidades, aptitudes e idoneidad de sus funcionarios públicos, determinando políticas y prácticas de gestión humana que deben incorporar los derechos y deberes constitucionales para garantizar la justicia, equidad, imparcialidad y transparencia al realizar los procesos de selección, inducción, formación, capacitación, promoción y evaluación del desempeño. En este sentido, la Institución propenderá por la vinculación de los más capaces e idóneos a la Administración, bien sea como funcionarios públicos o como contratados.',
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
                    'pregunta'=>'Algunos de los ejes de la Política Nacional de Promoción de la Salud son:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Fortalecimiento de entornos claves; Participación y empoderamiento de la comunidad; Fortalecimiento de la rectoría y gobernanza.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Fortalecimiento de entornos claves; Participación y empoderamiento de la comunidad; Integración efectiva de la promoción de la salud en los servicios de salud.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Coordinación intersectorial para abordar los determinantes sociales de la salud; Fortalecimiento de la gestión del talento humano.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Fortalecimiento de la Atención Primaria de la Salud; Fortalecimiento de la Salud Pública en las fronteras.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Ninguna es correcta.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>62,
                    'pregunta'=>'Concepto de Promoción de la Salud.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es una estrategia para promocionar la salud y prevenir enfermedades.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Es el estado de total bienestar físico, social y mental.',
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
                            'respuesta'=>'Es la intervención que se realiza para reducir o eliminar la consecuencias de las enfermedades.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>63,
                    'pregunta'=>'Dimensiones para el desarrollo de competencias en promoción de la salud.',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Económico y tecnológico.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Social, cultural, ambiental, económico y político.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Ambiental, cultural y religioso.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Científico, cultural y tecnológico.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Todas son correctas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>64,
                    'pregunta'=>'Las estrategias utilizadas para la participación y empoderamiento de la comunidad y compromiso de la sociedad civil es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Desarrollo de espacios y mecanismos de deliberación.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Educación permanente.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Construcción de alianzas y rendición de cuentas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Desarrollo de diálogo por la salud.',
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
                            'respuesta'=>'Indicadores crecientes de utilización de los servicios de salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Alianzas a nivel nacional, regional y local sostenibles sobre entornos saludables.',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Incorporación de la promoción de la salud en leyes y políticas públicas.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'a y d son correctas.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>66,
                    'pregunta'=>'Uno de los resultados de la integración efectiva de la promoción de la salud en los servicios de salud:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Indicadores de promoción de la salud incorporados en la base de datos de la información estratégica en salud.',
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
                            'respuesta'=>'Incorporación de promoción de la salud en los sectores gubernamentales y no gubernamentales.',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>67,
                    'pregunta'=>'El abordaje o puerta de entrada de la promoción de la salud puede darse en:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Según necesidades del ciclo de vida.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Por temas específicos que hacen a los comportamientos, las oportunidades y condiciones de vida.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'En ámbitos específicos de la vida cotidiana.  ',
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
                    'pregunta'=>'El propósito de la Política Nacional de Promoción de la Salud es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Empoderar a la ciudadanía sobre el cuidado de su salud.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Indicar el camino a seguir para prevenir enfermedades.',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Renovar la promoción de la salud por medio de acciones sociales, políticas y técnicas, que aborden los determinantes sociales de la salud.',
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
                    'pregunta'=>'Uno de los principios básicos de la Política Nacional de Promoción de la Salud es:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Dignidad y trascendencia dela vida.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Derecho a la autonomía, la intimidad y la privacidad de las personas.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Equidad territorial, social, de género y ético cultural.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Honestidad y responsabilidad.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Ciudadanía, democracia y liderazgos participativos.  ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>70,
                    'pregunta'=>'Uno de los valores básicos de la Política Nacional de Promoción de la Salud es: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Interculturalidad, pluralismo, respeto a las diversidades.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Derecho a la libertad de pensamiento, de expresión y a la participación en el desarrollo.  ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Enfoque de calidad técnica y humana en los servicios.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Inversión social y en salud.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Sostenibilidad de las acciones que construyan políticas de estado. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>71,
                    'pregunta'=>'LA LEY 1626/2000 DE LA FUNCIÓN PÚBLICA TIENE POR OBJETO REGULAR LA SITUACIÓN JURÍDICA DE:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los funcionarios y de los empleados públicos, el personal de confianza, el contratado y el auxiliar que presten servicio en la Administración Central. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los entes descentralizados, los gobiernos departamentales y municipales, la Defensoría del Pueblo, la Contraloría, la Banca Pública y los demás Organismos y Entidades del Estado. ',
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
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, ES CONSIDERADO FUNCIONARIO PÚBLICO AQUELLA PERSONA QUE:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Es contratada por un Organismo del Estado. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Es nombrada mediante acto administrativo para ocupar de manera permanente un cargo previsto en el Presupuesto General de la Nación. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Es nombrada mediante acto administrativo para ocupar de manera permanente un cargo previsto en la Función Pública. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>73,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, LA PERSONA QUE EN VIRTUD DE UN CONTRATO Y POR TIEMPO DETERMINADO EJECUTA UNA OBRA O PRESTA UN SERVICIO AL ESTADO, SE DENOMINA:',
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
                            'respuesta'=>'Funcionario Público. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna es correcta. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>74,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, ESTÁN INHABILITADOS PARA INGRESAR A LA FUNCIÓN PÚBLICA:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los condenados por sentencia firme o pena privativa de libertad, mientras dure la condena. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los condenados por la comisión de delitos electorales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Los ex funcionarios y empleados que hubiesen terminado su relación jurídica con el Estado por causa justificada no imputable al empleador, salvo que hayan transcurrido más de cinco años de destitución. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>75,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, EL NOMBRAMIENTO DE UN FUNCIONARIO TENDRÁ CARÁCTER PROVISORIO (PLAZO DE PRUEBA) DURANTE UN PERIODO DE:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'6 meses. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'1 año. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'2 años. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ningunas son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>76,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, ¿EN QUE OCASIONES SE DARÁ LA CONTRATACIÓN TEMPORARIA EN LAS ENTIDADES PÚBLICA?',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Combatir brotes epidémicos. ',
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
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, LA RELACIÓN JURÍDICA ENTRE UN ORGANISMO O ENTIDAD DEL ESTADO Y SUS FUNCIONARIOS TERMINARÁ POR:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Renuncia. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Supresión o fusión del cargo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cesantía por inhabilidad física o mental debidamente comprobado. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>78,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, LA ESTABILIDAD EN LA FUNCIÓN PÚBLICA SE ADQUIERE A LOS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'A los 6 meses, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'A los 1 año, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'A los 2 años. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'A los 3 años. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>79,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, LOS FUNCIONARIOS PÚBLICOS TENDRÁN DERECHO A:',
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
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, SON OBLIGACIONES DEL FUNCIONARIO PÚBLICO:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Acatar las instrucciones de los superiores jerárquicos relativas al trabajo que realiza cuando ellas no sean manifiestamente contrarias a las leyes y reglamentos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Permanecer en el cargo en caso de renuncia, por el plazo máximo de treinta días, si antes no fuese reemplazado. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Concurrir a la citación por la instrucción de un sumario administrativo o prestar declaración en calidad de testigo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores son correctas ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>81,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, QUE ACCIONES SERÁN CONSIDERADAS FALTAS LEVES:',
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
                            'respuesta'=>'Violación del secreto profesional, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>82,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, SERÁN APLICADAS A LAS FALTAS LEVES LAS SIGUIENTES SANCIONES DISCIPLINARIAS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Amonestación verbal, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Apercibimiento por escrito, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Multa equivalente al importe de uno a cinco días de salario, ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>83,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, SERAN FALTAS GRAVES LAS SIGUIENTES:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Incumplimiento de las obligaciones o transgresión de las prohibiciones establecidas en la Ley 1626/2000,  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Ausencia injustificada por más de tres días continuos o cinco alternos en el mismo trimestre, ',
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
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, LAS SANCIONES DISCIPLINARIAS CORRESPONDIENTES A LAS FALTAS GRAVES SERÁN APLICADAS POR:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La Máxima Autoridad del Organismo o Entidad del Estado en que el afectado preste sus servicios, sin sumario administrativo previo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La Máxima Autoridad del Organismo o Entidad del Estado en que el afectado preste sus servicios, previo sumario administrativo. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El jefe de la repartición publica donde el afectado presta servicios. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna es correcta. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>85,
                    'pregunta'=>'SEGÚN LA LEY N° 1626/2000, LA RESPONSABILIDAD ADMINISTRATIVA DEL FUNCIONARIO PÚBLICO SE EXTINGUE:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Por muerte. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Por cumplimiento de la sanción. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Prescripción de la acción disciplinaria. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las anteriores son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>86,
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, LOS FUNCIONARIOS QUE TIENEN EXCEPTUADO EL CONTROL DE ASISTENCIA SON:',
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
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, LOS CERTIFICADOS MÉDICOS QUE AVALEN EL REPOSO DE UN FUNCIONARIO DEBERÁ SER VISADO POR LA DIRECCIÓN DE CONTROL DE PROFESIONES Y ESTABLECIMIENTOS DE SALUD CUANDO:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'El plazo del reposo sea mayor a 2 (dos) días corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El plazo del reposo sea mayor a 3 (tres) días hábiles. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El plazo del reposo sea mayor a 3 (tres) días corridos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'El plazo del reposo sea mayor a 4 (cuatro) días corridos. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>88,
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, LAS LLEGADAS TARDÍAS REITERADAS REGISTRADAS EN EL LAPSO DE UN MES SERÁN PASIBLES DE LAS SIGUIENTES SANCIONES:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Tres llegadas tardías en el mes, multa equivalente a dos días de salario. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Cuatro llegadas tardías en el mes, multa equivalente a dos días de salario. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cinco llegadas tardías en el mes, multa equivalente a cuatro días de salario. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>89,
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, LAS SALIDAS INJUSTIFICADAS DE LA INSTITUCIÓN REGISTRADAS EN EL LAPSO DE UN MES SERÁN PASIBLES DE LAS SIGUIENTES SANCIONES:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Dos salidas injustificadas de la Institución en el mes, multa equivalente a un día de salario. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Cuatro salidas injustificadas de la Institución en el mes, multa equivalente a un día de salario. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cinco salidas injustificadas de la Institución en el mes, multa equivalente a tres días de salario. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Seis salidas injustificadas de la Institución en el emes, multa equivalente a tres días de salario. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>90,
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, EN LOS CASOS DE REITERACIÓN O REINCIDENCIA DE LAS FALTAS LEVES, SERÁN CONSIDERADAS COMO FALTAS GRAVES SEGÚN LO DISPUESTO EN EL ART. 68° DE LA LEY N° 1626/2000 Y SERÁN PASIBLES DE LAS SIGUIENTES MEDIDAS DISCIPLINARIAS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Suspensión del Derecho a promoción por el periodo de dos años. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Suspensión en el cargo sin goce de sueldo de hasta cuarenta días. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Destitución o despido, con inhabilitación para ocupar cargos públicos por dos a cinco años. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Destitución o despido, con inhabilitación para ocupar cargos públicos por dos a diez años. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>91,
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, LOS DÍAS DE PERMISOS CONTEMPLADOS EN EL REGLAMENTO INTERNO DEL MSPBS, EN LO REFERENTE A FALLECIMIENTO DEL CÓNYUGE, HIJOS Y PADRES SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'5 (cinco) días corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'5 (cinco) días hábiles. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'10 (diez) días corridos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'10 (diez) días hábiles. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>92,
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, LOS DÍAS DE PERMISOS CONTEMPLADOS EN EL REGLAMENTO INTERNO DEL MSPBS, EN LO REFERENTE A FALLECIMIENTO DE ABUELOS O HERMANOS SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'3 (tres) días corridos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'3 (tres) días hábiles. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'5 (cinco) días corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'5 (cinco) días hábiles. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>93,
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, LOS DÍAS DE PERMISOS CONTEMPLADOS EN EL REGLAMENTO INTERNO DEL MSPBS, EN LO REFERENTE A MATRIMONIO DEL FUNCIONARIO SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'3 (tres) días corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'3 (tres) días hábiles. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'5 (cinco) días corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'5 (cinco) días hábiles. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>94,
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, LOS DÍAS DE PERMISOS CONTEMPLADOS EN EL REGLAMENTO INTERNO DEL MSPBS, EN LO REFERENTE A SALUD NO PODRÁ EXCEDER EN EL AÑO:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'80 (ochenta) días. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'90 (noventa) días. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'100 (cien) días. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'120 (ciento veinte) días. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>95,
                    'pregunta'=>'SEGÚN REGLAMENTO INTERNO DEL MSPBS, LOS FUNCIONARIOS Y PERSONAL CONTRATADO TIENEN DERECHO AL GOCE DE UN PERIODO DE VACACIONES REMUNERADAS DESPUES DE CADA AÑO DE TRABAJO CONTINUO, CUYA DURACIÓN SERÁ:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Antigüedad de hasta cinco años = 12 (doce) días corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Antigüedad de hasta cinco años = 12 (doce) días hábiles corridos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Antigüedad con más de cinco años y hasta diez años= 18 (dieciocho) días corridos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Antigüedad con más de diez años= 18 (treinta) días hábiles corridos. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>96,
                    'pregunta'=>'EL CÓDIGO DE ÉTICA INSTITUCIONAL:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Resume los pensamientos de los que desarrollamos nuestras tareas en la Institución en lo que respecta a compromisos personales asumidos, valores que destacamos y responsabilidad en nuestras acciones. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Son reglas que debemos cumplir estrictamente. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Se refiere a normas establecidas de carácter punitivo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>97,
                    'pregunta'=>'QUE SON LOS PRINCIPIOS ÉTICOS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Son reglas que debemos cumplir estrictamente. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Son normas del fuero interno y creencias básicas de las personas sobre las formas correctas como debemos relacionarnos con los otros y con el mundo. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Son formas de ser y de actuar de las personas que consideramos altamente deseables como atributos o cualidades nuestras y de los demás. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>98,
                    'pregunta'=>'LOS PRINCIPIOS ÉTICOS SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los recursos públicos son para el desarrollo social y humano de la población.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La esencia de la función pública es servir a la ciudadanía con excelencia. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El interés general prevalece sobre el interés particular. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>99,
                    'pregunta'=>'QUE SON LOS VALORES ÉTICOS:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Son normas del fuero interno y creencias básicas de las personas sobre las formas correctas como debemos relacionarnos con los otros y con el mundo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Son formas de ser y de actuar de las personas que consideramos altamente deseables como atributos o cualidades nuestras y de los demás. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Son orientaciones acerca de cómo debe relacionarse la entidad y los funcionarios públicos con cada uno de sus grupos de interés para poner en práctica los principios y valores éticos adoptados. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>100,
                    'pregunta'=>'LOS VALORES ÉTICOS DEL CÓDIGO DE ÉTICA INSTITUCIONAL SON:',
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
                            'respuesta'=>'Transparencia, Participación, Coherencia y Solidaridad. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>101,
                    'pregunta'=>'LAS DIRECTRICES ÉTICAS SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Son formas de ser y de actuar de las personas que consideramos altamente deseables como atributos o cualidades nuestras y de los demás. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Son orientaciones acerca de cómo debe relacionarse la entidad y los funcionarios públicos con cada uno de sus grupos de interés, para poner en práctica los valores y principios éticos adoptados. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Son normas del fuero interno y creencias básicas de las personas sobre las formas correctas como debemos relacionarnos con los otros y con el mundo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ninguna es correcta. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>102,
                    'pregunta'=>'LAS DIRECTRICES ÉTICAS CONTEMPLADAS EN EL CÓDIGO DE ÉTICA SON:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Con la ciudadanía y con los Extranjeros. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Con los Órganos de Control y con las Instituciones Privadas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Con las Organizaciones Sociales y con los Funcionarios Públicos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Todas las afirmaciones son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>103,
                    'pregunta'=>'LA DIRECTRIZ CON LOS FUNCIONARIOS PÚBLICOS SE REFIERE A:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Suministramos información oportuna y periódica a los gremios económicos sobre la política y desarrollo de la gestión del Ministerio. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Damos cumplimiento formal y real a la Ley de Contrataciones Públicas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Reconocemos y aceptamos las distintas formas de pensar, sentir y actuar de los funcionarios dentro de las normativas vigentes de la función pública. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Suministramos en tiempo, forma y de manera veraz los datos que nos fueren solicitados por los órganos de control. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>104,
                    'pregunta'=>'LA DIRECTRIZ CON LOS ÓRGANOS DE CONTROL SE REFIERE A:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Reconocemos y aceptamos las distintas formas de pensar, sentir y actuar de los funcionarios dentro de las normativas vigentes de la función pública. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Suministramos en tiempo, forma y de manera veraz los datos que nos fueren solicitados por los órganos de control para la auditoría y verificación de nuestras gestiones. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Establecemos alianzas con otras instituciones públicas para promover y coordinar programas y proyectos de interés común en beneficio de la ciudadanía. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Cumplimos cabalmente loas compromisos adquiridos con los órganos internacionales ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>105,
                    'pregunta'=>'LA DIRECTRIZ CON LA CIUDADNÍA SE REFIERE A:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Reconocemos la necesidad de un medio ambiente saludable de modo que orientamos las políticas de salud dentro de marco de conservación del ecosistema. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Establecemos alianzas con otras instituciones públicas para promover y coordinar programas y proyectos de interés común en beneficio de la ciudadanía. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Cumplimos cabalmente los compromisos adquiridos con los órganos internacionales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Prestamos servicios con excelencia, brindamos atención humanizada a cada usuario según sus necesidades, reconociendo y aceptando sus diferencias. ',
                            'correcto'=>'true'
                        )
                )),array(
                    'pregunta_id'=>106,
                    'pregunta'=>'EL MINISTERIO DE SALUD PÚBLICA SE CREO:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'En el año 1936, bajo el gobierno del Coronel Rafael Franco, mediante la promulgación del Decreto Ley N° 2000/36 del 15 de junio de 1936. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'En el año 1936, bajo el gobierno del Coronel Rafael Franco, mediante la promulgación del Decreto Ley N° 2001/36 del 15 de junio de 1936. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'En el año 1992, bajo el gobierno del General Alfredo Stroessner, mediante la promulgación del Decreto Ley N° 21376/1992 del 15 de junio de 1992. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'En el año 1998, bajo el gobierno de Juan Carlos Wasmosy, mediante la promulgación del Decreto Ley N° 21376/98 del 15 de junio de 1998. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>107,
                    'pregunta'=>'DESDE LA CREACIÓN DEL MINISTERIO DE SALUD PÚBLICA HASTA EL 2013 FUERON NOMBRADOS:',
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
                    'pregunta'=>'ANTES DE LA CREACIÓN DEL MINISTERIO DE SALUD PÚBLICA, LOS SERVICIOS DE SALUD ERAN PROVEÍDOS Y ADMINISTRADOS POR:',
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
                    'pregunta'=>'EL MINISTERIO DE SALUD PÚBLICA Y BIENESTAR SOCIAL TIENE COMO MISIÓN:',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rectoría, conducción, financiamiento y provisión de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de prevención social, en el marco del Sistema de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de conducción y provisión de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de prevención social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rectoría, conducción, financiamiento y provisión de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de protección social, en el marco del Sistema de Salud. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Garantizar, oportuna y eficientemente, las prestaciones del Seguros Social, con calidad y calidez en el servicio, a nuestros asegurados. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>110,
                    'pregunta'=>'EL MINISTERIO DE SALUD PÚBLICA Y BIENESTAR SOCIAL TIENE COMO MISIÓN: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rectoría, conducción, financiamiento y provisión de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de prevención social, en el marco del Sistema de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de conducción y provisión de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de prevención social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rectoría, conducción, financiamiento y provisión de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de protección social, en el marco del Sistema de Salud. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Garantizar, oportuna y eficientemente, las prestaciones del Seguros Social, con calidad y calidez en el servicio, a nuestros asegurados. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>111,
                    'pregunta'=>'EL MINISTERIO DE SALUD PÚBLICA Y BIENESTAR SOCIAL TIENE COMO VISIÓN: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Garantizar el cumplimiento de las funciones de rectoría, conducción, financiamiento y provisión de servicios de salud con el fin de alcanzar la cobertura universal, bajo el enfoque de protección social, en el marco del Sistema de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Ser un Ministerio eficaz, eficiente y transparente que garantiza el acceso efectivo de la población, para la asistencia sanitaria integral y equitativa, promoviendo la participación activa de la ciudadanía en función al ejercicio de sus derechos. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Ser la institución que administra el Seguro Social, con amplia cobertura, garantizando la sostenibilidad del sistema, en base al continuo perfeccionamiento de la gestión, contribuyendo al desarrollo del país. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Ser una Institución promotora y garante de los Derechos Humanos de las mujeres, con mayor nivel de incidencia en los ámbitos público y privado, para la igualdad real y efectiva entre mujeres y hombres. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>112,
                    'pregunta'=>'El derecho a la vida es inherente a la persona humana, además que se garantiza su protección, en general, desde la concepción y que toda persona será protegida por el Estado: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'En su integridad física y psíquica. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'En su integridad física, psíquica y en su salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'En su honor y en su reputación.  ',
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
                    'pregunta'=>'La Constitución de la República del Paraguay, dispone que la calidad de vida será promovida por el Estado mediante planes y políticas que reconozcan factores condicionantes, tales como: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La pobreza, los impedimentos de la discapacidad y de la educación.  ',
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
                            'respuesta'=>'Los factores de población. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'b) y c) son correctas. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>114,
                    'pregunta'=>'Según el Derecho a Peticionar a las Autoridades previsto en la Constitución de la República del Paraguay, ¿Quiénes tienen derecho a peticionar a las autoridades, por escrito?: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Todo ciudadano paraguayo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Toda persona mayor de edad, residente en la República. ',
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
                            'respuesta'=>'Las personas físicas y jurídicas, sin requisitos especiales. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>115,
                    'pregunta'=>'La Constitución de la República del Paraguay, establece que la familia, la sociedad y los poderes públicos promoverán el bienestar de toda persona en la tercera edad mediante servicios sociales que se ocupen de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Sus necesidades de alimentación, salud, vivienda, cultura y ocio. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Sus necesidades de alimentación, salud, vivienda y  cultura.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Sus necesidades fundamentales de alimentación y salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Sus necesidades fundamentales de alimentación, salud, y vivienda. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Sus necesidades esenciales y culturales. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>116,
                    'pregunta'=>'Según los Derechos de las Personas Excepcionales. Se garantizará a las personas excepcionales la atención de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'De su salud.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'De su educación. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'De su recreación y de su formación profesional. ',
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
                    'pregunta'=>'La Constitución de la Republica del Paraguay en cuanto los Derechos de las Personas Excepcionales dispone que el Estado organizará una política de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Prevención, tratamiento, y rehabilitación de los discapacitados físicos, psíquicos y sensoriales, a quienes prestará el cuidado especializado que requieran.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Prevención, tratamiento, rehabilitación e integración de los discapacitados físicos, psíquicos y sensoriales, a quienes prestará el cuidado especializado que requieran.  ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Prevención, tratamiento, rehabilitación e integración de los discapacitados físicos y sensoriales, a quienes prestará el cuidado especializado que requieran. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Tratamiento, rehabilitación e integración de los discapacitados físicos, psíquicos y sensoriales, a quienes prestará el cuidado especializado que requieran. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Rehabilitación e integración de los discapacitados físicos, a quienes prestará el cuidado especializado que requieran. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>118,
                    'pregunta'=>'La Constitución de la República del Paraguay prevé que se establecerán planes especiales de salud reproductiva y salud materno infantil: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Para la población en general. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Para la población nacional. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Para las familias de escasos recurso. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Para la población de escasos recurso. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Para la población de todos los estratos sociales. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>119,
                    'pregunta'=>'Selecciona la opción que completa el enunciado. “Se promoverá ……………… que ejecute acciones sanitarias integradas, con políticas que posibiliten la concertación, la coordinación y la complementación de programas y recursos del sector público y privado”. ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Una Política Nacional de Salud ',
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
                    'pregunta'=>'La ley establecerá programas de bienestar social mediante estrategias basadas en: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'La educación sanitaria. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La participación comunitaria.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La salud pública y la economía social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'a) y b) son correctas. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'La educación y la salud. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>121,
                    'pregunta'=>'La Constitución de la República del Paraguay  dispone que los funcionarios y los empleados públicos están al servicio: ',
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
                            'respuesta'=>'Del país.  ',
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
                    'pregunta'=>'El derecho de todas las personas a un nivel adecuado y digno de vida en lo respecta a la salud y al bienestar, no sólo descansa en la atención médica y sanitaria, sino que comprende: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Al derecho a un nivel adecuado de alimentos, vivienda, educación y servicios sociales desde una perspectiva de género. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Al derecho a un nivel adecuado de alimentos, agua, vivienda, educación, trabajo y servicios sociales desde una perspectiva de género. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Al derecho a un nivel adecuado de alimentos, agua, vivienda, educación y de trabajo. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Al derecho a un nivel adecuado de vida y la provisión de servicios sociales desde una perspectiva de género. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Al derecho a un nivel adecuado de alimentos, agua, vivienda, educación sanitaria, trabajo y servicios sociales desde una perspectiva de género. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>123,
                    'pregunta'=>'Salud Publica, como institución se constituye en el ente rector de la política sanitaria nacional y con tal tiene la responsabilidad de apoyar la conducción…: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'De los Establecimientos de Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'De la Política Nacional de Salud. ',
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
                            'respuesta'=>'De la Descentralización de los servicios. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>124,
                    'pregunta'=>'Constituyen ámbitos de responsabilidad, control y ejecución del Ministerio de Salud Pública y Bienestar Social, las siguientes áreas específicas de acción: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Conducción de la Política de Salud y del Ambiente Humano. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Vigilancia de la Salud y del Ambiente. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Desarrollo de la investigación científica; regulación y atención sanitaria. ',
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
                    'pregunta'=>'Son funciones específicas en el área de Conducción de la Salud y del Ambiente Humano: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Formular y conducir la ejecución de la Política de Salud y del Ambiente humano coordinada con otros Establecimientos. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Diseñar y conducir el sistema de información sanitaria. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Asesorar a los organismos nacionales, regionales y locales de salud, en la elaboración y seguimiento de  planes, proyectos y programas de salud y del ambiente humano. ',
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
                    'pregunta'=>'Definir normas generales y especificas de atención en salud, y  establecer normas que regulen participación de los subsectores publico y privados en el desarrollo de sistemas de abastecimiento de agua potable y de saneamiento ambiental, son algunas de las funciones específicas en el área de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Bienestar Social. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Conducción de la Salud y del Ambiente Humano. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Promoción de la Salud. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>4,
                            'respuesta'=>'Regulación y atención sanitaria. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>5,
                            'respuesta'=>'Ninguna de las anteriores. ',
                            'correcto'=>'false'
                        )
                )),array(
                    'pregunta_id'=>127,
                    'pregunta'=>'Fijar aranceles por servicios prestados, y ejercer la administración general de la institución como Ordenador de Gastos y responsable de los recursos humanos, físicos y financieros, son funciones específicas de: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'El Ministro de Salud Pública y Bienestar Social. ',
                            'correcto'=>'true'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'La Dirección General de Administración y Finanzas. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'La Dirección General de Recursos Humanos. ',
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
                    'pregunta'=>'Son funciones específicas del Ministro de Salud Pública y Bienestar Social representar al Ministerio de Salud Pública y Bienestar Social ante: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Los organismos nacionales. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'Los organismos del exterior del área de la salud.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'Los países  y otras instituciones que el Poder Ejecutivo disponga. ',
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
                    'pregunta'=>'Un de las funciones específicas del Ministro de Salud Pública y Bienestar Social, es promover la coordinación y complementación inter-institucional de los servicios públicos y privados de la salud…: ',
                    'puntaje'=>1,
                    'respuestas'=>array(
                        array(
                            'respuesta_id'=>1,
                            'respuesta'=>'Que permita la extensión de la cobertura sanitaria.  ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>2,
                            'respuesta'=>'El mejor control de las crisis sanitarias. ',
                            'correcto'=>'false'
                        ),array(
                            'respuesta_id'=>3,
                            'respuesta'=>'El mejor control de enfermedades endémicas y emergentes. ',
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
                    'pregunta'=>'Desarrollar y fortalecer los organismos sectoriales establecidos en la Ley N° 103296, que crea el Sistema Nacional de Salud como instrumento de participación  social en las instancias…: ',
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
                            'respuesta'=>'Nacionales, públicas y privadas. ',
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
                            'respuesta'=>'Política y el Plan Nacional de Salud. ',
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
            $message = 'ERROR: Exedió el horario establecido';
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
