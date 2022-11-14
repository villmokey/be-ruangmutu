<?php

namespace App\Http\Requests\Api\Master\Program;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateProgramRequest extends FormRequest
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
            'name' =>  'required|unique:programs,name,NULL,id,deleted_at,NULL',
            'color' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'     =>  'Nama Program tidak boleh kosong',
            'name.unique'     =>  'Nama Program telah ditambahkan sebelumnya',
            'color.required'     =>  'Inisial warna tidak boleh kosong',
        ];
    }
}
