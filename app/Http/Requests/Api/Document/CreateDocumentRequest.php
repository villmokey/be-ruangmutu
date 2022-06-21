<?php

namespace App\Http\Requests\Api\Document;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentRequest extends FormRequest
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
            'name' => 'required',
            'document_type_id' => 'required|exists:document_types,id',
            'program_id' => 'required|exists:programs,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required'   => 'Nama Dokumen Tidak boleh kosong',
            'document_type_id.required'   => 'Tipe Dokumen Tidak boleh kosong',
            'document_type_id.exists'     => 'Tipe Dokumen Tidak ditemukan',
            'program_id.required'   => 'Program Tidak boleh kosong',
            'program_id.exists'     => 'Program Tidak ditemukan',
        ];
    }
}
