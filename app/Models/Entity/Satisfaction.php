<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satisfaction extends AppModel
{
    protected $table    =   'satisfactions';

    protected $fillable =   [
        'health_service_id',
        'month',
        'average',
        'created_id'
    ];
}
