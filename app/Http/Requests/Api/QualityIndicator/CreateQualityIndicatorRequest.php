<?php

namespace App\Http\Requests\Api\QualityIndicator;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateQualityIndicatorRequest extends FormRequest
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
            'program_id' => 'required|exists:programs,id',
            'sub_program_id' => 'required|exists:sub_programs,id',
            'month' => 'required',
            'quality_goal_id' => 'required|exists:quality_indicator_profiles,id',
            'human' => 'required',
            'tools' => 'required',
            'method' => 'required',
            'policy' => 'required',
            'environment' => 'required',
            'next_plan' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'program_id.required'   => 'Program Tidak boleh kosong',
            'program_id.exists'     => 'Program Tidak ditemukan',
            'sub_program_id.required'   => 'Sub Program Tidak boleh kosong',
            'sub_program_id.exists'     => 'Sub Program Tidak ditemukan',
            'month.required'   => 'Bulan Tidak boleh kosong',
            'quality_goal_id.required'   => 'Sasaran Mutu Tidak boleh kosong',
            'quality_goal_id.exists'     => 'Sasaran Mutu Tidak ditemukan',
            'human.required'   => 'Manusia Tidak boleh kosong',
            'tools.required'   => 'Alat Tidak boleh kosong',
            'method.required'   => 'Metode Tidak boleh kosong',
            'policy.required'   => 'Kebijakan Tidak boleh kosong',
            'environment.required'   => 'Lingkungan Tidak boleh kosong',
            'next_plan.required'   => 'Rencana Tindak Lanjut Tidak boleh kosong',
        ];
    }
}
