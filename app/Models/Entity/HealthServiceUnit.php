<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthServiceUnit extends AppModel
{
    protected $table    =   'health_service_units';

    protected $guarded =   ['id'];
}
