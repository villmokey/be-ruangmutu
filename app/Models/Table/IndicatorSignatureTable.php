<?php

namespace App\Models\Table;

use App\Models\Entity\IndicatorSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorSignatureTable extends IndicatorSignature
{
    public function indicator()
    {
        return $this->belongsTo(IndicatorTable::class, 'indicator_id');
    }

    public function user()
    {
        return $this->belongsTo(UserTable::class, 'user_id');
    }
}
