<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ValidacionUsuarios extends FormRequest
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
            'ci' => 'required|string|max:12|unique:usuarios,ci',
            'email' => 'required|string|email|max:60|unique:usuarios,email',
            'password' => ['required', 'string',
                            Password::min(8)
                            ->mixedCase()
                            ->numbers()
                            ->symbols(),
                            'confirmed'],
            'activo' => 'boolean',
            'user_crea' => 'max:20'
        ];
    }
}
