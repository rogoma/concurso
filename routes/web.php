<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RolesController;
use App\Http\Controllers\CargosController;
use App\Http\Controllers\TipodocController;
use App\Http\Controllers\ExamenesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\PerfilesController;
use App\Http\Controllers\ConcursosController;
use App\Http\Controllers\DatosAcadsController;
use App\Http\Controllers\ExpLaboralesController;
use App\Http\Controllers\PostulacionesController;
use App\Http\Controllers\CapacitacionesController;
use App\Http\Controllers\TipoRechazoDocController;
use App\Http\Controllers\DatosPersonalesController;
use App\Http\Controllers\EvaluacionDocumentalController;
use App\Http\Controllers\EvaluacionCurricularController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

/*Route::get('dashboard', function () {
    dd(Auth::user());
})->middleware(['auth', 'verified']);*/

/*RUTA DEL CONCURSOS*/
Route::get('concursos', [ConcursosController::class, 'index'])->middleware(['auth', 'verified'])->name('concursos');
Route::get('concursos/evaluacion', [ConcursosController::class, 'evaluacion'])->middleware(['auth', 'verified'])->name('concursos.evaluacion');

/*RUTAS DEL EXAMEN*/
Route::get('examen', [ExamenesController::class, 'index'])->middleware(['auth', 'verified'])->name('examen');
Route::post('examen/rendir', [ExamenesController::class, 'rendir'])->middleware(['auth', 'verified'])->name('examen.rendir');
Route::post('examen/finish', [ExamenesController::class, 'finish'])->middleware(['auth', 'verified'])->name('examen.finish');


/*RUTA DEL PERFIL*/
Route::get('perfil', [PerfilesController::class, 'index'])->middleware('auth')->name('perfil');
Route::get('perfil/{id}/edit', [PerfilesController::class, 'edit'])->middleware('auth')->name('perfil.edit');
Route::put('perfil/{id}', [PerfilesController::class, 'update'])->middleware('auth')->name('perfil.update');

