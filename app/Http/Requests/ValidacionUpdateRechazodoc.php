<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UpperCase;
use Illuminate\Support\Facades\Session;

class ValidacionUpdateRechazodoc extends FormRequest
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
            'tipo_doc_id' => 'required|integer:tipo_rechazo_docs,tipo_doc_id,'.$this->route('id'),
            'rechazo' => 'required|max:60:tipo_rechazo_docs,rechazo,'.$this->route('id'),
            'user_mod' => 'max:20',
        ];
    }
}
