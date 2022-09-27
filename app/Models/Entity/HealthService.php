<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthService extends AppModel
{
    protected $table   =   'health_services';

    protected $guarded =   ['id'];
}
