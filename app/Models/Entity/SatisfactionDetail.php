<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatisfactionDetail extends AppModel
{
    protected $table    =   'satisfaction_details';

    protected $fillable =   [
        'satisfaction_id',
        'service_name',
        'value',
        'total',
        'percentage'
    ];
}
