<?php

namespace App\Http\Requests\Api\Master\User;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'nip'       =>  'required|string|max:255',
            'name'      =>  'required|string|max:255',
            'email'     =>  'required|string|email|max:255|unique:users',
            'password'  =>  'required|string|min:6|confirmed',
            'role_id'   =>  'required|integer|exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'nip.required'       =>  'NIP Tidak boleh kosong',
            'nip.string'         =>  'NIP Harus berupa string',
            'nip.max'            =>  'NIP Maksimal 255 karakter',
            'name.required'      =>  'Nama Tidak boleh kosong',
            'name.string'        =>  'Nama Harus berupa string',
            'name.max'           =>  'Nama Maksimal 255 karakter',
            'email.required'     =>  'Email Tidak boleh kosong',
            'email.string'       =>  'Email Harus berupa string',
            'email.email'        =>  'Email Harus berupa email',
            'email.max'          =>  'Email Maksimal 255 karakter',
            'email.unique'       =>  'Email sudah terdaftar',
            'password.required'  =>  'Password Tidak boleh kosong',
            'password.string'    =>  'Password Harus berupa string',
            'password.min'       =>  'Password Minimal 6 karakter',
            'password.confirmed' =>  'Password tidak sama',
            'role_id.required'   =>  'Role Tidak boleh kosong',
            'role_id.integer'    =>  'Role Harus berupa integer',
            'role_id.exists'     =>  'Role tidak ditemukan',
        ];
    }
}
