<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicatorProfileAnalystPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorProfileAnalystPeriodTable extends QualityIndicatorProfileAnalystPeriod
{
    public function profileIndicator()
    {
        return $this->belongsTo(QualityIndicatorProfileTable::class, 'profile_id');
    }
}
