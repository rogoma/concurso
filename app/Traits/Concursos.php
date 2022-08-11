<?php

namespace App\Traits;

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Storage;

trait Concursos
{

    private $fileName = '';
    private $fileDirectory = '';
    private $sotrageName = 'documentos/';
    private $sotrageDirectory = '';

    /*public function __invoke(Request $request, $path)
    {
        abort_if(
            ! Storage::disk('documentos') ->exists($path),
            404,
            "El directorio de Almacenamiento no existe. Verifique la ruta."
        );

        return Storage::disk('documentos')->response($path);
    }*/

    /*public function __construct($fileModel, $filePath, $fileType, $fileName)
    {
        $this->fileModel = $fileModel;
        $this->filePath = $filePath;
        $this->fileType = $fileType;
        $this->fileName = $fileName;
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function fileUpload($request)
    {
        $arr = explode(',', $request['objetos']);

        if($request->hasFile($arr['objetos'][1]))
        {
            $this->fileName = $request['user'].'-'.date('YmdHis').$arr['objetos'][0].'.'.$request->file($arr['objetos'][1])->extension();

            $this->fileDirectory = $this->sotrageName.$request['user'].'/'.$this->sotrageDirectory;

            $uploaded = $request->file($arr['objetos'][1])->storeAs($this->fileDirectory, $this->fileName);
        } else {
            $uploaded = false;
        }
        return $uploaded;

        /*
        $fileModel = new $Model;

        if($request->file()) {
            $fileName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');

            $fileModel->name = time().'_'.$request->file->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
            $fileModel->save();

            return back()
            ->with('success','File has been uploaded.')
            ->with('file', $fileName);
        }
        */
    }


    public function fixFecha($fecha)
    {

        $response = ['error' => 'Formato de Fecha no Valido'];

        if(strstr($fecha, '-'))
        {
            $arr = explode('-', $fecha);
            if (count($arr) == 3)
            {
                if (strlen($arr[0]) == 4 && strlen($arr[1]) == 2 && strlen($arr[2]) == 2)
                {
                    return $arr[0].'-'.$arr[1].'-'.$arr[2];
                }
                else if (strlen($arr[2]) == 4 && strlen($arr[1]) == 2 && strlen($arr[0]) == 2)
                {
                    return $arr[2].'-'.$arr[1].'-'.$arr[0];
                }
                else
                {
                    return $response;
                }
            }
            return $response;
        }
        else if (strstr($fecha, '/'))
        {
            $arr = explode('/', $fecha);
            if (count($arr) == 3)
            {
                if (strlen($arr[0]) == 4 && strlen($arr[1]) == 2 && strlen($arr[2]) == 2)
                {
                    return $arr[0].'-'.$arr[1].'-'.$arr[2];
                }
                else if (strlen($arr[2]) == 4 && strlen($arr[1]) == 2 && strlen($arr[0]) == 2)
                {
                    return $arr[2].'-'.$arr[1].'-'.$arr[0];
                }
                else
                {
                    return $response;
                }
            }
            return $response;
        }
        return false;
    }
}
