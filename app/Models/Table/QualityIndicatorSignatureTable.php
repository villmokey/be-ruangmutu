<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicatorSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorSignatureTable extends QualityIndicatorSignature
{
    public function indicator()
    {
        return $this->belongsTo(QualityIndicatorTable::class, 'indicator_id');
    }

    public function user()
    {
        return $this->belongsTo(UserTable::class, 'user_id');
    }
}
