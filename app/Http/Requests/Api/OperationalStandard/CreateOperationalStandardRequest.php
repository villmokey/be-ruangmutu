<?php

namespace App\Http\Requests\Api\OperationalStandard;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateOperationalStandardRequest extends FormRequest
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
            'document_number' => 'required',
            'revision_number' => 'required',
            'released_date' => 'required|date',
            'meaning' => 'required',
            'goal' => 'required',
            'policy' => 'required',
            'reference' => 'required',
            'tools' => 'required',
            'procedures' => 'required',
            'flow_diagram' => 'required',
            'related_program' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'   => 'Nama Dokumen Tidak boleh kosong',
            'document_number.required' => 'Nomor Dokumen Tidak boleh kosong',
            'revision_number.required' => 'Nomor Revisi Tidak boleh kosong',
            'released_date.required' => 'Tanggal Publikasi Tidak boleh kosong',
            'released_date.date' => 'Format tanggal publikasi tidak valid',
            'meaning.required' => 'Pengertian Tidak boleh kosong',
            'goal.required' => 'Tujuan Tidak boleh kosong',
            'policy.required' => 'Kebijakan Tidak boleh kosong',
            'reference.required' => 'Referensi Tidak boleh kosong',
            'tools.required' => 'Alat dan Bahan Tidak boleh kosong',
            'procedures.required' => 'Prosedur Tidak boleh kosong',
            'flow_diagram.required' => 'Flow Diagram Tidak boleh kosong',
            'related_program.required' => 'Program Terkait Tidak boleh kosong'
        ];
    }
}