Route::group(['prefix' => 'system', 'middleware' => ['auth', 'administrador']], function(){
    /*RUTAS DEL ROLES*/
    Route::get('roles', [RolesController::class, 'index'])->name('roles');
    /*Route::get('roles/create', [RolesController::class, 'create'])->name('roles.create');
    Route::get('roles/{id}/edit', [RolesController::class, 'edit'])->name('roles.edit');
    Route::post('roles', [RolesController::class, 'store'])->name('roles.store');
    Route::put('roles/{id}', [RolesController::class, 'update'])->name('roles.update');
    Route::delete('roles/{id}/destroy', [RolesController::class, 'destroy'])->name('roles.destroy');*/

    /*RUTAS DEL USUARIOS*/
    Route::get('usuarios', [UsuariosController::class, 'index'])->name('usuarios');
    Route::get('usuarios/create', [UsuariosController::class, 'create'])->name('usuarios.create');
    Route::get('usuarios/{id}/edit', [UsuariosController::class, 'edit'])->name('usuarios.edit');
    Route::post('usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
    Route::put('usuarios/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::delete('usuarios/{id}/destroy', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');

    /*RUTAS DE TIPOS DE DOCUMENTOS*/
    Route::get('tipodoc', [TipodocController::class, 'index'])->name('tipodoc');
    Route::get('tipodoc/create', [TipodocController::class, 'create'])->name('tipodoc.create');
    Route::get('tipodoc/{id}/edit', [TipodocController::class, 'edit'])->name('tipodoc.edit');
    Route::post('tipodoc', [TipodocController::class, 'store'])->name('tipodoc.store');
    Route::put('tipodoc/{id}', [TipodocController::class, 'update'])->name('tipodoc.update');
    Route::delete('tipodoc/{id}/destroy', [TipodocController::class, 'destroy'])->name('tipodoc.destroy');

    /*RUTAS DE TIPOS DE DOCUMENTOS*/
    Route::get('rechazodoc', [TipoRechazoDocController::class, 'index'])->name('rechazodoc');
    Route::get('rechazodoc/create', [TipoRechazoDocController::class, 'create'])->name('rechazodoc.create');
    Route::get('rechazodoc/{id}/edit', [TipoRechazoDocController::class, 'edit'])->name('rechazodoc.edit');
    Route::post('rechazodoc', [TipoRechazoDocController::class, 'store'])->name('rechazodoc.store');
    Route::put('rechazodoc/{id}', [TipoRechazoDocController::class, 'update'])->name('rechazodoc.update');
    Route::delete('rechazodoc/{id}/destroy', [TipoRechazoDocController::class, 'destroy'])->name('rechazodoc.destroy');

    /*RUTAS DE CARGOS*/
    Route::get('cargos', [CargosController::class, 'index'])->name('cargos');
    Route::get('cargos/create', [CargosController::class, 'create'])->name('cargos.create');
    Route::get('cargos/{id}/edit', [CargosController::class, 'edit'])->name('cargos.edit');
    Route::post('cargos', [CargosController::class, 'store'])->name('cargos.store');
    Route::put('cargos/{id}', [CargosController::class, 'update'])->name('cargos.update');
    Route::delete('cargos/{id}/destroy', [CargosController::class, 'destroy'])->name('cargos.destroy');

    /*RUTA DEL CONCURSOS*/
    Route::get('concourse', [ConcursosController::class, 'indexx'])->name('concourse');
    Route::get('concourse/create', [ConcursosController::class, 'create'])->name('concourse.create');
    Route::get('concourse/{id}/edit', [ConcursosController::class, 'edit'])->name('concourse.edit');
    Route::post('concourse', [ConcursosController::class, 'store'])->name('concourse.store');
    Route::put('concourse/{id}', [ConcursosController::class, 'update'])->name('concourse.update');
    Route::delete('concourse/{id}/destroy', [ConcursosController::class, 'destroy'])->name('concourse.destroy');

    /*RUTAS DE EXAMENES*/
    Route::get('examen/list', [ExamenesController::class, 'list'])->name('examen.list');
    Route::get('examen/{id}/show', [ExamenesController::class, 'show'])->name('examen.show');
    Route::get('examen/create', [ExamenesController::class, 'create'])->name('examen.create');
    Route::get('examen/createq', [ExamenesController::class, 'createq'])->name('examen.createq');

    /*Route::get('examen/store', [ExamenesController::class, 'store'])->name('examen.store');*/

    Route::post('examen/questions', [ExamenesController::class, 'questions'])->name('examen.questions');
    Route::delete('examen/{id}/destroy', [ConcursosController::class, 'destroy'])->name('examen.destroy');
    Route::delete('examen/{id}/destroyq', [ConcursosController::class, 'destroyq'])->name('examen.destroyq');
});

Route::group(['prefix' => 'datos', 'middleware' => ['auth', 'userauth']], function(){
    /*RUTAS DE DATOS PERSONALES*/
    Route::get('personales', [DatosPersonalesController::class, 'index'])->name('personales');
    Route::get('personales/create', [DatosPersonalesController::class, 'create'])->name('personales.create');
    Route::get('personales/{id}/edit', [DatosPersonalesController::class, 'edit'])->name('personales.edit');
    Route::post('personales', [DatosPersonalesController::class, 'store'])->name('personales.store');
    Route::put('personales/{id}', [DatosPersonalesController::class, 'update'])->name('personales.update');
    Route::delete('personales/{id}/destroy', [DatosPersonalesController::class, 'destroy'])->name('personales.destroy');

    /*RUTAS DE DATOS ACADEMICOS*/
    Route::get('academicos', [DatosAcadsController::class, 'index'])->name('academicos');
    Route::get('academicos/create', [DatosAcadsController::class, 'create'])->name('academicos.create');
    Route::get('academicos/{id}/edit', [DatosAcadsController::class, 'edit'])->name('academicos.edit');
    Route::post('academicos', [DatosAcadsController::class, 'store'])->name('academicos.store');
    Route::put('academicos/{id}', [DatosAcadsController::class, 'update'])->name('academicos.update');
    Route::delete('academicos/{id}/destroy', [DatosAcadsController::class, 'destroy'])->name('academicos.destroy');

    /*RUTAS DE DATOS LABORALES*/
    Route::get('laborales', [ExpLaboralesController::class, 'index'])->name('laborales');
    Route::get('laborales/create', [ExpLaboralesController::class, 'create'])->name('laborales.create');
    Route::get('laborales/{id}/edit', [ExpLaboralesController::class, 'edit'])->name('laborales.edit');
    Route::post('laborales', [ExpLaboralesController::class, 'store'])->name('laborales.store');
    Route::put('laborales/{id}', [ExpLaboralesController::class, 'update'])->name('laborales.update');
    Route::delete('laborales/{id}/destroy', [ExpLaboralesController::class, 'destroy'])->name('laborales.destroy');

    /*RUTAS DE CAPACITACIONES LABORALES*/
    Route::get('capacitacion', [CapacitacionesController::class, 'index'])->name('capacitacion');
    Route::get('capacitacion/create', [CapacitacionesController::class, 'create'])->name('capacitacion.create');
    Route::get('capacitacion/{id}/edit', [CapacitacionesController::class, 'edit'])->name('capacitacion.edit');
    Route::post('capacitacion', [CapacitacionesController::class, 'store'])->name('capacitacion.store');
    Route::put('capacitacion/{id}', [CapacitacionesController::class, 'update'])->name('capacitacion.update');
    Route::delete('capacitacion/{id}/destroy', [CapacitacionesController::class, 'destroy'])->name('capacitacion.destroy');

    /*RUTAS DE POSTULACION*/
    Route::get('postulacion', [PostulacionesController::class, 'index'])->name('postulacion');
    Route::get('postulacion/{concurso_id}', [PostulacionesController::class, 'show'])->name('postulacion.show');
    Route::get('postulacion/{concurso_id}/print', [PostulacionesController::class, 'print'])->name('postulacion.print');
    Route::post('postulacion', [PostulacionesController::class, 'store'])->name('postulacion.store');
});

Route::group(['prefix' => 'validador', 'middleware' => ['auth', 'uservalid']], function(){
    /*RUTAS DE VALIDACION DOCUMENTAL*/
    Route::get('documental', [EvaluacionDocumentalController::class, 'documental'])->name('documental');
    Route::get('documentals', [EvaluacionDocumentalController::class, 'searchs'])->name('documentals');
    Route::post('documental', [EvaluacionDocumentalController::class, 'search'])->name('documental.search');
    Route::post('matriz-documental', [EvaluacionDocumentalController::class, 'matriz'])->name('documental.matriz');
    Route::post('documentalstore', [EvaluacionDocumentalController::class, 'store'])->name('documental.store');
    Route::post('documental/edit', [EvaluacionDocumentalController::class, 'edit'])->name('documental.edit');
    Route::put('documental/update', [EvaluacionDocumentalController::class, 'update'])->name('documental.update');

    /*RUTAS DE VALIDACION CURRICULAR*/
    Route::get('curricular', [EvaluacionCurricularController::class, 'curricular'])->name('curricular');
    Route::get('curriculars', [EvaluacionCurricularController::class, 'searchs'])->name('curriculars');
    Route::post('curricular', [EvaluacionCurricularController::class, 'search'])->name('curricular.search');
    Route::post('matriz-curricular', [EvaluacionCurricularController::class, 'matriz'])->name('curricular.matriz');
    Route::post('curricularstore', [EvaluacionCurricularController::class, 'store'])->name('curricular.store');
    Route::post('curricular/edit', [EvaluacionCurricularController::class, 'edit'])->name('curricular.edit');
    Route::put('curricular/update', [EvaluacionCurricularController::class, 'update'])->name('curricular.update');
});
