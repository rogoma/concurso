<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionRoles extends FormRequest
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
            'rol' => 'required|max:25|unique:roles,rol',
            'slug' => 'required|max:25|unique:roles,slug',
            'user_crea' => 'max:20',
        ];
    }
}
