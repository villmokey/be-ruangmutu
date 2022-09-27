<?php

namespace App\Http\Requests\Api\Master\HealthService;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHealthServiceRequest extends FormRequest
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
            'name'      =>  'string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.string'        =>  'Nama Harus berupa string',
            'name.max'           =>  'Nama Maksimal 100 karakter'
        ];
    }
}
