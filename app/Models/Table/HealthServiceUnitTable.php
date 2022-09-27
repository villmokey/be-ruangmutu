<?php

namespace App\Models\Table;

use App\Models\Entity\HealthServiceUnit;
use App\Models\Table\ServiceUnitTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthServiceUnitTable extends HealthServiceUnit
{
    public function service()
    {
        return $this->belongsTo(ServiceUnitTable::class, 'service_unit_id', 'id');
    }
}
