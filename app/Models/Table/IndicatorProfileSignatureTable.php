<?php

namespace App\Models\Table;

use App\Models\Entity\IndicatorProfileSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorProfileSignatureTable extends IndicatorProfileSignature
{
    public function profileIndicator()
    {
        return $this->belongsTo(IndicatorProfileTable::class, 'indicator_profile_id');
    }

    public function user()
    {
        return $this->belongsTo(UserTable::class, 'user_id');
    }
}
