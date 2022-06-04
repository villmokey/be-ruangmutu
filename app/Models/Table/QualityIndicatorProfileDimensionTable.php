<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicatorProfileDimension;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorProfileDimensionTable extends QualityIndicatorProfileDimension
{
    public function profileIndicator()
    {
        return $this->belongsTo(QualityIndicatorProfileTable::class, 'profile_id');
    }
}
