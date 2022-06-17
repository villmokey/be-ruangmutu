<?php

namespace App\Models\Table;

use App\Models\Entity\IndicatorProfileAnalystPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorProfileAnalystPeriodTable extends IndicatorProfileAnalystPeriod
{
    public function profileIndicator()
    {
        return $this->belongsTo(IndicatorProfileTable::class, 'indicator_profile_id');
    }
}
