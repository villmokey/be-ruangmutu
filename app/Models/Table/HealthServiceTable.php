<?php

namespace App\Models\Table;

use App\Models\Entity\HealthService;
use App\Models\Table\HealthServiceUnitTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthServiceTable extends HealthService
{
    public function units()
    {
        return $this->hasMany(HealthServiceUnitTable::class, 'health_service_id');
    }
}
