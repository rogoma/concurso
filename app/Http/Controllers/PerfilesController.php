<?php

namespace App\Http\Controllers;


use App\Models\Role;
use App\Models\Perfile;
use App\Models\Usuario;
use App\Models\DatosPersonale;
use App\Models\ExpLaborale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ValidacionUpdatePerfil;

class PerfilesController extends Controller
{

    private $fileName = '';
    private $fileDirectory = '';
    private $sotrageName = 'documentos/';
    private $sotrageDirectory = 'perfil';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = Usuario::findOrFail(session('usuario_id'));
        $perfil = (!$usuario->perfil) ? false : $usuario->perfil;
        if ($perfil->foto != null && file_exists($perfil->foto))
        {
            Storage::setVisibility($perfil->foto,'public');
        }
        $personales = DatosPersonale::with('tipoDoc')
                        ->where('usuario_id', session('usuario_id'))
                        //->orderby('id','DESC')
                        //->take(3)
                        ->get();
        $academicos = (!$usuario->datosacademicos) ? false : $usuario->datosacademicos;
        $laborales = (!$usuario->laborales) ? false : $usuario->laborales;
        $capacitaciones = (!$usuario->capacitaciones) ? false : $usuario->capacitaciones;
        $data = (object)[
            'user' => session('ci'),
            'usuario' => $usuario,
            'perfil' => $perfil,
            'personales' => $personales,
            'academicos' => $academicos,
            'laborales' => $laborales,
            'capacitaciones' => $capacitaciones
            ];

        return view('backend.perfil.index', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $perfil = Perfile::findOrFail($id);
        $user = Usuario::with('rol')->where('id', $perfil->usuario_id)->first();
        $datos = (object)[
                    'user' => session('ci'),
                    'perfil' => $perfil,
                    'rol' => $user->rol[0]
                ];
                //dd($datos);
        return view('backend.perfil.edit', compact('datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionUpdatePerfil $request, $id)
    {
        $data = $request->validated();
        $perfil = Perfile::findOrFail($id);

        if($request->hasFile('foto'))
        {
            if ($perfil->foto != null && file_exists($perfil->foto))
            {
                Storage::delete($perfil->foto);
            }

            $this->fileName = $request->user_mod.'.'.$request->file('foto')->extension();

            $this->fileDirectory = $this->sotrageName.$request->user_mod.'/'.$this->sotrageDirectory;

            $perfil->foto = $request->file('foto')->storeAs($this->fileDirectory, $this->fileName);
        }
        $data['foto'] = $perfil->foto;
        $perfil->update($data);

        return redirect()->route('perfil')->with('mensaje', 'Perfil Actualizado');
    }
}
