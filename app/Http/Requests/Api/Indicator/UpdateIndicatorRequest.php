<?php

namespace App\Http\Requests\Api\Indicator;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIndicatorRequest extends FormRequest
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
            'title' => 'required|exists:indicator_profiles,id',
            'program_id' => 'required|exists:programs,id',
            'sub_program_id' => 'required|exists:sub_programs,id',
            'month_target' => 'required|numeric',
            'month' => 'required',
            'quality_goal' => 'required',
            'human' => 'required',
            'tools' => 'required',
            'method' => 'required',
            'policy' => 'required',
            'environment' => 'required',
            'next_plan' => 'required',
            'first_pic_id' =>  'required|exists:users,id',
            'second_pic_id' =>  'exists:users,id',
            'created_by' =>  'required',
            'assign_by' =>  'required|exists:users,id',
            'signature.*.user_id' => 'exists:users,id',
            'signature.*.level' => 'in:1,2,3',
        ];
    }

    public function messages()
    {
        return [
            'title.required'   => 'Judul Tidak boleh kosong',
            'title.exists'     => 'Profile Indikator Tidak ditemukan',
            'program_id.required'   => 'Program Tidak boleh kosong',
            'program_id.exists'     => 'Program Tidak ditemukan',
            'sub_program_id.required'   => 'Sub Program Tidak boleh kosong',
            'sub_program_id.exists'     => 'Sub Program Tidak ditemukan',
            'month_target.required'   => 'Target Bulanan Tidak boleh kosong',
            'month_target.numeric'     => 'Target Bulanan Harus berupa angka',
            'month.required'   => 'Bulan Tidak boleh kosong',
            'quality_goal.required'   => 'Sasaran Mutu Tidak boleh kosong',
            'human.required'   => 'Manusia Tidak boleh kosong',
            'tools.required'   => 'Alat Tidak boleh kosong',
            'method.required'   => 'Metode Tidak boleh kosong',
            'policy.required'   => 'Kebijakan Tidak boleh kosong',
            'environment.required'   => 'Lingkungan Tidak boleh kosong',
            'next_plan.required'   => 'Rencana Tindak Lanjut Tidak boleh kosong',
            'first_pic_id.required'   => 'Penanggung Jawab 1 Tidak boleh kosong',
            'first_pic_id.exists'     => 'Penanggung Jawab 1 Tidak ditemukan',
            'second_pic_id.exists'     => 'Penanggung Jawab 2 Tidak ditemukan',
            'created_by.required'   => 'Pembuat Dokumen Tidak boleh kosong',
            'assign_by.required'   => 'Ditugaskan Oleh Tidak boleh kosong',
            'assign_by.exists'     => 'Ditugaskan Oleh Tidak ditemukan',
            'signature.*.user_id.exists'     => 'User Tidak ditemukan',
            'signature.*.level.in'          => 'Tingkatan tanda tangan hanya 1,2 dan 3',
        ];
    }
}
