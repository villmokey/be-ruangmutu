<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicatorProfileSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorProfileSignatureTable extends QualityIndicatorProfileSignature
{
    public function profileIndicatore()
    {
        return $this->belongsTo(QualityIndicatorProfileTable::class, 'indicator_profile_id');
    }

    public function user()
    {
        return $this->belongsTo(UserTable::class, 'user_id');
    }
}
