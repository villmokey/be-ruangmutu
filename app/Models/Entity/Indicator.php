<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicator extends AppModel
{
    use SoftDeletes;

    protected $table    =   'indicators';

    protected $fillable =   [
        'program_id',
        'sub_program_id',
        'month',
        'quality_goal_id',
        'human',
        'tools',
        'method',
        'policy',
        'environment',
        'next_plan',
    ];
}
