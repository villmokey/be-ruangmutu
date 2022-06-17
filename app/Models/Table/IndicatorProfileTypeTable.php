<?php

namespace App\Models\Table;

use App\Models\Entity\IndicatorProfileType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorProfileTypeTable extends IndicatorProfileType
{
    public function profileIndicator()
    {
        return $this->belongsTo(IndicatorProfileTable::class, 'indicator_profile_id');
    }
}
