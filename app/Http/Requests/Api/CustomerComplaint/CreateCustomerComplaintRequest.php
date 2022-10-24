<?php

namespace App\Http\Requests\Api\CustomerComplaint;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerComplaintRequest extends FormRequest
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
            'program_id'        => 'required|exists:programs,id',
            'report'            => 'required',
            'source'            => 'required',
        ];
    }

    public function messages()
    {
        return [
            'health_service_id.required'   => 'Fasilitas kesehatan tidak boleh kosong',
            'health_service_id.exists'     => 'Fasilitas kesehatan tidak ditemukan',
            'program_id.required'          => 'Program mutu tidak boleh kosong',
            'program_id.exists'            => 'Program mutu tidak ditemukan',
            'report.required'              => 'Isi laporan tidak boleh kosong',
            'units.required'               => 'Unit kesehatan tidak boleh kosong',
        ];
    }
}
