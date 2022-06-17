<?php

namespace App\Models\Table;

use App\Models\Entity\IndicatorProfileDimension;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorProfileDimensionTable extends IndicatorProfileDimension
{
    public function profileIndicator()
    {
        return $this->belongsTo(IndicatorProfileTable::class, 'indicator_profile_id');
    }
}
