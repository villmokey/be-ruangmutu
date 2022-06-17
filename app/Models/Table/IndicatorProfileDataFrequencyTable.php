<?php

namespace App\Models\Table;

use App\Models\Entity\IndicatorProfileDataFrequency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorProfileDataFrequencyTable extends IndicatorProfileDataFrequency
{
    public function profileIndicator()
    {
        return $this->belongsTo(IndicatorProfileTable::class, 'indicator_profile_id');
    }
}
