<?php

namespace App\Http\Requests\Api\Master\Service;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceUnitRequest extends FormRequest
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
            'pic_id' => 'required|exists:users,id',
            'name' =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'pic_id.required'   => 'PIC Tidak boleh kosong',
            'pic_id.exists'     => 'PIC Tidak ditemukan',
            'name.required'     =>  'Nama Unit Layanan Tidak boleh kosong',
        ];
    }
}
