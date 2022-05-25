<?php

namespace App\Models\Table;

use App\Models\Entity\ServiceUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceUnitTable extends ServiceUnit
{
    public function pic()
    {
        return $this->belongsTo(UserTable::class, 'pic_id');
    }
}
