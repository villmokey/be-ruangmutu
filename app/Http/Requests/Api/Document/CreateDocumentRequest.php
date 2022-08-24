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
            'file_id' => 'required|exists:files,id',
            'document_related' => 'exists:documents,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required'   => 'Nama Dokumen Tidak boleh kosong',
            'document_type_id.required'   => 'Tipe Dokumen Tidak boleh kosong',
            'document_type_id.exists'     => 'Tipe Dokumen Tidak ditemukan',
            'file_id.required'   => 'File Tidak boleh kosong',
            'file_id.exists'     => 'File Tidak ditemukan',
            'document_related.exists' => 'Dokumen terkait tidak ditemukan',
        ];
    }
}
