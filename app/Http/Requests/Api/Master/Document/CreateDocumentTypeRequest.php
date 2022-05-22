<?php

namespace App\Http\Requests\Api\Master\Document;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentTypeRequest extends FormRequest
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
            'desc' =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     =>  'Nama Tipe Dokumen Tidak boleh kosong',
            'desc.required'      =>  'Deskripsi Tipe Dokumen Tidak boleh kosong',
        ];
    }
}
