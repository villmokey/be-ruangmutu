<?php

namespace App\Http\Requests;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'password'  =>  'required|confirmed|min:6',
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'password.required'                 => 'Kata Sandi baru wajib diisi',
            'password.min'                      => 'Kata sandi harus minimal 6 karakter',
            'password.confirmed'                => 'konfirmasi kata sandi salah, konfirmasi kata sandi dan kata sandi baru harus sama',
            'password_confirmation.required'    => 'Konfirmasi Kata Sandi wajib diisi',
            'password_confirmation.same'        =>  "Konfirmasi kata sandi tidak sama"
        ];
    }
}
