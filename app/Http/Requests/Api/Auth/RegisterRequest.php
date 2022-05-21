<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use InitialRequestValidation;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' =>  'required|string|min:2|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     =>  'Nama tidak boleh kosong',
            'email.required'     =>  'Email tidak boleh kosong',
            'password.required'  =>  'Password tidak boleh kosong',
        ];
    }
}
