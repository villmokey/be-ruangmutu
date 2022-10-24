<?php

namespace App\Http\Requests\Api\SatisfactionLevel;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateSatisfactionLevelRequest extends FormRequest
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
            'health_service_id' => 'required|exists:health_services,id',
            'month' => 'required',
            'units' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'health_service_id.required'   => 'Fasilitas kesehatan tidak boleh kosong',
            'health_service_id.exists'     => 'Fasilitas kesehatan tidak ditemukan',
            'month.required'               => 'Bulan tidak boleh kosong',
            'units.required'               => 'Unit kesehatan tidak boleh kosong',
        ];
    }
}
