<?php

namespace App\Http\Requests\Api\Master\Program;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubProgramRequest extends FormRequest
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
            'name' =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     =>  'Nama Sub Program Tidak boleh kosong',
        ];
    }
}
