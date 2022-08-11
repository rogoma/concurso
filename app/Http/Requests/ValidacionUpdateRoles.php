<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionUpdateRoles extends FormRequest
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
            'rol' => 'required|max:25|unique:roles,rol,'.$this->route('id'),
            'slug' => 'required|max:25|unique:roles,slug,'.$this->route('id'),
            'user_mod' => 'nullable|max:20',
        ];
    }
}
