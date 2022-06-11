<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicatorProfileType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorProfileTypeTable extends QualityIndicatorProfileType
{
    public function profileIndicator()
    {
        return $this->belongsTo(QualityIndicatorProfileTable::class, 'profile_id');
    }
}
