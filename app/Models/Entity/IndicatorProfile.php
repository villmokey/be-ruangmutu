<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndicatorProfile extends AppModel
{
    use SoftDeletes;

    protected $table    =   'indicator_profiles';

    protected $fillable =   [
        'program_id',
        'sub_program_id',
        'title',
        'indicator_selection_based',
        'quality_dimension',
        'objective',
        'operational_definition',
        'measurement_status',
        'numerator',
        'denominator',
        'achievement_target',
        'criteria',
        'measurement_formula',
        'data_collection_design',
        'data_source',
        'population',
        'data_presentation',
        'data_collection_instrument',
        'first_pic_id',
        'second_pic_id',
        'created_by',
        'assign_by',
    ];
}
