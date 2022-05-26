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
            'quality_dimension' =>  'required',
            'objective' =>  'required',
            'operational_definition' =>  'required',
            'indicator_type' =>  'required',
            'measurement_status' =>  'required',
            'numerator' =>  'required',
            'denominator' =>  'required',
            'inclusion_criteria' =>  'required',
            'exclusion_criteria' =>  'required',
            'measurement_formula' =>  'required',
            'data_collection_design' =>  'required',
            'data_source' =>  'required',
            'population' =>  'required',
            'data_collection_frequency' =>  'required',
            'data_collection_period' =>  'required',
            'data_analyst_period' =>  'required',
            'data_presentation' =>  'required',
            'data_collection_instrument' =>  'required',
            'pic_id' =>  'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'program_id.required'   => 'Program Tidak boleh kosong',
            'program_id.exists'     => 'Prgram Tidak ditemukan',
            'sub_program_id.required'   => 'Sub Program Tidak boleh kosong',
            'sub_program_id.exists'     => 'Sub Program Tidak ditemukan',
            'title.required'   => 'Title Tidak boleh kosong',
            'indicator_selection_based.required'   => 'Indicator Selection Based Tidak boleh kosong',
            'quality_dimension.required'   => 'Quality Dimension Tidak boleh kosong',
            'objective.required'   => 'Objective Tidak boleh kosong',
            'operational_definition.required'   => 'Operational Definition Tidak boleh kosong',
            'indicator_type.required'   => 'Indicator Type Tidak boleh kosong',
            'measurement_status.required'   => 'Measurement Status Tidak boleh kosong',
            'numerator.required'   => 'Numerator Tidak boleh kosong',
            'denominator.required'   => 'Denominator Tidak boleh kosong',
            'inclusion_criteria.required'   => 'Inclusion Criteria Tidak boleh kosong',
            'exclusion_criteria.required'   => 'Exclusion Criteria Tidak boleh kosong',
            'measurement_formula.required'   => 'Measurement Formula Tidak boleh kosong',
            'data_collection_design.required'   => 'Data Collection Design Tidak boleh kosong',
            'data_source.required'   => 'Data Source Tidak boleh kosong',
            'population.required'   => 'Population Tidak boleh kosong',
            'data_collection_frequency.required'   => 'Data Collection Frequency Tidak boleh kosong',
            'data_collection_period.required'   => 'Data Collection Period Tidak boleh kosong',
            'data_analyst_period.required'   => 'Data Analyst Period Tidak boleh kosong',
            'data_presentation.required'   => 'Data Presentation Tidak boleh kosong',
            'data_collection_instrument.required'   => 'Data Collection Instrument Tidak boleh kosong',
            'pic_id.required'   => 'PIC Tidak boleh kosong',
            'pic_id.exists'     => 'PIC Tidak ditemukan',
        ];
    }
}
