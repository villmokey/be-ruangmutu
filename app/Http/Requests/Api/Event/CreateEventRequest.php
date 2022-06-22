<?php

namespace App\Http\Requests\Api\Event;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'document_related' => 'exists:documents,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required'   => 'Nama Dokumen Tidak boleh kosong',
            'start_date.required' => 'Tanggal Mulai Tidak boleh kosong',
            'end_date.required' => 'Tanggal Selesai Tidak boleh kosong',
            'document_related.exists' => 'Dokumen terkait tidak ditemukan',
        ];
    }
}
