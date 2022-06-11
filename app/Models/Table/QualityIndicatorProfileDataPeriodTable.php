<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicatorProfileDataPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorProfileDataPeriodTable extends QualityIndicatorProfileDataPeriod
{
    public function profileIndicator()
    {
        return $this->belongsTo(QualityIndicatorProfileTable::class, 'profile_id');
    }
}
