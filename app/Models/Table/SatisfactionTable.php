<?php

namespace App\Models\Table;

use App\Models\Entity\Satisfaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatisfactionTable extends Satisfaction
{
    public function healthService()
    {
        return $this->belongsTo(HealthServiceTable::class, 'health_service_id');
    }
    
    public function satisfactionDetail()
    {
        return $this->hasMany(SatisfactionDetailTable::class, 'satisfaction_id');
    }
}
