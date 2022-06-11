<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicatorProfileDataFrequency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorProfileDataFrequencyTable extends QualityIndicatorProfileDataFrequency
{
    public function profileIndicator()
    {
        return $this->belongsTo(QualityIndicatorProfileTable::class, 'profile_id');
    }
}
