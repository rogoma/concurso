<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionUpdateUsuarios extends FormRequest
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
            'ci' => 'required|string|max:12|unique:usuarios,ci,'.$this->route('id'),
            'email' => 'required|string|email|max:60|unique:usuarios,email,'.$this->route('id'),
            'user_mod' => 'nullable|max:20',
        ];
    }
}
