<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UpperCase;
use Illuminate\Support\Facades\Session;

class ValidacionRechazodoc extends FormRequest
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
            'tipo_doc_id' => 'required|integer',
            'rechazo' => ['required', 'max:60', new UpperCase],
            'user_crea' => 'max:20',
        ];
    }
}
