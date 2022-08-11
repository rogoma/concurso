<?php

namespace App\Http\Requests;

use App\Rules\UpperCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;

class ValidacionDocumental extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isValidador();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'postuacion_id' => 'required|integer',
            'motivo_rechazo_id' => 'nullable|integer',
            'obs' => ['nullable', 'string', new UpperCase]
        ];
    }
}
