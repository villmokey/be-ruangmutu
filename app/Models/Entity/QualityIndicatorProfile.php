<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualityIndicatorProfile extends AppModel
{
    use SoftDeletes;

    protected $table    =   'quality_indicator_profiles';

    protected $fillable =   [
        'program_id',
        'sub_program_id',
        'title',
        'indicator_selection_based',
        'quality_dimension',
        'objective',
        'operational_definition',
        'indicator_type',
        'measurement_status',
        'numerator',
        'denominator',
        'inclusion_criteria',
        'exclusion_criteria',
        'measurement_formula',
        'data_collection_design',
        'data_source',
        'population',
        'data_collection_frequency',
        'data_collection_period',
        'data_analyst_period',
        'data_presentation',
        'data_collection_instrument',
        'pic_id'
    ];
}
