<?php

namespace App\Models\Table;

use App\Models\Entity\IndicatorProfileDataPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorProfileDataPeriodTable extends IndicatorProfileDataPeriod
{
    public function profileIndicator()
    {
        return $this->belongsTo(IndicatorProfileTable::class, 'indicator_profile_id');
    }
}
