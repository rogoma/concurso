<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UpperCase;
use Illuminate\Support\Facades\Session;

class ValidacionCargos extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'descripcion' => ['required', 'max:50', new UpperCase, 'unique:cargos,descripcion'],
            'user_crea' => 'max:20',
        ];
    }
}
