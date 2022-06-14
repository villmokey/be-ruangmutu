<?php

namespace App\Http\Requests\Api\QualityIndicator;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateQualityIndicatorProfileRequest extends FormRequest
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
            'title' =>  'required',
            'indicator_selection_based' =>  'required',
            'objective' =>  'required',
            'operational_definition' =>  'required',
            'measurement_status' =>  'required',
            'numerator' =>  'required',
            'denominator' =>  'required',
            'achievement_target' =>  'required',
            'criteria' =>  'required',
            'measurement_formula' =>  'required',
            'data_collection_design' =>  'required',
            'data_source' =>  'required',
            'population' =>  'required',
            'data_presentation' =>  'required',
            // 'data_collection_instrument' =>  'required',
            'pic_id' =>  'required|exists:users,id',
            'created_by' =>  'required|exists:users,id',
            'assign_by' =>  'required|exists:users,id',
            // 'signature.*.user_id' => 'required|exists:users,id',
            // 'signature.*.level' => 'required|in:1,2,3',
            'quality_dimension.*.name' => 'required',
            'indicator_type.*.name' => 'required',
            'data_collection_frequency.*.name' => 'required',
            'data_collection_period.*.name' => 'required',
            'data_analyst_period.*.name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'program_id.required'   => 'Program Tidak boleh kosong',
            'program_id.exists'     => 'Prgram Tidak ditemukan',
            'sub_program_id.required'   => 'Sub Program Tidak boleh kosong',
            'sub_program_id.exists'     => 'Sub Program Tidak ditemukan',
            'title.required'   => 'Judul indikator Tidak boleh kosong',
            'indicator_selection_based.required'   => 'Dasar pemilihan indikator Tidak boleh kosong',
            'objective.required'   => 'Tujuan Tidak boleh kosong',
            'operational_definition.required'   => 'Definisi Operasional Tidak boleh kosong',
            'measurement_status.required'   => 'Status Pengukuran Tidak boleh kosong',
            'numerator.required'   => 'Numerator Tidak boleh kosong',
            'denominator.required'   => 'Denominator Tidak boleh kosong',
            'achievement_target.required'   => 'Target Capaian Tidak boleh kosong',
            'criteria.required'   => 'Kriteria Tidak boleh kosong',
            'measurement_formula.required'   => 'Formula Pengukuran Tidak boleh kosong',
            'data_collection_design.required'   => 'Pengumpulan Data Tidak boleh kosong',
            'data_source.required'   => 'Sumber Data Tidak boleh kosong',
            'population.required'   => 'Populasi atau Sampel Tidak boleh kosong',
            'data_presentation.required'   => 'Penyajian Data Tidak boleh kosong',
            'pic_id.required'   => 'PIC Tidak boleh kosong',
            'pic_id.exists'     => 'PIC Tidak ditemukan',
            'created_by.required'   => 'Pembuat Dokumen Tidak boleh kosong',
            'created_by.exists'     => 'Pembuat Dokumen Tidak ditemukan',
            'assign_by.required'   => 'Ditugaskan Oleh Tidak boleh kosong',
            'assign_by.exists'     => 'Ditugaskan Oleh Tidak ditemukan',
            // 'signature.*.user_id.required'   => 'User Tidak boleh kosong',
            // 'signature.*.user_id.exists'     => 'User Tidak ditemukan',
            // 'signature.*.level.required'    => 'Tingkatan tanda tangan Tidak boleh kosong',
            // 'signature.*.level.in'          => 'Tingkatan tanda tangan hanya 1,2 dan 3',
            'quality_dimension.*.name.required'   => 'Quality Dimension Tidak boleh kosong',
            'indicator_type.*.name.required'   => 'Tipe Indikator Tidak boleh kosong',
            'data_collection_frequency.*.name.required'   => 'Frekuensi Pengumpulan Data Tidak boleh kosong',
            'data_collection_period.*.name.required'   => 'Periode Waktu Pelaporan Tidak boleh kosong',
            'data_analyst_period.*.name.required'   => 'Periode Analisis Tidak boleh kosong',
        ];
    }
}
