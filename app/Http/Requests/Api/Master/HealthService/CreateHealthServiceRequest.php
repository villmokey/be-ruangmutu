<?php

namespace App\Http\Requests\Api\Master\HealthService;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateHealthServiceRequest extends FormRequest
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
            'name'      =>  'required|string|max:100|unique:health_services',
        ];
    }

    public function messages()
    {
        return [
            'name.required'       =>  'Nama layanan kesehatan tidak boleh kosong',
            'name.unique'         =>  'Layanan kesehatan telah ditambahkan sebelumnya',
        ];
    }
}
